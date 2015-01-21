<?php

require_once __DIR__ . '/../../../src/plenigo/services/TokenService.php';
require_once __DIR__ . '/../internal/utils/RestClientMock.php';

use \plenigo\services\TokenService;


/**
 * TokenServiceMock
 * 
 * <b>
 * Mock and override class for TokenService
 * </b>
 *
 * @category Test
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     http://plenigo.com
 */
class TokenServiceMock extends TokenService
{
    public static $requestResponse;

    public function __construct($request, $csrfToken = null)
    {
        parent::__construct($request, $csrfToken);

        $this->request = RestClientMock::get('mock');
    }

    protected function getRequestResponse()
    {
        return static::$requestResponse;
    }
}