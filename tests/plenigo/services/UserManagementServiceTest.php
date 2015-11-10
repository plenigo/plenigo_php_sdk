<?php

require_once __DIR__ . '/UserManagementServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiResults;

/**
 * UserManagementServiceTest
 * 
 * <b>
 * Test class for UserManagementService
 * </b>
 *
 * @category SDK
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class UserManagementServiceTest extends PlenigoTestCase {

    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const VALID_CUSTOMER_EMAIL = 's.dieguez@plenigo.com';
    const INVALID_CUSTOMER_EMAIL = 's.dieguez';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';

    const TEST_LOGIN_TOKEN = "FICTIONALTESTTOKEN";

    public static function setUpBeforeClass() {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function userServiceProvider() {
        $data = json_decode('{"customerId": "' . self::CUSTOMER_ID . '"}');

        return array(array($data));
    }
    
    public function tokenServiceProvider() {
        $data = json_decode('{"loginToken": "' . self::TEST_LOGIN_TOKEN . '"}');

        return array(array($data));
    }

    /**
     * @dataProvider userServiceProvider
     */
    public function testRegisterUser($data) {
        UserManagementServiceMock::$requestResponse = $data;

        $result = UserManagementServiceMock::registerUser(self::VALID_CUSTOMER_EMAIL);

        $this->assertFalse(is_null($result));
        $this->assertTrue($result == self::CUSTOMER_ID);

        $this->assertError(E_USER_NOTICE, "POST JSON URL");
    }

    public function testInvalidRegisterUser() {
        $result = UserManagementServiceMock::registerUser(self::INVALID_CUSTOMER_EMAIL);

        $this->assertTrue(is_null($result));
        $this->assertError(E_USER_WARNING, "Invalid email");
    }
    
    public function testValidChangeEmail() {
        $result = UserManagementServiceMock::changeEmail(self::CUSTOMER_ID, self::VALID_CUSTOMER_EMAIL);

        $this->assertTrue($result);
        $this->assertError(E_USER_NOTICE, "PUT JSON URL");
    }
    
    public function testInvalidChangeEmail() {
        $result = UserManagementServiceMock::changeEmail(self::CUSTOMER_ID, self::INVALID_CUSTOMER_EMAIL);

        $this->assertFalse($result);
        $this->assertError(E_USER_WARNING, "Invalid email");
    }
    
    /**
     * @dataProvider tokenServiceProvider
     */
    public function testCreateLoginToken($data) {
        UserManagementServiceMock::$requestResponse = $data;

        $result = UserManagementServiceMock::createLoginToken(self::CUSTOMER_ID);

        $this->assertFalse(is_null($result));
        $this->assertTrue($result == self::TEST_LOGIN_TOKEN);

        $this->assertError(E_USER_NOTICE, "POST URL CALL");
    }
}
