<?php

require_once __DIR__ . '/../../../src/plenigo/services/AccessService.php';
require_once __DIR__ . '/../internal/utils/RestClientMock.php';

use \plenigo\services\AccessService;

/**
 * <b>
 * Mock and override class for AccessService
 * </b>
 */
class AccessServiceMock extends AccessService
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