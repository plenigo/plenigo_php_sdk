<?php

namespace plenigo\builders;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../internal/models/Product.php';
require_once __DIR__ . '/../internal/utils/EncryptionUtils.php';
require_once __DIR__ . '/../internal/server-interface/payment/Checkout.php';
require_once __DIR__ . '/../internal/exceptions/ProductException.php';

use plenigo\internal\exceptions\EncryptionException;
use plenigo\internal\models\Product;
use plenigo\internal\serverInterface\payment\Checkout;
use plenigo\internal\utils\EncryptionUtils;
use plenigo\PlenigoManager;

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
class CheckoutSnippetBuilder {

    /**
     * The Product object used to build the link.
     */
    protected $product;

    /**
     * This constructor takes a {@link Product} object as a parameter.
     *
     * @param Product $productToChkOut The product object to build the link from
     */
    public function __construct(Product $productToChkOut) {
        $this->product = $productToChkOut;
    }

    /**
     * This method is used to build the link once all the information and
     * options have been selected, this will produce a Javascript snippet of
     * code that can be used as an event on a webpage.
     *
     * @param array $settings A map of settings to pass to the Checkout service interface
     * @param string $loginToken A string containing the loginToken (if obtained from the external user management) or null
     * @param bool $showRegisterFirst Shows a register screen if the user is not loged in (default: false)
     * @param string $sourceUrl URL of the page that lead to that checkout
     * @param string $targetUrl URL of the page the process should redirect to after successful payment
     * @param string $affiliateId affiliate id to associate with this checkout
     * @param string $elementId id of the element the checkout should be inserted into, if this parameter is passed the checkout will be embedded
     * 
     * @return string A Javascript snippet that is compliant with plenigo's Javascript SDK.
     * @throws \Exception When the PlenigoManager has not been previously configured.
     * @throws EncryptionException When an error occurs during data encoding
     */
    public function build($settings = array(), $loginToken = null, $showRegisterFirst = false, $sourceUrl = null, $targetUrl = null, $affiliateId = null, $elementId = null) {
        $clazz = get_class();
        PlenigoManager::get()->notice($clazz, "Building CHECKOUT snippet:");
        //Add testMode SDK check
        if (PlenigoManager::get()->isTestMode() === true) {
            $settings["testMode"] = "true";
        }

        $requestQueryString = $this->buildCheckoutRequestQueryString($settings);
        $encodedData = $this->buildEncodedData($requestQueryString);
        PlenigoManager::get()->notice($clazz, "Checkout QUERYSTRING:" . $requestQueryString);

        $strFunction = "plenigo.checkout";
        $strFirstParam = "";
        if ($showRegisterFirst) {
            $strFirstParam = ", true";
        }
        if (!is_null($loginToken)) {
            PlenigoManager::get()->notice($clazz, "Login TOKEN:" . $loginToken);
            $strFunction = "plenigo.checkoutWithRemoteLogin";
            $strFirstParam = ", '" . $loginToken . "'";
        }
        $strSourceURL = "";
        $strTargetURL = "";
        if (!is_null($sourceUrl)) {
            PlenigoManager::get()->notice($clazz, "Source URL:" . $sourceUrl);
            $strSourceURL = ", '" . $sourceUrl . "'";
            if (!$showRegisterFirst && is_null($loginToken)) {
                $strFirstParam = ", false";
            }
        }
        if (!is_null($targetUrl)) {
            PlenigoManager::get()->notice($clazz, "Target URL:" . $targetUrl);
            $strTargetURL = ", '" . $targetUrl . "'";
            if (is_null($sourceUrl)) {
                $strSourceURL = ", null";
            }
	        if (empty($strFirstParam) && !$showRegisterFirst && is_null($loginToken)) {
		        $strFirstParam = ", false";
	        }
        }
        $strAffiliate = null;
        if (!is_null($affiliateId)) {
            PlenigoManager::get()->notice($clazz, "Affiliate ID:" . $affiliateId);
            $strAffiliate = ", '" . $affiliateId . "'";
            if (is_null($sourceUrl)) {
                $strSourceURL = ", null";
            }
            if (is_null($targetUrl)) {
                $strTargetURL = ", null";
            }
        }
        $strElementId = "";
        if (!is_null($elementId)) {
            PlenigoManager::get()->notice($clazz, "Element ID:" . $elementId);
            $strElementId = ", '" . $elementId . "'";
            if (is_null($sourceUrl)) {
                $strSourceURL = ", null";
            }
            if (is_null($targetUrl)) {
                $strTargetURL = ", null";
            }
            if (is_null($strAffiliate)) {
                $strAffiliate = ", null";
            }
        }

        $strFunctionFormula = $strFunction . "('%s'" . $strFirstParam . $strSourceURL . $strTargetURL . $strAffiliate . $strElementId . ");";
        return sprintf($strFunctionFormula, $encodedData);
    }

    /**
     * This method builds the encoded data from the Checkout Object.
     *
     * @param array $settings A map of settings to pass to the Checkout service interface.
     *
     * @return string The encoded data
     * @throws \Exception
     */
    private function buildCheckoutRequestQueryString($settings = array()) {
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
     * @throws \Exception
     * @throws EncryptionException
     */
    private function buildEncodedData($dataToEncode) {
        $secret = PlenigoManager::get()->getSecret();

        return EncryptionUtils::encryptWithAES($secret, $dataToEncode);
    }

    /**
     * Returns the product for testing purposes
     * 
     * @return Product
     */
    public function getProduct() {
        return $this->product;
    }

}
