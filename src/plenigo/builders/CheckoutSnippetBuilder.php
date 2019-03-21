<?php

namespace plenigo\builders;

require_once __DIR__ . '/../PlenigoManager.php';
require_once __DIR__ . '/../internal/models/Product.php';
require_once __DIR__ . '/../internal/utils/EncryptionUtils.php';
require_once __DIR__ . '/../internal/server-interface/payment/Checkout.php';
require_once __DIR__ . '/../internal/exceptions/ProductException.php';
require_once __DIR__ . '/../internal/utils/JWT.php';

use plenigo\internal\exceptions\EncryptionException;
use plenigo\internal\models\Product;
use plenigo\internal\serverInterface\payment\Checkout;
use plenigo\internal\utils\JWT;
use plenigo\PlenigoManager;
use \DateTime;

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
     * @var array
     */
    protected $rules = [];

    /**
     * This constructor takes a {@link Product} object as a parameter.
     *
     * @param Product $productToChkOut The product object to build the link from
     */
    public function __construct(Product $productToChkOut) {
        $this->product = $productToChkOut;
    }

    /**
     * Adding a birthday to checkout to automatically fill the verification rule
     *
     * @param DateTime $date
     * @return bool id rule can be added
     * @throws \Exception
     */
    public function addBirthdayRule(DateTime $date) {

        // should never happen because of TypeHinting
        if (!is_a($date, "\DateTime")) {
            throw new \Exception("Parameter has to be a DateTime");
        }

        // defining maximum and minimum of a valid birthday
        // sorry for this oldest man alive
        $min = new DateTime("1900-01-01");
        $max = new DateTime("now");

        // valid birthday is a valid birthday
        if (!($min < $date) && ($date < $max)) {
            throw new \Exception("Paramater has to be a valid birthday date. You gave " . $date->format("Y-m-d"));
        }

        array_push($this->rules, ['name' => 'birthday', 'date' => $date->format("mdY")]);

        return true;

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
     * @throws EncryptionException When an error occurs during data encoding
     */
    public function build($settings = array(), $loginToken = null, $showRegisterFirst = false, $sourceUrl = null, $targetUrl = null, $affiliateId = null, $elementId = null) {
        $clazz = get_class();
        PlenigoManager::get()->notice($clazz, "Building CHECKOUT snippet:");
        //Add testMode SDK check
        if (PlenigoManager::get()->isTestMode() === true) {
            $settings["testMode"] = "true";
        }

        if (!empty($this->rules) && is_array($this->rules)) {
            foreach ($this->rules as $rule) {
                if ($rule['name'] === 'birthday' && !empty($rule['date'])) {
                    $settings['birthdayRuleParam'] = $rule['date'];
                }
            }
        }

        $requestQuery = $this->buildCheckoutRequestQueryString($settings);
        $encodedData = $this->buildEncodedData($requestQuery);
        PlenigoManager::get()->notice($clazz, "Checkout QUERYSTRING:" . http_build_query($requestQuery));

        $strFunction = "plenigo.checkout";

        $checkoutParams = ['paymentData' => $encodedData,
            'startWithRegistration' => $showRegisterFirst];
        
        if (!is_null($loginToken)) {
            PlenigoManager::get()->notice($clazz, "Login TOKEN:" . $loginToken);
            $strFunction = "plenigo.checkoutWithRemoteLogin";
            $checkoutParams['loginToken'] = $loginToken;
        }
        
        if (!is_null($sourceUrl)) {
            PlenigoManager::get()->notice($clazz, "Source URL:" . $sourceUrl);
            $checkoutParams['sourceUrl'] = $sourceUrl;
        }
        if (!is_null($targetUrl)) {
            PlenigoManager::get()->notice($clazz, "Target URL:" . $targetUrl);
            $checkoutParams['targetUrl'] = $targetUrl;
        }
        if (!is_null($affiliateId)) {
            PlenigoManager::get()->notice($clazz, "Affiliate ID:" . $affiliateId);
            $checkoutParams['affiliateId'] = $affiliateId;
        }
        if (!is_null($elementId)) {
            PlenigoManager::get()->notice($clazz, "Element ID:" . $elementId);
            $checkoutParams['elementId'] = $elementId;
        }


        $strFunctionFormula = $strFunction . "(" . json_encode($checkoutParams) . ");";
        return $strFunctionFormula;
    }

    /**
     * This method builds the encoded data from the Checkout Object.
     *
     * @param array $settings A map of settings to pass to the Checkout service interface.
     *
     * @return array The encoded data
     */
    private function buildCheckoutRequestQueryString($settings = array()) {
        $request = new Checkout($this->product);

        $request->setValuesFromMap($settings);

        return $request->getMap();
    }

    /**
     * This method builds the encoded data from the Checkout Object.
     *
     * @param array $dataToEncode the string data to encode.
     *
     * @return string The encoded data
     */
    private function buildEncodedData($dataToEncode) {
        $secret = PlenigoManager::get()->getSecret();
        $dataToEncode['aud'] = 'plenigo';

        return JWT::encode($dataToEncode, $secret);
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
