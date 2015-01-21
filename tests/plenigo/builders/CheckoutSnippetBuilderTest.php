<?php

require_once( __DIR__ . '/../../../src/plenigo/PlenigoManager.php' );
require_once( __DIR__ . '/../../../src/plenigo/models/ProductBase.php' );
require_once( __DIR__ . '/../../../src/plenigo/builders/CheckoutSnippetBuilder.php' );
require_once(__DIR__ . '/../internal/utils/PlenigoTestCase.php');

use \plenigo\PlenigoManager;
use \plenigo\models\ProductBase;
use \plenigo\builders\CheckoutSnippetBuilder;

class CheckoutSnippetBuilderTest extends PlenigoTestCase
{

    public function checkoutSnippetBuilderProvider()
    {
        $product = new ProductBase(
            'item-123', 'item-review', 1.5, 'USD'
        );
        $product->setCategoryId("New Category");

        return array(array($product));
    }

    /**
     * @dataProvider checkoutSnippetBuilderProvider
     */
    public function testBuild($product)
    {
        $checkout = new CheckoutSnippetBuilder($product);

        $plenigoCheckoutCode = $checkout->build();

        $this->assertRegExp("/^plenigo\\.checkout\\('\\w+'\\);$/", $plenigoCheckoutCode);
        $this->assertError(E_USER_NOTICE, "Building CHECKOUT");
    }

    /**
     * @dataProvider checkoutSnippetBuilderProvider
     */
    public function testBuildSettings($product)
    {
        $checkout = new CheckoutSnippetBuilder($product);

        $plenigoCheckoutCode = $checkout->build(array(
            'showBuyingAgainScreen' => true,
            'showCheckoutConfirmationScreen' => true,
            'testMode' => true
        ));

        $this->assertRegExp("/^plenigo\\.checkout\\('\\w+'\\);$/", $plenigoCheckoutCode);
        $this->assertError(E_USER_NOTICE, "Building CHECKOUT");
    }

    /**
     * @dataProvider checkoutSnippetBuilderProvider
     */
    public function testCheckCategory($product)
    {
        $checkout = new CheckoutSnippetBuilder($product);

        $plenigoCheckoutCode = $checkout->build();

        $this->assertEquals("New Category", $checkout->getProduct()->getCategoryId());

        $this->assertRegExp("/^plenigo\\.checkout\\('\\w+'\\);$/", $plenigoCheckoutCode);
        $this->assertError(E_USER_NOTICE, "Building CHECKOUT");
    }

}
