<?php

require_once __DIR__ . '/TransactionServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';
require_once __DIR__ . '/../../../src/plenigo/models/TransactionList.php';
require_once __DIR__ . '/../../../src/plenigo/models/Transaction.php';

use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiResults;
use \plenigo\models\TransactionList;
use \plenigo\models\Transaction;

/**
 * TransactionServiceTest
 * 
 * <b>
 * Test class for TransactionService
 * </b>
 *
 * @category SDK
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class TransactionServiceTest extends PlenigoTestCase {

    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';

    public static function setUpBeforeClass() {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function transactionsProvider() {
        $data = json_decode('{"totalElements":100,"size":10,"pageNumber":0,"elements":[{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1",'
                . '"customerId":"0QPHCEIQBNJN","productId":"fuwUyCq8281643736141","title":"Super cool PHP Product",'
                . '"price":2,"taxesPercentage":0.50,"taxesAmount":1,"taxesCountry":"DE","currency":"USD","paymentMethod":"CREDIT_CARD",'
                . '"transactionDate":"2015-12-10T15:00:00","status":"DONE","shippingCosts":0,"shippingCostsTaxesPercentage":0,'
                . '"shippingCostsTaxesAmount":1},{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1","customerId":"0QPHCEIQBNJN",'
                . '"productId":"fuwUyCq8281643736141","title":"Super cool PHP Product","price":2,"taxesPercentage":0.50,'
                . '"taxesAmount":1,"taxesCountry":"DE","currency":"USD","paymentMethod":"CREDIT_CARD","transactionDate":"2015-12-10T15:00:00",'
                . '"status":"DONE","shippingCosts":0,"shippingCostsTaxesPercentage":0,"shippingCostsTaxesAmount":1},'
                . '{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1","customerId":"0QPHCEIQBNJN","productId":"fuwUyCq8281643736141",'
                . '"title":"Super cool PHP Product","price":2,"taxesPercentage":0.50,"taxesAmount":1,"taxesCountry":"DE",'
                . '"currency":"USD","paymentMethod":"CREDIT_CARD","transactionDate":"2015-12-10T15:00:00","status":"DONE",'
                . '"shippingCosts":0,"shippingCostsTaxesPercentage":0,"shippingCostsTaxesAmount":1},{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1",'
                . '"customerId":"0QPHCEIQBNJN","productId":"fuwUyCq8281643736141","title":"Super cool PHP Product",'
                . '"price":2,"taxesPercentage":0.50,"taxesAmount":1,"taxesCountry":"DE","currency":"USD","paymentMethod":"CREDIT_CARD",'
                . '"transactionDate":"2015-12-10T15:00:00","status":"DONE","shippingCosts":0,"shippingCostsTaxesPercentage":0,'
                . '"shippingCostsTaxesAmount":1},{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1","customerId":"0QPHCEIQBNJN",'
                . '"productId":"fuwUyCq8281643736141","title":"Super cool PHP Product","price":2,"taxesPercentage":0.50,'
                . '"taxesAmount":1,"taxesCountry":"DE","currency":"USD","paymentMethod":"CREDIT_CARD",'
                . '"transactionDate":"2015-12-10T15:00:00","status":"DONE","shippingCosts":0,"shippingCostsTaxesPercentage":0,'
                . '"shippingCostsTaxesAmount":1},{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1","customerId":"0QPHCEIQBNJN",'
                . '"productId":"fuwUyCq8281643736141","title":"Super cool PHP Product","price":2,"taxesPercentage":0.50,'
                . '"taxesAmount":1,"taxesCountry":"DE","currency":"USD","paymentMethod":"CREDIT_CARD",'
                . '"transactionDate":"2015-12-10T15:00:00","status":"DONE","shippingCosts":0,"shippingCostsTaxesPercentage":0,'
                . '"shippingCostsTaxesAmount":1},{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1",'
                . '"customerId":"0QPHCEIQBNJN","productId":"fuwUyCq8281643736141","title":"Super cool PHP Product","price":2,'
                . '"taxesPercentage":0.50,"taxesAmount":1,"taxesCountry":"DE","currency":"USD","paymentMethod":"CREDIT_CARD",'
                . '"transactionDate":"2015-12-10T15:00:00","status":"DONE","shippingCosts":0,"shippingCostsTaxesPercentage":0,'
                . '"shippingCostsTaxesAmount":1},{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1","customerId":"0QPHCEIQBNJN",'
                . '"productId":"fuwUyCq8281643736141","title":"Super cool PHP Product","price":2,"taxesPercentage":0.50,'
                . '"taxesAmount":1,"taxesCountry":"DE","currency":"USD","paymentMethod":"CREDIT_CARD","transactionDate":"2015-12-10T15:00:00",'
                . '"status":"DONE","shippingCosts":0,"shippingCostsTaxesPercentage":0,"shippingCostsTaxesAmount":1},'
                . '{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1","customerId":"0QPHCEIQBNJN","productId":"fuwUyCq8281643736141",'
                . '"title":"Super cool PHP Product","price":2,"taxesPercentage":0.50,"taxesAmount":1,"taxesCountry":"DE","currency":"USD",'
                . '"paymentMethod":"CREDIT_CARD","transactionDate":"2015-12-10T15:00:00","status":"DONE","shippingCosts":0,'
                . '"shippingCostsTaxesPercentage":0,"shippingCostsTaxesAmount":1},{"transactionId":"Cu30wyt1r6h0S042z9U5J776Y7A404P1",'
                . '"customerId":"0QPHCEIQBNJN","productId":"fuwUyCq8281643736141","title":"Super cool PHP Product",'
                . '"price":2,"taxesPercentage":0.50,"taxesAmount":1,"taxesCountry":"DE","currency":"USD","paymentMethod":"CREDIT_CARD",'
                . '"transactionDate":"2015-12-10T15:00:00","status":"DONE",'
                . '"shippingCosts":0,"shippingCostsTaxesPercentage":0,"shippingCostsTaxesAmount":1}]}');

        return array(array($data));
    }

    /**
     * @dataProvider transactionsProvider
     */
    public function testGetUserList($data) {
        TransactionServiceMock::$requestResponse = $data;
        $result = TransactionServiceMock::searchTransactions();

        $this->assertFalse(is_null($result));
        $this->assertInstanceOf('plenigo\models\TransactionList', $result);
        foreach ($result as $transx) {
            $this->assertInstanceOf('plenigo\models\Transaction', $transx);
        }
        $this->assertError(E_USER_NOTICE, "GET URL CALL");
    }

    /**
     * @dataProvider transactionsProvider
     */
    public function testSearchBigPage($data) {
        TransactionServiceMock::$requestResponse = $data;
        $result = TransactionServiceMock::searchTransactions(0, 200);

        $this->assertFalse(is_null($result));

        $this->assertError(E_USER_NOTICE, "size=100");
    }

    /**
     * @dataProvider transactionsProvider
     */
    public function testSearchSmallPage($data) {
        TransactionServiceMock::$requestResponse = $data;
        $result = TransactionServiceMock::searchTransactions(0, 2);

        $this->assertFalse(is_null($result));

        $this->assertError(E_USER_NOTICE, "size=10");
    }

    /**
     * @dataProvider transactionsProvider
     */
    public function testSearchNegativePageNumber($data) {
        TransactionServiceMock::$requestResponse = $data;
        $result = TransactionServiceMock::searchTransactions(-1);

        $this->assertFalse(is_null($result));

        $this->assertError(E_USER_NOTICE, "page=0");
    }

    /**
     * @dataProvider transactionsProvider
     */
    public function testSearchBigRangeTrim($data) {
        TransactionServiceMock::$requestResponse = $data;

        $startDate = strtotime("-28 months");
        $endDate = strtotime("today");
        $resultingStartDate = date("Y-m-d", strtotime("-18 months"));

        $result = TransactionServiceMock::searchTransactions(0, 10, $startDate, $endDate);

        $this->assertFalse(is_null($result));

        $this->assertError(E_USER_NOTICE, "startDate=" . $resultingStartDate);
    }

    /**
     * @dataProvider transactionsProvider
     */
    public function testSearchFutureTrim($data) {
        TransactionServiceMock::$requestResponse = $data;

        $endDate = strtotime("+6 months");
        $startDate = strtotime("-3 months", $endDate);
        $resultingEndDate = date("Y-m-d", strtotime("today"));

        $result = TransactionServiceMock::searchTransactions(0, 10, $startDate, $endDate);

        $this->assertFalse(is_null($result));

        $this->assertError(E_USER_NOTICE, "endDate=" . $resultingEndDate);
    }

    /**
     * @dataProvider transactionsProvider
     */
    public function testSearchEndNullTrim($data) {
        TransactionServiceMock::$requestResponse = $data;

        $endDate = null;
        $startDate = strtotime("-3 months");
        $resultingEndDate = date("Y-m-d", strtotime("today"));

        $result = TransactionServiceMock::searchTransactions(0, 10, $startDate, $endDate);

        $this->assertFalse(is_null($result));

        $this->assertError(E_USER_NOTICE, "endDate=" . $resultingEndDate);
    }

    /**
     * @dataProvider transactionsProvider
     */
    public function testSearchsTARTNullTrim($data) {
        TransactionServiceMock::$requestResponse = $data;

        $endDate = strtotime("today");
        $startDate = null;
        $resultingStartDate = date("Y-m-d", strtotime("-18 months"));

        $result = TransactionServiceMock::searchTransactions(0, 10, $startDate, $endDate);

        $this->assertFalse(is_null($result));

        $this->assertError(E_USER_NOTICE, "startDate=" . $resultingStartDate);
    }

}
