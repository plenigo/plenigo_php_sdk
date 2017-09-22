<?php

require_once __DIR__ . '/RestClientMock.php';
require_once __DIR__ . '/PlenigoTestCase.php';
require_once __DIR__ . '/../../../../src/plenigo/internal/utils/CurlRequestInterface.php';

use \plenigo\internal\utils\CurlRequestInterface;

class CurlRequestMockProvider extends PlenigoTestCase
{

    public function curlClientProvider()
    {
        $curlRequest = $this->getMockBuilder('\plenigo\internal\utils\CurlRequestInterface')->getMock();

        $curlRequest->expects($this->any())
            ->method('getInfo')
            ->with($this->equalTo(CURLINFO_CONTENT_TYPE))
            ->will($this->returnValue('application/json'))
        ;

        RestClientMock::$curlClient = null;

        return array(array($curlRequest));
    }

}
