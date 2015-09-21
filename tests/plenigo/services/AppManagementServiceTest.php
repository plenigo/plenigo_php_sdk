<?php

require_once __DIR__ . '/AppManagementServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/utils/EncryptionUtils.php';
require_once __DIR__ . '/../../../src/plenigo/internal/utils/SdkUtils.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';

use \plenigo\internal\utils\EncryptionUtils;
use \plenigo\internal\utils\SdkUtils;
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
    const PROD_ID_2 = "t7xHNEy6132168075141";
    const PROD_APP_ID_2 = "GLORIOUSFICTIONALAPPID2";
    const TEST_APP_TOKEN = "FICTIONALTESTTOKEN";

    public static function setUpBeforeClass() {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function tokenServiceProvider() {
        $data = json_decode('{"customerId": "'.self::CUSTOMER_ID.'","token": "'.self::TEST_APP_TOKEN.'"}');

        return array(array($data));
    }
    
    public function singleAppServiceProvider() {
        $data = json_decode('{ "apps": [{ "customerId": "'.self::CUSTOMER_ID.'", "customerAppId": "'.self::PROD_APP_ID_1
                .'", "description": "This is a test", "productId": "'.self::PROD_ID_1.'" } ] }');

        return array(array($data));
    }

    public function multipleAppServiceProvider() {
        $data = json_decode('{ "apps": [{ "customerId": "'.self::CUSTOMER_ID.'", "customerAppId": "'.self::PROD_APP_ID_1
                .'", "description": "This is a test 1", "productId": "'.self::PROD_ID_1.'" },'
                . '{ "customerId": "'.self::CUSTOMER_ID.'", "customerAppId": "'.self::PROD_APP_ID_2
                .'", "description": "This is a test 2", "productId": "'.self::PROD_ID_2.'" } ] }');

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
        $this->assertTrue($result->getAppToken()==self::TEST_APP_TOKEN);
        $this->assertTrue($result->getCustomerId()==self::CUSTOMER_ID);
        
        $this->assertError(E_USER_NOTICE, "POST JSON URL");
    }

}
