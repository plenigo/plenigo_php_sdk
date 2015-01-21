<?php

require_once __DIR__ . '/../../../../src/plenigo/internal/utils/CurlRequest.php';

use \plenigo\internal\utils\CurlRequest;

class CurlRequestTest extends PHPUnit_Framework_Testcase
{

    public function testConstructor()
    {
        $req = new CurlRequest("http://www.google.com/");

        $this->assertInstanceOf('\plenigo\internal\utils\CurlRequest', $req);

        $req->close();
    }

    public function testExecutionHTTPS()
    {
        $req = new CurlRequest("https://www.google.com/");

        $req->setOption(CURLOPT_SSL_VERIFYPEER, false);
        $req->setOption(CURLOPT_RETURNTRANSFER, true);
        $req->setOption(CURLOPT_FOLLOWLOCATION, true);

        $response = $req->execute();

        $this->assertNotSame(false, strpos($response, 'google'));
        $req->close();
    }

    /**
     * @expectedException \Exception
     */
    public function testExecutionMarlformedURLException()
    {
        $req = new CurlRequest("htp://www.om/something_invalid_here");

        $req->setOption(CURLOPT_RETURNTRANSFER, true);

        $req->execute();

        $req->close();
    }

    /**
     * @expectedException \Exception
     */
    public function testExecutionHTTPStatusCodeException()
    {
        $req = new CurlRequest("http://www.google.com/");

        $req->setOption(CURLOPT_RETURNTRANSFER, true);
        $req->setOption(CURLOPT_FOLLOWLOCATION, false);

        $req->execute();

        $req->close();
    }

}
