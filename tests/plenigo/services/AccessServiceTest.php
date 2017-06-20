<?php

require_once __DIR__ . '/AccessServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';

use plenigo\PlenigoManager;

/**
 * <b>
 * Test class for AccessService.
 * </b>
 */
class AccessServiceTest extends PlenigoTestCase {

    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';
    const PROD_ID_1 = "fuwUyCq8281643736141";

    public static function setUpBeforeClass() {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function testAddUserAccess() {
        AccessServiceMock::$requestResponse = '';

        AccessServiceMock::grantUserAccess(self::CUSTOMER_ID, false, null, array(self::PROD_ID_1));
        $this->assertError(E_USER_NOTICE, "POST JSON URL");
    }

    public function testDeleteCustomerAppFalse() {
        AccessServiceMock::$requestResponse = '';

        AccessServiceMock::removeUserAccess(self::CUSTOMER_ID, false, array(self::PROD_ID_1));
        $this->assertError(E_USER_NOTICE, "DELETE URL CALL");
    }

}
