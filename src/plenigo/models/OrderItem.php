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

    private $productId;
    private $title;
    private $price;
    private $shippingCosts;
    private $taxes;
    private $taxCountry;
    private $quantity;
    private $costCenter;
    private $revenueAccount;
    private $status;
    
    
    private function __construct() {
        
    }
    
    public function getProductId() {
        return $this->productId;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getPrice() {
        return $this->price;
    }

    public function getShippingCosts() {
        return $this->shippingCosts;
    }

    public function getTaxes() {
        return $this->taxes;
    }

    public function getTaxCountry() {
        return $this->taxCountry;
    }

    public function getQuantity() {
        return $this->quantity;
    }

    public function getCostCenter() {
        return $this->costCenter;
    }

    public function getRevenueAccount() {
        return $this->revenueAccount;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function setPrice($price) {
        $this->price = $price;
    }

    public function setShippingCosts($shippingCosts) {
        $this->shippingCosts = $shippingCosts;
    }

    public function setTaxes($taxes) {
        $this->taxes = $taxes;
    }

    public function setTaxCountry($taxCountry) {
        $this->taxCountry = $taxCountry;
    }

    public function setQuantity($quantity) {
        $this->quantity = $quantity;
    }

    public function setCostCenter($costCenter) {
        $this->costCenter = $costCenter;
    }

    public function setRevenueAccount($revenueAccount) {
        $this->revenueAccount = $revenueAccount;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Creates an OrderItem array from an array of maps containing the fields
     * 
     * @param array $arrParam the array of maps containning the fields
     * @return array an array of OrderItem objects 
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
