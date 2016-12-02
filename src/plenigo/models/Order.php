<?php

namespace plenigo\models;

require_once __DIR__ . '/OrderItem.php';

use \plenigo\models\OrderItem;

/**
 * Order
 * 
 * <p>
 * This class contains the order information.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class Order {

    /**
     * Order Id
     * @var string 
     */
    private $orderId;

    /**
     * Date of the orde
     * @var string 
     */
    private $orderDate;

    /**
     * Customer Id
     * @var string 
     */
    private $customerId;

    /**
     * Currency of the order price
     * @var string 
     */
    private $currency;

    /**
     * The cumulated order price
     * @var number 
     */
    private $cumulatedPrice;

    /**
     * The VAT number
     * @var string 
     */
    private $vatNumber;

    /**
     * Shipping costs
     * @var number 
     */
    private $shippingCosts;

    /**
     * Shipping costs taxes
     * @var number 
     */
    private $shippingCostsTaxes;

    /**
     * Purchase order indicator
     * @var number 
     */
    private $purchaseOrderIndicator;

    /**
     * Order discount
     * @var number 
     */
    private $discount;

    /**
     * Discount percentage
     * @var int 
     */
    private $discountPercentage;

    /**
     * Array of order items
     * @var array 
     */
    private $orderItems;

    /**
     * Default constructor.
     */
    private function __construct() {
    }

    /**
     * Get the order id.
     * 
     * @return string
     */
    public function getOrderId() {
        return $this->orderId;
    }

    /**
     * Get the Order Date.
     * 
     * @return string
     */
    public function getOrderDate() {
        return $this->orderDate;
    }

    /**
     * Get the Customer Id.
     * 
     * @return string
     */
    public function getCustomerId() {
        return $this->customerId;
    }

    /**
     * Get the Currency.
     * 
     * @return string
     */
    public function getCurrency() {
        return $this->currency;
    }

    /**
     * Get the cumulated price.
     * 
     * @return number
     */
    public function getCumulatedPrice() {
        return $this->cumulatedPrice;
    }

    /**
     * Get the VAT Number.
     * 
     * @return string
     */
    public function getVatNumber() {
        return $this->vatNumber;
    }

    /**
     * Get the ShippingCosts.
     * 
     * @return number
     */
    public function getShippingCosts() {
        return $this->shippingCosts;
    }

    /**
     * Get the Shipping Costs Taxes.
     * 
     * @return number
     */
    public function getShippingCostsTaxes() {
        return $this->shippingCostsTaxes;
    }

    /**
     * The the Purchased Order Indicator.
     * 
     * @return number
     */
    public function getPurchaseOrderIndicator() {
        return $this->purchaseOrderIndicator;
    }

    /**
     * Get the Discount.
     * 
     * @return number
     */
    public function getDiscount() {
        return $this->discount;
    }

    /**
     * Get the Discount Percentage.
     * 
     * @return int
     */
    public function getDiscountPercentage() {
        return $this->discountPercentage;
    }

    /**
     * Get all Order Items.
     * 
     * @return array
     */
    public function getOrderItems() {
        return $this->orderItems;
    }

    /**
     * Setter method for order id.
     * 
     * @param string $orderId
     */
    public function setOrderId($orderId) {
        $this->orderId = $orderId;
    }

    /**
     * Setter method for order date.
     * 
     * @param string $orderDate
     */
    public function setOrderDate($orderDate) {
        $this->orderDate = $orderDate;
    }

    /**
     * Setter method for customer id.
     * 
     * @param string $customerId
     */
    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }

    /**
     * Setter method for currency.
     * 
     * @param string $currency
     */
    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    /**
     * Setter method for cumulated price.
     * 
     * @param number $cumulatedPrice
     */
    public function setCumulatedPrice($cumulatedPrice) {
        $this->cumulatedPrice = $cumulatedPrice;
    }

    /**
     * Setter method for VAT number.
     * 
     * @param string $vatNumber
     */
    public function setVatNumber($vatNumber) {
        $this->vatNumber = $vatNumber;
    }

    /**
     * Setter method for shipping costs.
     * 
     * @param number $shippingCosts
     */
    public function setShippingCosts($shippingCosts) {
        $this->shippingCosts = $shippingCosts;
    }

    /**
     * Setter method for shipping costs taxes.
     * 
     * @param number $shippingCostsTaxes
     */
    public function setShippingCostsTaxes($shippingCostsTaxes) {
        $this->shippingCostsTaxes = $shippingCostsTaxes;
    }

    /**
     * Setter method purchased order indicator.
     * 
     * @param number $purchaseOrderIndicator
     */
    public function setPurchaseOrderIndicator($purchaseOrderIndicator) {
        $this->purchaseOrderIndicator = $purchaseOrderIndicator;
    }

    /**
     * Setter method for discount.
     * 
     * @param number $discount
     */
    public function setDiscount($discount) {
        $this->discount = $discount;
    }

    /**
     * Setter method for discount percentage.
     * 
     * @param int $discountPercentage
     */
    public function setDiscountPercentage($discountPercentage) {
        $this->discountPercentage = $discountPercentage;
    }

    /**
     * Setter method for all order items.
     * 
     * @param array $orderItems
     */
    public function setOrderItems($orderItems) {
        $this->orderItems = $orderItems;
    }

    /**
     * Creates a Order instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * 
     * @return Order instance.
     */
    public static function createFromMap($map) {
        $instance = new Order();
        $instance->setOrderId(isset($map['orderId']) ? $map['orderId'] : null);
        $instance->setOrderDate(isset($map['orderDate']) ? $map['orderDate'] : null);
        $instance->setCustomerId(isset($map['customerId']) ? $map['customerId'] : null);
        $instance->setCurrency(isset($map['currency']) ? $map['currency'] : null);
        $instance->setCumulatedPrice(isset($map['cumulatedPrice']) ? $map['cumulatedPrice'] : null);
        $instance->setVatNumber(isset($map['vatNumber']) ? $map['vatNumber'] : null);
        $instance->setShippingCosts(isset($map['shippingCosts']) ? $map['shippingCosts'] : null);
        $instance->setShippingCostsTaxes(isset($map['shippingCostsTaxes']) ? $map['shippingCostsTaxes'] : null);
        $instance->setPurchaseOrderIndicator(isset($map['purchaseOrderIndicator']) ? $map['purchaseOrderIndicator'] : null);
        $instance->setDiscount(isset($map['discount']) ? $map['discount'] : null);
        $instance->setDiscountPercentage(isset($map['discountPercentage']) ? $map['discountPercentage'] : null);
        if (isset($map['orderItems']) && !is_null($map['orderItems']) && count(($map['orderItems'])) > 0) {
            $arrItems = array();
            foreach ($map['orderItems'] as $oItem) {
                $arrItems[] = OrderItem::createFromMap((array) $oItem);
            }
            $instance->setOrderItems($arrItems);
        }
        return $instance;
    }
}