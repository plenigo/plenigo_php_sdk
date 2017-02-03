<?php

require_once __DIR__ . '/CheckoutServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiResults;

/**
 * CheckoutServiceTest
 * 
 * <b>
 * Test class for CheckoutService
 * </b>
 *
 * @category SDK
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class CheckoutServiceTest extends PlenigoTestCase {

    const PROD_ID_1 = "fuwUyCq8281643736141";
    const PROD_DESC_1 = "This is a test 1";
    const PROD_CHAN_1 = "Channel Test 1";
    const PROD_CHAN_2 = "Channel Test 2";
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';

    public static function setUpBeforeClass() {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function voucherServiceProvider() {
        $data = json_decode('{ "name": "' . self::PROD_DESC_1 . '", "productId": "' . self::PROD_ID_1 . '", "channels": [ "' . self::PROD_CHAN_1 . '","' . self::PROD_CHAN_2 . '" ], "channelVouchers": [ { "channel": "' . self::PROD_CHAN_1 . '", "ids": [ "ABC","CDE","EFG" ] }, { "channel": "' . self::PROD_CHAN_2 . '", "ids": [ "ABC","CDE","EFG" ] } ] }');

        return array(array($data));
    }

    public function testBuyFreeProductTrue() {
        $result = CheckoutServiceMock::buyFreeProduct(self::PROD_ID_1, self::CUSTOMER_ID, false);

        $this->assertFalse(is_null($result));
        $this->assertTrue(is_bool($result));
        $this->assertTrue($result);

        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    public function testBuyFreeProductFalse() {
        $data = json_decode('{"error":"403","description":"Access is not allowed"}');
        CheckoutServiceMock::$requestResponse = $data;

        try {
            $result = CheckoutServiceMock::buyFreeProduct(self::PROD_ID_1, self::CUSTOMER_ID, false);
            $this->assertTrue(false, "Method should have thrown an error");
        } catch (\Exception $exc) {
            $this->assertTrue(true);
        }

        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

}
