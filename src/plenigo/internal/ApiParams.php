<?php

namespace plenigo\internal;

use plenigo\internal\utils\BasicEnum;

/**
 * <p>
 * This class contains api parameters
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
class ApiParams extends BasicEnum
{

    /**
     * The product price parameter.
     */
    const PROD_PRICE = "pr";

    /**
     * The product price currency parameter.
     */
    const CURRENCY = "cu";

    /**
     * The product type parameter.
     */
    const PROD_TYPE = "pt";

    /**
     * The product title parameter.
     */
    const PROD_TITLE = "ti";

    /**
     * The product id parameter.
     */
    const PROD_ID = "pi";

    /**
     * The category id parameter.
     */
    const CAT_ID = "ci";

    /**
     * The info screen shown on retry parameter.
     */
    const INFO_SCRN_SHOWN_ON_RETRY = "sab";

    /**
     * The info screen shown at the end of a payment parameter.
     */
    const INFO_SCRN_SHOWN_AT_END_OF_PAYMENT = "spf";

    /**
     * Custom amount parameter.
     */
    const CUSTOM_AMOUNT = "sp";

    /**
     * The test transaction parameter.
     */
    const TEST_TRANSACTION = "ts";

    /**
     * cross-site request forgery token parameter.
     */
    const CSRF_TOKEN = "csrf";

    /**
     * single sign on parameter.
     */
    const SINGLE_SIGN_ON = "sso";

    /**
     * Company Id parameter.
     */
    const COMPANY_ID = "companyId";

    /**
     * Secret parameter.
     */
    const SECRET = "secret";

    /**
     * Checksum parameter.
     */
    const CHECKSUM = "checksum";

    /**
     * Unique customer ID parameter.
     */
    const CUSTOMER_ID = "customerId";

    /**
     * Unique product ID parameter.
     */
    const PRODUCT_ID = "productId";

    /**
     * Test mode parameter.
     */
    const TEST_MODE = "testMode";

    /**
     * Access token parameter.
     */
    const ACCESS_TOKEN = "token";

    /**
     * The grant type of the token.
     */
    const TOKEN_GRANT_TYPE = "grant_type";

    /**
     * The access code given by a redirect url during oauth authentication.
     */
    const OAUTH_ACCESS_CODE = "code";

    /**
     * Redirect URL parameter.
     */
    const REDIRECT_URI = "redirect_uri";

    /**
     * The customer id parameter in OAuth standard.
     */
    const CLIENT_ID = "client_id";

    /**
     * The secret key parameter.
     */
    const CLIENT_SECRET = "client_secret";

    /**
     * The CSRF Token parameter in OAuth standard.
     */
    const STATE = "state";

    /**
     * The Refresh token parameter in OAuth standard.
     */
    const REFRESH_TOKEN = "refresh_token";

}