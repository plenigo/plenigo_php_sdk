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
use plenigo\internal\utils\SdkUtils;
use plenigo\models\MobileSecretData;
use plenigo\PlenigoException;

/**
 * <p>
 * A class used to retrieve Access Tokens from the plenigo API
 * when given a valid Access Code.
 * </p>
 */
class MobileService extends Service {

    const ERR_MSG_VERIFY = "Error during mobile secret verification";
    const ERR_MSG_GET = "Error getting mobile secret";
    const ERR_MSG_POST = "Error creating mobile secret";
    const ERR_MSG_DELETE = "Error deleting mobile secret";

    /**
     * The constructor for the MobileService instance.
     *
     * @param RestClient $request   The RestClient request to execute.
     *
     * @return MobileService instance.
     */
    public function __construct($request) {
        parent::__construct($request);
    }

    /**
     * This method allows to verify if mobile app has access to certain customer
     * 
     * @param string $email The user email
     * @param string $mobileSecret The mobile secret to verify
     * 
     * @return string The Customer ID for that mobile secret
     * 
     * @throws PlenigoException
     */
    public static function verifyMobileSecret($email, $mobileSecret) {
        $map = array(
            'email' => $email,
            'mobileSecret' => $mobileSecret
        );

        $url = ApiURLs::MOBILE_SECRET_VERIFY;

        $request = static::getRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::MOBILE_SECRET_VERIFY, self::ERR_MSG_VERIFY);

        if (isset($data->customerId)) {
            $result = $data->customerId;
        } else {
            $result = "" . $data;
        }

        return $result;
    }

    /**
     * Get the mobile secret for a given Customer ID
     * 
     * @param string $customerId The Customer ID
     * @return MobileSecretData the email and secret for the customer mobile
     * @throws PlenigoException
     */
    public static function getMobileSecret($customerId) {

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::MOBILE_SECRET_URL);

        $request = static::getRequest($url, false);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::MOBILE_SECRET_URL, self::ERR_MSG_GET);

        $result = MobileSecretData::createFromMap((array) $data);

        return $result;
    }

    /**
     * Creates a mobile secret for a given Customer ID
     * 
     * @param string $customerId The Customer ID
     * @param int $size The size of the mobile secret 6 to 40
     * 
     * @return MobileSecretData the email and secret for the customer mobile
     * 
     * @throws PlenigoException
     */
    public static function createMobileSecret($customerId, $size) {
        $map = array(
            'size' => SdkUtils::clampNumber($size, 6, 40)
        );

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::MOBILE_SECRET_URL);

        $request = static::postJSONRequest($url, false, $map);

        $appTokenRequest = new static($request);

        $data = parent::executeRequest($appTokenRequest, ApiURLs::MOBILE_SECRET_URL, self::ERR_MSG_POST);

        $result = MobileSecretData::createFromMap((array) $data);

        return $result;
    }

    /**
     * Deletes a mobile secret for a given Customer ID
     * 
     * @param string $customerId The Customer ID
     * @throws PlenigoException
     */
    public static function deleteMobileSecret($customerId) {
        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::MOBILE_SECRET_URL);

        $request = static::deleteRequest($url, false);

        $appTokenRequest = new static($request);

        parent::executeRequest($appTokenRequest, ApiURLs::MOBILE_SECRET_URL, self::ERR_MSG_DELETE);
    }

    /**
     * Executes the prepared request and returns
     * the Response object on success.
     *
     * @return The request's response.
     *
     * @throws PlenigoException on request error.
     */
    public function execute() {
        try {
            $response = parent::execute();
        } catch (\Exception $exc) {
            throw new PlenigoException('Mobile Service execution failed!', $exc->getCode(), $exc);
        }

        return $response;
    }

}
