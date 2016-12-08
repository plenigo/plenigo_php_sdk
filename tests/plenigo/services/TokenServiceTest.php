<?php

require_once __DIR__ . '/TokenServiceMock.php';
require_once __DIR__ . '/../../../src/plenigo/models/TokenData.php';
require_once __DIR__ . '/../../../src/plenigo/models/TokenGrantType.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';

use \plenigo\models\TokenData;
use \plenigo\models\TokenGrantType;
use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;

/**
 * TokenServiceTest
 * 
 * <b>
 * Test class for TokenService
 * </b>
 *
 * @category Test
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     http://plenigo.com
 */
class TokenServiceTest extends PlenigoTestCase
{

    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';

    public function tokenServiceProvider()
    {
        TokenServiceMock::$requestResponse = null;

        $data = (object) array(
                'accessCode' => '2345',
                'redirectUri' => 'http://example.com/redirect?id=1&L=232&other=asd',
                'csrfToken' => 'token-value'
        );

        return array(array($data));
    }

    public static function setUpBeforeClass()
    {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    /**
     * @dataProvider tokenServiceProvider
     */
    public function testGetTokenDataError($data)
    {
        $response = (object) array(
                'error' => '101',
                'description' => 'This is a custom error message from the server'
        );

        TokenServiceMock::$requestResponse = $response;

        try {
            TokenServiceMock::getAccessToken(
                $data->accessCode, $data->redirectUri, $data->csrfToken
            );
        } catch (Exception $exc) {
            $this->assertTrue(true);
        }
        $this->assertError(E_USER_NOTICE, "Getting Access Token");
    }

    /**
     * @dataProvider tokenServiceProvider
     */
    public function testStateError($data)
    {
        $response = (object) array(
                'state' => 'wrong-token'
        );

        TokenServiceMock::$requestResponse = $response;
        try {
            TokenServiceMock::getAccessToken(
                $data->accessCode, $data->redirectUri, $data->csrfToken
            );
        } catch (PlenigoException $exc) {
            $this->assertError(E_USER_NOTICE, "Getting Access Token");
            $this->assertError(E_USER_WARNING, "The request and response CSRF Token are different");
        }
    }

    /**
     * @dataProvider tokenServiceProvider
     */
    public function testAuthorizationSuccessCase($data)
    {
        $response = (object) array(
                'access_token' => 'access-token',
                'expires_in' => '2015-01-01 00:00:00',
                'refresh_token' => 'refresh-token',
                'state' => $data->csrfToken,
                'token_type' => TokenGrantType::AUTHORIZATION_CODE
        );

        TokenServiceMock::$requestResponse = $response;

        $tokenData = TokenServiceMock::getAccessToken(
                $data->accessCode, $data->redirectUri, $data->csrfToken
        );

        $this->assertInstanceOf('\plenigo\models\TokenData', $tokenData);

        $this->assertEquals($response->access_token, $tokenData->getAccessToken());

        $this->assertError(E_USER_NOTICE, "Getting Access Token");
    }

    /**
     * @dataProvider tokenServiceProvider
     */
    public function testRefreshSuccessCase($data)
    {
        $response = (object) array(
                'access_token' => 'access-token',
                'expires_in' => '2015-01-01 00:00:00',
                'refresh_token' => 'refresh-token',
                'token_type' => TokenGrantType::AUTHORIZATION_CODE
        );

        TokenServiceMock::$requestResponse = $response;

        $tokenData = TokenServiceMock::getNewAccessToken(
                $data->accessCode, $data->csrfToken
        );

        $this->assertInstanceOf('\plenigo\models\TokenData', $tokenData);

        $this->assertEquals($response->access_token, $tokenData->getAccessToken());

        $this->assertError(E_USER_NOTICE, "Getting NEW Access Token");
    }

    public function testCreateToken()
    {
        $token = TokenServiceMock::createCsrfToken();

        $this->assertNotNull($token, "There was an error creating the token");
        $this->assertStringMatchesFormat('%x', $token, "The generated token contains non-hexa or special characters");
        $this->assertEquals(32, strlen($token), "The length of the generated token is not 32 characters");

        $this->assertError(E_USER_NOTICE, "Creating a random CSRF");
    }

}
