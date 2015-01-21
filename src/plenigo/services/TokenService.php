<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../internal/server-interface/oauth2/Verify.php';
require_once __DIR__ . '/../models/TokenGrantType.php';
require_once __DIR__ . '/../models/TokenData.php';
require_once __DIR__ . '/../models/ErrorCode.php';

use \plenigo\PlenigoManager;
use plenigo\PlenigoException;
use \plenigo\internal\ApiURLs;
use \plenigo\internal\services\Service;
use \plenigo\internal\serverInterface\oauth2\Verify;
use \plenigo\models\TokenGrantType;
use \plenigo\models\TokenData;
use \plenigo\models\ErrorCode;

/**
 * TokenService
 *
 * <p>
 * A class used to retrieve Access Tokens from the plenigo API
 * when given a valid Access Code.
 * </p>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   Rene Olivo <r.olivo@plenigo.com>
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class TokenService extends Service
{

    const ERR_MSG_TOKEN = "Error getting Acces Token";
    const ERR_MSG_TOKEN_DIF = "The request and response CSRF Token are different";

    /**
     * The CSRF Token used for the request.
     */
    protected $csrfToken;

    /**
     * The constructor for the TokenService instance.
     *
     * @param RestClient $request   The RestClient request to execute.
     * @param string     $csrfToken The CSRF Token to use for the request.
     *
     * @return TokenService instance.
     */
    public function __construct($request, $csrfToken = null)
    {
        parent::__construct($request);

        $this->csrfToken = $csrfToken;
    }

    /**
     * Executes the request to get the Access Token.
     *
     * @param string $accessCode  The Access Code provided by the Plenigo API.
     * @param string $redirectUri The redirect URI used to get the Access Code.
     * @param string $csrfToken   An optional CSRF Token to pass to the request.
     *
     * @return TokenService instance.
     *
     * @throws \Exception on request error.
     */
    public static function getAccessToken($accessCode, $redirectUri, $csrfToken = null)
    {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Getting Access Token for AccessCode=" . $accessCode);
        
        return static::getToken(TokenGrantType::AUTHORIZATION_CODE, $accessCode, $redirectUri, $csrfToken);
    }

    /**
     * Executes the request to get a new Access Token using a Refresh Code.
     *
     * @param string $refreshCode The Access Code provided by the Plenigo API.
     * @param string $csrfToken   An optional CSRF Token to pass to the request.
     *
     * @return TokenService instance.
     *
     * @throws \Exception on request error.
     */
    public static function getNewAccessToken($refreshCode, $csrfToken = null)
    {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Getting NEW Access Token for RefreshCode=" . $refreshCode);
        
        return static::getToken(TokenGrantType::REFRESH_TOKEN, $refreshCode, null, $csrfToken);
    }

    /**
     * Prepares the request to get the Access Token.
     *
     * @param string $type        The type of Token Grant Type to use.
     * @param string $code        The Access Code provided by the Plenigo API.
     * @param string $redirectUri An optional redirect URI used to get the Access Code.
     * @param string $csrfToken   An optional CSRF Token to pass to the request.
     *
     * @return TokenService instance.
     *
     * @throws \Exception on request error.
     */
    protected static function getToken($type, $code, $redirectUri = null, $csrfToken = null)
    {
        $map = array(
            'grant_type' => $type,
            'code' => $code,
            'client_id' => PlenigoManager::get()->getCompanyId(),
            'client_secret' => PlenigoManager::get()->getSecret()
        );

        if ($redirectUri !== null) {
            $map['redirect_uri'] = $redirectUri;
        }

        if ($csrfToken !== null) {
            $map['state'] = $csrfToken;
        }

        $verify = new Verify($map);

        if ($type == TokenGrantType::REFRESH_TOKEN) {
            $accessUrl = ApiURLs::REFRESH_ACCESS_TOKEN;
        } else {
            $accessUrl = ApiURLs::GET_ACCESS_TOKEN;
        }

        $request = static::postRequest($accessUrl, $verify->getMap());

        $accessTokenRequest = new static($request, $csrfToken);

        try {
            $result = $accessTokenRequest->execute();
        } catch (Exception $exc) {
            $errorCode = ErrorCode::getTranslation(ApiURLs::GET_ACCESS_TOKEN, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }
            $clazz=get_class();
            PlenigoManager::error($clazz, self::ERR_MSG_TOKEN, $exc);
            throw new PlenigoException(self::ERR_MSG_TOKEN, $errorCode, $exc);
        }

        return $result;
    }

    /**
     * Checks if the provided CSRF Token, when passed to the request,
     * is the same as the returned in the response.
     *
     * @param object $response The request response.
     * @param string $state    The expected CSRF Token.
     *
     * @return void.
     *
     * @throws \Exception when the states don't match.
     */
    protected static function validateResponse($response, $state = null)
    {
        if ($state != null && isset($response->state) && (empty($response->state) || $response->state != $state)) {
            $clazz = get_class();
            PlenigoManager::warn($clazz, self::ERR_MSG_TOKEN_DIF);
            throw new PlenigoException(self::ERR_MSG_TOKEN_DIF);
        }
    }

    /**
     * Executes the prepared request and returns a
     * Token Data on success.
     *
     * @return The Token Data {@link \plenigo\models\TokenData}.
     *
     * @throws \Exception on request error or CSRF Token state mismatch.
     */
    public function execute()
    {
        $response = $this->getRequestResponse();

        $this->checkForErrors($response);

        $this->validateResponse($response, $this->csrfToken);

        return TokenData::createFromMap((array)$response);
    }

    /**
     * This method generates the cross-site request forgery (CSRF) token.
     * 
     * @return string the CSRF Token or NULL if there is a problem generating the CSRF Token
     */
    public static function createCsrfToken()
    {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "Creating a random CSRF Token!");
        
        $randomtoken = null;
        try {
            if (function_exists("openssl_random_pseudo_bytes")) {
                $randomtoken = md5(base64_encode(openssl_random_pseudo_bytes(32)));
            } else {
                $randomtoken = md5(uniqid(rand(), true));
            }
        } catch (Exception $exc) {
            $clazz = get_class();
            PlenigoManager::warn($clazz, self::ERR_MSG_TOKEN_CREATE, $exc);
        }
        return $randomtoken;
    }

}
