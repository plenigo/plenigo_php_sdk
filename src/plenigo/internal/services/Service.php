<?php

namespace plenigo\internal\services;

require_once __DIR__ . '/../../PlenigoManager.php';
require_once __DIR__ . '/../../internal/utils/RestClient.php';

use \plenigo\PlenigoManager;
use \plenigo\internal\utils\RestClient;

/**
 * Service
 *
 * <p>
 * This class serves as a parent implementation for all
 * Service Request classes.
 * </p>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class Service
{

    /**
     * The request to be executed.
     */
    protected $request;

    /**
     * The default Service constructor. Accepts a
     * request object to be executed.
     *
     * @param RestClient $request The request object to be executed.
     */
    protected function __construct($request)
    {
        $this->request = $request;
    }

    /**
     * Returns a response to a GET RestClient request to a specific
     * end-point on the Plenigo REST API.
     *
     * @param string $endPoint The REST end-point to access.
     * @param array  $params   Optional params to pass to the request.
     *
     * @return the request result.
     */
    protected static function getRequest($endPoint, array $params = array())
    {
        $url = PlenigoManager::get()->getUrl() . $endPoint;

        return RestClient::get($url, $params);
    }

    /**
     * Returns a response to a POST RestClient request to a specific
     * end-point on the Plenigo REST API.
     *
     * @param string $endPoint The REST end-point to access.
     * @param array  $params   Optional params to pass to the request.
     *
     * @return the request result.
     */
    protected static function postRequest($endPoint, array $params)
    {
        $url = PlenigoManager::get()->getUrl() . $endPoint;

        return RestClient::post($url, $params);
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
        $this->request->setOption($name, $value);

        return $this;
    }

    /**
     * Executes the request and returns the response.
     *
     * @return The request's response.
     *
     * @throws \Exception on request error.
     */
    protected function getRequestResponse()
    {
        return $this->request->execute();
    }

    /**
     * Validates that the response is a proper object and
     * has no error properties.
     *
     * @param object $response The request response.
     *
     * @throws \Exception on unexpected response or response with errors.
     */
    protected function checkForErrors($response)
    {
        if (!is_object($response)) {
            throw new \Exception('Broken response. Expecting JSON Object; Got: ' . gettype($response));
        }

        if (isset($response->error)) {

            $error = $response->error;
            $errorDesc = "";

            if (isset($response->description)) {
                $errorDesc .= $response->description;
            }
            if (isset($response->error_description)) {
                $errorDesc .= " : " . $response->error_description;
            }
            if (is_numeric($response->error)) {
                throw new \Exception($response->description, intval($response->error));
            } else {
                throw new \Exception($error . " - " . $errorDesc);
            }
        }

        $statusCode = $this->request->getStatusCode();

        if ($statusCode != 200) {
            throw new \Exception("Request Estatus Code: " . $statusCode, $statusCode);
        }
    }

    /**
     * Executes the request, checks for errors
     * and returns the response.
     *
     * @return The request's response.
     */
    public function execute()
    {
        try {
            $response = $this->getRequestResponse();
            $this->checkForErrors($response);
        } catch (\Exception $exc) {
            throw new \Exception('Error checking the response', $exc->getCode(), $exc);
        }

        return $response;
    }

    /**
     * Obtains (or mocks) the contents of a cookie, so its implementation is abstracted...
     * 
     * @param string $name the name of the Cookie
     */
    protected static function getCookieContents($name)
    {
        return filter_input(INPUT_COOKIE, $name);
    }

}
