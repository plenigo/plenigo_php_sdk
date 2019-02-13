<?php

namespace plenigo\internal\services;

require_once __DIR__ . '/../../PlenigoManager.php';
require_once __DIR__ . '/../../PlenigoException.php';
require_once __DIR__ . '/../../internal/utils/RestClient.php';
require_once __DIR__ . '/../../models/ErrorCode.php';

use plenigo\internal\exceptions\RegistrationException;
use plenigo\internal\utils\RestClient;
use plenigo\models\ErrorCode;
use plenigo\PlenigoException;
use plenigo\PlenigoManager;

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
class Service {

    /**
     * The request to be executed.
     */
    protected $request;

    /**
     * The default Service constructor. Accepts a
     * request object to be executed.
     *
     * @param \plenigo\internal\utils\RestClient $request The request object to be executed.
     */
    protected function __construct($request) {
        $this->request = $request;
    }

    /**
     * Returns a response to a GET RestClient request to a specific
     * end-point on the plenigo REST API.
     *
     * @param string $endPoint The REST end-point to access.
     * @param bool $oauth TRUE if the needed request is going to the OAuth API.
     * @param array  $params   Optional params to pass to the request.
     *
     * @return \plenigo\internal\utils\RestClient request result.
     */
    protected static function getRequest($endPoint, $oauth = false, array $params = array()) {
        if ($oauth) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "OAUTH GET REQUEST");
            $url = PlenigoManager::get()->getUrlOAuth() . $endPoint;
        } else {
            $url = PlenigoManager::get()->getUrl() . $endPoint;
        }

        return RestClient::get($url, $params);
    }

    /**
     * Returns a response to a DELETE RestClient request to a specific
     * end-point on the plenigo REST API.
     * 
     * @param string $endPoint The REST end-point to access.
     * @param bool $oauth TRUE if the needed request is going to the OAuth API.
     * @param array $params Optional params to pass to the request.
     * 
     * @return \plenigo\internal\utils\RestClient request result
     */
    protected static function deleteRequest($endPoint, $oauth = false, array $params = array()) {
        if ($oauth) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "OAUTH DELETE REQUEST");
            $url = PlenigoManager::get()->getUrlOAuth() . $endPoint;
        } else {
            $url = PlenigoManager::get()->getUrl() . $endPoint;
        }

        return RestClient::delete($url, $params);
    }

    /**
     * Returns a response to a POST RestClient request to a specific
     * end-point on the plenigo REST API.
     *
     * @param string $endPoint The REST end-point to access.
     * @param bool $oauth TRUE if the needed request is going to the OAuth API.
     * @param array  $params   Optional params to pass to the request.
     *
     * @return \plenigo\internal\utils\RestClient the request result.
     */
    protected static function postRequest($endPoint, $oauth = false, array $params = array()) {
        if ($oauth) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "OAUTH POST REQUEST");
            $url = PlenigoManager::get()->getUrlOAuth() . $endPoint;
        } else {
            $url = PlenigoManager::get()->getUrl() . $endPoint;
        }

        return RestClient::post($url, $params);
    }

    /**
     * Returns a response to a POST RestClient request to a specific
     * end-point on the plenigo REST API.
     *
     * @param string $endPoint The REST end-point to access.
     * @param bool $oauth TRUE if the needed request is going to the OAuth API.
     * @param array  $params   Optional params to pass to the request.
     *
     * @return \plenigo\internal\utils\RestClient request result.
     */
    protected static function postJSONRequest($endPoint, $oauth = false, array $params = array()) {
        if ($oauth) {
            $clazz = get_class();
            PlenigoManager::notice($clazz, "OAUTH JSON POST REQUEST");
            $url = PlenigoManager::get()->getUrlOAuth() . $endPoint;
        } else {
            $url = PlenigoManager::get()->getUrl() . $endPoint;
        }

        return RestClient::postJSON($url, $params);
    }

    /**
     * Returns a response to a PUT RestClient request to a specific
     * end-point on the plenigo REST API.
     *
     * @param string $endPoint The REST end-point to access.
     * @param array  $params   Optional params to pass to the request.
     *
     * @return \plenigo\internal\utils\RestClient request result.
     */
    protected static function putJSONRequest($endPoint, array $params = array()) {
        $clazz = get_class();
        PlenigoManager::notice($clazz, "JSON PUT REQUEST");
        $url = PlenigoManager::get()->getUrl() . $endPoint;

        return RestClient::putJSON($url, $params);
    }

    /**
     * Sets the request options before execution.
     *
     * @param string $name  The option name.
     * @param mixed  $value The option value.
     *
     * @return Service Returns itself for chaining purposes.
     */
    public function setOption($name, $value) {
        $this->request->setOption($name, $value);

        return $this;
    }

    /**
     * Executes the request and returns the response.
     *
     * @return mixed The request's response.
     *
     * @throws \Exception on request error.
     */
    protected function getRequestResponse() {
        return $this->request->execute();
    }

    /**
     * Validates that the response is a proper object and
     * has no error properties.
     *
     * @param mixed $response The request response.
     *
     * @throws \Exception on unexpected response or response with errors.
     */
    protected function checkForErrors($response) {
        //Sanitize a string empty response
        if (is_string($response) && trim($response) === '') {
            $response = json_decode('{}');
        }
        if (!is_object($response) && !is_array($response)) {
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

        // All 200 codes are good answers
        if ($statusCode < 200 || $statusCode >= 300) {
            $exception = new PlenigoException("Request Status Code: {$statusCode}, {$response->error}", $statusCode);
            $exception->addErrorDetail($statusCode, $response->error);
            throw $exception;
        }
    }


    /**
     * Executes the fiven RestClient and detects if there is an error, 
     * gets its code and provides a PlenigoException describing it 
     * with the error parameters
     * 
     * @param \plenigo\internal\services\Service $pRequest The RestClient object to execute for this request
     * @param string $pErrorSource the URL key for the error translation table
     * @param string $pErrorMsg the Error message to show in the Plenigo Exception thrown
     * 
     * @return mixed The request response or null
     * 
     * @throws PlenigoException|RegistrationException
     */
    protected static function executeRequest($pRequest, $pErrorSource, $pErrorMsg) {
        $res = null;
        try {
            $res = $pRequest->execute();
        } catch (RegistrationException $exception) {
            throw $exception;
        }
        catch (\Exception $exc) {
            $errorCode = ErrorCode::getTranslation($pErrorSource, $exc->getCode());
            if (empty($errorCode) || is_null($errorCode)) {
                $errorCode = $exc->getCode();
            }
            $clazz = get_class();
            PlenigoManager::error($clazz, $pErrorMsg, $exc);
            throw new PlenigoException($pErrorMsg, $errorCode, $exc);
        }

        return $res;
    }

    /**
     * Executes the request, checks for errors
     * and returns the response.
     *
     * @return mixed The request's response.
     */
    public function execute() {
        try {
            $response = $this->getRequestResponse();
            $this->checkForErrors($response);
        } catch (RegistrationException $exception) {
            throw $exception;
        }
        catch (\Exception $exc) {
            throw new \Exception('Error checking the response', $exc->getCode(), $exc);
        }

        return  $response;
    }

    /**
     * Obtains (or mocks) the contents of a cookie, so its implementation is abstracted...
     * 
     * @param string $name the name of the Cookie
     */
    protected static function getCookieContents($name) {
        return filter_input(INPUT_COOKIE, $name);
    }

}
