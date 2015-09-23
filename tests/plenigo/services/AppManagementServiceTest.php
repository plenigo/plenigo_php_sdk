<?php

require_once __DIR__ . '/AppManagementServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiResults;

/**
 * AppManagementServiceTest
 * 
 * <b>
 * TODO Description
 * </b>
 *
 * @category SDK
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class AppManagementServiceTest extends PlenigoTestCase {

    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';
    const PROD_ID_1 = "fuwUyCq8281643736141";
    const PROD_APP_ID_1 = "GLORIOUSFICTIONALAPPID1";
    const PROD_DESC_1 = "This is a test 1";
    const PROD_ID_2 = "t7xHNEy6132168075141";
    const PROD_APP_ID_2 = "GLORIOUSFICTIONALAPPID2";
    const PROD_DESC_2 = "This is a test 2";
    const TEST_APP_TOKEN = "FICTIONALTESTTOKEN";

    public static function setUpBeforeClass() {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function tokenServiceProvider() {
        $data = json_decode('{"customerId": "' . self::CUSTOMER_ID . '","token": "' . self::TEST_APP_TOKEN . '"}');

        return array(array($data));
    }

    public function singleAppServiceProvider() {
        $data = json_decode('{ "apps": [{ "customerId": "' . self::CUSTOMER_ID . '", "customerAppId": "' . self::PROD_APP_ID_1
                . '", "description": "' . self::PROD_DESC_1 . '", "productId": "' . self::PROD_ID_1 . '" } ] }');

        return array(array($data));
    }

    public function multipleAppServiceProvider() {
        $data = json_decode('{ "apps": [{ "customerId": "' . self::CUSTOMER_ID . '", "customerAppId": "' . self::PROD_APP_ID_1
                . '", "description": "' . self::PROD_DESC_1 . '", "productId": "' . self::PROD_ID_1 . '" },'
                . '{ "customerId": "' . self::CUSTOMER_ID . '", "customerAppId": "' . self::PROD_APP_ID_2
                . '", "description": "' . self::PROD_DESC_2 . '", "productId": "' . self::PROD_ID_2 . '" } ] }');

        return array(array($data));
    }

    public function simpleAppServiceProvider() {
        $data = json_decode('{ "customerId": "' . self::CUSTOMER_ID . '", "customerAppId": "' . self::PROD_APP_ID_1
                . '", "description": "' . self::PROD_DESC_1 . '", "productId": "' . self::PROD_ID_1 . '" }');

        return array(array($data));
    }

    /**
     * @dataProvider tokenServiceProvider
     */
    public function testRequestAppToken($data) {
        AppManagementServiceMock::$requestResponse = $data;

        $result = AppManagementServiceMock::requestAppToken(self::CUSTOMER_ID, self::PROD_ID_1, "This is a test");

        $this->assertFalse(is_null($result));
        $this->assertInstanceOf('plenigo\models\AppTokenData', $result);
        $this->assertTrue($result->getAppToken() == self::TEST_APP_TOKEN);
        $this->assertTrue($result->getCustomerId() == self::CUSTOMER_ID);

        $this->assertError(E_USER_NOTICE, "POST JSON URL");
    }

    /**
     * @dataProvider singleAppServiceProvider
     */
    public function testGetCustomerAppsSingle($data) {
        AppManagementServiceMock::$requestResponse = $data;

        $result = AppManagementServiceMock::getCustomerApps(self::CUSTOMER_ID);

        $this->assertFalse(is_null($result));
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) == 1);
        $first = $result[0];

        $this->assertInstanceOf('plenigo\models\AppAccessData', $first);
        $this->assertTrue($first->getCustomerAppId() == self::PROD_APP_ID_1);
        $this->assertTrue($first->getProductId() == self::PROD_ID_1);
        $this->assertTrue($first->getCustomerId() == self::CUSTOMER_ID);
        $this->assertTrue($first->getDescription() == self::PROD_DESC_1);

        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    /**
     * @dataProvider multipleAppServiceProvider
     */
    public function testGetCustomerAppsMultiple($data) {
        AppManagementServiceMock::$requestResponse = $data;

        $result = AppManagementServiceMock::getCustomerApps(self::CUSTOMER_ID);

        $this->assertFalse(is_null($result));
        $this->assertTrue(is_array($result));
        $this->assertTrue(count($result) == 2);
        $first = $result[0];

        $this->assertInstanceOf('plenigo\models\AppAccessData', $first);
        $this->assertTrue($first->getCustomerAppId() == self::PROD_APP_ID_1);
        $this->assertTrue($first->getProductId() == self::PROD_ID_1);
        $this->assertTrue($first->getCustomerId() == self::CUSTOMER_ID);
        $this->assertTrue($first->getDescription() == self::PROD_DESC_1);

        $second = $result[1];

        $this->assertInstanceOf('plenigo\models\AppAccessData', $second);
        $this->assertTrue($second->getCustomerAppId() == self::PROD_APP_ID_2);
        $this->assertTrue($second->getProductId() == self::PROD_ID_2);
        $this->assertTrue($second->getCustomerId() == self::CUSTOMER_ID);
        $this->assertTrue($second->getDescription() == self::PROD_DESC_2);

        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    /**
     * @dataProvider simpleAppServiceProvider
     */
    public function testRequestAppId($data) {
        AppManagementServiceMock::$requestResponse = $data;

        $result = AppManagementServiceMock::requestAppId(self::CUSTOMER_ID, self::TEST_APP_TOKEN);

        $this->assertInstanceOf('plenigo\models\AppAccessData', $result);
        $this->assertTrue($result->getCustomerAppId() == self::PROD_APP_ID_1);
        $this->assertTrue($result->getProductId() == self::PROD_ID_1);
        $this->assertTrue($result->getCustomerId() == self::CUSTOMER_ID);
        $this->assertTrue($result->getDescription() == self::PROD_DESC_1);

        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    public function testHasUserBoughtTrue() {
        $result = AppManagementServiceMock::hasUserBought(self::CUSTOMER_ID, self::PROD_ID_1, self::PROD_APP_ID_1);

        $this->assertFalse(is_null($result));
        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);

        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    public function testHasUserBoughtFalse() {
        $data = json_decode('{"error":"403","description":"Access is not allowed"}');
        AppManagementServiceMock::$requestResponse = $data;

        try {
            $result = AppManagementServiceMock::hasUserBought(self::CUSTOMER_ID, self::PROD_ID_1, self::PROD_APP_ID_1);
            $this->assertTrue(false);
        } catch (\Exception $exc) {
            $this->assertTrue(true);
        }

        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    public function testDeleteCustomerAppFalse() {
        $data = json_decode('{"error":"400","description":"The given parameters were incorrect"}');
        AppManagementServiceMock::$requestResponse = $data;

        try {
            $result = AppManagementServiceMock::deleteCustomerApp(self::CUSTOMER_ID, self::PROD_APP_ID_1);
            $this->assertTrue(false);
        } catch (\Exception $exc) {
            $this->assertTrue(true);
        }

        $this->assertError(E_USER_NOTICE, "DELETE URL CALL");
    }

}
