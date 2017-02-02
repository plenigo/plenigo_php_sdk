<?php

require_once __DIR__ . '/VoucherServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiResults;

/**
 * VoucherServiceTest
 * 
 * <b>
 * Test class for VoucherService
 * </b>
 *
 * @category SDK
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class VoucherServiceTest extends PlenigoTestCase {

    const PROD_ID_1 = "fuwUyCq8281643736141";
    const PROD_DESC_1 = "This is a test 1";
    const PROD_CHAN_1 = "Channel Test 1";
    const PROD_CHAN_2 = "Channel Test 2";
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';

    public static function setUpBeforeClass()
    {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }
    
    public function voucherServiceProvider() {
        $data = json_decode('{ "name": "' . self::PROD_DESC_1 . '", "productId": "' . self::PROD_ID_1 . '", "channels": [ "' . self::PROD_CHAN_1 . '","' . self::PROD_CHAN_2 . '" ], "channelVouchers": [ { "channel": "' . self::PROD_CHAN_1 . '", "ids": [ "ABC","CDE","EFG" ] }, { "channel": "' . self::PROD_CHAN_2 . '", "ids": [ "ABC","CDE","EFG" ] } ] }');

        return array(array($data));
    }

    /**
     * @dataProvider voucherServiceProvider
     */
    public function testCreateVoucherCampaign($data) {
        VoucherServiceMock::$requestResponse = $data;
        $amount = 3;
        $result = VoucherServiceMock::generateCampaign(self::PROD_DESC_1, self::PROD_ID_1, '2001-01-01', '2090-12-31', "MULTI", $amount, array(self::PROD_CHAN_1));

        $this->assertFalse(is_null($result));
        $this->assertInstanceOf('plenigo\models\CampaignResponse', $result);
        $this->assertTrue($result->getName() == self::PROD_DESC_1);
        $this->assertTrue($result->getProductId() == self::PROD_ID_1);
        $this->assertTrue(count($result->getChannelVouchers()) == 2);
        $this->assertTrue(count($result->getChannels()) == 2);
        $arrCVouchers=$result->getChannelVouchers();
        $this->assertTrue(count($arrCVouchers[0]->getIds()) == $amount);

        $this->assertError(E_USER_NOTICE, "POST JSON URL");
    }

}
