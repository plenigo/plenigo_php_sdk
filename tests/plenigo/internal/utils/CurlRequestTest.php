<?php

require_once __DIR__ . '/../../../../src/plenigo/internal/utils/CurlRequest.php';

use PHPUnit\Framework\TestCase;
use \plenigo\internal\utils\CurlRequest;

class CurlRequestTest extends TestCase
{

    public function testConstructor()
    {
        $req = new CurlRequest("http://www.google.com/");

        $this->assertInstanceOf('\plenigo\internal\utils\CurlRequest', $req);

        $req->close();
    }

    /**
     * @expectedException \Exception
     */
    public function testExecutionMalformedURLException()
    {
        $req = new CurlRequest("htp://www.om/something_invalid_here");

        $req->setOption(CURLOPT_RETURNTRANSFER, true);

        $req->execute();

        $req->close();
    }

}
