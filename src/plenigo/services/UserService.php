<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/utils/RestClient.php';
require_once __DIR__ . '/../models/UserData.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../internal/models/Customer.php';
require_once __DIR__ . '/../internal/utils/EncryptionUtils.php';
require_once __DIR__ . '/../internal/utils/SdkUtils.php';
require_once __DIR__ . '/../internal/ApiResults.php';
require_once __DIR__ . '/../internal/ApiParams.php';
require_once __DIR__ . '/../models/ErrorCode.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiURLs;
use \plenigo\internal\utils\RestClient;
use \plenigo\models\UserData;
use \plenigo\internal\services\Service;
use \plenigo\internal\models\Customer;
use \plenigo\internal\utils\EncryptionUtils;
use \plenigo\internal\utils\SdkUtils;
use \plenigo\internal\ApiResults;
use \plenigo\internal\ApiParams;
use \plenigo\models\ErrorCode;

/**
 * UserService
 *
 * <p>
 * This class communicates with the Plenigo REST API
 * to retrieve user information
 * </p>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class UserService extends Service
{

    const ERR_MSG_USER_DATA = "Could not retrieve User Data!";
    const ERR_MSG_CUSTOMER = "Could not retrieve Customer Information!";
    const ERR_MSG_EXPIRED = "Plenigo Cookie has expired, please login again!";
    const ERR_MSG_USER_BOUGHT = "Error while determining if the user bought an item!";
    const ERR_MSG_PAYWALL = "Error while determining if the paywall is enabled!";
    const INF_MSG_ACCESS = "User tried to access an item!";

    /**
     * Cookie expiration time lapse in milliseconds.
     * (24*60*60*100)
     */
    const TS_EXP_TIME_LAPSE_IN_MILLIS = 8640000; //24hours in millis

    /**
     * Gets the user data using the access token provided.
     *
     * @param string $accessToken the access token to use.
     * @return UserData the UserData object {@link \plenigo\models\UserData}
     * @throws {@link \plenigo\PlenigoException}\ on response error.
     */

    public static function getUserData($accessToken)
    {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Obtaining Logged In User Data!");

        $params = array(
            'companyId' => PlenigoManager::get()->getCompanyId(),
            'secret' => PlenigoManager::get()->getSecret(),
            'token' => $accessToken,
        );

        $request = static::getRequest(ApiURLs::USER_PROFILE, $params);

        $userDataRequest = new static($request);
        try {
            $response = $userDataRequest->execute();
        } catch (PlenigoException $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::USER_PROFILE, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_USER_DATA, $exc);
            throw new PlenigoException(self::ERR_MSG_USER_DATA, $errorCode, $exc);
        }

        PlenigoManager::notice($clazz, "User Data returned!\n" . print_r($response, true));

        $result = UserData::createFromMap((array) $response);

        return $result;
    }

    /**
     * Executes the prepared request and returns
     * the Response object on success.
     *
     * @return The request's response.
     *
     * @throws \plenigo\PlenigoException on request error.
     */
    public function execute()
    {
        try {
            $response = parent::execute();
        } catch (\Exception $exc) {
            throw new PlenigoException('User Service execution failed!', $exc->getCode(), $exc);
        }

        return $response;
    }

    /**
     * Checks if the user can access a product. If there is an error response from the API this will
     * throw am {@link \plenigo\PlenigoException}, in the case of BAD_REQUEST types, the exception will contain
     * am array of \plenigo\models\ErrorDetail.
     *
     * @param string $productId The id of the product to be queried against the user
     * @return TRUE if the user in the cookie has bought the product and the session is not expired, false otherwise
     * @throws \plenigo\PlenigoException whenever an error happens
     */
    public static function hasUserBought($productId)
    {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Checking if user bought Product with ID=" . print_r($productId, true));

        $customer = self::getCustomerInfo();
        if (is_null($customer)) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, self::ERR_MSG_CUSTOMER);
            return false;
        }
        if (self::hasExpired($customer) === true) {
            PlenigoManager::notice($clazz, self::ERR_MSG_EXPIRED);
            return false;
        }

        PlenigoManager::notice($clazz, "customer is good=" . print_r($customer, true));
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';

        $params = array(
            ApiParams::COMPANY_ID => PlenigoManager::get()->getCompanyId(),
            ApiParams::SECRET => PlenigoManager::get()->getSecret(),
            ApiParams::CUSTOMER_ID => $customer->getCustomerId(),
            ApiParams::PRODUCT_ID => $productId,
            ApiParams::TEST_MODE => $testModeText
        );
        $request = static::getRequest(ApiURLs::USER_PRODUCT_ACCESS, $params);

        $userDataRequest = new static($request);
        try {
            $response = $userDataRequest->execute();
        } catch (PlenigoException $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::USER_PRODUCT_ACCESS, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }

            // Forbidden means that the user has not bought the product.
            if ($errorCode == ErrorCode::CANNOT_ACCESS_PRODUCT) {
                PlenigoManager::notice($clazz, "Product NOT accesible!");
                return false;
            } else {
                $clazz = get_class();
                PlenigoManager::error($clazz, self::ERR_MSG_USER_BOUGHT, $exc);
                throw new PlenigoException(self::ERR_MSG_USER_BOUGHT, $exc->getCode(), $exc);
            }
        }
        if (!is_null($response)) {
            PlenigoManager::notice($clazz, "Product is accesible=" . print_r($response, true));
            return true;
        } else {
            PlenigoManager::notice($clazz, "Product NOT accesible!");
            return false;
        }
    }

    /**
     * Calls teh paywall service to check if the entire paywall service is enabled, if disabled, 
     * all product paywall should be disabled and acces should be granted
     * 
     * @return bool true if Paywall is enabled and we need to check for specific product buy information
     */
    public static function isPaywallEnabled()
    {
        $params = array(
            ApiParams::COMPANY_ID => PlenigoManager::get()->getCompanyId(),
            ApiParams::SECRET => PlenigoManager::get()->getSecret()
        );
        $request = static::getRequest(ApiURLs::PAYWALL_STATE, $params);

        $userDataRequest = new static($request);
        try {
            $response = $userDataRequest->execute();
        } catch (PlenigoException $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::PAYWALL_STATE, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }

            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_PAYWALL, $exc);
            // Default state for the paywall is ENABLED
            return true;
        }
        $resArray = get_object_vars($response);

        if (isset($resArray['enabled']) && $resArray['enabled'] === 'false') {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Check if the user has been logged in (cookie is found and valid)
     * 
     * @return bool TRUE if the user has been logged in
     */
    public static function isLoggedIn()
    {
        $customer = self::getCustomerInfo();
        $clazz = get_class();
        if (is_null($customer) || !($customer instanceof \plenigo\internal\models\Customer)) {
            PlenigoManager::error($clazz, self::ERR_MSG_CUSTOMER);
            return false;
        }
        if (self::hasExpired($customer) === true) {
            PlenigoManager::error($clazz, self::ERR_MSG_EXPIRED);
            return false;
        }
        return true;
    }

    /**
     * Retrieves the user info from the cookie.
     * @return The Customer Information from the cookie
     * @throws \plenigo\PlenigoException whenever an error happens
     */
    private static function getCustomerInfo()
    {
        $cookieText = static::getCookieContents(PlenigoManager::PLENIGO_USER_COOKIE_NAME);
        if (!isset($cookieText) || is_null($cookieText) || !is_string($cookieText) || empty($cookieText)
        ) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "Plenigo cookie not set!!");
            return null;
        }
        // For decryption purposes, the first part of the cookie only is necessary
        if (stristr($cookieText, '|') !== false) {
            $cookieText = stristr($cookieText, '|', true);
        }
        $data = EncryptionUtils::decryptWithAES(PlenigoManager::get()->getSecret(), $cookieText);

        $dataMap = SdkUtils::getMapFromString($data);


        if (!isset($dataMap[ApiResults::TIMESTAMP]) || !isset($dataMap[ApiResults::CUSTOMER_ID])) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "Plenigo cookie has missing components!!");
            return null;
        }

        $timestamp = $dataMap[ApiResults::TIMESTAMP];

        if (!is_numeric($timestamp)) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "Illegal value for the expiration date timestamp!");
            return null;
        }

        $customerId = $dataMap[ApiResults::CUSTOMER_ID];
        if (is_null($customerId) || !is_string($customerId) || empty($customerId)) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "Plenigo cookie CustomerID invalid!!");
            return null;
        }

        $timestampInMillis = intval($timestamp);
        return new Customer($customerId, $timestampInMillis);
    }

    /**
     * Checks if the customer timestamp has expired.
     *
     * @param Customer $customer The customer entity to be used in order to examine if the cookie has expired.
     * @return A TRUE if the cookie has expired.
     */
    private static function hasExpired($customer)
    {
        if (is_null($customer) || !($customer instanceof \plenigo\internal\models\Customer)) {
            return true;
        }

        $curTime = time();

        $timeLapse = $curTime - $customer->getTimestamp();

        if ($timeLapse > 0 && intval($timeLapse) > intval(static::TS_EXP_TIME_LAPSE_IN_MILLIS)) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "Expired Cookie from CustomerID=" . $customer->getCustomerId());
            $hasExpired = true;
        } else {
            $hasExpired = false;
        }
        return $hasExpired;
    }

}
