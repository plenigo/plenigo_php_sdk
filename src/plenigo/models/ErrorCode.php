<?php

namespace plenigo\models;

require_once __DIR__ . '/../internal/utils/BasicEnum.php';
require_once __DIR__ . '/../internal/ApiURLs.php';

use plenigo\internal\ApiURLs;
use plenigo\internal\utils\BasicEnum;

/**
 * <p>
 * Error codes that can be returned by the API.
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 */
abstract class ErrorCode extends BasicEnum {

    const SERVER_ERROR = 0;
    const INVALID_PARAMETERS = 1;
    const CANNOT_ACCESS_PRODUCT = 2;
    const CRYPTOGRAPHY_ERROR = 3;
    const INVALID_SECRET_OR_COMPANY_ID = 4;
    const CONNECTION_ERROR = 5;
    const UNKNOWN_HOST = 6;
    const PRODUCT_NOT_FOUND = 7;
    const CATEGORY_NOT_FOUND = 8;
    const USER_NOT_FOUND = 9;
    const INTERNAL_ERROR = 10;
    const PRECONDITION_FAILED = 11;
    const LIMIT_REACHED = 12;
    const TOKEN_PROBLEM = 13;
    const NOT_ALLOWED = 14;
    const MOBILE_SECRET_PROBLEM = 15;
    const MOBILE_SECRET_NOT_FOUND = 16;
    const USER_MGMT_INVALID_COMPANY = 17;
    const USER_MGMT_NOT_FOUND = 18;
    const ID_DATA_NOT_FOUND = 19;
    const PRODUCT_NOT_FREE = 20;
    const HTTP_OK = 200;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
    const HTTP_PRECONDITION_FAILED = 412;
    const HTTP_LOCKED = 423;
    const HTTP_INTERNAL_ERROR = 500;
    const HTTP_NOT_IMPLEMENTED = 501;
    const HTTP_BAD_GATEWAY = 502;
    const HTTP_SERVICE_UNAVAILABLE = 503;

    private static $description = array(
        self::SERVER_ERROR =>
        'A server error has occured, we are informed about the problem and will try to solve it as fast as possible',
        self::INVALID_PARAMETERS =>
        'There are invalid errors in your request, please check the ErrorDetail list in this object for more details',
        self::CANNOT_ACCESS_PRODUCT => 'The user cannot access the queried product',
        self::CRYPTOGRAPHY_ERROR => 'Encryption error',
        self::INVALID_SECRET_OR_COMPANY_ID =>
        'The company id or secret provided were incorrect, please check your configuration',
        self::CONNECTION_ERROR => 'There was a connection error',
        self::UNKNOWN_HOST => 'Unable to resolve host',
        self::PRODUCT_NOT_FOUND => 'The provided product id is not valid',
        self::USER_NOT_FOUND => 'The provided user id is not valid',
        self::CATEGORY_NOT_FOUND => 'The provided category id is not valid',
        self::INTERNAL_ERROR => 'There was an internal server error',
        self::PRECONDITION_FAILED => 'The product is not owned by the suer',
        self::LIMIT_REACHED => 'The user has reached the limit of parallel app accesses',
        self::TOKEN_PROBLEM => 'Access Token not valid',
        self::NOT_ALLOWED => 'Access is not allowed',
        self::MOBILE_SECRET_PROBLEM => 'Mobile secret not valid',
        self::MOBILE_SECRET_NOT_FOUND => 'Mobile secret not found',
        self::USER_MGMT_INVALID_COMPANY => 'The company is not qualified for a closed user group',
        self::USER_MGMT_NOT_FOUND => 'The customer id was not found',
        self::ID_DATA_NOT_FOUND => 'Company id, customer id or product id is not valid',
        self::PRODUCT_NOT_FREE => 'The Product is not for free',
    );

    /**
     * This variable serves as translation between HTTP error codes and the
     * corresponding PlenigoException ErrorCodes
     *
     */
    private static $errorTranslation = array(
        ApiURLs::USER_PRODUCT_ACCESS => array(
            self::HTTP_FORBIDDEN => self::CANNOT_ACCESS_PRODUCT,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::USER_PRODUCTS => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::GET_PRODUCT => array(
            self::HTTP_NOT_FOUND => self::PRODUCT_NOT_FOUND,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::GET_CATEGORY => array(
            self::HTTP_NOT_FOUND => self::CATEGORY_NOT_FOUND,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::USER_PROFILE => array(
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::PAYWALL_STATE => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::LIST_PRODUCTS => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::LIST_CATEGORIES => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::GET_APP_TOKEN => array(
            self::HTTP_PRECONDITION_FAILED => self::PRECONDITION_FAILED,
            self::HTTP_LOCKED => self::LIMIT_REACHED,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::GET_APPS_ID => array(
            self::HTTP_FORBIDDEN => self::TOKEN_PROBLEM,
            self::HTTP_LOCKED => self::LIMIT_REACHED,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::GET_PROD_ACCESS => array(
            self::HTTP_FORBIDDEN => self::CANNOT_ACCESS_PRODUCT,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::MOBILE_SECRET_VERIFY => array(
            self::HTTP_FORBIDDEN => self::MOBILE_SECRET_PROBLEM,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::MOBILE_SECRET_URL => array(
            self::HTTP_NOT_FOUND => self::MOBILE_SECRET_NOT_FOUND,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::USER_MGMT_REGISTER => array(
            self::HTTP_FORBIDDEN => self::USER_MGMT_INVALID_COMPANY,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::USER_MGMT_CHANGEMAIL => array(
            self::HTTP_NOT_FOUND => self::USER_MGMT_NOT_FOUND,
            self::HTTP_FORBIDDEN => self::USER_MGMT_INVALID_COMPANY,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::USER_MGMT_CREATELOGIN => array(
            self::HTTP_NOT_FOUND => self::USER_MGMT_NOT_FOUND,
            self::HTTP_FORBIDDEN => self::USER_MGMT_INVALID_COMPANY,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::USER_MGMT_ACCESS => array(
            self::HTTP_NOT_FOUND => self::USER_MGMT_NOT_FOUND,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::COMPANY_USERS => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::COMPANY_USERS_SELECT => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::COMPANY_FAILED_PAYMENTS=> array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::COMPANY_ORDERS=> array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::TX_SEARCH => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::VOUCHER_CREATE => array(
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::CHECKOUT_VOUCHER => array(
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_NOT_FOUND => self::ID_DATA_NOT_FOUND,
            self::HTTP_PRECONDITION_FAILED => self::PRODUCT_NOT_FREE,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
        ApiURLs::CHECKOUT_FREE_PRODUCT=> array(
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_NOT_FOUND => self::ID_DATA_NOT_FOUND,
            self::HTTP_PRECONDITION_FAILED => self::PRODUCT_NOT_FREE,
            self::HTTP_INTERNAL_ERROR => self::INTERNAL_ERROR
        ),
    );

    /**
     * <p>
     * Obtain the error description for this ErrorCode. Description will be in english. 
     * For other languages see Tranlations
     * </p>
     *
     * @param int $code The code to get de Description of this kind of error.
     */
    public static function getDescription($code) {
        $desc = 'no-description';
        if (parent::isValidName($code)) {
            $desc = self::$description[$code];
        }
        return $desc;
    }

    /**
     * This function server as translation between HTTP error codes and the 
     * corresponding PlenigoException ErrorCodes
     * 
     * @param string $requestString The request path from the API
     * @param int    $httpCode The actual HTTP response status code to translate
     * @return int The actual Error Code corresponding to the HTTP status provided or NULL if not found
     */
    public static function getTranslation($requestString, $httpCode) {
        if (isset(self::$errorTranslation[$requestString])) {
            if (isset(self::$errorTranslation[$requestString][$httpCode])) {
                return self::$errorTranslation[$requestString][$httpCode];
            }
        }
        return null;
    }

}
