<?php

require_once __DIR__ . '/RestClientMock.php';

require_once __DIR__ . '/CurlRequestMockProvider.php';

class RestClientTest extends CurlRequestMockProvider
{

    /**
     * @dataProvider curlClientProvider
     */
    public function testGetMethod($curlRequest)
    {
        $expectedResponse = '{"response": "<b>Response</b>"}';
        $expectedValue = json_decode($expectedResponse);
        $expectedURL = 'http://example.com?prop=value';

        $curlRequest->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($expectedResponse))
        ;

        RestClientMock::$curlClient = $curlRequest;

        $response = RestClientMock::get(
                'http://example.com', array('prop' => 'value')
            )->execute();

        $this->assertObjectHasAttribute('response', $response);
        $this->assertEquals($expectedValue->response, $response->response);
        $this->assertEquals($expectedURL, RestClientMock::$url);
        $this->assertError(E_USER_NOTICE, "example.com");
    }

    /**
     * @dataProvider curlClientProvider
     */
    public function testPostMethod($curlRequest)
    {
        $expectedResponse = '{"response": "<b>Response</b>"}';
        $expectedValue = json_decode($expectedResponse);

        $curlRequest->expects($this->any())
            ->method('execute')
            ->will($this->returnValue($expectedResponse))
        ;

        RestClientMock::$curlClient = $curlRequest;

        $response = RestClientMock::post(
                'http://example.com', (array) $expectedValue
            )->execute();

        $this->assertObjectHasAttribute('response', $response);
        $this->assertEquals($expectedValue->response, $response->response);
        $this->assertError(E_USER_NOTICE, "example.com");
    }

}