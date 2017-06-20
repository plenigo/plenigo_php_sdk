<?php

require_once __DIR__ . '/../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/internal/utils/PlenigoLoggerStaticHelper.php';
require_once __DIR__ . '/internal/utils/PlenigoTestCase.php';

use plenigo\PlenigoManager;

class PlenigoManagerTest extends PlenigoTestCase
{

    const MSG_ERROR_TEST = "This is a test for logging";
    const MSG_LAST_ERROR = "The last error couldn't be retrieved";
    const MSG_LAST_MSG = "The expected message couldnt be found in the notice";
    const MSG_NOT_CONFIGURED = "Plenigo Manager needs to be configured";

    public function managerProvider()
    {
        $data = array(
            'secret' => 'my-secret',
            'companyId' => 'my-company-id',
            'testMode' => true,
            'url' => 'http://example.com'
        );

        return array(array($data));
    }

    public static function setUpBeforeClass()
    {
        PlenigoManager::setDebug(true);
    }
    
    public function testUnconfiguredSingleton()
    {
        try {
            PlenigoManager::get();
        } catch (Exception $exc) {
            $this->assertStringStartsWith(self::MSG_NOT_CONFIGURED, $exc->getMessage(),
                'Unespected exception has ben thrown');
        }
        $this->assertError(E_USER_WARNING, self::MSG_NOT_CONFIGURED);
    }

    /**
     * @dataProvider managerProvider
     */
    public function testConstructor($data)
    {
        $manager = PlenigoManager::configure(
                $data['secret'], $data['companyId'], $data['testMode']
        );

        $this->assertEquals($data['secret'], $manager->getSecret());
        $this->assertEquals($data['companyId'], $manager->getCompanyId());
        $this->assertEquals($data['testMode'], $manager->isTestMode());
    }

    /**
     * @depends testConstructor
     */
    public function testSingleton()
    {
        $plenigoManagerA = PlenigoManager::get();
        $plenigoManagerB = PlenigoManager::get();

        $this->assertInstanceOf('plenigo\PlenigoManager', $plenigoManagerA);
        $this->assertEquals($plenigoManagerA, $plenigoManagerB);
    }

    public function testInfoError()
    {
        PlenigoManager::notice($this, self::MSG_ERROR_TEST);
        $this->assertError(E_USER_NOTICE, self::MSG_ERROR_TEST);
    }

    public function testInfoErrorWithStackTrace()
    {
        $strExcMessage = "Nice Exception";
        PlenigoManager::notice($this, self::MSG_ERROR_TEST,
            new Exception($strExcMessage, 0, new Exception("Not so nice Exception")));
        $this->assertError(E_USER_NOTICE, $strExcMessage);
    }

    public function testInfoErrorWithInvalidException()
    {
        $strExcMessage = "Nice Exception";
        PlenigoManager::notice($this, self::MSG_ERROR_TEST, $this);
        $this->assertNotError(E_USER_NOTICE, $strExcMessage);
    }

    public function testStaticInfoError()
    {
        PlenigoLoggerStaticHelper::testNoticeAsStatic();
        //var_dump($this->errors);
        $this->assertError(E_USER_NOTICE, PlenigoLoggerStaticHelper::MSG_STATIC_NOTICE);
    }

    public function testInfoErrorWithString()
    {
        $strSource = "MyPreetyFakeClass";
        PlenigoManager::notice($strSource, self::MSG_ERROR_TEST);
        $this->assertError(E_USER_NOTICE, $strSource);
    }

    public function testInfoErrorWithOther()
    {
        $invalidTypeVar = 404;
        PlenigoManager::notice($invalidTypeVar, self::MSG_ERROR_TEST);
        $this->assertError(E_USER_NOTICE, '404');
    }

    public function testWarnError()
    {
        PlenigoManager::warn($this, self::MSG_ERROR_TEST);
        $this->assertError(E_USER_WARNING, self::MSG_ERROR_TEST);
    }

    public function testWarnErrorWithStackTrace()
    {
        $strExcMessage = "Nice Exception";
        PlenigoManager::warn($this, self::MSG_ERROR_TEST,
            new Exception($strExcMessage, 0, new Exception("Not so nice Exception")));
        $this->assertError(E_USER_WARNING, $strExcMessage);
    }

    public function testError()
    {
        PlenigoManager::error($this, self::MSG_ERROR_TEST);
        $this->assertError(E_USER_WARNING, self::MSG_ERROR_TEST);
    }

    public function testErrorWithStackTrace()
    {
        $strExcMessage = "Nice Exception";
        PlenigoManager::error($this, self::MSG_ERROR_TEST,
            new Exception($strExcMessage, 0, new Exception("Not so nice Exception")));
        $this->assertError(E_USER_WARNING, $strExcMessage);
    }

    public function testInfoNull()
    {
        $this->assertFalse(PlenigoManager::notice($this, null));
    }

    public function testWarnNull()
    {
        $this->assertFalse(PlenigoManager::warn($this, null));
    }

    public function testErrorNull()
    {
        $this->assertFalse(PlenigoManager::error($this, null));
    }

}
