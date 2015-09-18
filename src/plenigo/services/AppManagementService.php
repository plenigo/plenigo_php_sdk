<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/ApiParams.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../models/AppTokenData.php';
require_once __DIR__ . '/../models/AppAccessData.php';
require_once __DIR__ . '/../models/ErrorCode.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiURLs;
use \plenigo\internal\ApiParams;
use \plenigo\internal\services\Service;
use \plenigo\models\AppTokenData;
use \plenigo\models\AppAccessData;
use \plenigo\models\ErrorCode;

/**
 * AppManagementService
 *
 * <p>
 * A class used to retrieve Access Tokens from the plenigo API
 * when given a valid Access Code.
 * </p>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class AppManagementService extends Service {

    const ERR_MSG_TOKEN = "Error getting App Access Token";
    const ERR_MSG_ALL_APPS = "Error getting All Apps for a customer";
    const ERR_MSG_ACCESS = "Acces is denied for this App ID";
    const ERR_MSG_DELETE = "Error trying to delete the App ID";

    /**
     * The constructor for the AppManagementService instance.
     *
     * @param RestClient $request   The RestClient request to execute.
     *
     * @return AppManagementService instance.
     */
    public function __construct($request) {
        parent::__construct($request);
    }

    /**
     * Executes the request to get the App Access Token. This allows 3rd party apps to get access from a customer to a product
     * 
     * @param string $customerId the Customer ID to send to the API
     * @param string $productId  the Product ID to send to the API
     * @param string $description the App Access Description to send to the API
     * @return AppTokenData the access token to get the App ID
     * @throws PlenigoException
     */
    public static function requestAppToken($customerId, $productId, $description) {
        $map = array(
            'companyId' => PlenigoManager::get()->getCompanyId(),
            'secret' => PlenigoManager::get()->getSecret(),
            'testMode' => PlenigoManager::get()->isTestMode(),
            'productId' => $productId,
            'description' => $description
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_APP_TOKEN);

        $request = static::postJSONRequest($url, false, $map);

        $appTokenRequest = new static($request);

        try {
            $data = $appTokenRequest->execute();
        } catch (Exception $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_APP_TOKEN, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_TOKEN, $exc);
            throw new PlenigoException(self::ERR_MSG_TOKEN, $errorCode, $exc);
        }

        $result = AppTokenData::createFromMap((array) $data);

        return $result;
    }

    /**
     * Executes the request to get all App IDs for a given customer
     * 
     * @param string $customerId the Customer ID to send to the API
     * @return array An array of AppAccessData objects
     * @throws PlenigoException
     */
    public static function getCustomerApps($customerId) {
        $map = array(
            'companyId' => PlenigoManager::get()->getCompanyId(),
            'secret' => PlenigoManager::get()->getSecret(),
            'testMode' => PlenigoManager::get()->isTestMode()
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_APPS_ID);

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        try {
            $data = $appTokenRequest->execute();
        } catch (Exception $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_APPS_ID, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_TOKEN, $exc);
            throw new PlenigoException(self::ERR_MSG_TOKEN, $errorCode, $exc);
        }

        $result = AppAccessData::createFromMapArray((array) $data);

        return $result;
    }

    /**
     * Executes the request to get App IDs for a given customer and product given its Access Token
     * 
     * @param string $customerId The Customer ID to send to the API
     * @param string $accessToken The Access Token 
     * @return AppAccessData the access data with the App ID
     * @throws PlenigoException
     */
    public static function requestAppId($customerId, $accessToken) {
        $map = array(
            'companyId' => PlenigoManager::get()->getCompanyId(),
            'secret' => PlenigoManager::get()->getSecret(),
            'testMode' => PlenigoManager::get()->isTestMode(),
            'accessToken' => $accessToken
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_APPS_ID);

        $request = static::postJSONRequest($url, false, $map);

        $appTokenRequest = new static($request);

        try {
            $data = $appTokenRequest->execute();
        } catch (Exception $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_APPS_ID, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_TOKEN, $exc);
            throw new PlenigoException(self::ERR_MSG_TOKEN, $errorCode, $exc);
        }

        $result = AppAccessData::createFromMap((array) $data);

        return $result;
    }

    /**
     * 
     * 
     * @param string $customerId
     * @param string $productId
     * @param string $appId
     * @return boolean
     */
    public static function hasUserBought($customerId, $productId, $appId) {
        $map = array(
            'companyId' => PlenigoManager::get()->getCompanyId(),
            'secret' => PlenigoManager::get()->getSecret(),
            'testMode' => PlenigoManager::get()->isTestMode()
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_PROD_ACCESS);
        $url = str_ireplace(ApiParams::URL_PROD_ID_TAG, $productId, $url);
        $url = str_ireplace(ApiParams::URL_APP_ID_TAG, $appId, $url);

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        try {
            $data = $appTokenRequest->execute();
        } catch (Exception $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_PROD_ACCESS, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_ACCESS, $exc);
            return false;
        }

        // 200 or 204 will return true
        return true;
    }

    public static function deleteCustomerApp($customerId, $appId) {
        $map = array(
            'companyId' => PlenigoManager::get()->getCompanyId(),
            'secret' => PlenigoManager::get()->getSecret(),
            'testMode' => PlenigoManager::get()->isTestMode()
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_DELETE_APP);
        $url = str_ireplace(ApiParams::URL_APP_ID_TAG, $appId, $url);
        
        $request = static::deleteRequest($url, false, $map);

        $appTokenRequest = new static($request);

        try {
            $data = $appTokenRequest->execute();
        } catch (Exception $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_DELETE_APP, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_DELETE, $exc);
            throw new PlenigoException(self::ERR_MSG_DELETE, $errorCode, $exc);
        }
    }

    /**
     * Executes the prepared request and returns
     * the Response object on success.
     *
     * @return The request's response.
     *
     * @throws \plenigo\PlenigoException on request error.
     */
    public function execute() {
        try {
            $response = parent::execute();
        } catch (\Exception $exc) {
            throw new PlenigoException('App Management Service execution failed!', $exc->getCode(), $exc);
        }

        return $response;
    }

}
