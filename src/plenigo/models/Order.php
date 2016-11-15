<?php

namespace plenigo\models;

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

    private $date;
    private $customerId;
    private $productId;
    private $productTitle;
    private $status;

    private function __construct() {
        
    }

    public function getDate() {
        return $this->date;
    }

    public function getCustomerId() {
        return $this->customerId;
    }

    public function getProductId() {
        return $this->productId;
    }

    public function getProductTitle() {
        return $this->productTitle;
    }

    public function getStatus() {
        return $this->status;
    }

    public function setDate($date) {
        $this->date = $date;
    }

    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }

    public function setProductId($productId) {
        $this->productId = $productId;
    }

    public function setProductTitle($productTitle) {
        $this->productTitle = $productTitle;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    /**
     * Creates a Order instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return Order instance.
     */
    public static function createFromMap($map) {
        $instance = new Order();
        $instance->setDate(isset($map['date']) ? $map['date'] : null);
        $instance->setCustomerId(isset($map['customerId']) ? $map['customerId'] : null);
        $instance->setProductId(isset($map['productId']) ? $map['productId'] : null);
        $instance->setProductTitle(isset($map['title']) ? $map['title'] : null);
        $instance->setStatus(isset($map['status']) ? $map['status'] : null);

        return $instance;
    }

}
