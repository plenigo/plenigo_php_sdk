<?php

require_once __DIR__ . '/../../../src/plenigo/services/MeterService.php';
require_once __DIR__ . '/../internal/utils/RestClientMock.php';

use \plenigo\services\MeterService;

/**
 * MeterServiceMock
 * 
 * <b>
 * Mock and override class for MeterService
 * </b>
 */
class MeterServiceMock extends MeterService
{

    public static $requestResponse;
    public static $cookieCache = array();

    protected function __construct($request)
    {
        $this->request = RestClientMock::get('mock');
    }

    protected function getRequestResponse()
    {
        return static::$requestResponse;
    }

    /**
     * Obtains (or mocks) the contents of a cookie, so its implementation is abstracted...
     * 
     * @param string $name the name of the Cookie
     */
    protected static function getCookieContents($name)
    {
        return static::$cookieCache[$name];
    }

    /**
     * 
     * 
     * @param string $name name of the cookie to mock
     * @param string $value value of the cookie to mock
     */
    public static function setCookie($name, $value = '')
    {
        static::$cookieCache[$name] = $value;
    }

}
