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
