<?php

namespace plenigo\models;

/**
 * <p>
 * This class contains the transaction information.
 * </p>
 */
class Transaction {

    private $transactionId;
    private $customerId;
    private $productId;
    private $title;
    private $price;
    private $taxesPercentage;
    private $taxesAmount;
    private $taxesCountry;
    private $currency;
    private $paymentMethod;
    private $transactionDate;
    private $status;
    private $billingId;
    private $cancellationTransactionId;
    private $cancelledTransactionId;

    private function __construct() {
        
    }
    
    public function getTransactionId() {
        return $this->transactionId;
    }

    public function getCustomerId() {
        return $this->customerId;
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

    public function getTaxesPercentage() {
        return $this->taxesPercentage;
    }

    public function getTaxesAmount() {
        return $this->taxesAmount;
    }

    public function getTaxesCountry() {
        return $this->taxesCountry;
    }

    public function getCurrency() {
        return $this->currency;
    }

    public function getPaymentMethod() {
        return $this->paymentMethod;
    }

    public function getTransactionDate() {
        return $this->transactionDate;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setTransactionId($transactionId) {
        $this->transactionId = $transactionId;
    }

    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
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

    public function setTaxesPercentage($taxesPercentage) {
        $this->taxesPercentage = $taxesPercentage;
    }

    public function setTaxesAmount($taxesAmount) {
        $this->taxesAmount = $taxesAmount;
    }

    public function setTaxesCountry($taxesCountry) {
        $this->taxesCountry = $taxesCountry;
    }

    public function setCurrency($currency) {
        $this->currency = $currency;
    }

    public function setPaymentMethod($paymentMethod) {
        $this->paymentMethod = $paymentMethod;
    }

    public function setTransactionDate($transactionDate) {
        $this->transactionDate = $transactionDate;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function getBillingId() {
        return $this->billingId;
    }

    public function setBillingId($billingId) {
        $this->billingId = $billingId;
    }

    public function getCancellationTransactionId() {
        return $this->cancellationTransactionId;
    }

    public function setCancellationTransactionId($cancellationTransactionId) {
        $this->cancellationTransactionId = $cancellationTransactionId;
    }

    public function getCancelledTransactionId() {
        return $this->cancelledTransactionId;
    }

    public function setCancelledTransactionId($cancelledTransactionId) {
        $this->cancelledTransactionId = $cancelledTransactionId;
    }

        /**
     * Creates a CompanyUser instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return CompanyUser instance.
     */
    public static function createFromMap($map) {
        $instance = new Transaction();
        $instance->setTransactionId(isset($map['transactionId']) ? $map['transactionId'] : null);
        $instance->setCustomerId(isset($map['customerId']) ? $map['customerId'] : null);
        $instance->setProductId(isset($map['productId']) ? $map['productId'] : null);
        $instance->setBillingId(isset($map['billingId']) ? $map['billingId'] : null);
        $instance->setTitle(isset($map['title']) ? $map['title'] : null);
        $instance->setPrice(isset($map['price']) ? $map['price'] : null);
        $instance->setTaxesPercentage(isset($map['taxesPercentage']) ? $map['taxesPercentage'] : null);
        $instance->setTaxesAmount(isset($map['taxesAmount']) ? $map['taxesAmount'] : null);
        $instance->setTaxesCountry(isset($map['taxesCountry']) ? $map['taxesCountry'] : null);
        $instance->setCurrency(isset($map['currency']) ? $map['currency'] : null);
        $instance->setPaymentMethod(isset($map['paymentMethod']) ? $map['paymentMethod'] : null);
        $instance->setTransactionDate(isset($map['transactionDate']) ? $map['transactionDate'] : null);
        $instance->setStatus(isset($map['status']) ? $map['status'] : null);
        $instance->setCancellationTransactionId(isset($map['cancellationTransactionId']) ? $map['cancellationTransactionId'] : null);
        $instance->setCancelledTransactionId(isset($map['cancelledTransactionId']) ? $map['cancelledTransactionId'] : null);

        return $instance;
    }
}
