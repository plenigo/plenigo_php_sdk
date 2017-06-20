<?php

require_once __DIR__ . '/../../../src/plenigo/services/AppManagementService.php';
require_once __DIR__ . '/../internal/utils/RestClientMock.php';

use \plenigo\services\AppManagementService;

/**
 * <b>
 * Mock and override class for AppManagementService
 * </b>
 */
class AppManagementServiceMock extends AppManagementService
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