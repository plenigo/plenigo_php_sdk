<?php

namespace plenigo\models;

/**
 * OrderItem
 * 
 * <p>
 * This class contains the failed payment information.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class OrderItem {

    /**
     * Product Id
     * @var string 
     */
    private $productId;

    /**
     * Order item title 
     * @var string 
     */
    private $title;

    /**
     * Order item price
     * @var number 
     */
    private $price;

    /**
     * Shipping costs
     * @var number 
     */
    private $shippingCosts;

    /**
     * Taxes 
     * @var number 
     */
    private $taxes;

    /**
     * Tax Country
     * @var string 
     */
    private $taxCountry;

    /**
     * Amount of order item
     * @var int 
     */
    private $quantity;

    /**
     * Order item cost center
     * @var string
     */
    private $costCenter;

    /**
     * The revenue account 
     * @var string
     */
    private $revenueAccount;

    /**
     * Order Item status
     * @var string 
     */
    private $status;

    /**
     * Default constructor.
     */
    private function __construct() {
    }

    /**
     * Getter method.
     * 
     * @return string
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * Getter method.
     * 
     * @return string
     */
    public function getTitle() {
        return $this->title;
    }

    /**
     * Getter method.
     * 
     * @return number
     */
    public function getPrice() {
        return $this->price;
    }

    /**
     * Getter method.
     * 
     * @return number
     */
    public function getShippingCosts() {
        return $this->shippingCosts;
    }

    /**
     * Getter method.
     * 
     * @return number
     */
    public function getTaxes() {
        return $this->taxes;
    }

    /**
     * Getter method.
     * 
     * @return string
     */
    public function getTaxCountry() {
        return $this->taxCountry;
    }

    /**
     * Getter method.
     * 
     * @return int
     */
    public function getQuantity() {
        return $this->quantity;
    }

    /**
     * Getter method.
     * 
     * @return string
     */
    public function getCostCenter() {
        return $this->costCenter;
    }

    /**
     * Getter method.
     * 
     * @return string
     */
    public function getRevenueAccount() {
        return $this->revenueAccount;
    }

    /**
     * Getter method.
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Setter method for product id.
     * 
     * @param string $productId
     */
    public function setProductId($productId) {
        $this->productId = $productId;
    }

    /**
     * Setter method for title.
     * 
     * @param string $title
     */
    public function setTitle($title) {
        $this->title = $title;
    }

    /**
     * Setter method for price.
     * 
     * @param number $price
     */
    public function setPrice($price) {
        $this->price = $price;
    }

    /**
     * Setter method shipping costs.
     * 
     * @param number $shippingCosts
     */
    public function setShippingCosts($shippingCosts) {
        $this->shippingCosts = $shippingCosts;
    }

    /**
     * Setter method taxes.
     * 
     * @param number $taxes
     */
    public function setTaxes($taxes) {
        $this->taxes = $taxes;
    }

    /**
     * Setter method tax country.
     * 
     * @param string $taxCountry
     */
    public function setTaxCountry($taxCountry) {
        $this->taxCountry = $taxCountry;
    }

    /**
     * Setter method for quantity.
     * 
     * @param int $quantity
     */
    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    /**
     * Setter method for cost center.
     * 
     * @param string $costCenter
     */
    public function setCostCenter($costCenter) {
        $this->costCenter = $costCenter;
    }

    /**
     * Setter method for revenue account.
     * 
     * @param string $revenueAccount
     */
    public function setRevenueAccount($revenueAccount) {
        $this->revenueAccount = $revenueAccount;
    }

    /**
     * Setter method for status.
     * 
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Creates an OrderItem array from an array of maps containing the fields.
     * 
     * @param array $arrParam the array of maps containning the fields.
     * 
     * @return array an array of OrderItem objects .
     */
    public static function createFromArray($arrParam = array()) {
        $res = array();

        if (!is_null($arrParam) && is_array($arrParam) & count($arrParam) > 0) {
            foreach ($arrParam as $oItem) {
                $res[] = static::createFromMap($oItem);
            }
        }
        return $res;
    }

    /**
     * Creates a OrderItem instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * 
     * @return OrderItem instance.
     */
    public static function createFromMap($map) {
        $instance = new OrderItem();
        $instance->setProductId(isset($map['productId']) ? $map['productId'] : null);
        $instance->setTitle(isset($map['title']) ? $map['title'] : null);
        $instance->setPrice(isset($map['price']) ? $map['price'] : null);
        $instance->setShippingCosts(isset($map['shippingCosts']) ? $map['shippingCosts'] : null);
        $instance->setTaxes(isset($map['taxes']) ? $map['taxes'] : null);
        $instance->setTaxCountry(isset($map['taxCountry']) ? $map['taxCountry'] : null);
        $instance->setQuantity(isset($map['quantity']) ? $map['quantity'] : null);
        $instance->setCostCenter(isset($map['costCenter']) ? $map['costCenter'] : null);
        $instance->setRevenueAccount(isset($map['revenueAccount']) ? $map['revenueAccount'] : null);
        $instance->setStatus(isset($map['status']) ? $map['status'] : null);
        return $instance;
    }

}
