<?php

namespace plenigo\models;

require_once __DIR__ . '/../internal/utils/BasicEnum.php';
require_once __DIR__ . '/../internal/ApiURLs.php';

use \plenigo\internal\utils\BasicEnum;
use \plenigo\internal\ApiURLs;

/**
 * <p>
 * Error codes that can be returned by the API.
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
abstract class ErrorCode extends BasicEnum
{

    const SERVER_ERROR = 0;
    const INVALID_PARAMETERS = 1;
    const CANNOT_ACCESS_PRODUCT = 2;
    const CRYPTOGRAPHY_ERROR = 3;
    const INVALID_SECRET_OR_COMPANY_ID = 4;
    const CONNECTION_ERROR = 5;
    const UNKNOWN_HOST = 6;
    const PRODUCT_NOT_FOUND = 7;
    const CATEGORY_NOT_FOUND = 8;
    const HTTP_OK = 200;
    const HTTP_BAD_REQUEST = 400;
    const HTTP_UNAUTHORIZED = 401;
    const HTTP_FORBIDDEN = 403;
    const HTTP_NOT_FOUND = 404;
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
        self::CRYPTOGRAPHY_ERROR => '',
        self::INVALID_SECRET_OR_COMPANY_ID =>
        'The company id or secret provided were incorrect, please check your configuration',
        self::CONNECTION_ERROR => '',
        self::UNKNOWN_HOST => '',
        self::PRODUCT_NOT_FOUND => 'The provided product id is not valid',
        self::CATEGORY_NOT_FOUND => 'The provided category id is not valid'
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
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS
        ),
        ApiURLs::GET_PRODUCT => array(
            self::HTTP_NOT_FOUND => self::PRODUCT_NOT_FOUND,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS
        ),
        ApiURLs::GET_CATEGORY => array(
            self::HTTP_NOT_FOUND => self::CATEGORY_NOT_FOUND,
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS
        ),
        ApiURLs::USER_PROFILE => array(
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS
        ),
        ApiURLs::PAYWALL_STATE => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS
        ),
        ApiURLs::LIST_PRODUCTS => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS
        ),
        ApiURLs::LIST_CATEGORIES => array(
            self::HTTP_UNAUTHORIZED => self::INVALID_SECRET_OR_COMPANY_ID,
            self::HTTP_BAD_REQUEST => self::INVALID_PARAMETERS
        )
    );

    /**
     * <p>
     * Obtain the error description for this ErrorCode. Description will be in english. 
     * For other languages see Tranlations
     * </p>
     *
     * @param int $code The code to get de Description of this kind of error.
     */
    public static function getDescription($code)
    {
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
    public static function getTranslation($requestString, $httpCode)
    {
        if (isset(self::$errorTranslation[$requestString])) {
            if (isset(self::$errorTranslation[$requestString][$httpCode])) {
                return self::$errorTranslation[$requestString][$httpCode];
            }
        }
        return null;
    }

}