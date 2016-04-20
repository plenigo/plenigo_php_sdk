<?php

require_once __DIR__ . '/../../../../../src/plenigo/internal/server-interface/payment/Checkout.php';
require_once __DIR__ . '/../../../../../src/plenigo/models/ProductBase.php';

use plenigo\internal\serverInterface\payment\Checkout;
use plenigo\models\ProductBase;

class CheckoutTest extends PHPUnit_Framework_Testcase
{
    public function checkoutRequestProvider()
    {
        $map = array(
            'price'         => 1.5,
            'title'         => 'premium-read',
            'testMode'      => true,
            'segmentId'      => 'test_seg',
        );

        $checkoutRequest = new Checkout($map);

        return array(array($checkoutRequest, $map));
    }

    /**
     * @expectedException Exception
     */
    public function testConstructorException()
    {
        $checkoutRequest = new Checkout(1234);
    }

    public function testCheckoutWithProductInstance()
    {
        $expectedResult     = 'pr=>2.5&cu=>USD&ti=>premium-read&pi=>premium-123&ts=>true';
        $product            = new ProductBase('premium-123', 'premium-read', 2.5, 'USD');

        $checkoutRequest    = new Checkout($product);
        $checkoutRequest->setTestMode(true);

        $queryString        = $checkoutRequest->getQueryString();

        $this->assertEquals($expectedResult, $queryString);
    }

    public function testSetProduct()
    {
        $expectedResult     = 'pr=>2.5&cu=>USD&ti=>premium-read&pi=>premium-123&ts=>true';
        $product            = new ProductBase('premium-123', 'premium-read', 2.5, 'USD');

        $checkoutRequest    = new Checkout();
        $checkoutRequest->setProduct($product);
        $checkoutRequest->setTestMode(true);

        $queryString        = $checkoutRequest->getQueryString();

        $this->assertEquals($expectedResult, $queryString);
    }

    public function testSetValuesFromMap()
    {
        $expectedResult     = 'pr=>1.5&cu=>USD&ti=>premium-read&ts=>true';
        $map            = array(
            'price'     => 1.5,
            'title'     => 'premium-read',
            'currency'  => 'USD',
            'testMode'  => true
        );

        $checkoutRequest    = new Checkout();
        $checkoutRequest->setValuesFromMap($map);

        $queryString        = $checkoutRequest->getQueryString();

        $this->assertEquals($expectedResult, $queryString);
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testProductIdSetterAndGetter($checkout)
    {
        $expectedResult = '123456789A123456789B';

        $checkout->setProductId($expectedResult);

        $this->assertEquals($expectedResult, $checkout->getProductId());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testPriceSetterAndGetter($checkout)
    {
        $expectedResult = 4;

        $checkout->setPrice($expectedResult);

        $this->assertEquals($expectedResult, $checkout->getPrice());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testTypeSetterAndGetter($checkout)
    {
        $expectedResult = "EBOOK";

        $checkout->setType($expectedResult);

        $this->assertEquals($expectedResult, $checkout->getType());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testSABSetterAndGetter($checkout)
    {
        $expectedResult = true;

        $checkout->setShowBuyingAgainScreen($expectedResult);

        $this->assertEquals($expectedResult, $checkout->getShowBuyingAgainScreen());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testSPFSetterAndGetter($checkout)
    {
        $expectedResult = true;

        $checkout->setShowCheckoutConfirmationScreen($expectedResult);

        $this->assertEquals($expectedResult, $checkout->getShowCheckoutConfirmationScreen());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testSSOSetterAndGetter($checkout)
    {
        $expectedResult = 'http://example.com/';

        $checkout->setOauth2RedirectUrl($expectedResult);

        $this->assertEquals($expectedResult, $checkout->getOauth2RedirectUrl());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testCSRFSetterAndGetter($checkout)
    {
        $expectedResult = '123456789A123456789B';

        $checkout->setCsrfToken($expectedResult);

        $this->assertEquals($expectedResult, $checkout->getCsrfToken());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testSPSetterAndGetter($checkout)
    {
        $expectedResult = true;

        $checkout->setPayWhatYouWant($expectedResult);

        $this->assertEquals($expectedResult, $checkout->getPayWhatYouWant());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testTestModeSetterAndGetter($checkout)
    {
        $expectedResult = true;

        $checkout->setTestMode($expectedResult);

        $this->assertEquals($expectedResult, $checkout->getTestMode());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testGetMap($checkout, $original)
    {
        $expectedResult = array(
            'pr'    => $original['price'],
            'ti'    => $original['title'],
            'ts'    => $original['testMode'],
            'si'    => $original['segmentId']
        );

        $checkout->anotherProperty = 'value';

        $this->assertEquals($expectedResult, $checkout->getMap());
    }

    /**
     * @dataProvider checkoutRequestProvider
     */
    public function testQueryString($checkout)
    {
        $expectedResult = 'pr=>1.5&ti=>premium-read&si=>test_seg&ts=>true';

        $queryString = $checkout->getQueryString();

        $this->assertEquals($expectedResult, $queryString);
    }
}