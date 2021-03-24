<?php

namespace plenigo\internal;

/**
 * <p>
 * The URLs that are used internally by the SDK.
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternal
 * @link     https://www.plenigo.com
 *
 */

final class ApiURLs
{
    /**
     * Default plenigo URL.
     */
    const DEFAULT_PLENIGO_URL = "https://api.plenigo.com";

    /**
     * OAuth2 plenigo URL.
     */
    const OAUTH_PLENIGO_URL = "https://api.plenigo.com";

    /**
     * This URL is used to add user access rights.
     */
    const PRODUCT_ACCESS_RIGHTS_ADDITION_URL = "/api/v2/access/{USER_ID}/add";

    /**
     * This URL is used to add user access rights.
     */
    const PRODUCT_ACCESS_RIGHTS_ADDITION_WITH_DETAILS_URL = "/api/v2/access/{USER_ID}/addWithDetails";

    /**
     * This URL is used to remove user access rights.
     */
    const PRODUCT_ACCESS_RIGHTS_REMOVAL_URL = "/api/v2/access/{USER_ID}/remove";

    /**
     * This URL is used to check if an user has access to a product.
     * This is usually used to see if a user has bought a product or not.
     */
    const USER_PRODUCT_ACCESS = "/api/v2/user/product";

    /**
     * This URL is used to get user information with the given access token.
     */
    const USER_PROFILE = "/api/v2/user/profile";

    /**
     * This URL is used to verify the user's login
     */
    const USER_LOGIN = "/api/v2/user/verifyLogin";

    /**
     * This URL is used to get user information by the session cookie.
     */
    const USER_PROFILE_BY_SESSION_COOKIE = "/api/v2/user/profile/sessionCookie";

    /**
     * This URL is used to retrieve an access token,
     * this is usually called after an access code has been given.
     */
    const GET_ACCESS_TOKEN = "/api/v2/oauth2/verify";

    /**
     * This URL is used to refresh an access token,
     * this is usually called after an access token has been expired
     * and the third party application still needs to access the user data.
     */
    const REFRESH_ACCESS_TOKEN = "/api/v2/oauth2/renew";

    /**
     * This URL is used to retrieve product information.
     */
    const GET_PRODUCT = "/api/v2/product";

    /**
     * This URL is used to get all the products
     */
    const LIST_PRODUCTS = "/api/v2/products/search";

    /**
     * This URL is used to get all the products with full details
     */
    const LIST_PRODUCTS_FULL_DETAILS = "/api/v2/products/search/full";

    /**
     * This URL is used to get all the products this user has bought
     */
    const USER_PRODUCTS = "/api/v2/user/{USER_ID}/products";

    /**
     * This URL is used to get all the products this user has bought
     */
    const USER_SUBSCRIPTIONS = "/api/v2/user/{USER_ID}/subscriptions";

    /**
     * This URL is used to get all the order this user has bought
     */
    const USER_ORDERS = "/api/v2/user/{USER_ID}/orders";

    /**
     * This URL is used to get all the products this user has bouight with their details
     */
    const USER_PRODUCTS_DETAILS = "/api/v2/user/product/details";

    /**
     * This URL is used to retrieve category information.
     */
    const GET_CATEGORY = "/api/v2/category";
    
    /**
     * This URL is used to get all the products
     */
    const LIST_CATEGORIES = "/api/v2/categories/search";
    
    /**
     * This URL is used to check for paywall disabled
     */
    const PAYWALL_STATE = "/api/v2/paywall/state";
    
    /**
     * This URL is used for merchant apps to get access from a customer to a product
     */
    const GET_APP_TOKEN = "/api/v2/access/app/token/{USER_ID}";

    /**
     * This URL is used for merchant apps to get all customer apps
     */
    const GET_APPS_ID = "/api/v2/access/app/{USER_ID}";
    
    /**
     * This URL is used to verify if merchant app has access to certain product
     */
    const GET_PROD_ACCESS = "/api/v2/access/app/{USER_ID}/{PROD_ID}/{APP_ID}";

    /**
     * This URL is used to delete a merchant application access from a certain product
     */   
    const DELETE_APP_ACCESS = "/api/v2/access/app/{USER_ID}/{APP_ID}";

    /**
     * This URL is used to verify if mobile app has access to certain customer
     */
    const MOBILE_SECRET_VERIFY = "/api/v2/access/mobileSecret/verify";

    /**
     * This URL is used to get, create or delete mobile secret data
     */
    const MOBILE_SECRET_URL = "/api/v2/access/mobileSecret/{USER_ID}";
    
    /**
     * This URL is used to create a new user bound to the company that registers the user.
     */
    const USER_MGMT_REGISTER = "/api/v2/externalUser/register";

    /**
     * This is the URL used to change email address of an existing user.
     */
    const USER_MGMT_CHANGEMAIL = "/api/v2/externalUser/{USER_ID}/changeEmail?useExternalCustomerId={USE_EXTERNAL_ID}";

    /**
     * This is the URL used to change email address of an existing user.
     */
    const USER_MGMT_CHANGEADDRESS = "/api/v2/user/{USER_ID}/address?useExternalCustomerId={USE_EXTERNAL_ID}";

    /**
     * This is the URL used to add an external user id to an existing user.
     */
    const USER_MGMT_ADDEXTERNALUSERID = "/api/v2/externalUser/{USER_ID}/addExternalUserId";

    /**
     * This URL is used to create a login token for an existing user.
     */
    const USER_MGMT_CREATELOGIN = "/api/v2/externalUser/{USER_ID}/createLoginToken?useExternalCustomerId={USE_EXTERNAL_ID}";
    
    /**
     * This URL is used to assign different customer ids to a plenigo customer.
     */
    const USER_MGMT_ACCESS = "/api/v2/import/customerAccess";
    
    /**
     * This URL is used to get the paginated list of changed users in a given time frame
     */
    const COMPANY_USERS_CHANGED = "/api/v2/company/users/search/changes";

    /**
     * This URL is used to get the paginated list of changed users in a given time frame
     */
    const COMPANY_INCOMING_PAYMENTS = "/api/v2/incomingPayments/search";

    /**
     * This URL is used to get the paginated list of changed users in a given time frame
     */
    const COMPANY_INVOICES = "/api/v2/invoices/search";

    /**
     * This URL is used to get the paginated list of company users
     */
    const COMPANY_USERS = "/api/v2/company/users";

    /**
     * This URL is used to get a list of company users base on their ids
     */
    const COMPANY_USERS_SELECT = "/api/v2/company/users/select";

    /**
     * This URL is used to get a list of company failed payments
     */
    const COMPANY_FAILED_PAYMENTS = "/api/v2/company/users/failed/payments";    

    /**
     * This URL is used to get a list of company orders
     */
    const COMPANY_ORDERS = "/api/v2/orders/search";


    /**
     * This URL is used to get a specific order
     */
    const COMPANY_ORDER = "/api/v2/orders/{ORDER_ID}";

    /**
     * This URL is used to get a list of company subscriptions
     */
    const COMPANY_SUBSCRIPTIONS = "/api/v2/subscriptions/search";

    /**
     * This URL is used to search for transactions for the company
     */
    const TX_SEARCH = "/api/v2/transactions/search";
    
    /**
     * Creates a voucher campaign and returns the amount of vouchers
     */
    const VOUCHER_CREATE = "/api/v2/voucher/create";

    /**
     * Execute a voucher checkout for a free product
     */
    const CHECKOUT_VOUCHER = "/api/v2/checkout/free/voucher/{VOUCHER_ID}/{USER_ID}";

    /**
     * Execute checkout for a free product
     */
    const CHECKOUT_FREE_PRODUCT = "/api/v2/checkout/free/product/{PROD_ID}/{USER_ID}";

    /**
     * Execute a checkout for a non free product
     */
    const CHECKOUT_PRODUCT = "/api/v2/checkout";

    /**
     * This URL is used to import users.
     */
    const USER_IMPORT_URL = "/api/v2/import/customers?useExternalCustomerId={USE_EXTERNAL_ID}";


}