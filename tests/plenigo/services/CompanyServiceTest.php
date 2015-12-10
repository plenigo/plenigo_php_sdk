<?php

require_once __DIR__ . '/CompanyServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';
require_once __DIR__ . '/../../../src/plenigo/models/CompanyUserList.php';
require_once __DIR__ . '/../../../src/plenigo/models/CompanyUser.php';
require_once __DIR__ . '/../../../src/plenigo/models/CompanyUserBillingData.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiResults;
use \plenigo\models\CompanyUserList;
use \plenigo\models\CompanyUser;
use \plenigo\models\CompanyUserBillingData;

/**
 * CompanyServiceTest
 * 
 * <b>
 * Test class for CompanyService
 * </b>
 *
 * @category SDK
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class CompanyServiceTest extends PlenigoTestCase {

    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';
    
    public static function setUpBeforeClass() {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function userListProvider() {
        $data = json_decode('{"totalElements":43,"size":10,"elements":[{"userState":"ACTIVATED",'
                . '"agreementState":"AGREE","customerId":"MRFCP1G6I25C","language":"en","email":"rholfeld.plenigo+p1@gmail.com"},'
                . '{"firstName":"sdf","country":"DE","gender":"male","userState":"ACTIVATED","city":"dsf","agreementState":"DISAGREE",'
                . '"street":"df","customerId":"0QPHCEIQBNJN","name":"sdf","language":"en","postCode":"dsf",'
                . '"email":"rholfeld.plenigo+101@gmail.com"},{"firstName":"test","country":"DE","gender":"male",'
                . '"userState":"ACTIVATED","city":"test","agreementState":"DISAGREE","street":"test","customerId":"HWVFIG1HZBDJ",'
                . '"name":"test","language":"en","postCode":"test","email":"rholfeld.plenigo+200@gmail.com",'
                . '"billingAddress":{"gender":"male","firstName":"Sebastian","name":"Dieguez","street":"Padre Luis Monti 2221",'
                . '"city":"Cordoba","state":"Cordoba","country":"AR","postCode":"5000"}},{"country":"DE","gender":"male",'
                . '"city":"city","mobileNumber":"0170999999","language":"de","additionalAddressInfo":"MAP27KCA3K2",'
                . '"firstName":"SebastianPHP","userState":"ACTIVATED","agreementState":"PARTIAL","street":"street",'
                . '"customerId":"MAP27KCA3K2P","name":"DieguezPHP","postCode":"00002","email":"php@plenigo.com",'
                . '"username":"sdieguezPHP"},{"userState":"ACTIVATED","agreementState":"DISAGREE","customerId":"AMTDEH9R7MEA",'
                . '"language":"de","email":"rholfeld.plenigo+725@gmail.com"},{"userState":"ACTIVATED","agreementState":"DISAGREE",'
                . '"customerId":"6DQZHU7V16PF","language":"de","email":"rholfeld.plenigo+765@gmail.com"},{"userState":"ACTIVATED",'
                . '"agreementState":"DISAGREE","customerId":"0GVY3LUQXWPY","language":"de","email":"rholfeld.plenigo+8@gmail.com"},'
                . '{"country":"DE","gender":"male","city":"test","mobileNumber":"4915204336619","language":"en",'
                . '"additionalAddressInfo":"test","firstName":"test","userState":"ACTIVATED","agreementState":"DISAGREE",'
                . '"street":"test","customerId":"GL68XYIB4ZVH","name":"test","postCode":"test","email":"rholfeld.plenigo+3@gmail.com"},'
                . '{"firstName":"asdfasdf","country":"DE","gender":"male","userState":"ACTIVATED","city":"sadfsdaf",'
                . '"agreementState":"DISAGREE","street":"asdfsadf","customerId":"HNNMBKTWV5BS","name":"asfdsadf","language":"en",'
                . '"postCode":"3214","email":"nobody2525+php@gmail.com"},{"country":"DE","gender":"male",'
                . '"city":"München","mobileNumber":"4915204336619","language":"en","firstName":"Robert",'
                . '"userState":"ACTIVATED","agreementState":"AGREE","street":"Strasse","customerId":"UL8USLGAS50V",'
                . '"name":"Holfeld","postCode":"00001","email":"rholfeld.plenigo+66@gmail.com","username":"rholfeld"}]}');

        return array(array($data));
    }

    /**
     * @dataProvider userListProvider
     */
    public function testGetUserList($data) {
        CompanyServiceMock::$requestResponse = $data;
        $result = CompanyServiceMock::getUserList();
        
        $this->assertFalse(is_null($result));
        $this->assertInstanceOf('plenigo\models\CompanyUserList', $result);
        foreach ($result as $user) {
            $this->assertInstanceOf('plenigo\models\CompanyUser', $user);
            $billing = $user->getBillingAddress();
            if(!is_null($billing)){
                $this->assertInstanceOf('plenigo\models\CompanyUserBillingData', $billing);
            }else{
                $this->assertTrue(is_null($billing));
            }
        }
        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }
    
    /**
     * @dataProvider userListProvider
     */
    public function testGetUserListBigPage($data) {
        CompanyServiceMock::$requestResponse = $data;
        $result = CompanyServiceMock::getUserList(0,200);
        
        $this->assertFalse(is_null($result));
        
        $this->assertError(E_USER_NOTICE, "size=100");
    }
    
    /**
     * @dataProvider userListProvider
     */
    public function testGetUserListSmallPage($data) {
        CompanyServiceMock::$requestResponse = $data;
        $result = CompanyServiceMock::getUserList(0,2);
        
        $this->assertFalse(is_null($result));
        
        $this->assertError(E_USER_NOTICE, "size=10");
    }

    /**
     * @dataProvider userListProvider
     */
    public function testGetUserListNegativePageNumber($data) {
        CompanyServiceMock::$requestResponse = $data;
        $result = CompanyServiceMock::getUserList(-1);
        
        $this->assertFalse(is_null($result));
        
        $this->assertError(E_USER_NOTICE, "page=0");
    }

}
