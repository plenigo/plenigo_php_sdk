<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/utils/SdkUtils.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/ApiParams.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../models/MobileSecretData.php';
require_once __DIR__ . '/../models/ErrorCode.php';

use plenigo\internal\ApiParams;
use plenigo\internal\ApiURLs;
use plenigo\internal\services\Service;
use plenigo\PlenigoException;
use plenigo\PlenigoManager;

/**
 * Access service
 *
 * <p>
 * A class used to manage a user's access to a product.
 * </p>
 *
 * @category SDK
 * @package  PlenigoServices
 * @link     https://www.plenigo.com
 */
class AccessService extends Service
{
    const ERR_MSG_DELETE = "Could not remove user access to product.";
    const ERR_MSG_POST = "Could not grant user access to product.";

    /**
     * The constructor for the AccessService instance.
     *
     * @param \plenigo\internal\utils\RestClient $request The RestClient request to execute.
     *
     * @return AccessService instance.
     */
    public function __construct($request)
    {
        parent::__construct($request);
    }

    /**
     * Grant a user the right to access one or multiple products.
     *
     * @param string $customerId The Customer ID
     * @param boolean $useExternalCustomerId flag indicating if customer id is an external customer id
     * @param string $startTime time when access should start in the format Y-m-d
     * @param string $endTime time when access should end in the format Y-m-d
     * @param array $productIds ids of the products to grant customer access to
     *
     * @throws PlenigoException
     */
    public static function grantUserAccess($customerId, $useExternalCustomerId, $startTime, $endTime, $productIds)
    {
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';
        $map = array(
            'useExternalCustomerId' => $useExternalCustomerId,
            ApiParams::TEST_MODE => $testModeText,
            'productIds' => $productIds
        );

        if(!empty($startTime)) {
            $map['startTime'] = date('Y-m-d', strtotime($startTime));
        }

        if(!empty($endTime)) {
            $map['endTime'] = date('Y-m-d', strtotime($endTime));
        }

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::PRODUCT_ACCESS_RIGHTS_ADDITION_URL);

        $request = static::postJSONRequest($url, false, $map);

        $appTokenRequest = new static($request);

        parent::executeRequest($appTokenRequest, ApiURLs::PRODUCT_ACCESS_RIGHTS_ADDITION_URL, self::ERR_MSG_POST);
    }


    /**
     * Grant a user the right to access one or multiple products with details.
     * Use this method to add things like receipts to each access right.
     * @see https://api.plenigo.com/#!/access/addUserAccessDetail
     *
     * @param string $customerId The Customer ID
     * @param boolean $useExternalCustomerId flag indicating if customer id is an external customer id
     * @param string $startTime time when access should start in the format Y-m-d
     * @param string $endTime time when access should end in the format Y-m-d
     * @param array $accessRights ids of the products to grant customer access to
     *  $accessRights = [
     *      [
     *      'productId' => (string, optional): Product id to add access for. ,
     *      'receipt'   => (string, optional): Receipt of the user. ,
     *      'source'    => (string, optional): Access right source.
     *      ],
     * ]
     *
     * @throws PlenigoException
     */
    public static function grantUserAccessWithDetails($customerId, $useExternalCustomerId, $startTime, $endTime, $accessRights = [])
    {
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';

        foreach ($accessRights as $accessRight) {
            if (empty($accessRight['productId'])) {
                throw new PlenigoException("Parameter accessRights hast to be of array format with the following structure: [\n[\n'productId' => (string),\n'receipt' => (string, optional),\n'source' => (string, optional),\n],\n]");
            }
        }

        $map = array(
            'useExternalCustomerId' => $useExternalCustomerId,
            ApiParams::TEST_MODE => $testModeText,
            'details' => $accessRights
        );

        if(!empty($startTime)) {
            $map['startTime'] = date('Y-m-d', strtotime($startTime));
        }

        if(!empty($endTime)) {
            $map['endTime'] = date('Y-m-d', strtotime($endTime));
        }

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::PRODUCT_ACCESS_RIGHTS_ADDITION_WITH_DETAILS_URL);

        $request = static::postJSONRequest($url, false, $map);

        $appTokenRequest = new static($request);

        parent::executeRequest($appTokenRequest, ApiURLs::PRODUCT_ACCESS_RIGHTS_ADDITION_WITH_DETAILS_URL, self::ERR_MSG_POST);
    }

    /**
     * Removes a user's access right from one or multiple products.
     *
     * @param string $customerId The Customer ID
     * @param boolean $useExternalCustomerId flag indicating if customer id is an external customer id
     * @param array $productIds ids of the products to grant customer access to
     *
     * @throws PlenigoException
     */
    public static function removeUserAccess($customerId, $useExternalCustomerId, $productIds)
    {
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';
        $params = array(
            'useExternalCustomerId' => $useExternalCustomerId,
            ApiParams::TEST_MODE => $testModeText,
            'productIds' => $productIds
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::PRODUCT_ACCESS_RIGHTS_REMOVAL_URL);

        $request = static::deleteRequest($url, false, $params);

        $appTokenRequest = new static($request);

        parent::executeRequest($appTokenRequest, ApiURLs::PRODUCT_ACCESS_RIGHTS_REMOVAL_URL, self::ERR_MSG_DELETE);
    }
}
