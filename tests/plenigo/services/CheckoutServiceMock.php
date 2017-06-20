<?php

require_once __DIR__ . '/../../../src/plenigo/services/CheckoutService.php';
require_once __DIR__ . '/../internal/utils/RestClientMock.php';

use \plenigo\services\CheckoutService;

/**
 * CheckoutServiceMock
 * 
 * <b>
 * Mock and override class for CheckoutService
 * </b>
 */
class CheckoutServiceMock extends CheckoutService
{

    public static $requestResponse;

    public function __construct($request)
    {
        $this->request = RestClientMock::get('mock');
    }

    protected function getRequestResponse()
    {
        return static::$requestResponse;
    }
}