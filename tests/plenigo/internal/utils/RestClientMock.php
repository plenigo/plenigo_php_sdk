<?php

require_once __DIR__ . '/../../../../src/plenigo/internal/utils/RestClient.php';
require_once __DIR__ . '/CurlRequestMockProvider.php';

use \plenigo\internal\utils\RestClient;

class RestClientMock extends RestClient
{

    public static $url;
    public static $curlClient;

    public function getStatusCode()
    {
        return 200;
    }

    public static function createCurlRequest($url = null)
    {
        static::$url = $url;

        return static::$curlClient;
    }

}