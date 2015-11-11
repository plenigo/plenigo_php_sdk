<?php

require_once __DIR__ . '/MeterServiceMock.php';
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
 * MeterServiceTest
 * 
 * <b>
 * Test class for  MeterService
 * </b>
 *
 * @category SDK
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class MeterServiceTest extends PlenigoTestCase
{

    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';
    //browser|metered_activated|allowed|taken|limit_reached|XXX|XXX|XXX|XXX|
    //log_allowed|log_taken|log_limit_reached|startTime|meteredPeriod|startWithFirstDay|cookieCreationTime
    const COOKIE_DISABLED = "f6cbc774104b00b56007f12d48e0e06d|false|0|0|true|false|false|false|null|0|0|true|false|false|false";
    const COOKIE_JUST_LOGGEDIN = "f6cbc774104b00b56007f12d48e0e06d|true|0|0|true|false|false|false|null|5|2|false|false|false|false";
    const COOKIE_JUST_LOGGEDOUT = "f6cbc774104b00b56007f12d48e0e06d|true|5|2|false|false|false|false|null|0|0|true|false|false|false";
    const COOKIE_CONSUMED = "f6cbc774104b00b56007f12d48e0e06d|true|5|5|true|false|false|false|null|5|5|true|false|false|false";

    public static function setUpBeforeClass()
    {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function testHasFreeViewsDisabled()
    {
        $data = EncryptionUtils::encryptWithAES(PlenigoManager::get()->getCompanyId(), self::COOKIE_DISABLED,
                MeterServiceMock::METERED_INIT_VECTOR);

        MeterServiceMock::setCookie(PlenigoManager::PLENIGO_VIEW_COOKIE_NAME, $data);

        $hasFV = MeterServiceMock::hasFreeViews();

        $this->assertFalse($hasFV);
        $this->assertError(E_USER_NOTICE, "Metered view deactivated");
    }

    public function testHasFreeViewsTrue()
    {
        $data = EncryptionUtils::encryptWithAES(PlenigoManager::get()->getCompanyId(), self::COOKIE_JUST_LOGGEDOUT,
                MeterServiceMock::METERED_INIT_VECTOR);

        MeterServiceMock::setCookie(PlenigoManager::PLENIGO_VIEW_COOKIE_NAME, $data);

        $hasFV = MeterServiceMock::hasFreeViews();

        $this->assertTrue($hasFV);
        $this->assertError(E_USER_NOTICE, "Limit not reached.");
    }

    public function testHasFreeViewsFalse()
    {
        $data = EncryptionUtils::encryptWithAES(PlenigoManager::get()->getCompanyId(), self::COOKIE_CONSUMED,
                MeterServiceMock::METERED_INIT_VECTOR);

        MeterServiceMock::setCookie(PlenigoManager::PLENIGO_VIEW_COOKIE_NAME, $data);

        $hasFV = MeterServiceMock::hasFreeViews();

        $this->assertFalse($hasFV);
        $this->assertError(E_USER_NOTICE, "Limit reached.");
    }

}
