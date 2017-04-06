<?php

require_once __DIR__ . '/UserServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/models/UserData.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/utils/EncryptionUtils.php';
require_once __DIR__ . '/../../../src/plenigo/internal/utils/SdkUtils.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';

use \plenigo\models\UserData;
use \plenigo\internal\utils\EncryptionUtils;
use \plenigo\internal\utils\SdkUtils;
use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiResults;

/**
 * UserServiceTest
 * 
 * <b>
 * Test class for UserService
 * </b>
 *
 * @category Test
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     http://plenigo.com
 */class UserServiceTest extends PlenigoTestCase
{

    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';
    const ERR_MSG_NOT_SET = "Plenigo cookie not set";
    const ERR_MSG_CUSTOMER = "Plenigo cookie CustomerID invalid!!";
    const ERR_MSG_MISSING = "Plenigo cookie has missing components!!";
    const ERR_MSG_UNCOMMON = "This is an uncommon exception";
    const ERR_MSG_NOT_FOUND = "The product is not found";
    const ERR_MSG_TIMESTAMP = "Illegal value for the expiration";

    public function userServiceProvider()
    {
        UserServiceMock::$requestResponse = null;

        $data = (object) array(
                'id' => '2345',
                'email' => 'someone@example.com',
                'gender' => 'male',
                'lastName' => 'John',
                'firstName' => 'Smith',
                'street' => 'some address',
                'additionalAddressInfo' => 'some address 2',
                'postCode' => '45321',
                'city' => 'a city',
                'country' => 'a country'
        );

        return array(array($data));
    }

    public function productProvider()
    {
        UserServiceMock::$requestResponse = null;

        $data = json_decode('{'
            . '"id":"12345",'
            . '"id2":"23456",'
            . '"id3":"34567",'
            . '"id4":"45678",'
            . '"title":"Some great product"}');

        return array(array($data));
    }

    public function userBoughtProvider()
    {
        UserServiceMock::$requestResponse = null;

        $data = json_decode('{"accessGranted": true}');

        return array(array($data));
    }

    public function cookieSetProvider()
    {
        $nextWeek = time() + (7 * 24 * 60 * 60 * 100);

        $cookieSet = array();
        $cookieSet['nullified'] = array(null, self::ERR_MSG_NOT_SET);
        $cookieSet['empty'] = array("", self::ERR_MSG_NOT_SET);
        $cookieSet['invalid'] = array(EncryptionUtils::encryptWithAES(self::SECRET_ID, 'invalid'),
            self::ERR_MSG_MISSING);
        $strToken = ApiResults::TIMESTAMP . SdkUtils::KEY_VALUE_SEPARATOR . $nextWeek . SdkUtils::ENTRY_SEPARATOR
            . ApiResults::CUSTOMER_ID . SdkUtils::KEY_VALUE_SEPARATOR;
        $cookieSet['noCustomerValue'] = array(EncryptionUtils::encryptWithAES(self::SECRET_ID, $strToken),
            self::ERR_MSG_CUSTOMER);
        $strToken = ApiResults::TIMESTAMP . SdkUtils::KEY_VALUE_SEPARATOR . SdkUtils::ENTRY_SEPARATOR
            . ApiResults::CUSTOMER_ID . SdkUtils::KEY_VALUE_SEPARATOR . self::CUSTOMER_ID;
        $cookieSet['nullTimestamp'] = array(EncryptionUtils::encryptWithAES(self::SECRET_ID, $strToken), self::ERR_MSG_TIMESTAMP);
        $strToken = ApiResults::TIMESTAMP . SdkUtils::KEY_VALUE_SEPARATOR . "invalid" . SdkUtils::ENTRY_SEPARATOR
            . ApiResults::CUSTOMER_ID . SdkUtils::KEY_VALUE_SEPARATOR . self::CUSTOMER_ID;
        $cookieSet['invalidTimestamp'] = array(EncryptionUtils::encryptWithAES(self::SECRET_ID, $strToken), self::ERR_MSG_TIMESTAMP);
        $strToken = ' ' . SdkUtils::ENTRY_SEPARATOR
            . ApiResults::CUSTOMER_ID . SdkUtils::KEY_VALUE_SEPARATOR . self::CUSTOMER_ID;
        $cookieSet['noTimestamp'] = array(EncryptionUtils::encryptWithAES(self::SECRET_ID, $strToken),
            self::ERR_MSG_MISSING);
        $strToken = ApiResults::TIMESTAMP . SdkUtils::KEY_VALUE_SEPARATOR . $nextWeek;
        $cookieSet['noCustomerId'] = array(EncryptionUtils::encryptWithAES(self::SECRET_ID, $strToken),
            self::ERR_MSG_MISSING);

        return $cookieSet;
    }

    public static function setUpBeforeClass()
    {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    /**
     * @dataProvider userServiceProvider
     */
    public function testGetUserDataErrorUnknown()
    {
        $response = json_decode('{"error":"101","description":"message"}');

        UserServiceMock::$requestResponse = $response;

        try {
            UserServiceMock::getUserData('12345678');
        } catch (PlenigoException $exc) {
            $this->assertEquals(101, $exc->getCode());
        }
        $this->assertError(E_USER_WARNING, "Could not retrieve");
    }

    public function testGetUserDataErrorBadRequest()
    {
        $response = json_decode('{"error":"400","description":"Bad Request message"}');

        UserServiceMock::$requestResponse = $response;

        try {
            UserServiceMock::getUserData('12345678');
        } catch (PlenigoException $exc) {
            $this->assertEquals(1, $exc->getCode());
        }
        $this->assertError(E_USER_WARNING, "Could not retrieve");
    }

    /**
     * @dataProvider userServiceProvider
     */
    public function testGetUserDataSuccess($userData)
    {
        UserServiceMock::$requestResponse = $userData;

        $userResult = UserServiceMock::getUserData('12345678');

        $this->assertInstanceOf('\plenigo\models\UserData', $userResult);

        $this->assertEquals($userData->id, $userResult->getId());
        $this->assertError(E_USER_NOTICE, "Obtaining Logged In User");
    }

    public function testIsLoggedInErrorBadRequest()
    {
        $response = json_decode('{"error":"400","description":"Bad Request message"}');

        UserServiceMock::$requestResponse = $response;

        try {
            UserServiceMock::isLoggedIn();
        } catch (PlenigoException $exc) {
            $this->assertEquals(1, $exc->getCode());
        }
        $this->assertError(E_USER_WARNING, "Could not retrieve");
    }

    /**
     * @dataProvider cookieSetProvider
     */
    public function tsstIsLoggedSuccess($cookieText)
    {
        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);

        $userResult = UserServiceMock::isLoggedIn();
        $this->assertTrue($userResult);
    }

    /**
     * @dataProvider userServiceProvider
     */
    public function testPaywallEnabled()
    {
        $response = json_decode('{"enabled":"true"}');

        UserServiceMock::$requestResponse = $response;

        $resCheck = false;
        try {
            $resCheck = UserServiceMock::isPaywallEnabled();
        } catch (PlenigoException $exc) {
            print_r($exc);
        }
        $this->assertTrue($resCheck);
        $this->assertError(E_USER_NOTICE, "URL CALL");
    }

    public function testPaywallDisabled()
    {
        $response = json_decode('{"enabled":"false"}');

        UserServiceMock::$requestResponse = $response;

        $resCheck = false;
        try {
            $resCheck = UserServiceMock::isPaywallEnabled();
        } catch (PlenigoException $exc) {
            print_r($exc);
        }
        $this->assertFalse($resCheck);
        $this->assertError(E_USER_NOTICE, "URL CALL");
    }
    
    public function testProductsBoughtList()
    {
        $cookieText=  $this->getValidCookie();
        
        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);
        $response = json_decode('{"singleProducts":[{"productId":"Mze9xrw1055726494241"'
                . ',"title":"Live Single","buyDate":"2015-03-19 09:41:34 +0100"}],'
                . '"subscriptions":[{"productId":"wCltsuS9616126494241",'
                . '"title":"Live Subscription","buyDate":"2015-03-19 09:42:16 +0100"'
                . ',"endDate":"2016-03-19 09:42:16 +0100"}]}');

        UserServiceMock::$requestResponse = $response;

        $resCheck = array();
        try {
            $resCheck = UserServiceMock::getProductsBought();
        } catch (PlenigoException $exc) {
            var_export($exc);
        }
        $this->assertTrue(count($resCheck)>0,"The resulting associative array is empty");
        $this->assertError(E_USER_NOTICE, "URL CALL");
    }

    /**
     * This will check for a bought product, the mocked service will return 200 code and thus granting access to the item
     * 
     * @param object $prodData given by the Provider method
     *
     * @dataProvider productProvider
     */
    public function testSuccessfulHasUserBought($prodData)
    {
        UserServiceMock::$requestResponse = $prodData;

        $cookieText = $this->getValidCookie();
        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);

        $hasBought = UserServiceMock::hasUserBought($prodData->{'id'});

        $this->assertTrue($hasBought);
        $this->assertError(E_USER_NOTICE, "Checking if user bought Product");
    }

    /**
     * This will check for a bought product, the mocked service will return 200 code and thus granting access to the item
     *
     * @param object $userBoughtData given by the Provider method
     *
     * @dataProvider userBoughtProvider
     */
    public function testSuccessfulHasBoughtProductWithProducts($userBoughtData)
    {
        UserServiceMock::$requestResponse = $userBoughtData;

        $cookieText = $this->getValidCookie();
        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);

        $accessDetails = UserServiceMock::hasBoughtProductWithProducts('2345');

        $this->assertTrue($accessDetails["accessGranted"]);
        $this->assertError(E_USER_NOTICE, "Checking if user bought Product");
    }

    /**
     * This will check for a set of bought products, the mocked service will return 200 code and thus granting access to the item
     * 
     * @param object $prodData given by the Provider method
     * @dataProvider productProvider
     */
    public function testMultipleHasUserBought($prodData)
    {

        UserServiceMock::$requestResponse = $prodData;

        $cookieText = $this->getValidCookie();
        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);

        $hasBought = UserServiceMock::hasUserBought(array($prodData->{'id'}, $prodData->{'id2'}, $prodData->{'id3'}, $prodData->{'id4'}));

        $this->assertTrue($hasBought);
        $this->assertError(E_USER_NOTICE, "Checking if user bought Product");
    }

    /**
     * This will check for a bought product, but it will mock a not found exception
     * 
     */
    public function testHasUserBoughtWithNotFoundException()
    {
        $response = json_decode('{"error":"400","description":"' . self::ERR_MSG_NOT_FOUND . '"}');
        UserServiceMock::$requestResponse = $response;

        $cookieText = $this->getValidCookie();
        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);

        try {
            UserServiceMock::hasUserBought('123456');
        } catch (Exception $exc) {
            $this->assertInstanceOf("\plenigo\PlenigoException", $exc);
        }

        $this->assertError(E_USER_WARNING, self::ERR_MSG_NOT_FOUND);
    }

    /**
     * This will check for a bought product, but it will mock a uncommon exception
     * 
     */
    public function testHasUserBoughtWithException()
    {
        $response = json_decode('{"error":"402","description":"' . self::ERR_MSG_UNCOMMON . '"}');
        UserServiceMock::$requestResponse = $response;

        $cookieText = $this->getValidCookie();
        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);

        try {
            UserServiceMock::hasUserBought('123456');
        } catch (Exception $exc) {
            $this->assertInstanceOf("\plenigo\PlenigoException", $exc);
        }

        $this->assertError(E_USER_WARNING, self::ERR_MSG_UNCOMMON);
    }

    /**
     * This will check for a NOT bought product
     * 
     */
    public function testHasUserNotBought()
    {
        $response = json_decode('{"error":"403","description":"The product is not found"}');
        UserServiceMock::$requestResponse = $response;

        $cookieText = $this->getValidCookie();
        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);

        $hasBought = UserServiceMock::hasUserBought('123456');

        $this->assertFalse($hasBought);
        $this->assertError(E_USER_NOTICE, "Checking if user bought Product");
    }

    /**
     * This will check for a bought product, but with the nullified cookie
     * 
     * @dataProvider cookieSetProvider
     */
    public function testHasUserBoughtBrokenCookie($cookieText, $errorMsg = null)
    {
        $response = json_decode('{"id":"123456","title":"This is my product"}');
        UserServiceMock::$requestResponse = $response;

        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);

        $hasBought = UserServiceMock::hasUserBought('123456');

        if (!is_null($errorMsg)) {
            $this->assertError(E_USER_NOTICE, $errorMsg);
        }

        $this->assertFalse($hasBought);
    }

    /**
     * @dataProvider userServiceProvider
     */
    public function testGetCurrentUserFromSessionCookie($userData)
    {
        UserServiceMock::$requestResponse = $userData;

        $cookieText = $this->getValidCookie();
        UserServiceMock::setCookie(PlenigoManager::PLENIGO_USER_COOKIE_NAME, $cookieText);

        $userResult = UserServiceMock::getCurrentUserFromSessionCookie();

        $this->assertInstanceOf('\plenigo\models\UserData', $userResult);

        $this->assertEquals($userData->id, $userResult->getId());
        $this->assertError(E_USER_NOTICE, "Obtaining Logged In User");
    }

    /**
     * Returns a valid cookie text for the current date
     * 
     * @return string The valid cookie text
     */
    private function getValidCookie() {
        $nextWeek = time() + (7 * 24 * 60 * 60 * 100);
        $strValidCustomer = ApiResults::TIMESTAMP . SdkUtils::KEY_VALUE_SEPARATOR . $nextWeek . SdkUtils::ENTRY_SEPARATOR
            . ApiResults::CUSTOMER_ID . SdkUtils::KEY_VALUE_SEPARATOR . self::CUSTOMER_ID;

        return EncryptionUtils::encryptWithAES(PlenigoManager::get()->getSecret(), $strValidCustomer);
    }
}
