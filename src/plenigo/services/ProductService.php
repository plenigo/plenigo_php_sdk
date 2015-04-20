<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../models/ProductData.php';
require_once __DIR__ . '/../models/CategoryData.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/utils/RestClient.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../internal/utils/EncryptionUtils.php';
require_once __DIR__ . '/../internal/utils/SdkUtils.php';
require_once __DIR__ . '/../internal/ApiResults.php';
require_once __DIR__ . '/../internal/ApiParams.php';
require_once __DIR__ . '/../models/ErrorCode.php';

use plenigo\internal\ApiParams;
use plenigo\internal\ApiURLs;
use plenigo\internal\services\Service;
use plenigo\models\CategoryData;
use plenigo\models\ErrorCode;
use plenigo\models\ProductData;
use plenigo\PlenigoException;
use plenigo\PlenigoManager;

/**
 * ProductService
 *
 * <b>
 * This contains the services required  for Product management with plenigo.
 * </b>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class ProductService extends Service {

    const ERR_MSG_PROD_DATA = "Could not retrieve Product Data!";
    const ERR_MSG_CAT_DATA = "Could not retrieve Category Data!";
    const ERR_MSG_PROD_LIST = "Could not retrieve Product List!";
    const ERR_MSG_CAT_LIST = "Could not retrieve Category List!";
    const ERR_MSG_PLIST_BUILD = "Could not build Product List from results!";
    const ERR_MSG_CLIST_BUILD = "Could not build Category List from results!";
    const ERR_MSG_PDATA_BUILD = "Could not build Product Data from results!";
    const ERR_MSG_CDATA_BUILD = "Could not build Category Data from results!";
    const ERR_MSG_NOT_FOUND = "The product was not found! ID=";
    const ERR_MSG_SECRET_COMPANY = "There was an error with the Company ID and/or the Secret Key!";
    const ERR_MSG_INVALID_PARAMS = "There are invalid parameters in this request!";

    /**
     * This method retrieves the product data of a provided product id.
     * This can only be used for plenigo managed products.
     *
     * @param string $productId The product id to use.
     * @return ProductData the product data related to the access token
     * @throws PlenigoException whenever an error happens
     */
    public static function getProductData($productId) {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Getting Product data for ProductID=" . $productId);

        $params = array(
            ApiParams::COMPANY_ID => PlenigoManager::get()->getCompanyId(),
            ApiParams::SECRET => PlenigoManager::get()->getSecret()
        );

        $request = static::getRequest(ApiURLs::GET_PRODUCT . "/" . $productId, false, $params);

        $prodDataRequest = new static($request);

        try {
            $response = $prodDataRequest->execute();
        } catch (PlenigoException $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_PRODUCT, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }

            // Specific Error Code
            if ($errorCode === ErrorCode::PRODUCT_NOT_FOUND) {
                PlenigoManager::warn($clazz, self::ERR_MSG_NOT_FOUND . $productId, $exc);
                throw new PlenigoException(self::ERR_MSG_NOT_FOUND . $productId, $errorCode, $exc);
            }
            throw self::getException($exc, $errorCode, self::ERR_MSG_PROD_DATA);
        }

        try {
            $prodData = self::buildProductData($response);
        } catch (\Exception $exc) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_PDATA_BUILD, $exc);
            throw new PlenigoException(self::ERR_MSG_PDATA_BUILD, $exc->getCode(), $exc);
        }

        return $prodData;
    }

    /**
     * Constructs a ProductData object populated from a response object
     *
     * @param mixed $response the response object from the cURL call
     * @return ProductData the resulting ProductData object
     */
    private static function buildProductData($response) {
        return ProductData::createFromMap(get_object_vars($response));
    }

    /**
     * Obtain a list of product in a paginated way.
     *
     * @param int $pageSize The size of the page, it will be trimmed to 10...100
     * @param string $lastID the last id of the page that will set the page number to be requested
     * @return Array an asociative array as a ResultSet with totalElements, page size, last id and the list of products
     * @throws PlenigoException
     */
    public static function getProductList($pageSize = 10, $lastID = null) {
        $clazz = get_class();
        PlenigoManager::notice(
                $clazz, "Getting Product Listing (page size=" . $pageSize . ' lastID=' . $lastID . ')');


        $params = self::configureListParams($pageSize, $lastID);

        $request = static::getRequest(ApiURLs::LIST_PRODUCTS, false, $params);

        $prodDataRequest = new static($request);

        try {
            $response = $prodDataRequest->execute();
        } catch (PlenigoException $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::LIST_PRODUCTS, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }

            throw self::getException($exc, $errorCode, self::ERR_MSG_PROD_LIST);
        }

        try {
            $prodData = get_object_vars($response);
        } catch (\Exception $exc) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_PLIST_BUILD, $exc);
            throw new PlenigoException(self::ERR_MSG_PLIST_BUILD, $exc->getCode(), $exc);
        }

        return $prodData;
    }

    /**
     * This method retrieves the category data of a provided category id.
     * This can only be used for plenigo managed categories.
     *
     * @param string $categoryId The category id to use.
     * @return the category data related to the access token
     * @throws PlenigoException whenever an error happens
     */
    public static function getCategoryData($categoryId) {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Getting Category data for CategoryID=" . $categoryId);

        $params = array(
            ApiParams::COMPANY_ID => PlenigoManager::get()->getCompanyId(),
            ApiParams::SECRET => PlenigoManager::get()->getSecret()
        );

        $request = static::getRequest(ApiURLs::GET_CATEGORY . "/" . $categoryId, false, $params);

        $categoryDataRequest = new static($request);

        try {
            $response = $categoryDataRequest->execute();
        } catch (PlenigoException $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_CATEGORY, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }

            // Specific Error Code
            if ($errorCode === ErrorCode::CATEGORY_NOT_FOUND) {
                PlenigoManager::warn($clazz, self::ERR_MSG_NOT_FOUND . $categoryId, $exc);
                throw new PlenigoException(self::ERR_MSG_NOT_FOUND . $categoryId, $errorCode, $exc);
            }
            throw self::getException($exc, $errorCode, self::ERR_MSG_CAT_DATA);
        }

        try {
            $categoryData = self::buildCategoryData($response);
        } catch (\Exception $exc) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_CDATA_BUILD, $exc);
            throw new PlenigoException(self::ERR_MSG_CDATA_BUILD, $exc->getCode(), $exc);
        }

        return $categoryData;
    }

    /**
     * Constructs a CategoryData object populated fom a response object
     *
     * @param mixed $response the response object from the cURL call
     * @return CategoryData the resulting CategoryData object
     */
    private static function buildCategoryData($response) {
        return CategoryData::createFromMap(get_object_vars($response));
    }

    /**
     * Obtain a list of categories in a paginated way.
     *
     * @param int $pageSize The size of the page, it will be trimmed to 10...100
     * @param string $lastID the last id of the page that will set the page number to be requested
     * @return Array an asociative array as a ResultSet with totalElements, page size, last id and the list of products
     * @throws PlenigoException
     */
    public static function getCategoryList($pageSize = 10, $lastID = null) {
        $clazz = get_class();
        PlenigoManager::notice(
                $clazz, "Getting Category Listing (page size=" . $pageSize . ' lastID=' . $lastID . ')');

        $params = self::configureListParams($pageSize, $lastID);

        $request = static::getRequest(ApiURLs::LIST_CATEGORIES, false, $params);

        $catRequest = new static($request);

        try {
            $response = $catRequest->execute();
        } catch (PlenigoException $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::LIST_CATEGORIES, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }

            throw self::getException($exc, $errorCode, self::ERR_MSG_CAT_LIST);
        }

        try {
            $catData = get_object_vars($response);
        } catch (\Exception $exc) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_CLIST_BUILD, $exc);
            throw new PlenigoException(self::ERR_MSG_CLIST_BUILD, $exc->getCode(), $exc);
        }

        return $catData;
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
            throw new PlenigoException('Product Service execution failed!', $exc->getCode(), $exc);
        }

        return $response;
    }

    /**
     * Prepares a correct exception to be thrown in response to a given error code from the services.
     * This method can't actually throw aan Exception because it will be called from inside a catch
     * block and the Exception would be occluded by a run-time error.
     *
     * @param Exception $exc the previous exception thrown
     * @param int $errorCode the error code for the exception
     * @param string $defaultMsg the error message for default error
     * @return PlenigoException the exception that needs to be thrown
     */
    private static function getException($exc, $errorCode, $defaultMsg) {
        $clazz = get_class();
        switch ($errorCode) {
            case ErrorCode::INVALID_SECRET_OR_COMPANY_ID:
                PlenigoManager::error($clazz, self::ERR_MSG_SECRET_COMPANY, $exc);
                return new PlenigoException(self::ERR_MSG_SECRET_COMPANY, $errorCode, $exc);
            case ErrorCode::INVALID_PARAMETERS:
                PlenigoManager::error($clazz, self::ERR_MSG_INVALID_PARAMS, $exc);
                return new PlenigoException(self::ERR_MSG_INVALID_PARAMS, $errorCode, $exc);
            default:
                PlenigoManager::error($clazz, $defaultMsg, $exc);
                return new PlenigoException($defaultMsg, $errorCode, $exc);
        }
    }

    /**
     * Create an array with the Company ID, the Secret, a given page size and an optional Last ID for product
     * or category listings.
     *
     * @param int $pageSize The number of items on a single page (min:10, max:100)
     * @param string $lastID Optional. A string containing the last ID of the current page
     * @return array A key=>value array to convert to queryString for the URL
     */
    private static function configureListParams($pageSize = 10, $lastID = null) {
        $size = max(min($pageSize, 100), 10);

        return array(
            ApiParams::COMPANY_ID => PlenigoManager::get()->getCompanyId(),
            ApiParams::SECRET => PlenigoManager::get()->getSecret(),
            'size' => $size,
            'lastId' => (!is_null($lastID)) ? $lastID : ''
        );
    }

}
