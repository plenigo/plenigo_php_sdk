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

    public function userListSelectProvider() {
        $data = json_decode('[{"userState":"ACTIVATED",'
                . '"agreementState":"AGREE","customerId":"MRFCP1G6I25C","language":"en","email":"rholfeld.plenigo+p1@gmail.com"},'
                . '{"firstName":"sdf","country":"DE","gender":"male","userState":"ACTIVATED","city":"dsf","agreementState":"DISAGREE",'
                . '"street":"df","customerId":"0QPHCEIQBNJN","name":"sdf","language":"en","postCode":"dsf",'
                . '"email":"rholfeld.plenigo+101@gmail.com"}]');

        return array(array($data));
    }

    public function failedPaymentsProvider() {
        $data = json_decode('{"pageNumber": 10,"totalElements": 103, "size": 10,"elements": ['
                . '{"date": "2016-01-01", "customerId": "MAP27KCA3K2P", '
                . '"productId": "fuwUyCq8281643736141", '
                . '"title": "This is a test 1", "status": "FAILED" },'
                . '{ "date": "2016-01-02", '
                . '"customerId": "MAP27KCA3K2P", '
                . '"productId": "t7xHNEy6132168075141", "title": "This is a test 2",'
                . '"status": "FIXED" },{ "date": "2016-01-03",'
                . '"customerId": "MAP27KCA3K2P","productId": "fuwUyCq8281643736141",'
                . '"title": "This is a test 1","status": "FIXED_MANUALLY"}]}');

        return array(array($data));
    }
    
    public function testOrdersProvider() {
        $data = json_decode('{"totalElements":179,"size":10,"elements":['
                . '{"discountPercentage":"0","orderId":"jYsFxpA7B9t4y1h2J5g272e2i94714V1",'
                . '"purchaseOrderIndicator":"test","customerId":"56200075",'
                . '"discount":"0.00","currency":"EUR","orderDate":"2016-11-15T15:08:42Z",'
                . '"cumulatedPrice":"12.00","orderItems":[{"quantity":"1",'
                . '"productId":"3t41fM82422006249641","price":"12.00","taxes":"19.00",'
                . '"taxCountry":"DE","taxesAmount":"1.92","title":"12 Month Subscription (Futurama)",'
                . '"status":"DONE"}]},{"discountPercentage":"0","orderId":"p22bbaW8Q6k0C2i3H3I3C9A738p7k4b1",'
                . '"customerId":"56200012","discount":"0.00","currency":"EUR",'
                . '"orderDate":"2016-11-10T15:55:32Z","cumulatedPrice":"0.00","orderItems":['
                . '{"quantity":"1","productId":"czkGrDf4945046557741","price":"0.00","taxes":"19.00",'
                . '"taxCountry":"DE","taxesAmount":"0.00","title":"Freimonate","status":"DONE"}]},'
                . '{"discountPercentage":"0","orderId":"x6MuBNB7f3f1w428p6l109A748876411",'
                . '"customerId":"56200012","discount":"0.00","currency":"BGN","orderDate":"2016-11-10T15:28:04Z",'
                . '"cumulatedPrice":"252.00","orderItems":[{"quantity":"1","productId":"SzWRwQb8532582137741",'
                . '"price":"252.00","taxes":"19.00","taxCountry":"DE","taxesAmount":"40.24",'
                . '"title":"Neustes Hightech-must-have-Product","status":"DONE"}]},{"discountPercentage":"0",'
                . '"orderId":"Xtn6FTR3f5T240I5p15069F7Y85794g1","customerId":"56200012","discount":"0.00",'
                . '"currency":"EUR","orderDate":"2016-11-10T15:02:31Z","cumulatedPrice":"2.00","orderItems":['
                . '{"quantity":"1","productId":"Qn8TBVI1322799370741","price":"2.00","taxes":"19.00",'
                . '"taxCountry":"DE","taxesAmount":"0.32","title":"Urbanes Wohnzimmer","status":"DONE"}]},'
                . '{"discountPercentage":"0","orderId":"8SpFSWI3K8b947G9I83906j788n7j4V1","customerId":"56200071",'
                . '"currency":"EUR","orderDate":"2016-11-10T09:24:07Z","cumulatedPrice":"12.99",'
                . '"orderItems":[{"quantity":"1","productId":"RgKUHT78563991046641","price":"12.99",'
                . '"costCenter":"520002","taxes":"19.00","taxCountry":"DE","taxesAmount":"2.07",'
                . '"revenueAccount":"8200020","title":"Standard Subscription","status":"DONE"}]},'
                . '{"discountPercentage":"0","orderId":"2MSGIbc9n2E0z5v1P6k9R6r7F8L7W4x1",'
                . '"customerId":"56200071","currency":"EUR","orderDate":"2016-11-10T09:21:28Z",'
                . '"cumulatedPrice":"9.99","orderItems":[{"quantity":"1","productId":"SVjYNCn5024813046641",'
                . '"price":"9.99","costCenter":"520002","taxes":"19.00","taxCountry":"DE","taxesAmount":"1.60",'
                . '"revenueAccount":"8200030","title":"Single product","status":"DONE"}]},{"discountPercentage":"0",'
                . '"orderId":"fZR3DSg5i9V1P2j68659t667g8v764m1","customerId":"56200071","currency":"EUR",'
                . '"orderDate":"2016-11-10T09:21:10Z","cumulatedPrice":"4.00","orderItems":['
                . '{"quantity":"1","productId":"yhmBdXU8239023046641","price":"4.00","costCenter":"520002",'
                . '"taxes":"19.00","taxCountry":"DE","taxesAmount":"0.64","revenueAccount":"8200030",'
                . '"title":"Pay-What-You-Want","status":"DONE"}]},{"discountPercentage":"0",'
                . '"orderId":"PrqlaTF220W5G9u0v2X6k816Q8s7M4z1","customerId":"56200015","discount":"0.00",'
                . '"currency":"EUR","orderDate":"2016-11-09T10:10:09Z","cumulatedPrice":"25.00",'
                . '"orderItems":[{"quantity":"1","productId":"NpVeQv89447653046641","price":"25.00",'
                . '"costCenter":"520002","taxes":"7.00","taxCountry":"DE","taxesAmount":"1.64",'
                . '"revenueAccount":"8200020","title":"Subscription with delivery","status":"DONE"}]},'
                . '{"discountPercentage":"0","orderId":"td0mYFJ3G7A9F7C2W2X769d5G8J7N4x1","customerId":"56200070",'
                . '"currency":"EUR","orderDate":"2016-11-08T09:28:18Z","cumulatedPrice":"9.99","orderItems":['
                . '{"quantity":"1","productId":"SVjYNCn5024813046641","price":"9.99","costCenter":"520002",'
                . '"taxes":"19.00","taxCountry":"DE","taxesAmount":"1.60","revenueAccount":"8200030",'
                . '"title":"Single product","status":"DONE"}]},{"discountPercentage":"0",'
                . '"orderId":"E9DEHFt9L705m5y6W4l0k7u2P8r7h4I1","purchaseOrderIndicator":"BE13029578",'
                . '"customerId":"7B8JEDXXTZ27","discount":"0.00","currency":"EUR","orderDate":"2016-11-04T14:41:05Z",'
                . '"cumulatedPrice":"10.00","orderItems":[{"quantity":"1","productId":"3t41fM82422006249641",'
                . '"price":"10.00","taxes":"0.00","taxCountry":"AT","taxesAmount":"0.00",'
                . '"title":"12 Month Subscription (Futurama)","status":"DONE"}],"vatNumber":"ATU33864707"}],"pageNumber":0}');

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
            if (!is_null($billing)) {
                $this->assertInstanceOf('plenigo\models\CompanyUserBillingData', $billing);
            } else {
                $this->assertTrue(is_null($billing));
            }
        }
        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    /**
     * @dataProvider userListSelectProvider
     */
    public function testGetUserSelect($data) {
        CompanyServiceMock::$requestResponse = $data;
        $result = CompanyServiceMock::getUserByIds("MRFCP1G6I25C,0QPHCEIQBNJN");

        $this->assertFalse(is_null($result));
        $this->assertInstanceOf('plenigo\models\CompanyUserList', $result);
        foreach ($result as $user) {
            $this->assertInstanceOf('plenigo\models\CompanyUser', $user);
            $billing = $user->getBillingAddress();
            if (!is_null($billing)) {
                $this->assertInstanceOf('plenigo\models\CompanyUserBillingData', $billing);
            } else {
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
        $result = CompanyServiceMock::getUserList(0, 200);

        $this->assertFalse(is_null($result));

        $this->assertError(E_USER_NOTICE, "size=100");
    }

    /**
     * @dataProvider userListProvider
     */
    public function testGetUserListSmallPage($data) {
        CompanyServiceMock::$requestResponse = $data;
        $result = CompanyServiceMock::getUserList(0, 2);

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

    /**
     * @dataProvider failedPaymentsProvider
     */
    public function testFailedPayments($data) {
        CompanyServiceMock::$requestResponse = $data;

        $result = CompanyServiceMock::getFailedPayments("2016-01-01", "2016-01-05", NULL, 10, 10);

        $this->assertNotNull($result, "The result is not correct");

        $this->assertTrue(count($result->getElements()) == 3);
        
        $this->assertTrue($result->getPageNumber() == 10);
        
        $this->assertTrue($result->getElements()[0]->getCustomerId()== "MAP27KCA3K2P");

        $this->assertError(E_USER_NOTICE, "page=10");
    }

        /**
     * @dataProvider testOrdersProvider
     */
    public function testOrdersList($data) {
        CompanyServiceMock::$requestResponse = $data;

        $result = CompanyServiceMock::getOrders("2016-01-01", "2016-01-05", true, 0, 10);

        $this->assertNotNull($result, "The result is not correct");

        $this->assertTrue(count($result->getElements()) == 10);
        
        $this->assertTrue($result->getPageNumber() == 0);
        
        $this->assertTrue($result->getElements()[0]->getCustomerId()== "56200075");

        $this->assertTrue($result->getElements()[0]->getorderItems()[0]->getProductId() == "3t41fM82422006249641");

        $this->assertError(E_USER_NOTICE, "page=0");
    }
}
