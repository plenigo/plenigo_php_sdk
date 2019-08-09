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

use PHPUnit\Runner\Exception;
use plenigo\internal\ApiParams;
use plenigo\internal\ApiResults;
use plenigo\internal\ApiURLs;
use plenigo\internal\Cache;
use plenigo\internal\exceptions\EncryptionException;
use plenigo\internal\exceptions\RegistrationException;
use plenigo\internal\models\Customer;
use plenigo\internal\services\Service;
use plenigo\internal\utils\CurlRequest;
use plenigo\internal\utils\EncryptionUtils;
use plenigo\internal\utils\SdkUtils;
use plenigo\models\ErrorCode;
use plenigo\models\OrderList;
use plenigo\models\Subscription;
use plenigo\models\SubscriptionList;
use plenigo\models\UserData;
use plenigo\PlenigoException;
use plenigo\PlenigoManager;

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
    const ERR_MSG_USER_LIST = "Error while retrieving bought product listing!";
    const ERR_MSG_PAYWALL = "Error while determining if the paywall is enabled!";
    const ERR_USER_LOGIN = "Error while verifying user";
    const INF_MSG_ACCESS = "User tried to access an item!";
    const ERR_MSG_ACCESS = "User can't access item";

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
            'token' => $accessToken,
        );

        $request = static::getRequest(ApiURLs::USER_PROFILE, false, $params);

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

        $result = UserData::createFromMap((array)$response);

        return $result;
    }


    /**
     * Verify the combination of email and password
     * and returns the user object if successfull.
     * @see https://plenigo.github.io/sdks/php#verify-users-login
     *
     * array['os']          string Operation System of the User (max 40)
     *      ['browser']     string browser of the user (max 40)
     *      ['source']      string source of the user (max 255)
     *      ['ipAddress']   string IP-Address of the user (max 45)
     *
     * @param string $email the user's email
     * @param string $password the users password
     * @param array $data (optional) additional data to track login information (See above)
     * @param string $error (optional) error message
     *
     * @return array|boolean user data or boolean false
     */
    public static function verifyLogin($email, $password, $data = array(), &$error = '') {

        $result = Cache::get(md5($email.$password));

        if (null !== $result && is_array($result)) {
            $error = $result['error'];
            return $result['result'];
        }

        $clazz = get_class();
        PlenigoManager::notice($clazz, "Verifying the user's login");

        $params = array_merge($data, array(
            'email' => $email,
            'password' => $password,
        ));

        $request = static::postJSONRequest(ApiURLs::USER_LOGIN, false, $params);

        $LoginRequest = new static($request);

        try {
            $result = parent::executeRequest($LoginRequest, ApiURLs::USER_LOGIN, self::ERR_USER_LOGIN);

            return $result;
        }
        // we only catch one specific Exception
        catch (PlenigoException $exception) {
            $result = CurlRequest::getLastResult();

            // something else is broken
            if (!is_array($result) || !key_exists('error', $result) || !$result['error']) {
                // throwing it outside
                throw $exception;
            }

            // our errorMsg from API
            $error = $result['error'];
        }

        $cachedData = array(
            'result' => false,
            'error'  => $error
        );

        Cache::set(md5($email.$password), $cachedData);
        return false;
    }

    /**
     * Executes the prepared request and returns
     * the Response object on success.
     *
     * @return mixed The request's response.
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
     * an array of \plenigo\models\ErrorDetail.
     *
     * @param mixed $productId The ID (or array of IDs) of the product to be queried against the user
     * @param string $customerId (optional) The customer ID if its not logged in
     * @param boolean $useExternalCustomerId (optional) Flag indicating if customer id sent is the external customer id
     *
     * @return bool TRUE if the user in the cookie has bought the product and the session is not expired, false otherwise
     *
     * @throws \Exception whenever an error happens
     */
    public static function hasUserBought($productId, $customerId = null, $useExternalCustomerId = false)
    {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Checking if user bought Product with ID=" . print_r($productId, true));

        $customer = self::getCustomerInfo($customerId);
        if (is_null($customer)) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, self::ERR_MSG_CUSTOMER);
            return false;
        }
        PlenigoManager::notice($clazz, "customer is good=" . print_r($customer, true));
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';

        $params = array(
            ApiParams::CUSTOMER_ID => $customer->getCustomerId(),
            ApiParams::PRODUCT_ID => $productId,
            ApiParams::TEST_MODE => $testModeText,
            ApiParams::USE_EXTERNAL_CUSTOMER_ID => ($useExternalCustomerId ? 'true' : 'false')
        );
        $request = static::getRequest(ApiURLs::USER_PRODUCT_ACCESS, false, $params);

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
                PlenigoManager::notice($clazz, "Product NOT accessible!");
                return false;
            } else {
                $clazz = get_class();
                PlenigoManager::error($clazz, self::ERR_MSG_USER_BOUGHT, $exc);
                throw new PlenigoException(self::ERR_MSG_USER_BOUGHT, $exc->getCode(), $exc);
            }
        }
        if (!is_null($response)) {
            PlenigoManager::notice($clazz, "Product is accessible=" . print_r($response, true));
            return true;
        } else {
            PlenigoManager::notice($clazz, "Product NOT accesible!");
            return false;
        }
    }

    /**
     * Checks if the user can access a product and return detail information about that product. Multiple products can be requested by passing an array
     * to productId. If multiple products are passed to the productId field all products the user has bought are returned.If there is an error response
     * from the API this will throw am {@link \plenigo\PlenigoException}, in the case of BAD_REQUEST types, the exception will contain
     * an array of \plenigo\models\ErrorDetail.
     *
     * @param mixed $productId The ID (or array of IDs) of the product to be queried against the user
     * @param string $customerId The customer ID if its not logged in
     * @param boolean $useExternalCustomerId Flag indicating if customer id sent is the external customer id
     *
     * @return array
     *
     * @throws \Exception whenever an error happens
     */
    public static function hasBoughtProductWithProducts($productId, $customerId = null, $useExternalCustomerId = false)
    {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Checking if user bought Product with ID=" . print_r($productId, true));

        $customer = self::getCustomerInfo($customerId);
        if (is_null($customer)) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, self::ERR_MSG_CUSTOMER);
            return array('accessGranted' => false);
        }
        PlenigoManager::notice($clazz, "customer is good=" . print_r($customer, true));
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';

        $params = array(
            ApiParams::CUSTOMER_ID => $customer->getCustomerId(),
            ApiParams::PRODUCT_ID => $productId,
            ApiParams::TEST_MODE => $testModeText,
            ApiParams::USE_EXTERNAL_CUSTOMER_ID => ($useExternalCustomerId ? 'true' : 'false')
        );
        $request = static::getRequest(ApiURLs::USER_PRODUCT_ACCESS, false, $params);

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
                PlenigoManager::notice($clazz, "Product NOT accessible!");
                return array('accessGranted' => false);
            } else {
                $clazz = get_class();
                PlenigoManager::error($clazz, self::ERR_MSG_USER_BOUGHT, $exc);
                throw new PlenigoException(self::ERR_MSG_USER_BOUGHT, $exc->getCode(), $exc);
            }
        }
        if (!is_null($response)) {

            PlenigoManager::notice($clazz, "Product is accessible=" . print_r($response, true));
            return get_object_vars($response);
        } else {
            PlenigoManager::notice($clazz, "Product NOT accesible!");
            return array('accessGranted' => false);
        }
    }

    /**
     * Calls the paywall service to check if the entire paywall service is enabled, if disabled,
     * all product paywall should be disabled and access should be granted
     *
     * @return bool true if Paywall is enabled and we need to check for specific product buy information
     *
     * @throws PlenigoException
     */
    public static function isPaywallEnabled()
    {
        $request = static::getRequest(ApiURLs::PAYWALL_STATE, false);

        $userDataRequest = new static($request);
        try {
            $response = $userDataRequest->execute();
        } catch (PlenigoException $exc) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_PAYWALL, $exc);

            throw new PlenigoException(self::ERR_MSG_PAYWALL);
        }

        $resArray = get_object_vars($response);

        if (isset($resArray['enabled'])) {
            return !!$resArray['enabled'];
        }

        throw new PlenigoException(self::ERR_MSG_PAYWALL);
    }

    /**
     * Check if the user has been logged in (cookie is found and valid)
     *
     * @return bool TRUE if the user has been logged in
     *
     * @throws PlenigoException
     */
    public static function isLoggedIn()
    {
        $customer = self::getCustomerInfo();
        $clazz = get_class();
        if (is_null($customer) || !($customer instanceof \plenigo\internal\models\Customer)) {
            PlenigoManager::error($clazz, self::ERR_MSG_CUSTOMER);
            return false;
        }
        return true;
    }

    /**
     * Retrieves the user info from the cookie.
     * @param string $pCustId The customer ID if its not logged in
     * @return Customer The Customer Information from the cookie
     * @throws \Exception whenever an error happens
     * @throws EncryptionException if there are encryption errors
     *
     */
    public static function getCustomerInfo($pCustId = null)
    {
        if (is_null($pCustId)) {
            $cookieText = static::getCookieContents(PlenigoManager::PLENIGO_USER_COOKIE_NAME);
            if (!isset($cookieText) || is_null($cookieText) || !is_string($cookieText) || empty($cookieText)) {
                $clazz = get_class();
                PlenigoManager::notice($clazz, "Plenigo cookie not set and no customer id given!!");
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

            $customerId = $dataMap[ApiResults::CUSTOMER_ID];
            $timestamp = $dataMap[ApiResults::TIMESTAMP];
        } else {
            $customerId = $pCustId;
            $timestamp = strtotime("+1 day") * 1000;
        }

        if (!is_numeric($timestamp)) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "Illegal value for the expiration date timestamp!");
            return null;
        }
        if (is_null($customerId) || !is_string($customerId) || empty($customerId)) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "Plenigo cookie CustomerID invalid!!");
            return null;
        }

        $timestampInMillis = intval($timestamp);
        return new Customer($customerId, $timestampInMillis);
    }

    /**
     * <p>Retrieves the product and subscriptions list for the current (logged in)
     * user, then returns it as an associative array with this syntax</p>
     * <pre>
     * array (
     *   'singleProducts' => array (
     *     0 => array(
     *        'productId' => 'xxxx',
     *        'originalProductId' => 'xxxx',
     *        'transactionId' => 'AAAAAAABBBBBBDDDEDDEDEDEDEDEDED'
     *        'title' => 'prod title',
     *        'buyDate' => 'YYYY-MM-DD HH:mm:ss +0100',
     *        'price' => 4.00,
     *        'taxesPercentage' => 10,
     *        'taxesAmount' => 0.4,
     *        'taxesCountry' => 'DE',
     *        'currency' => 'EUR',
     *        'paymentMethod' => 'PAYPAL',
     *        'status' => 'CANCELED'
     *        'shippingCosts' => 1.00,
     *        'shippingCostsTaxesPercentage' => 20,
     *        'shippingCostsTaxesAmount' => 0.2
     *        'cancellationTransactionId' => 'AAAAAAABBBBBBDDDEDDEDEDEDEDEDEF'
     *     ),
     *   ),
     *   'subscriptions' => array (
     *     0 => array(
     *        'productId' => 'yyyyyy',
     *        'originalProductId' => 'yyyyyy',
     *        'title' => 'Subscription title',
     *        'startDate' => 'YYYY-MM-DD HH:mm:ss +0100',
     *        'endDate' => 'YYYY-MM-DD HH:mm:ss +0100',
     *        'price' => 4.00,
     *        'taxesPercentage' => 10,
     *        'taxesCountry' => 'DE',
     *        'currency' => 'EUR',
     *        'paymentMethod' => 'PAYPAL',
     *        'shippingCosts' => 1.00,
     *        'shippingCostsTaxesPercentage' => 20
     *        'cancellationDate' => 'YYYY-MM-DD HH:mm:ss +0100'
     *     ),
     *   ),
     * )</pre>
     *
     * @param string $pCustId (optional) The customer ID if its not logged in
     * @param boolean $useExternalCustomerId (optional) Flag indicating if customer id sent is the external customer id
     * @return array The associative array containing the bought products/subscriptions or an empty array
     * @throws \Exception If the company ID and/or the Secret key is rejected
     */
    public static function getProductsBought($pCustId = null, $useExternalCustomerId = false)
    {
        $res = array();
        $customer = self::getCustomerInfo($pCustId);
        $clazz = get_class();
        if (is_null($customer)) {
            PlenigoManager::notice($clazz, self::ERR_MSG_CUSTOMER);
            return $res;
        }
        PlenigoManager::notice($clazz, "customer is good=" . print_r($customer, true));
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';

        $params = array(
            ApiParams::TEST_MODE => $testModeText,
            ApiParams::USE_EXTERNAL_CUSTOMER_ID => ($useExternalCustomerId ? 'true' : 'false')

        );
        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customer->getCustomerId(), ApiURLs::USER_PRODUCTS);
        $request = static::getRequest($url, false, $params);

        $userDataRequest = new static($request);
        try {
            $response = $userDataRequest->execute();
        } catch (PlenigoException $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::USER_PRODUCTS, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }

            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_USER_LIST, $exc);
            throw new PlenigoException(self::ERR_MSG_USER_LIST, $errorCode, $exc);
        }
        if (!is_null($response)) {
            PlenigoManager::notice($clazz, "Product list is accessible=" . print_r($response, true));
            $res = get_object_vars($response);
        } else {
            PlenigoManager::notice($clazz, "Product list NOT accesible!");
        }
        return $res;
    }


    /**
     * returns all Subscriptions of a given user
     *
     * @see https://api.plenigo.com/#!/user/getSubscriptionsBought
     *
     * @param string $customerId
     * @param bool $useExternalCustomerId
     * @return SubscriptionList
     * @throws PlenigoException | \Exception If the company ID and/or the Secret key is rejected
     */
    public static function getSubscriptions($customerId = null, $useExternalCustomerId = false)
    {
        if (is_null($customerId)) {
            throw new PlenigoException("CustomerID is mandatory!");
        }

        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';

        $params = array(
            ApiParams::TEST_MODE => $testModeText,
            ApiParams::USE_EXTERNAL_CUSTOMER_ID => ($useExternalCustomerId ? 'true' : 'false')
        );
        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::USER_SUBSCRIPTIONS);
        $request = static::getRequest($url, false, $params);

        $userDataRequest = new static($request);

        $response = $userDataRequest->execute();

        return SubscriptionList::createFromMap((array) $response);
    }


    /**
     * returns all Subscriptions of a given user
     *
     * @see https://api.plenigo.com/#!/user/getOrders
     *
     * @param string $customerId
     * @param bool $useExternalCustomerId
     * @return OrderList
     * @throws PlenigoException | \Exception If the company ID and/or the Secret key is rejected
     */
    public static function getOrders($customerId = null, $useExternalCustomerId = false)
    {
        if (is_null($customerId)) {
            throw new PlenigoException("CustomerID is mandatory!");
        }

        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';

        $params = array(
            ApiParams::TEST_MODE => $testModeText,
            ApiParams::USE_EXTERNAL_CUSTOMER_ID => ($useExternalCustomerId ? 'true' : 'false')
        );
        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::USER_ORDERS);
        $request = static::getRequest($url, false, $params);

        $userDataRequest = new static($request);

        $response = $userDataRequest->execute();

        return OrderList::createFromMap((array) $response);
    }


    /**
     * Get AccessRights with their Details
     *
     * @see https://api.plenigo.com/#!/user/hasBoughtProductWithProducts
     *
     * @param $pCustomerId
     * @param array|string $productId
     * @param bool $useExternalCustomerId
     * @return \stdClass
     * @throws PlenigoException|RegistrationException|\Exception
     */
    public static function getProductsBoughtWithDetails($pCustomerId, $productId, $useExternalCustomerId = false)
    {

        $productIds = is_array($productId) ? implode(",", $productId) : $productId;

        $params = array(
            'customerId' => $pCustomerId,
            'productId' => $productIds,
            ApiParams::USE_EXTERNAL_CUSTOMER_ID => ($useExternalCustomerId ? 'true' : 'false')
        );

        $url = ApiURLs::USER_PRODUCTS_DETAILS;

        $request = static::getRequest($url, false, $params);

        $appTokenRequest = new static($request);

        try {
            $data = parent::executeRequest($appTokenRequest, $url, self::ERR_MSG_ACCESS);
            return $data;
        } catch (PlenigoException $exception) {
            // 403 is no access and should not result in an exception
            if (403 !== $exception->getCode()) {
                throw $exception;
            }
        }

        $ret = new \stdClass();
        $ret->accessGranted = false;
        $ret->userProducts = array();
        return $ret;
    }

    /**
     * Gets the user data using the current plenigo session cookie.
     *
     * @return UserData the UserData object {@link \plenigo\models\UserData}
     * @throws {@link \plenigo\PlenigoException} on response error.
     */
    public static function getCurrentUserFromSessionCookie()
    {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Obtaining Logged In User Data!");
        $cookieText = static::getCookieContents(PlenigoManager::PLENIGO_USER_COOKIE_NAME);
        if (!isset($cookieText) || is_null($cookieText) || !is_string($cookieText) || empty($cookieText)) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "Plenigo cookie not set and no customer id given!!");
            return null;
        }

        $params = array(
            'sessionCookie' => $cookieText,
        );

        $request = static::getRequest(ApiURLs::USER_PROFILE_BY_SESSION_COOKIE, false, $params);

        $userDataRequest = new static($request);
        try {
            $response = $userDataRequest->execute();
        } catch (PlenigoException $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::USER_PROFILE_BY_SESSION_COOKIE, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_USER_DATA, $exc);
            throw new PlenigoException(self::ERR_MSG_USER_DATA, $errorCode, $exc);
        }

        PlenigoManager::notice($clazz, "User Data returned!\n" . print_r($response, true));

        $result = UserData::createFromMap((array)$response);

        return $result;
    }
}
