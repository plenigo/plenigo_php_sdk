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

use plenigo\internal\ApiParams;
use plenigo\internal\ApiURLs;
use plenigo\internal\services\Service;
use plenigo\models\AppAccessData;
use plenigo\models\AppTokenData;
use plenigo\PlenigoException;
use plenigo\PlenigoManager;

/**
 * <p>
 * A class used to retrieve Access Tokens from the plenigo API
 * when given a valid Access Code.
 * </p>
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
     * 
     * @return AppTokenData the access token to get the App ID
     * 
     * @throws PlenigoException
     */
    public static function requestAppToken($customerId, $productId, $description) {
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';
        $map = array(
            ApiParams::TEST_MODE => $testModeText,
            'productId' => $productId,
            'description' => $description
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_APP_TOKEN);

        $request = static::postJSONRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::GET_APP_TOKEN, self::ERR_MSG_TOKEN);

        $result = AppTokenData::createFromMap((array) $data);

        return $result;
    }

    /**
     * Executes the request to get all App IDs for a given customer
     * 
     * @param string $customerId the Customer ID to send to the API
     * 
     * @return array An array of AppAccessData objects
     * 
     * @throws PlenigoException
     */
    public static function getCustomerApps($customerId) {
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';
        $map = array(
            ApiParams::TEST_MODE => $testModeText
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_APPS_ID);

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::GET_APPS_ID, self::ERR_MSG_TOKEN);

        $result = AppAccessData::createFromMapArray((array) $data);

        return $result;
    }

    /**
     * Executes the request to get App IDs for a given customer and product given its Access Token
     * 
     * @param string $customerId The Customer ID to send to the API
     * @param string $accessToken The Access Token 
     * 
     * @return AppAccessData the access data with the App ID
     * 
     * @throws PlenigoException
     */
    public static function requestAppId($customerId, $accessToken) {
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';
        $map = array(
             ApiParams::TEST_MODE => $testModeText,
            'accessToken' => $accessToken
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_APPS_ID);

        $request = static::postJSONRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::GET_APPS_ID, self::ERR_MSG_TOKEN);

        $result = AppAccessData::createFromMap($data);

        return $result;
    }

    /**
     * Verify if customer app is having access to a specific product
     * 
     * @param string $customerId The Customer ID to send to the API
     * @param string $productId The Product ID to send to the API
     * @param string $appId The App ID to send to the API
     * 
     * @return bool TRUE if the customer can access this product, FALSE otherwise
     */
    public static function hasUserBought($customerId, $productId, $appId) {
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';
        $map = array(
            ApiParams::TEST_MODE => $testModeText
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_PROD_ACCESS);
        $url = str_ireplace(ApiParams::URL_PROD_ID_TAG, $productId, $url);
        $url = str_ireplace(ApiParams::URL_APP_ID_TAG, $appId, $url);

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        try {
            parent::executeRequest($appTokenRequest, ApiURLs::GET_PROD_ACCESS, self::ERR_MSG_ACCESS);
        } catch (\Exception $exc) {
            return false;
        }

        // 200 or 204 will return true
        return true;
    }

    /**
     * Deletes a customer App ID to allow access with another app
     * 
     * @param string $customerId he Customer ID to send to the API
     * @param string $appId The App ID to send to the API
     * 
     * @throws PlenigoException
     */
    public static function deleteCustomerApp($customerId, $appId) {
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';
        $map = array(
            ApiParams::TEST_MODE => $testModeText
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::DELETE_APP_ACCESS);
        $url = str_ireplace(ApiParams::URL_APP_ID_TAG, $appId, $url);
        
        $request = static::deleteRequest($url, false, $map);

        $appTokenRequest = new static($request);

        parent::executeRequest($appTokenRequest, ApiURLs::DELETE_APP_ACCESS, self::ERR_MSG_DELETE);
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
