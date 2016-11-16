<?php

namespace plenigo\models;

require_once __DIR__ . '/OrderItem.php';

use \plenigo\models\OrderItem;

/**
 * Order
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
class Order {

    private $orderId;
    private $orderDate;
    private $customerId;
    private $currency;
    private $cumulatedPrice;
    private $vatNumbe;
    private $shippingCosts;
    private $shippingCostsTaxes;
    private $purchaseOrderIndicator;
    private $discount;
    private $discountPercentage;
    private $orderItems;
    
    
    private function __construct() {
        
    }

    public function getOrderId() {
        return $this->orderId;
    }

    public function getOrderDate() {
        return $this->orderDate;
    }

    public function getCustomerId() {
        return $this->customerId;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function getCumulatedPrice() {
        return $this->cumulatedPrice;
    }

    public function getVatNumbe() {
        return $this->vatNumbe;
    }

    public function getShippingCosts() {
        return $this->shippingCosts;
    }

    public function getShippingCostsTaxes() {
        return $this->shippingCostsTaxes;
    }

    public function getPurchaseOrderIndicator() {
        return $this->purchaseOrderIndicator;
    }

    public function getDiscount() {
        return $this->discount;
    }

    public function getDiscountPercentage() {
        return $this->discountPercentage;
    }

    public function getOrderItems() {
        return $this->orderItems;
    }

    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }

    public function setOrderDate($orderDate) {
        $this->orderDate = $orderDate;
    }

    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }

    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    public function setCumulatedPrice($cumulatedPrice) {
        $this->cumulatedPrice = $cumulatedPrice;
    }

    public function setVatNumbe($vatNumbe) {
        $this->vatNumbe = $vatNumbe;
    }

    public function setShippingCosts($shippingCosts) {
        $this->shippingCosts = $shippingCosts;
    }

    public function setShippingCostsTaxes($shippingCostsTaxes) {
        $this->shippingCostsTaxes = $shippingCostsTaxes;
    }

    public function setPurchaseOrderIndicator($purchaseOrderIndicator) {
        $this->purchaseOrderIndicator = $purchaseOrderIndicator;
    }

    public function setDiscount($discount) {
        $this->discount = $discount;
    }

    public function setDiscountPercentage($discountPercentage) {
        $this->discountPercentage = $discountPercentage;
    }

    public function setOrderItems($orderItems) {
        $this->orderItems = $orderItems;
    }

    /**
     * Creates a Order instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return Order instance.
     */
    public static function createFromMap($map) {
        $instance = new Order();
        $instance->setOrderId(isset($map['orderId']) ? $map['orderId'] : null);
        $instance->setOrderDate(isset($map['orderDate']) ? $map['orderDate'] : null);
        $instance->setCustomerId(isset($map['customerId']) ? $map['customerId'] : null);
        $instance->setCurrency(isset($map['currency']) ? $map['currency'] : null);
        $instance->setCumulatedPrice(isset($map['cumulatedPrice']) ? $map['cumulatedPrice'] : null);
        $instance->setVatNumbe(isset($map['vatNumber']) ? $map['vatNumber'] : null);
        $instance->setShippingCosts(isset($map['shippingCosts']) ? $map['shippingCosts'] : null);
        $instance->setShippingCostsTaxes(isset($map['shippingCostsTaxes']) ? $map['shippingCostsTaxes'] : null);
        $instance->setPurchaseOrderIndicator(isset($map['purchaseOrderIndicator']) ? $map['purchaseOrderIndicator'] : null);
        $instance->setDiscount(isset($map['discount']) ? $map['discount'] : null);
        $instance->setDiscountPercentage(isset($map['discountPercentage']) ? $map['discountPercentage'] : null);
        $instance->setOrderItems(isset($map['orderItems']) ? $map['orderItems'] : null);
        if (isset($map['orderItems']) && !is_null($map['orderItems']) && count(($map['orderItems'])) > 0) {
            $arrItems = array();
            foreach ($map['orderItems'] as $oItem) {
                $arrItems[] = OrderItem::createFromMap((array)$oItem);
            }
            $instance->setOrderItems($arrItems);
        }
        
        return $instance;
    }

}
