<?php

namespace plenigo\internal\serverInterface\payment;

require_once __DIR__ . '/../ServerInterface.php';
require_once __DIR__ . '/../../models/Product.php';
require_once __DIR__ . '/../../utils/ArrayUtils.php';
require_once __DIR__ . '/../../../PlenigoException.php';
require_once __DIR__ . '/../../../PlenigoManager.php';

use \plenigo\internal\serverInterface\ServerInterface;
use \plenigo\internal\models\Product as AbstractProduct;
use \plenigo\internal\utils\ArrayUtils;
use \plenigo\PlenigoException;
use \plenigo\PlenigoManager;
use \Exception;

/**
 * Checkout
 *
 * <p>
 * Prepares all parameters needed for the
 * payment/Checkout server interface
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalServerInterfacePayment
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
final class Checkout extends ServerInterface {

    const TITLE_MAX_LENGTH = 100;
    const PROD_ID_MAX_LENGTH = 20;
    const ERR_MSG_TITLE_TOO_LONG = "The Product title is too long and it will be truncated (100 chars max.)";
    const ERR_MSG_PROD_ID_TOO_LONG = "The Product ID can be up to 20 chars long!";

    protected $productId;
    protected $price;
    protected $currency;
    protected $type;
    protected $title;
    protected $categoryId;
    protected $showBuyingAgainScreen;
    protected $showCheckoutConfirmationScreen;
    protected $oauth2RedirectUrl;
    protected $csrfToken;
    protected $payWhatYouWant;
    protected $testMode;

    /**
     * <p>
     * Default constructor.
     * Accepts a map with the checkout information
     * </p>
     * 
     * @param array $map a map with the checkout information
     *
     * @return Checkout an instance of {@link plenigo\internal\serverInterface\payment\Checkout}
     */
    public function __construct($map = array()) {
        if (!is_array($map) && $map instanceof AbstractProduct === false) {
            throw new Exception('$map parameter is not a map nor an instance of Product');
        }

        if ($map instanceof AbstractProduct) {
            $this->setProduct($map);
        } else {
            $this->setValuesFromMap($map);
        }
    }

    /**
     * Accepts relevant request parameters from a {@link \Plenigo\models\Product} instance.
     *
     * @param AbstractProduct $product The product instance to extract values from.
     *
     * @return void
     */
    public function setProduct(AbstractProduct $product) {
        $productMap = $product->getMap();
        $map = array();

        ArrayUtils::addIfDefined($map, 'productId', $productMap, 'id');
        ArrayUtils::addIfDefined($map, 'price', $productMap, 'price');
        ArrayUtils::addIfDefined($map, 'title', $productMap, 'title');
        ArrayUtils::addIfDefined($map, 'categoryId', $productMap, 'categoryId');
        ArrayUtils::addIfDefined($map, 'currency', $productMap, 'currency');
        ArrayUtils::addIfDefined($map, 'type', $productMap, 'type');
        ArrayUtils::addIfDefined($map, 'subscriptionRenewal', $productMap, 'subscriptionRenewal');

        $this->setValuesFromMap($map);
    }

    /**
     * Accepts relevant request parameters from a array map.
     *
     * @param array $map The array to extract values from.
     *
     * @return void
     */
    public function setValuesFromMap($map) {
        $this->setValueFromMapIfNotEmpty('productId', $map);
        $this->setValueFromMapIfNotEmpty('price', $map);
        $this->setValueFromMapIfNotEmpty('currency', $map);
        $this->setValueFromMapIfNotEmpty('type', $map);
        $this->setValueFromMapIfNotEmpty('title', $map);
        $this->setValueFromMapIfNotEmpty('showBuyingAgainScreen', $map);
        $this->setValueFromMapIfNotEmpty('showCheckoutConfirmationScreen', $map);
        $this->setValueFromMapIfNotEmpty('oauth2RedirectUrl', $map);
        $this->setValueFromMapIfNotEmpty('csrfToken', $map);
        $this->setValueFromMapIfNotEmpty('categoryId', $map);
        $this->setValueFromMapIfNotEmpty('payWhatYouWant', $map);
        $this->setValueFromMapIfNotEmpty('testMode', $map);
        $this->setValueFromMapIfNotEmpty('subscriptionRenewal', $map);

        $this->performValidation();
    }

    /**
     * Sets the Product Id.
     *
     * @param string $productId The product ID.
     *
     * @return void
     */
    public function setProductId($productId) {
        //$pi validation

        $this->productId = $productId;
    }

    /**
     * Sets the product's price.
     *
     * @param float $price The product price.
     *
     * @return void
     */
    public function setPrice($price) {
        if ($this->validateNumber($price)) {
            $this->price = $price;
        }
    }

    /**
     * Sets the product type.
     *
     * @param string $type product type.
     *
     * @return void
     */
    public function setType($type) {
        $this->type = $type;
    }

    /**
     * <p>
     * Sets the flag that determines if a screen
     * is to be shown when the user has already
     * bought the item.
     * </p>
     *
     * @param bool $sab The "show screen" flag
     *
     * @return void
     */
    public function setShowBuyingAgainScreen($sab) {
        $this->showBuyingAgainScreen = safe_boolval($sab);
    }

    /**
     * <p>
     * Sets the flag that determines if a screen
     * is to be after an item has been bought.
     * </p>
     *
     * @param bool $spf the "show screen" flag
     *
     * @return void
     */
    public function setShowCheckoutConfirmationScreen($spf) {
        $this->showCheckoutConfirmationScreen = safe_boolval($spf);
    }

    /**
     * Sets the OAUTH2 redirection URL.
     *
     * @param string $sso the OAUTH2 URL to redirect to.
     *
     * @return void
     */
    public function setOauth2RedirectUrl($sso) {
        //is this a string?
        $this->oauth2RedirectUrl = $sso;
    }

    /**
     * Sets the CSRF Token to use for the transaction.
     *
     * @param string $csrf The CSRF token name.
     *
     * @return void
     */
    public function setCsrfToken($csrf) {
        //validate csrf

        $this->csrfToken = $csrf;
    }

    /**
     * Sets the flag that determines if the product is
     * a "Pay what you want" type.
     *
     * @param bool $sp The flag value.
     *
     * @return void
     */
    public function setPayWhatYouWant($sp) {
        $this->payWhatYouWant = safe_boolval($sp);
    }

    /**
     * Sets the test mode flag.
     *
     * @param bool $ts The flag value.
     *
     * @return void
     */
    public function setTestMode($ts) {
        $this->testMode = safe_boolval($ts);
    }

    /**
     * Sets the Category Id.
     *
     * @param string $ci The category ID.
     */
    public function setCategoryId($ci) {
        $this->categoryId = $ci;
    }

    /**
     * Sets the Currency.
     *
     * @param string $currency The Currency.
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    /**
     * Sets the Title.
     *
     * @param string $title The title.
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Gets the map data to be used for the transaction.
     *
     * @return array The map with the non null values assigned to the instance.
     */
    public function getMap() {
        $map = array();

        $this->insertIntoMapIfDefined($map, 'price', 'pr');
        $this->insertIntoMapIfDefined($map, 'currency', 'cu');
        $this->insertIntoMapIfDefined($map, 'type', 'pt');
        $this->insertIntoMapIfDefined($map, 'title', 'ti');
        $this->insertIntoMapIfDefined($map, 'productId', 'pi');
        $this->insertIntoMapIfDefined($map, 'showBuyingAgainScreen', 'sab');
        $this->insertIntoMapIfDefined($map, 'showCheckoutConfirmationScreen', 'spf');
        $this->insertIntoMapIfDefined($map, 'oauth2RedirectUrl', 'sso');
        $this->insertIntoMapIfDefined($map, 'csrfToken', 'csrf');
        $this->insertIntoMapIfDefined($map, 'categoryId', 'ci');
        $this->insertIntoMapIfDefined($map, 'payWhatYouWant', 'sp');
        $this->insertIntoMapIfDefined($map, 'testMode', 'ts');
        $this->insertIntoMapIfDefined($map, 'subscriptionRenewal', 'rs');

        return $map;
    }

    /**
     * Perform field validations when creating the Checkout object and throws Exceptions if needed
     * @throws PlenigoException if strict validations fail
     */
    private function performValidation() {
        $clazz = get_class();
        if (!is_null($this->title) && strlen($this->title) > self::TITLE_MAX_LENGTH) {
            \plenigo\PlenigoManager::notice($clazz, self::ERR_MSG_TITLE_TOO_LONG);
        }
        if (!is_null($this->productId) && strlen($this->productId) > self::PROD_ID_MAX_LENGTH) {
            throw new PlenigoException(self::ERR_MSG_PROD_ID_TOO_LONG);
        }
    }

}
