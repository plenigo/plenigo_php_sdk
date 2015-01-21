<?php

namespace plenigo\internal\utils;

require_once __DIR__ . '/CurlRequest.php';
require_once __DIR__ . '/../../PlenigoManager.php';

use \plenigo\internal\utils\CurlRequest;
use \plenigo\PlenigoManager;

/**
 * RestClient
 *
 * <p>
 * Provides an arrange of methods to easily execute cURL requests.
 * </p>
 *
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalUtils
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class RestClient
{

    /**
     * The CURL Request object to be executed.
     */
    private $curlRequest;

    /**
     * Default RestClient constructor. Accepts a
     * CURL Request object to be executed.
     *
     * @param CurlRequest $curlRequest The CURL Request to execute.
     *
     * @return RestClient instance.
     */
    private function __construct($curlRequest)
    {
        $this->curlRequest = $curlRequest;
    }

    /**
     * Executes a cURL GET request at the given URL
     * with optional get parameters.
     *
     * @param string $url    The url to access.
     * @param array  $params An optional map of params to pass
     *                       on to the request as a query string.
     *
     * @return the request response
     *
     * @throws \Exception on request error.
     */
    public static function get($url, array $params = array())
    {
        if (count($params) > 0) {
            $query = http_build_query($params, null, '&');
            // taking out the brackets because we need to use the very same variable name
            $queryString = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $query);

            $url .= '?' . $queryString;
        }
        $clazz = get_class();
        PlenigoManager::notice($clazz, "GET URL CALL=" . $url);
        $curlRequest = static::createCurlRequest($url);

        return new static($curlRequest);
    }

    /**
     * Executes a cURL POST request at the given URL
     * with optional get parameters.
     *
     * @param string $url    The url to access.
     * @param array  $params An optional map of params to pass
     *                       on to the request as post values.
     *
     * @return the request response
     *
     * @throws \Exception on request error.
     */
    public static function post($url, array $params = array())
    {
        $curlRequest = static::createCurlRequest($url);

        $curlRequest->setOption(CURLOPT_POST, true);

        if (count($params) > 0) {
            $queryString = http_build_query($params);

            $curlRequest->setOption(CURLOPT_POSTFIELDS, $queryString);
        }
        $clazz = get_class();
        PlenigoManager::notice($clazz, "POST URL CALL=" . $url);
        return new static($curlRequest);
    }

    /**
     * Creates a new CurlRequest object.
     * This method helps mocking the CurlRequest class.
     *
     * @param string $url The URL to access.
     *
     * @return CurlRequest instance.
     */
    private static function createCurlRequest($url = null)
    {
        return new CurlRequest($url);
    }

    /**
     * Gets the status code returned by the response.
     *
     * @return The status code.
     */
    public function getStatusCode()
    {
        return $this->curlRequest->getInfo(CURLINFO_HTTP_CODE);
    }

    /**
     * Sets the request options before execution.
     *
     * @param string $name  The option name.
     * @param mixed  $value The option value.
     *
     * @return Returns itself for chaining purposes.
     */
    public function setOption($name, $value)
    {
        $this->curlRequest->setOption($name, $value);

        return $this;
    }

    /**
     * Carries out the actual request and returns a response result
     * depending on the response's content type.
     *
     * @return the request response
     *
     * @throws \Exception on request error.
     */
    public function execute()
    {
        $this->setOption(CURLOPT_RETURNTRANSFER, true);

        try {
            $result = $this->curlRequest->execute();
        } catch (Exception $exc) {
            throw $exc;
        }

        $contentType = $this->curlRequest->getInfo(CURLINFO_CONTENT_TYPE);

        if (preg_match('/^application\/json/', $contentType)) {
            return json_decode($result);
        } else {
            return $result;
        }
    }

}
