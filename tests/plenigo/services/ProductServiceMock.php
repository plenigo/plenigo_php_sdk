<?php

require_once __DIR__ . '/../../../src/plenigo/services/ProductService.php';
require_once __DIR__ . '/../internal/utils/RestClientMock.php';

use \plenigo\services\ProductService;

/**
 * ProductServiceMock
 * 
 * <b>
 * Mock and override class for ProductService
 * </b>
 */
class ProductServiceMock extends ProductService
{

    public static $requestResponse;

    protected function __construct($request)
    {
        $this->request = RestClientMock::get('mock');
    }

    protected function getRequestResponse()
    {
        return static::$requestResponse;
    }
}