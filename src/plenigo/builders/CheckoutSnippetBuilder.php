<?php

namespace plenigo\builders;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../internal/models/Product.php';
require_once __DIR__ . '/../internal/utils/EncryptionUtils.php';
require_once __DIR__ . '/../internal/server-interface/payment/Checkout.php';
require_once __DIR__ . '/../internal/exceptions/ProductException.php';

use \plenigo\PlenigoManager;
use \plenigo\internal\models\Product;
use \plenigo\internal\utils\EncryptionUtils;
use \plenigo\internal\serverInterface\payment\Checkout;
use \plenigo\internal\exceptions\ProductException;

/**
 * CheckoutSnippetBuilder
 *
 * <p>
 * This class builds a plenigo's Javascript API checkout
 * snippet based on a {@link \plenigo\internal\models\Product} object that is
 * compliant.
 * </p>
 *
 * @category SDK
 * @package  PlenigoBuilders
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class CheckoutSnippetBuilder
{

    /**
     * The Product object used to build the link.
     */
    protected $product;

    /**
     * This constructor takes a {@link Product} object as a parameter.
     *
     * @param Product $productToChkOut The product object to build the link from
     */
    public function __construct(Product $productToChkOut)
    {
        $this->product = $productToChkOut;
    }

    /**
     * This method is used to build the link once all the information and
     * options have been selected, this will produce a Javascript snippet of
     * code that can be used as an event on a webpage.
     *
     * @param array $settings A map of settings to pass to the Checkout service interface.
     *
     * @return string A Javascript snippet that is compliant with plenigo's Javascript
     * SDK.
     * @throws CryptographyException When an error occurs during data encoding
     */
    public function build($settings = array())
    {
        $clazz = get_class();
        PlenigoManager::get()->notice($clazz, "Building CHECKOUT snippet:");
        //Add testMode SDK check
        if (PlenigoManager::get()->isTestMode() === true) {
            $settings["testMode"] = "true";
        }

        $requestQueryString = $this->buildCheckoutRequestQueryString($settings);
        $encodedData = $this->buildEncodedData($requestQueryString);
		PlenigoManager::get()->notice($clazz, "Checkout QUERYSTRING:". $requestQueryString);
        return sprintf("plenigo.checkout('%s');", $encodedData);
    }

    /**
     * This method builds the encoded data from the Checkout Object.
     *
     * @param array $settings A map of settings to pass to the Checkout service interface.
     *
     * @return string The encoded data
     */
    private function buildCheckoutRequestQueryString($settings = array())
    {
        $request = new Checkout($this->product);

        $request->setValuesFromMap($settings);

        return $request->getQueryString();
    }

    /**
     * This method builds the encoded data from the Checkout Object.
     *
     * @param string $dataToEncode the string data to encode.
     *
     * @return string The encoded data
     */
    private function buildEncodedData($dataToEncode)
    {
        $secret = PlenigoManager::get()->getSecret();

        return EncryptionUtils::encryptWithAES($secret, $dataToEncode);
    }

    /**
     * Returns the product for testing purposes
     * 
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

}
