<?php

namespace plenigo;

require_once __DIR__ . '/internal/models/Configuration.php';
require_once __DIR__ . '/internal/ApiURLs.php';
require_once __DIR__ . '/internal/PlenigoLogger.php';

use plenigo\internal\Cache;
use plenigo\internal\models\Configuration;
use plenigo\internal\PlenigoLogger;
use plenigo\models\Loggable;

/**
 * PlenigoManager
 *
 * <p>
 * This class centralizes plenigo's Configuration so that it can be used through
 * the complete SDK.
 * </p>
 *
 * @category SDK
 * @package  Plenigo
 * @author   René Olivo <r.olivo@plenigo.com>
 * @author   Ricardo Torres <r.torres@plenigo.com>
 * @link     https://www.plenigo.com
 */
final class PlenigoManager {

    /**
     * plenigo's User cookie name.
     */
    const PLENIGO_USER_COOKIE_NAME = 'plenigo_user';

    /**
     * plenigo's Metered View cookie name.
     */
    const PLENIGO_VIEW_COOKIE_NAME = 'plenigo_view';

    /**
     * Error message when the manager hasn't been configured
     */
    const ERROR_MSG_CONFIGURE = "Plenigo Manager needs to be configured first.";

    /**
     * Singleton instance.
     */
    private static $instance = null;

    /**
     * Configuration Object containing environment specific data.
     */
    private $config;

    /**
     * Loggable interface variable.
     */
    private $loggable;

    /**
     * Debug variable for PlenigoLogger commands
     *
     * @var bool
     */
    private static $debug = false;

    /**
     * @var int
     */
    private static $curlTimeout = 10;

    /**
     * @var int
     */
    private static $curlConnectTimeout = 10;

    /**
     * <p>
     * Default constructor.
     * Sets the configuration parameters
     * </p>
     *
     * @param string $secret the application secret
     * @param string $companyId the application company ID
     * @param bool $testMode specifies the mode of operation
     * @param string $url the URL to use for communication end-points
     * @param string $urlOAuth the URL to use for OAuth API calls
     *
     * @return void
     */
    private function __construct($secret, $companyId, $testMode = null, $url = null, $urlOAuth = null) {
        $this->config = new Configuration($secret, $companyId, $testMode, $url, $urlOAuth);
    }

    /**
     * Configuration method that instantiate the PlenigoManager class.
     *
     * @param string $secret a String that represents the secret key for your specific company
     * @param string $companyId a String that represents the company ID used
     * @param bool $testMode specifies the mode of operation
     * @param string $url the URL to use for communication end-points
     * @param string $urlOAuth the URL to use for OAuth API calls
     *
     * @return PlenigoManager Singleton instance of {@link plenigo.PlenigoManager}
     */
    public static function configure($secret, $companyId, $testMode = null, $url = null, $urlOAuth = null) {

        self::$instance = new PlenigoManager($secret, $companyId, $testMode, $url, $urlOAuth);

        return self::$instance;
    }

    /**
     * Singleton instance retrieval method.
     *
     * @return PlenigoManager Singleton instance of {@link plenigo.PlenigoManager}
     * @throws \Exception when the PlenigoManager has not been previously configured.
     */
    public static function get() {
        if (self::$instance === null) {
            $clazz = get_class();
            static::error($clazz, self::ERROR_MSG_CONFIGURE);
            throw new \Exception(self::ERROR_MSG_CONFIGURE);
        }

        return self::$instance;
    }

    /**
     * Returns the company id.
     *
     * @return string the company id
     */
    public function getCompanyId() {
        return $this->config->getCompanyId();
    }

    /**
     * Returns the secret key.
     *
     * @return string The secret key
     */
    public function getSecret() {
        return $this->config->getSecret();
    }

    /**
     * This returns the URL used by all the API communications within plenigo.
     *
     * @return string The API base URL
     */
    public function getUrl() {
        return $this->config->getUrl();
    }

    /**
     * This returns the URL used by the OAuth API communications within plenigo.
     *
     * @return string The API OAuth URL
     */
    public function getUrlOAuth() {
        return $this->config->getUrlOAuth();
    }

    /**
     * Checks if test mode is active or not.
     *
     * @return bool test mode
     */
    public function isTestMode() {
        return ($this->config->isTestMode() === true);
    }

    /**
     * Checks if debug mode is active or not.
     *
     * @return bool debug mode
     */
    public static function isDebug() {
        return self::$debug;
    }

    /**
     * Sets debug mode active or not.
     *
     * @param bool $debug debug mode
     */
    public static function setDebug($debug) {
        self::$debug = $debug;
    }


    /**
     * @return int
     */
    public static function getCurlTimeout(): int
    {
        return self::$curlTimeout;
    }

    /**
     * @param int $curlTimeout
     */
    public static function setCurlTimeout(int $curlTimeout)
    {
        self::$curlTimeout = $curlTimeout;
    }

    /**
     * @return int
     */
    public static function getCurlConnectTimeout(): int
    {
        return self::$curlConnectTimeout;
    }

    /**
     * @param int $curlConnectTimeout
     */
    public static function setCurlConnectTimeout(int $curlConnectTimeout)
    {
        self::$curlConnectTimeout = $curlConnectTimeout;
    }


    /**
     * Configure Cache. Each engine may have their own set of settings.
     * To choose an engine use $settings['engine']
     * Engines Memcache, Memcached and APCu are implemented yet.
     * If not set, we will use APCu if enabled or none
     * If you want to go without any cache, set $settings['engine'] to 'None'
     *
     * @param array $settings
     */
    public static function configureCache(array $settings) {
        Cache::configure($settings);
    }

    /**
     * Convenience method for calling NOTICE info messages. The object reference is needed to show the referenced
     * class that is calling this method. An optional Exception can be sent so it outputs the entire stacktrace.
     *
     * @param mixed $obj can be an object, a string or any other variable, if its an object, it's class is shown
     * @param string $msg the NOTICE message to send
     * @param \Exception $exc an optional Exception object to show its stacktrace and messages
     * @return bool returns FALSE only if the object reference or message are NULL
     */
    public static function notice($obj, $msg, $exc = null) {
        if (is_null($obj) || is_null($msg) || self::$debug === FALSE) {
            return false;
        }
        PlenigoLogger::notice($obj, $msg, $exc);
    }

    /**
     * Convenience method for calling WARNING messages. The object reference is needed to show the referenced
     * class that is calling this method. An optional Exception can be sent so it outputs the entire stacktrace.
     *
     * @param mixed $obj can be an object, a string or any other variable, if its an object, it's class is shown
     * @param string $msg the WARNING message to send
     * @param \Exception $exc an optional Exception object to show its stacktrace and messages
     *
     * @return bool returns FALSE only if the object reference or message are NULL
     */
    public static function warn($obj, $msg, $exc = null) {
        if (is_null($obj) || is_null($msg) || self::$debug === FALSE) {
            return false;
        }
        PlenigoLogger::warn($obj, $msg, $exc);
    }

    /**
     * Convenience method for calling ERROR messages. The object reference is needed to show the referenced
     * class that is calling this method. An optional Exception can be sent so it outputs the entire stacktrace.
     *
     * @param mixed $obj can be an object, a string or any other variable, if its an object, it's class is shown
     * @param string $msg the ERROR message to send
     * @param \Exception $exc an optional Exception object to show its stacktrace and messages
     *
     * @return bool returns FALSE only if the object reference or message are NULL
     */
    public static function error($obj, $msg, $exc = null) {
        if (is_null($obj) || is_null($msg) || self::$debug === FALSE) {
            return false;
        }
        PlenigoLogger::error($obj, $msg, $exc);
    }

    /**
     * Set the loggable interface.
     *
     * @param Loggable $loggable loggable interface to set
     */
    public function setLoggable($loggable) {
        if (is_object($loggable)) {
            $this->loggable = $loggable;
        }
    }

    /**
     * Logs an error through the loggable interface.
     *
     * @param string $msg message to log
     * @param object $obj to log
     *
     *
     */
    public function logError($msg, $obj) {
        if (is_object($this->loggable) && method_exists($this->loggable, "logData")) {
            try {
                $date = new \DateTime();
                $date = $date->format("Y-m-d h:i:s");
                $this->loggable->logData($date . " - " . $msg . ", Data: [" . print_r($obj, true) . "]" . PHP_EOL);
            } catch (\Exception $exception) {

            }
        }
    }
}
