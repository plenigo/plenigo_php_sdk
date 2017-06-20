<?php

namespace plenigo\models;

/**
 * FailedPayment
 * 
 * <p>
 * This class contains the failed payment information.
 * </p>
 */
class FailedPayment {

    /**
     * Failed Payment Date 
     * @var string 
     */
    private $date;

    /**
     * Customer Id
     * @var string 
     */
    private $customerId;

    /**
     * Product Id
     * @var string 
     */
    private $productId;

    /**
     * Title 
     * @var string 
     */
    private $productTitle;

    /**
     * Failed Payment Status (failed, fixed, fixed_manually)
     * @var string
     */
    private $status;

    /**
     * Default Constructor
     */
    private function __construct() {
    }

    /**
     * Getter method for date variable.
     * 
     * @return string
     */
    public function getDate() {
        return $this->date;
    }

    /**
     * Getter method for customerId variable.
     * 
     * @return string
     */
    public function getCustomerId() {
        return $this->customerId;
    }

    /**
     * Getter method for productId variable.
     * 
     * @return string
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * Getter method for productTitle variable.
     * 
     * @return string
     */
    public function getProductTitle() {
        return $this->productTitle;
    }

    /**
     * Getter method for status variable.
     * 
     * @return string
     */
    public function getStatus() {
        return $this->status;
    }

    /**
     * Setter method for date.
     * 
     * @param string $date
     */
    public function setDate($date) {
        $this->date = $date;
    }

    /**
     * Setter method customer id.
     * 
     * @param string $customerId
     */
    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
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
     * Setter method product title.
     * 
     * @param string $productTitle
     */
    public function setProductTitle($productTitle) {
        $this->productTitle = $productTitle;
    }

    /**
     * Setter method status.
     * 
     * @param string $status
     */
    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Creates a FailedPayment instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * 
     * @return FailedPayment instance.
     */
    public static function createFromMap($map) {
        $instance = new FailedPayment();
        $instance->setDate(isset($map['date']) ? $map['date'] : null);
        $instance->setCustomerId(isset($map['customerId']) ? $map['customerId'] : null);
        $instance->setProductId(isset($map['productId']) ? $map['productId'] : null);
        $instance->setProductTitle(isset($map['title']) ? $map['title'] : null);
        $instance->setStatus(isset($map['status']) ? $map['status'] : null);

        return $instance;
    }
}