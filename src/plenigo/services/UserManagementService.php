<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/ApiParams.php';
require_once __DIR__ . '/../internal/services/Service.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiURLs;
use \plenigo\internal\ApiParams;
use \plenigo\internal\services\Service;

/**
 * UserManagementService
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
class UserManagementService extends Service {

    const ERR_MSG_EMAIL = "Invalid email address!";
    const ERR_MSG_REGISTER = "Error registering a customer";
    const ERR_MSG_CHANGEMAIL = "The Emails address could not be changed for this user";
    const ERR_MSG_CREATELOGIN = "Error creating a login token for the customer";

    /**
     * The constructor for the UserManagementService instance.
     *
     * @param RestClient $request   The RestClient request to execute.
     *
     * @return UserManagementService instance.
     */
    public function __construct($request) {
        parent::__construct($request);
    }

    /**
     * Registers a new user bound to the company that registers the user. This functionality is only available for companies with closed user groups.
     * 
     * @param string $email  Email address of the user to register
     * @param string $language Language of the user as two digit ISO code
     * @return string Id of the created customer.
     * @throws PlenigoException In case of communication errors or invalid parameters
     */
    public static function registerUser($email, $language = "en") {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_EMAIL);
            return null;
        }

        $map = array(
            'email' => $email,
            'language' => $language
        );

        $url = ApiURLs::USER_MGMT_REGISTER;

        $request = static::postJSONRequest($url, false, $map);

        $curlRequest = new static($request);

        $data = parent::executeRequest($curlRequest, ApiURLs::USER_MGMT_REGISTER, self::ERR_MSG_REGISTER);

        if (isset($data->customerId)) {
            $result = $data->customerId;
        } else {
            $result = "" . $data;
        }

        return $result;
    }

    /**
     * Change email address of an existing user. This functionality is only available for companies with closed user groups.
     * 
     * @param string $customerId Customer id of the user to change email address for
     * @param string $email New email address of user
     * @return bool TRUE Email address changed
     * @throws PlenigoException In case of communication errors or invalid parameters
     */
    public static function changeEmail($customerId, $email) {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_EMAIL);
            return false;
        }

        $map = array(
            'email' => $email
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::USER_MGMT_CHANGEMAIL);

        $request = static::putJSONRequest($url, $map);

        $curlRequest = new static($request);

        parent::executeRequest($curlRequest, ApiURLs::USER_MGMT_CHANGEMAIL, self::ERR_MSG_CHANGEMAIL);

        return true;
    }

    /**
     * Create a login token for an existing user. This functionality is only available for companies with closed user groups.
     * 
     * @param string $customerId Customer id of the user to create login token for
     * @return string One time token used to create a valid user session
     * @throws PlenigoException In case of communication errors or invalid parameters
     */
    public static function createLoginToken($customerId) {

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::USER_MGMT_CREATELOGIN);

        $request = static::postRequest($url);

        $curlRequest = new static($request);

        $data = parent::executeRequest($curlRequest, ApiURLs::USER_MGMT_CREATELOGIN, self::ERR_MSG_CREATELOGIN);

        if (isset($data->loginToken)) {
            $result = $data->loginToken;
        } else {
            $result = "" . $data;
        }

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
            throw new PlenigoException('User Management Service execution failed!', $exc->getCode(), $exc);
        }

        return $response;
    }

}
