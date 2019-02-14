<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/ApiParams.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../models/CampaignResponse.php';
require_once __DIR__ . '/../PlenigoManager.php';

use plenigo\internal\ApiParams;
use plenigo\internal\ApiURLs;
use plenigo\internal\services\Service;
use plenigo\PlenigoException;
use plenigo\PlenigoManager;

/**
 * <p>
 * A class used to manage generated vouchers
 * </p>
 */
class CheckoutService extends Service {

    const ERR_MSG_VOUCHER = "Error during voucher checkout";
    const ERR_MSG_VPROD = "Error during free product checkout";

    /**
     * The constructor for the CheckoutService instance.
     *
     * @param RestClient $request   The RestClient request to execute.
     *
     * @return CheckoutService instance.
     */
    public function __construct($request) {
        parent::__construct($request);
    }

    /**
     * Executes the request to checkout a voucher
     * 
     * @param string $voucherCode Voucher code to use
     * @param string $customerId Customer id of the user to checkout the voucher for
     * @param boolean $externalUserId Flag indicating if customer id sent is the external customer id
     * 
     * @return boolean TRUE if the Checkout succeeded 
     * 
     * @throws PlenigoException
     */
    public static function redeemVoucher($voucherCode = null, $customerId = null, $externalUserId = false) {
        if (is_null($customerId)) {
            throw new PlenigoException('Customer ID is mandatory!');
        }
        if (is_null($voucherCode)) {
            throw new PlenigoException('Voucher ID is mandatory!');
        }

        $ipAddress = null;
        if (function_exists('filter_input') && filter_has_var(INPUT_SERVER, 'REMOTE_ADDR')) {
            $ipAddress = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        if (is_null($ipAddress) || trim($ipAddress) == '') {
            $ipAddress = 'INVALID';
        }

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::CHECKOUT_VOUCHER);
        $url = str_ireplace(ApiParams::URL_VOUCHER_ID_TAG, $voucherCode, $url);
        $external = ($externalUserId) ? 'true' : 'false';
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';
        $url .= "?ipAddress=" . $ipAddress . "&useExternalCustomerId=" . $external . "&testMode=" . $testModeText;
        
        $request = static::postRequest($url, false);

        $objRequest = new static($request);

        try {
            parent::executeRequest($objRequest, ApiURLs::CHECKOUT_VOUCHER, self::ERR_MSG_VOUCHER);
        } catch(PlenigoException $ex) {
            switch ($ex->getCode()) {
                case 400:
                    throw new PlenigoException("Parameters passed are not correct.", $ex->getCode(), $ex->getPrevious());
                case 401:
                    throw new PlenigoException("Company id and/or company secret is not correct.", $ex->getCode(), $ex->getPrevious());
                case 404:
                    throw new PlenigoException("Company id, customer id or product id cannot be found.", $ex->getCode(), $ex->getPrevious());
                case 412:
                    throw new PlenigoException("Voucher represents a product that is not zero payment.", $ex->getCode(), $ex->getPrevious());
                case 500:
                    throw new PlenigoException("Internal server error.", $ex->getCode(), $ex->getPrevious());
                default:
                    throw $ex;
            }
        }

        return true;
    }

    /**
     * Purchase a complete order. Returns orderID of purchase
     *
     * @param string $customerId ID of plenigo-customer.
     * @param array $order
     * @param string $paymentMethod
     * @param bool $useMerchantCustomerId
     * @return string OrderID
     * @throws PlenigoException
     */
    public static function purchase($customerId, $order, $paymentMethod = 'PREFFERED', $useMerchantCustomerId = false) {
        // purchase(customer_id, [['product_id' => '1', 'title' => 'title', 'description' => '2', 'amount' => 1]], 'PREFFERED')

        if (!isset($customerId)) {
            throw new PlenigoException("CustomerID is not optional");
        }

        if (empty($order) || !is_array($order)) {
            throw new PlenigoException("Order is not optional and should be of type array");
        }

        foreach ($order as $orderItem) {
            if (empty($orderItem['product_id'])) {
                throw new PlenigoException("Each orderItem needs the key product_id with a valid plenigo productID as value");
            }
        }

        return  bin2hex(openssl_random_pseudo_bytes(15));
    }
    
    /**
     * Executes the request to checkout a free product
     * 
     * @param string $productId Product id to use
     * @param string $customerId Customer id of the user to checkout the voucher for
     * @param boolean $externalUserId Flag indicating if customer id sent is the external customer id
     * 
     * @return boolean TRUE if the Checkout succeeded 
     * 
     * @throws PlenigoException
     */
    public static function buyFreeProduct($productId = null, $customerId = null, $externalUserId = false) {
        if (is_null($customerId)) {
            throw new PlenigoException('Customer ID is mandatory!');
        }
        if (is_null($productId)) {
            throw new PlenigoException('Product ID is mandatory!');
        }

        $ipAddress = null;
        if (function_exists('filter_input') && filter_has_var(INPUT_SERVER, 'REMOTE_ADDR')) {
            $ipAddress = filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP);
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ipAddress = $_SERVER['REMOTE_ADDR'];
        }
        if (is_null($ipAddress) || trim($ipAddress) == '') {
            $ipAddress = 'INVALID';
        }

        $url = str_ireplace(ApiParams::URL_USER_ID_TAG, $customerId, ApiURLs::CHECKOUT_PRODUCT);
        $url = str_ireplace(ApiParams::URL_PROD_ID_TAG, $productId, $url);
        $external = ($externalUserId) ? 'true' : 'false';
        $testModeText = (PlenigoManager::get()->isTestMode()) ? 'true' : 'false';
        $url .= "?ipAddress=" . $ipAddress . "&useExternalCustomerId=" . $external . "&testMode=" . $testModeText;
        
        $request = static::postRequest($url, false);

        $objRequest = new static($request);

        try {
            parent::executeRequest($objRequest, ApiURLs::CHECKOUT_PRODUCT, self::ERR_MSG_VOUCHER);
        } catch(PlenigoException $ex) {
            switch ($ex->getCode()) {
                case 400:
                    throw new PlenigoException("Parameters passed are not correct.", $ex->getCode(), $ex->getPrevious());
                case 401:
                    throw new PlenigoException("Company id and/or company secret is not correct.", $ex->getCode(), $ex->getPrevious());
                case 404:
                    throw new PlenigoException("Company id, customer id or product id cannot be found.", $ex->getCode(), $ex->getPrevious());
                case 412:
                    throw new PlenigoException("Product is not a zero payment product.", $ex->getCode(), $ex->getPrevious());
                case 500:
                    throw new PlenigoException("Internal server error.", $ex->getCode(), $ex->getPrevious());
                default:
                    throw $ex;
            }
        }

        return true;
    }

    /**
     * Executes the prepared request and returns
     * the Response object on success.
     *
     * @return The request's response.
     *
     * @throws \plenigo\PlenigoException on request error.
     */
    public function execute() {
        try {
            $response = parent::execute();
        } catch (\Exception $exc) {
            throw new PlenigoException('Checkout Service execution failed!', $exc->getCode(), $exc);
        }

        return $response;
    }

}
