<?php

require_once __DIR__ . '/../../../src/plenigo/services/TransactionService.php';
require_once __DIR__ . '/../internal/utils/RestClientMock.php';

use \plenigo\services\TransactionService;

/**
 * TransactionServiceMock
 * 
 * <b>
 * Mock and override class for TransactionService
 * </b>
 */
class TransactionServiceMock extends TransactionService
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