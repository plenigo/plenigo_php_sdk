<?php

require_once __DIR__ . '/MobileServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiResults;

/**
 * MobileServiceTest
 * 
 * <b>
 * Test class for MobileService
 * </b>
 */
class MobileServiceTest extends PlenigoTestCase {

    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';
    const USER_SECRET = "some@email.com";
    const USER_EMAIL = "some5ecret4mobile";

    public static function setUpBeforeClass() {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function validVerifyServiceProvider() {
        $data = json_decode('{"customerId": "' . self::CUSTOMER_ID . '"}');

        return array(array($data));
    }

    public function invalidVerifyServiceProvider() {
        $data = (object) array(
                    'error' => '403',
                    'description' => 'Mobile secret not valid'
        );

        return array(array($data));
    }

    public function secretServiceProvider() {
        $data = json_decode('{"email": "' . self::USER_EMAIL . '","mobileAppSecret": "' . self::USER_SECRET . '"}');

        return array(array($data));
    }

    /**
     * @dataProvider validVerifyServiceProvider
     */
    public function testVerifyRequestValid($data) {
        MobileServiceMock::$requestResponse = $data;

        $result = MobileServiceMock::verifyMobileSecret(self::USER_EMAIL, self::USER_SECRET);

        $this->assertEquals($result, self::CUSTOMER_ID);
        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    /**
     * @dataProvider invalidVerifyServiceProvider
     */
    public function testVerifyRequestInvalid($data) {
        MobileServiceMock::$requestResponse = $data;

        try {
            $result = MobileServiceMock::verifyMobileSecret(self::USER_EMAIL, self::USER_SECRET);
        } catch (\Exception $ex) {
            $this->assertEquals($ex->getMessage(), "Error during mobile secret verification'");
        }
        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    /**
     * @dataProvider secretServiceProvider
     */
    public function testRequestAppToken($data) {
        MobileServiceMock::$requestResponse = $data;

        $result = MobileServiceMock::getMobileSecret(self::CUSTOMER_ID);

        $this->assertFalse(is_null($result));
        $this->assertInstanceOf('plenigo\models\MobileSecretData', $result);
        $this->assertTrue($result->getEmail() == self::USER_EMAIL);
        $this->assertTrue($result->getMobileSecret() == self::USER_SECRET);

        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    /**
     * @dataProvider invalidVerifyServiceProvider
     */
    public function testRequestAppTokenInvalid($data) {
        MobileServiceMock::$requestResponse = $data;

        try {
            $result = MobileServiceMock::getMobileSecret(self::CUSTOMER_ID);
        } catch (\Exception $ex) {
            $this->assertEquals($ex->getMessage(), "Error getting mobile secret");
        }
        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    /**
     * @dataProvider secretServiceProvider
     */
    public function testPostAppToken($data) {
        MobileServiceMock::$requestResponse = $data;

        $result = MobileServiceMock::createMobileSecret(self::CUSTOMER_ID, 20);

        $this->assertFalse(is_null($result));
        $this->assertInstanceOf('plenigo\models\MobileSecretData', $result);
        $this->assertTrue($result->getEmail() == self::USER_EMAIL);
        $this->assertTrue($result->getMobileSecret() == self::USER_SECRET);

        $this->assertError(E_USER_NOTICE, "POST JSON URL CALL");
    }

    /**
     * @dataProvider secretServiceProvider
     */
    public function testPostAppTokenLowValue($data) {
        MobileServiceMock::$requestResponse = $data;

        $result = MobileServiceMock::createMobileSecret(self::CUSTOMER_ID, 2);

        $this->assertFalse(is_null($result));
        $this->assertInstanceOf('plenigo\models\MobileSecretData', $result);
        $this->assertTrue($result->getEmail() == self::USER_EMAIL);
        $this->assertTrue($result->getMobileSecret() == self::USER_SECRET);

        $this->assertError(E_USER_NOTICE, "POST JSON URL CALL");
    }

    /**
     * @dataProvider secretServiceProvider
     */
    public function testPostAppTokenHighValue($data) {
        MobileServiceMock::$requestResponse = $data;

        $result = MobileServiceMock::createMobileSecret(self::CUSTOMER_ID, 150);

        $this->assertFalse(is_null($result));
        $this->assertInstanceOf('plenigo\models\MobileSecretData', $result);
        $this->assertTrue($result->getEmail() == self::USER_EMAIL);
        $this->assertTrue($result->getMobileSecret() == self::USER_SECRET);

        $this->assertError(E_USER_NOTICE, "POST JSON URL CALL");
    }

    /**
     * @dataProvider invalidVerifyServiceProvider
     */
    public function testPostAppTokenInvalid($data) {
        MobileServiceMock::$requestResponse = $data;

        try {
            MobileServiceMock::createMobileSecret(self::CUSTOMER_ID, 150);
        } catch (\Exception $ex) {
            $this->assertEquals($ex->getMessage(), "Error creating mobile secret");
        }
        $this->assertError(E_USER_NOTICE, "POST JSON URL CALL");
    }

    /**
     * @dataProvider secretServiceProvider
     */
    public function testDeleteAppToken($data) {
        MobileServiceMock::$requestResponse = $data;

        MobileServiceMock::deleteMobileSecret(self::CUSTOMER_ID);

        $this->assertError(E_USER_NOTICE, "DELETE URL CALL");
    }

    /**
     * @dataProvider invalidVerifyServiceProvider
     */
    public function testDeleteAppTokenInvalid($data) {
        MobileServiceMock::$requestResponse = $data;

        try {
            $result = MobileServiceMock::deleteMobileSecret(self::CUSTOMER_ID);
        } catch (\Exception $ex) {
            $this->assertEquals($ex->getMessage(), "Error deleting mobile secret");
        }
        $this->assertError(E_USER_NOTICE, "DELETE URL CALL");
    }

}
