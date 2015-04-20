<?php

namespace plenigo\internal\models;

require_once __DIR__ . '/../ApiURLs.php';

use plenigo\internal\ApiURLs;

/**
 * <p>
 * This class contains general attributes regarding plenigo's configuration.
 * </p>
 *
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 *
 */
class Configuration
{
    /**
     * The URL where plenigos API is located.
     */
    private $url;

    /**
     * The URL where Oauth API is located.
     */
    private $urlOAuth;

    /**
     * The users secret that will be used with the SDK.
     */
    private $secret;

    /**
     * The company ID used by the API user.
     */
    private $companyId;

    /**
     * This indicates if all the transactions are being used in test mode.
     */
    private $testMode;

    /**
     * Default constructor.
     *
     * @param string $secret    the application secret
     * @param string $companyId the application company ID
     * @param bool   $testMode  specifies the mode of operation
     * @param string $url       the URL to use for communication end-points
     * @param string $urlOAuth       the URL to use for OAuth API calls
     *
     * @return void
     */
    public function __construct($secret, $companyId, $testMode = false, $url = null, $urlOAuth = null)
    {
        if ($url === null) {
                $url = ApiURLs::DEFAULT_PLENIGO_URL;
        }
        if ($urlOAuth === null) {
                $urlOAuth = ApiURLs::OAUTH_PLENIGO_URL;
        }

        $this->secret = $secret;
        $this->companyId = $companyId;
        $this->testMode = safe_boolval($testMode);
        $this->url = $url;
        $this->urlOAuth = $urlOAuth;
    }

    /**
     * gets the application secret
     *
     * @return string the secret
     */
    public function getSecret()
    {
        return $this->secret;
    }

    /**
     * gets the application URL
     *
     * @return string the url
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * gets the OAuth URL
     *
     * @return string the OAuth url
     */
    public function getUrlOAuth()
    {
        return $this->urlOAuth;
    }

    /**
     * gets the application company ID
     *
     * @return string the companyId
     */
    public function getCompanyId()
    {
        return $this->companyId;
    }

    /**
     * checks if test mode is set
     *
     * @return bool is in test mode
     */
    public function isTestMode()
    {
        return $this->testMode;
    }
}

