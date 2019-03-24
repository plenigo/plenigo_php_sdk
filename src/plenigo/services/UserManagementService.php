<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/ApiParams.php';
require_once __DIR__ . '/../internal/services/Service.php';

use plenigo\internal\ApiParams;
use plenigo\internal\ApiURLs;
use plenigo\internal\exceptions\RegistrationException;
use plenigo\internal\services\Service;
use plenigo\PlenigoException;
use plenigo\PlenigoManager;
use plenigo\internal\utils\RestClient;

/**
 * <p>
 * A class used to retrieve Access Tokens from the plenigo API
 * when given a valid Access Code.
 * </p>
 */
class UserManagementService extends Service
{

    const ERR_MSG_EMAIL = "Invalid email address!";
    const ERR_MSG_ADDRESS = "Invalid or empty address!";
    const ERR_MSG_REGISTER = "Error registering a customer";
    const ERR_MSG_CHANGEMAIL = "The email address could not be changed for this user";
    const ERR_MSG_ADDEXTERNALUSERID = "The external user id could not be added to this user";
    const ERR_MSG_CREATELOGIN = "Error creating a login token for the customer";
    const ERR_MSG_CUSTOMERIDS = "No customer identifiers provided";
    const ERR_MSG_CUSTOMERIDS_RESP = "Problem assigning customer access";

    /**
     * The constructor for the UserManagementService instance.
     *
     * @param RestClient $request The RestClient request to execute.
     *
     * @return UserManagementService instance.
     */
    public function __construct($request)
    {
        parent::__construct($request);
    }

    /**
     * Registers a new user bound to the company that registers the user. This functionality is only available for companies with closed user groups.
     *
     * @param string $email Email address of the user to register
     * @param string $language Language of the user as two digit ISO code
     * @param int $externalUserId An integer number that represents the user in the external system
     * @param string $firstName A given name for the new user
     * @param string $name A last name for the new user
     * @param boolean $withPasswordReset flag indicating if user should get an email with a one time password
     * @param boolean $failByExistingEmail flag indicating if the registration process should fail if the user is already registered
     *
     * @return string Id of the created customer.
     *
     * @throws PlenigoException | RegistrationException In case of communication errors or invalid parameters.
     */
    public static function registerUser($email, $language = "en", $externalUserId = null, $firstName = null, $name = null, $withPasswordReset = false, $failByExistingEmail = false)
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_EMAIL);
            return null;
        }

        $map = array(
            'email' => $email,
            'language' => $language,
            'withPasswordReset' => $withPasswordReset,
            'failByExistingEmail' => $failByExistingEmail
        );

        if (!is_null($externalUserId)) {
            $map["externalUserId"] = $externalUserId;
        }
        if (!is_null($firstName) && is_string($firstName)) {
            $map["firstName"] = $firstName;
        }
        if (!is_null($name) && is_string($name)) {
            $map["name"] = $name;
        }

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
     * @param bool $useExternalCustomerId Flag indicating if customer id sent is the external customer id
     *
     * @return bool TRUE Email address changed
     *
     * @throws PlenigoException | RegistrationException In case of communication errors or invalid parameters
     */
    public static function changeEmail($customerId, $email, $useExternalCustomerId = false)
    {

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_EMAIL);
            return false;
        }

        $map = array(
            'email' => $email
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::USER_MGMT_CHANGEMAIL);
        $url = str_ireplace(ApiParams::URL_USE_EXTERNAL_ID_TAG, var_export($useExternalCustomerId, true), $url);

        $request = static::putJSONRequest($url, $map);

        $curlRequest = new static($request);

        parent::executeRequest($curlRequest, ApiURLs::USER_MGMT_CHANGEMAIL, self::ERR_MSG_CHANGEMAIL);

        return true;
    }

    /**
     * Change address of an existing user.
     * @link https://plenigo.github.io/user_management_php
     *
     * @param string $customerId Customer id of the user to change address for
     * @param array $address
     * @param bool $useExternalCustomerId Flag indicating if customer id sent is the external customer id
     * @return bool TRUE address changed
     *
     * @throws PlenigoException | RegistrationException In case of communication errors or invalid parameters
     */
    public static function changeAddress($customerId, $address, $useExternalCustomerId = false)
    {

        if (empty($address) || !is_array($address) || !isset($address['gender'])) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_ADDRESS);
            return false;
        }

        if (!empty($address['street']) && empty($address['country'])) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_ADDRESS);
            return false;
        }

        $address['gender'] = strtoupper($address['gender']);

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::USER_MGMT_CHANGEADDRESS);
        $url = str_ireplace(ApiParams::URL_USE_EXTERNAL_ID_TAG, var_export($useExternalCustomerId, true), $url);

        $request = static::putJSONRequest($url, $address);

        $curlRequest = new static($request);

        parent::executeRequest($curlRequest, ApiURLs::USER_MGMT_CHANGEMAIL, self::ERR_MSG_CHANGEMAIL);

        return true;
    }

    /**
     * Add an external user id to a user that doesn't have one yet. This functionality is only available for companies with closed user groups.
     *
     * @param string $customerId Customer id of the user to change email address for
     * @param string $externalCustomerId external customer id
     *
     * @return bool TRUE external customer id added
     *
     * @throws PlenigoException | RegistrationException In case of communication errors or invalid parameters
     */
    public static function addExternalCustomerId($customerId, $externalCustomerId)
    {
        $map = array(
            'externalUserId' => $externalCustomerId
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::USER_MGMT_ADDEXTERNALUSERID);

        $request = static::putJSONRequest($url, $map);

        $curlRequest = new static($request);

        parent::executeRequest($curlRequest, ApiURLs::USER_MGMT_ADDEXTERNALUSERID, self::ERR_MSG_ADDEXTERNALUSERID);

        return true;
    }

    /**
     * Create a login token for an existing user. This functionality is only available for companies with closed user groups.
     *
     * @param string $customerId Customer id of the user to create login token for
     * @param bool $useExternalCustomerId Flag indicating if customer id sent is the external customer id
     *
     * @return string One time token used to create a valid user session
     *
     * @throws PlenigoException | RegistrationException In case of communication errors or invalid parameters
     */
    public static function createLoginToken($customerId, $useExternalCustomerId = false)
    {

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::USER_MGMT_CREATELOGIN);
        $url = str_ireplace(ApiParams::URL_USE_EXTERNAL_ID_TAG, var_export($useExternalCustomerId, true), $url);

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
     * Provide several (up to 4) access ids to a company customer id (which can be an external Id already).
     *
     * @param string $customerId The plenigo (or external) customer id
     * @param bool $isExternal TRUE if the previous id was an external id (default: FALSE)
     * @param array $customIds an array of one to four customer access ids
     *
     * @return boolean TRUE if the transaction was successful
     *
     * @throws PlenigoException | RegistrationException In case of communication errors or invalid parameters
     */
    public static function importCustomerAccess($customerId, $isExternal = false, $customIds = array())
    {
        $map = array(
            'customerId' => $customerId,
            'useExternalCustomerId' => $isExternal
        );

        if (!is_array($customIds) || count($customIds) == 0) {
            $clazz = get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_CUSTOMERIDS);
            return false;
        }

        if (isset($customIds[0])) {
            $map['yourFirstIdentifier'] = $customIds[0];
        }
        if (isset($customIds[1])) {
            $map['yourSecondIdentifier'] = $customIds[1];
        }
        if (isset($customIds[2])) {
            $map['yourThirdIdentifier'] = $customIds[2];
        }
        if (isset($customIds[3])) {
            $map['yourForthIdentifier'] = $customIds[3];
        }

        $url = ApiURLs::USER_MGMT_ACCESS;

        $request = static::postJSONRequest($url, false, $map);

        $curlRequest = new static($request);

        $data = parent::executeRequest($curlRequest, ApiURLs::USER_MGMT_ACCESS, self::ERR_MSG_CUSTOMERIDS_RESP);

        return true;
    }

    /**
     * Executes the prepared request and returns
     * the Response object on success.
     *
     * @return mixed The request's response.
     *
     * @throws PlenigoException | RegistrationException on request error.
     */
    public function execute()
    {
        try {
            $response = parent::execute();
        } catch (RegistrationException $exception) {
          throw $exception;
        } catch (\Exception $exc) {
            throw new PlenigoException('User Management Service execution failed!', $exc->getCode(), $exc);
        }
        return $response;
    }
}