<?php

require_once __DIR__ . '/../../../src/plenigo/services/UserManagementService.php';
require_once __DIR__ . '/../internal/utils/RestClientMock.php';

use \plenigo\services\UserManagementService;

/**
 * UserManagementServiceMock
 * 
 * <b>
 * Mock and override class for UserManagementService
 * </b>
 *
 * @category Test
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     http://plenigo.com
 */
class UserManagementServiceMock extends UserManagementService
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