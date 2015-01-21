<?php
 
namespace plenigo\internal;

require_once __DIR__ . '/utils/BasicEnum.php';

use plenigo\internal\utils\BasicEnum;

/**
 * <p>
 * This class contains api result variables
 * that are used through the different methods
 * of the plenigo API.
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternal
 * @author Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class ApiResults extends BasicEnum
{

    const ERROR = "error";
    const ERROR_DESCRIPTION = "error_description";
    const DESCRIPTION = "description";
    const ACCESS_TOKEN = "access_token";
    const EXPIRES_IN = "expires_in";
    const REFRESH_TOKEN = "refresh_token";
    const STATE = "state";
    const COLLECTIBLE = "collectible";

    /**
     * Timestamp result variable.
     */
    const TIMESTAMP = "ts";

    /**
     * The customer id variable.
     */
    const CUSTOMER_ID = "ci";

    /**
     * Error message parameter when the plenigo API
     * returns a BAD_REQUEST http response code.
     */
    const ERROR_MSG = "Error";

    /**
     * The user id variable.
     */
    const USER_ID = "userId";

    /**
     * E-mail.
     */
    const EMAIL = "email";

    /**
     * Gender prefix (e.g. Mr. or Mrs.).
     */
    const GENDER = "gender";

    /**
     * User's name.
     */
    const LAST_NAME = "name";

    /**
     * User's first name.
     */
    const FIRST_NAME = "firstName";
    const STREET = "street";
    const ADDITIONAL_ADDRESS_INFO = "additionalAddressInfo";
    const POST_CODE = "postCode";
    const CITY = "city";
    const COUNTRY = "country";

    /**
     * Product attributes
     */
    const PRODUCT_ID = "id";
    const SUBSCRIPTION = "subscription";
    const TITLE = "title";
    const URL = "url";
    const CAN_CHOOSE_PRICE = "choosePrice";
    const PRICE = "price";
    const TYPE = "type";
    const CURRENCY = "currency";
    const TERM = "term";
    const CANCELLATION_PERIOD = "cancellationPeriod";
    const AUTO_RENEWAL = "autoRenewal";
    const ACTION_PERIOD_NAME = "actionPeriodName";
    const ACTION_PERIOD_TERM = "actionPeriodTerm";
    const ACTION_PERIOD_PRICE = "actionPeriodPrice";
    const IMAGES = "images";
    const ALT_TEXT = "altText";

}