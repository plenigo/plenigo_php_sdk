<?php

namespace plenigo\internal\utils;

require_once __DIR__ . '/CurlRequest.php';
require_once __DIR__ . '/JWT.php';
require_once __DIR__ . '/../../PlenigoManager.php';

use plenigo\internal\exceptions\ConfigException;
use plenigo\PlenigoManager;

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
class RestClient {

    /**
     * The CURL Request object to be executed.
     */
    private $curlRequest;
    private $inFile;

    /**
     * Default RestClient constructor. Accepts a
     * CURL Request object to be executed.
     *
     * @param CurlRequest $curlRequest The CURL Request to execute.
     *
     * @return RestClient instance.
     */
    private function __construct($curlRequest, $inFile = null) {
        $this->curlRequest = $curlRequest;
        $this->inFile = $inFile;
    }

    /**
     * Generate URL-encoded query string.
     * Replace http_build_query due to an error in this method.
     *
     * Taken from https://davidwalsh.name/curl-post
     *
     * @param array $params parameter array to pass to webservice.
     * @return string query-string
     */
    private static function buildQuery($params = array()) {
        $fields_string = '';
        //url-ify the data for the POST
        foreach($params as $key=>$value) {
            if (is_array($value)) {
                $value = implode(",", $value);
            }
            $fields_string .= $key.'='. rawurlencode($value) .'&';
        }
        $fields_string = rtrim($fields_string, '&');

        // taking out the brackets because we need to use the very same variable name
        $fields_string = preg_replace('/%5B(?:[0-9]|[1-9][0-9]+)%5D=/', '=', $fields_string);

        return $fields_string;
    }

    /**
     * Executes a cURL GET request at the given URL
     * with optional get parameters.
     *
     * @param string $url    The url to access.
     * @param array  $params An optional map of params to pass
     *                       on to the request as a query string.
     *
     * @return \plenigo\internal\utils\RestClient request response
     *
     * @throws \Exception on request error.
     */
    public static function get($url, array $params = array()) {
        if (count($params) > 0) {
            $queryString = self::buildQuery($params);

            $url .= '?' . $queryString;
        }
        $clazz = get_class();
        PlenigoManager::notice($clazz, "GET URL CALL=" . $url);
        $curlRequest = static::createCurlRequest($url);
        $curlRequest->setOption(CURLOPT_POST, false);
        $curlRequest->setOption(CURLOPT_PUT, false);
        $curlRequest->setOption(CURLOPT_CUSTOMREQUEST, "GET");

        return new static($curlRequest);
    }

    /**
     * Executes a cURL DELETE request at the given URL
     * with optional get parameters.
     *
     * @param string $url    The url to access.
     * @param array  $params An optional map of params to pass
     *                       on to the request as a query string.
     *
     * @return \plenigo\internal\utils\RestClient request response
     *
     * @throws \Exception on request error.
     */
    public static function delete($url, array $params = array()) {

        if (count($params) > 0) {
            $queryString = self::buildQuery($params);

            if (strpos($url, '?') === FALSE) {
                $url .= '?' . $queryString;
            } else {
                $url .= '&' . $queryString;
            }
        }
        $clazz = get_class();
        PlenigoManager::notice($clazz, "DELETE URL CALL=" . $url);
        $curlRequest = static::createCurlRequest($url);
        $curlRequest->setOption(CURLOPT_POST, false);
        $curlRequest->setOption(CURLOPT_PUT, false);
        $curlRequest->setOption(CURLOPT_CUSTOMREQUEST, "DELETE");

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
     * @return \plenigo\internal\utils\RestClient request response
     *
     * @throws \Exception on request error.
     */
    public static function post($url, array $params = array()) {
        $curlRequest = static::createCurlRequest($url);

        $curlRequest->setOption(CURLOPT_PUT, false);
        $curlRequest->setOption(CURLOPT_POST, true);
        $curlRequest->setOption(CURLOPT_CUSTOMREQUEST, "POST");

        if (count($params) > 0) {

            $queryString = '';
            foreach($params as $key=>$value) { $queryString .= $key.'='.$value.'&'; }
            rtrim($queryString, '&');

            $curlRequest->setOption(CURLOPT_POSTFIELDS, $queryString);
        }
        $clazz = get_class();
        PlenigoManager::notice($clazz, "POST URL CALL=" . $url);
        return new static($curlRequest);
    }

    /**
     * Executes a cURL JSON POST request at the given URL
     * with a body JSON object.
     *
     * @param string $url    The url to access.
     * @param array  $params An array to be represented as a JSON object in the requets body.
     *
     * @return \plenigo\internal\utils\RestClient request response
     *
     * @throws \Exception on request error.
     */
    public static function postJSON($url, array $params = array()) {
        $curlRequest = static::createCurlRequest($url);
        $data_string = json_encode($params);

        $curlRequest->setOption(CURLOPT_PUT, false);
        $curlRequest->setOption(CURLOPT_POST, true);
        $curlRequest->setOption(CURLOPT_CUSTOMREQUEST, "POST");
        $curlRequest->setOption(CURLOPT_POSTFIELDS, $data_string);
        $curlRequest->setOption(CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        $clazz = get_class();
        PlenigoManager::notice($clazz, "POST JSON URL CALL=" . $url);
        return new static($curlRequest);
    }

    /**
     * Executes a cURL JSON PUT request at the given URL
     * with a body JSON object.
     *
     * @param string $url    The url to access.
     * @param array  $params An array to be represented as a JSON object in the requets body.
     *
     * @return \plenigo\internal\utils\RestClient request response
     *
     * @throws \Exception on request error.
     */
    public static function putJSON($url, array $params = array()) {
        $clazz = get_class();
        $curlRequest = static::createCurlRequest($url);
        $data_string = json_encode($params);
        PlenigoManager::notice($clazz, "PUT JSON URL PARAMS=" . $data_string);

        $curlRequest->setOption(CURLOPT_PUT, true);
        $curlRequest->setOption(CURLOPT_CUSTOMREQUEST, "PUT");
        $curlRequest->setOption(CURLOPT_POSTFIELDS, $data_string);

        $infile = fopen('php://temp', 'w+');
        fwrite($infile, $data_string);
        rewind($infile);
        $curlRequest->setOption(CURLOPT_INFILE, $infile);

        $curlRequest->setOption(CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );
        PlenigoManager::notice($clazz, "PUT JSON URL CALL=" . $url);
        return new static($curlRequest, $infile);
    }

    /**
     * Creates a new CurlRequest object.
     * This method helps mocking the CurlRequest class.
     *
     * @param string $url The URL to access.
     *
     * @return CurlRequest instance.
     *
     * @throws ConfigException
     */
    private static function createCurlRequest($url = null) {
        return new CurlRequest($url);
    }

    /**
     * Gets the status code returned by the response.
     *
     * @return int The status code.
     */
    public function getStatusCode() {
        return $this->curlRequest->getInfo(CURLINFO_HTTP_CODE);
    }

    /**
     * Sets the request options before execution.
     *
     * @param string $name  The option name.
     * @param mixed  $value The option value.
     *
     * @return RestClient Returns itself for chaining purposes.
     */
    public function setOption($name, $value) {
        $this->curlRequest->setOption($name, $value);

        return $this;
    }

    /**
     * Carries out the actual request and returns a response result
     * depending on the response's content type.
     *
     * @return mixed the request response
     *
     * @throws \Exception on request error.
     */
    public function execute() {

        $this->setMandatoryOptions();

        try {
            $result = $this->curlRequest->execute();
        } catch (\Exception $exc) {
            throw $exc;
        }

        $contentType = $this->curlRequest->getInfo(CURLINFO_CONTENT_TYPE);
        if (!is_null($this->inFile)) {
            fclose($this->inFile);
        }
        if (preg_match('/^application\/json/', $contentType)) {
            return json_decode($result);
        } else {
            return $result;
        }
    }

    /**
     * @throws \Exception
     */
    public function setMandatoryOptions() {
        // Mandatory options
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_TIMEOUT, PlenigoManager::getCurlTimeout());
        $this->setOption(CURLOPT_CONNECTTIMEOUT, PlenigoManager::getCurlTimeout());

        // Create the JWT token
        $uuid = uniqid("", true);
        $expiration = strtotime('+5 minutes');
        $payload = JWT::jsonDecode('{ "jti": "' . $uuid . '", "aud": "plenigo", "exp": ' . $expiration . ', "companyId": "' . PlenigoManager::get()->getCompanyId() . '" }');

        $token = JWT::encode($payload, PlenigoManager::get()->getSecret());

        // Add the JWT Headers
        $headers = $this->curlRequest->getOption(CURLOPT_HTTPHEADER);
        $headers[] = 'plenigoToken: ' . $token;
        $this->setOption(CURLOPT_HTTPHEADER, $headers);
    }

}
