<?php

require_once __DIR__ . '/../../../src/plenigo/services/UserService.php';
require_once __DIR__ . '/../internal/utils/RestClientMock.php';

use \plenigo\services\UserService;

/**
 * UserServiceMock
 * 
 * <b>
 * Mock and override class for UserService
 * </b>
 */
class UserServiceMock extends UserService
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