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
     * @param string $productId 
     * @param string $description
     * @return AppTokenData
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
     * 
     * 
     * @param string $customerId
     * @return array An array of AppAccessData objects
     * @throws PlenigoException
     */
    public static function getCustomerApps($customerId) {
        $map = array(
            'companyId' => PlenigoManager::get()->getCompanyId(),
            'secret' => PlenigoManager::get()->getSecret(),
            'testMode' => PlenigoManager::get()->isTestMode()
        );
        
        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_ALL_APPS);
        
        $request = static::getRequest($url, false, $map);
        
        $appTokenRequest = new static($request);
        
        try {
            $data = $appTokenRequest->execute();
        } catch (Exception $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_ALL_APPS, $exc->getCode());
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

    public static function requestAppId($customerId, $accessToken) {
        $map = array(
            'companyId' => PlenigoManager::get()->getCompanyId(),
            'secret' => PlenigoManager::get()->getSecret(),
            'testMode' => PlenigoManager::get()->isTestMode(),
            'accessToken' => $accessToken
        );
        
        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::GET_ALL_APPS);
        
        $request = static::postJSONRequest($url, false, $map);
        
        $appTokenRequest = new static($request);
        
        try {
            $data = $appTokenRequest->execute();
        } catch (Exception $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_ALL_APPS, $exc->getCode());
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
