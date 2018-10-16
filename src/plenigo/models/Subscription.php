<?php

namespace plenigo\models;

/**
 * <p>
 * This class contains the subscription information.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @link     https://www.plenigo.com
 */
class Subscription
{
    private $customerId;
    private $productId;
    private $title;
    private $price;
    private $currency;
    private $paymentMethod;
    private $startDate;
    private $cancellationDate;
    private $endDate;
    private $active;
    private $term;
    private $orderId;
    private $subscriptionId;

    /**
     * @return mixed
     */
    public function getSubscriptionId()
    {
        return $this->subscriptionId;
    }

    /**
     * @param mixed $subscriptionId
     */
    public function setSubscriptionId($subscriptionId)
    {
        $this->subscriptionId = $subscriptionId;
    }

    /**
     * @return mixed
     */
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * @param mixed $orderId
     */
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;
    }

    /**
     * Default constructor.
     */
    private function __construct()
    {
    }

    /**
     * Get customer id.
     *
     * @return mixed customer id
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }


    /**
     * Get product id.
     *
     * @return mixed product id
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Get title.
     *
     * @return mixed title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Get price.
     *
     * @return mixed price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Get currency.
     *
     * @return mixed currency
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Get payment method.
     *
     * @return mixed payment method
     */
    public function getPaymentMethod()
    {
        return $this->paymentMethod;
    }

    /**
     * Get start date.
     *
     * @return mixed start date
     */
    public function getStartDate()
    {
        return $this->startDate;
    }

    /**
     * Get cancellation date.
     *
     * @return mixed cancellation date
     */
    public function getCancellationDate()
    {
        return $this->cancellationDate;
    }

    /**
     * Get end date.
     *
     * @return mixed end date
     */
    public function getEndDate()
    {
        return $this->endDate;
    }

    /**
     * Get active.
     *
     * @return mixed active
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * Get term.
     *
     * @return mixed term
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set customer id.
     *
     * @param mixed $customerId
     */
    public function setCustomerId($customerId)
    {
        $this->customerId = $customerId;
    }

    /**
     * Set product id.
     *
     * @param mixed $productId
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;
    }

    /**
     * Set title.
     *
     * @param mixed $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Set price.
     *
     * @param mixed $price
     */
    public function setPrice($price)
    {
        $this->price = $price;
    }

    /**
     * Set currency.
     *
     * @param mixed $currency
     */
    public function setCurrency($currency)
    {
        $this->currency = $currency;
    }

    /**
     * Set payment method.
     *
     * @param mixed $paymentMethod
     */
    public function setPaymentMethod($paymentMethod)
    {
        $this->paymentMethod = $paymentMethod;
    }

    /**
     * Set start date.
     *
     * @param mixed $startDate
     */
    public function setStartDate($startDate)
    {
        $this->startDate = $startDate;
    }

    /**
     * Set cancellation date
     *
     * @param mixed $cancellationDate
     */
    public function setCancellationDate($cancellationDate)
    {
        $this->cancellationDate = $cancellationDate;
    }

    /**
     * Set end date.
     *
     * @param mixed $endDate
     */
    public function setEndDate($endDate)
    {
        $this->endDate = $endDate;
    }

    /**
     * Set active.
     *
     * @param mixed $active
     */
    public function setActive($active)
    {
        $this->active = $active;
    }

    /**
     * Set term.
     *
     * @param mixed $term
     */
    public function setTerm($term)
    {
        $this->term = $term;
    }

    /**
     * Creates a subscription instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return Subscription instance.
     */
    public static function createFromMap($map)
    {
        $instance = new Subscription();
        $instance->setActive(isset($map['active']) ? $map['active'] : null);
        $instance->setCancellationDate(isset($map['cancellationDate']) ? $map['cancellationDate'] : null);
        $instance->setEndDate(isset($map['endDate']) ? $map['endDate'] : null);
        $instance->setPaymentMethod(isset($map['paymentMethod']) ? $map['paymentMethod'] : null);
        $instance->setTerm(isset($map['term']) ? $map['term'] : null);
        $instance->setTitle(isset($map['title']) ? $map['title'] : null);
        $instance->setPrice(isset($map['price']) ? $map['price'] : null);
        $instance->setStartDate(isset($map['startDate']) ? $map['startDate'] : null);
        $instance->setProductId(isset($map['productId']) ? $map['productId'] : null);
        $instance->setCustomerId(isset($map['customerId']) ? $map['customerId'] : null);
        $instance->setCurrency(isset($map['currency']) ? $map['currency'] : null);
        $instance->setOrderId(isset($map['orderId']) ? $map['orderId'] : null);
        $instance->setSubscriptionId(isset($map['id']) ? $map['id'] : null);
        return $instance;
    }
}