<?php

namespace plenigo\models;

/**
 * <p>
 * Description of AppAccessData
 * </p>
 */
class AppAccessData {

    /**
     * The given Customer ID
     *
     * @var string
     */
    private $customerId;

    /**
     * The customer App ID
     * 
     * @var string 
     */
    private $customerAppId;

    /**
     * The Product ID for this App
     *
     * @var string
     */
    private $productId;

    /**
     * The App ID description
     * 
     * @var string
     */
    private $description;

    /**
     * Constructor with parameters
     * 
     * @param string $customerId The Customer ID provided in the request
     * @param string $customerAppId The customer App ID
     * @param string $productId The Product ID for this App
     * @param string $description The App ID description
     */
    public function __construct($customerId, $customerAppId, $productId, $description) {
        $this->customerId = $customerId;
        $this->customerAppId = $customerAppId;
        $this->productId = $productId;
        $this->description = $description;
    }

    /**
     * Maps the respons of the GetAllApps call to a list of single access objects
     * 
     * @param array $map the array of maps representing the objects
     * 
     * @return array an array of AppAccessData objects
     */
    public static function createFromMapArray(array $map = array()) {
        $res = array();
        if (isset($map['apps'])) {
            foreach ($map['apps'] as $aData) {
                array_push($res, AppAccessData::createFromMap($aData));
            }
        }
        return $res;
    }

    /**
     * Maps an associative array to the AppAccessData object
     * 
     * @param array $map an associative array representing the AppAccessData object
     * 
     * @return \plenigo\models\AppAccessData
     */
    public static function createFromMap($map) {
        $customerId = isset($map->customerId) ? $map->customerId : null;
        $customerAppId = isset($map->customerAppId) ? $map->customerAppId : null;
        $productId = isset($map->productId) ? $map->productId : null;
        $description = isset($map->description) ? $map->description : null;

        return new AppAccessData($customerId, $customerAppId, $productId, $description);
    }

    /**
     * Gets the Customer ID
     * 
     * @return string The Customer ID
     */
    public function getCustomerId() {
        return $this->customerId;
    }

    /**
     * Gets the customer App ID
     * 
     * @return string The customer App ID
     */
    public function getCustomerAppId() {
        return $this->customerAppId;
    }

    /**
     * Gets the Product ID for this App
     * 
     * @return string The Product ID for this App
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * Gets the App ID description
     * 
     * @return string The App ID description
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * Sets the Customer ID
     * 
     * @param string $customerId The New Customer ID
     */
    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }

    /**
     * Sets the customer App ID
     * 
     * @param string $customerAppId The New customer App ID
     */
    public function setCustomerAppId($customerAppId) {
        $this->customerAppId = $customerAppId;
    }

    /**
     * Sets the product ID for this App
     * 
     * @param string $productId The New product ID for this App
     */
    public function setProductId($productId) {
        $this->productId = $productId;
    }

    /**
     * Sets the App ID description
     * 
     * @param string $description The New App ID description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

}
