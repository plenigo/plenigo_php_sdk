<?php

namespace plenigo\models;

/**
 * AppTokenData
 * 
 * <p>
 * This class contains the returned
 * access token information.
 * </p>
 */
class AppTokenData {

    /**
     * The given customer.
     *
     * @var string
     */
    private $customerId;

    /**
     * The response's app token.
     * 
     * @var string
     */
    private $appToken;

    /**
     * Constructor with parameters
     * 
     * @param string $customerId The Customer ID provided in the request
     * @param string $appToken The app token to user for this customer
     */
    public function __construct($customerId, $appToken) {
        $this->customerId = $customerId;
        $this->appToken = $appToken;
    }

    /**
     * Gets the current Customer ID
     * 
     * @return string the Customer ID
     */
    public function getCustomerId() {
        return $this->customerId;
    }

    /**
     * Gets the current App Token
     * 
     * @return string the App Token
     */
    public function getAppToken() {
        return $this->appToken;
    }

    /**
     * Sets the new Customer ID
     * 
     * @param string $customerId the Customer ID
     */
    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }

    /**
     * Sets the new App Token
     * 
     * @param string $appToken the App Token
     */
    public function setAppToken($appToken) {
        $this->appToken = $appToken;
    }

    /**
     * Creates a AppTokenData instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return AppTokenData instance.
     */
    public static function createFromMap(array $map) {
        $customerId = isset($map['customerId']) ? $map['customerId'] : null;
        $token      = isset($map['token']) ? $map['token'] : null;

        return new AppTokenData($customerId, $token);
    }

}
