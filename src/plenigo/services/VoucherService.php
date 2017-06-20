<?php

namespace plenigo\services;

require_once __DIR__ . '/../PlenigoException.php';
require_once __DIR__ . '/../internal/ApiURLs.php';
require_once __DIR__ . '/../internal/services/Service.php';
require_once __DIR__ . '/../models/CampaignResponse.php';

use plenigo\internal\ApiURLs;
use plenigo\internal\services\Service;
use plenigo\models\CampaignResponse;
use plenigo\PlenigoException;

/**
 * <p>
 * A class used to manage generated vouchers
 * </p>
 */
class VoucherService extends Service {

    const ERR_MSG_CREATE = "Error creating voucher campaign";

    /**
     * The constructor for the VoucherService instance.
     *
     * @param RestClient $request   The RestClient request to execute.
     *
     * @return VoucherService instance.
     */
    public function __construct($request) {
        parent::__construct($request);
    }

    /**
     * Executes the request to generate a Voucher campaign
     * 
     * @param string $name (optional) Campaign name.
     * @param string $productId The product id. 
     * @param string $startDate (optional) The start date in the following format: YYYY-MM-DD.
     * @param string $expirationDate (optional) The expiration date in the following format: YYYY-MM-DD.
     * @param string $type (default: 'SINGLE') the voucher type, it can be SINGLE or MULTI
     * @param int $amount (default: 1, max: 10000) The amount of vouchers, will always be 1 for SINGLE voucher types.
     * @param array $channels (optional) Array of channels (string[])
     * 
     * @return CampaignResponse the campaign 
     * 
     * @throws PlenigoException
     */
    public static function generateCampaign($name = null, $productId = null, $startDate = null, $expirationDate = null, $type = 'SINGLE', $amount = 1, $channels = array()) {
        if (is_null($productId)) {
            throw new PlenigoException('Product ID is mandatory!');
        }

        // Sanitize
        if ($amount < 1) {
            $amount = 1;
        }
        if ($amount > 10000) {
            $amount = 10000;
        }

        if ($type == 'SINGLE') {
            $amount = 1;
        }

        if ($type != 'SINGLE' && $type != 'MULTIPLE') {
            if ($amount == 1) {
                $type = 'SINGLE';
            } else {
                $type = 'MULTIPLE';
            }
        }

        if (!is_array($channels)) {
            $channels = array($channels);
        }

        $map = array();
        $map['name'] = $name;
        $map['productId'] = $productId;
        $map['startDate'] = $startDate;
        $map['expirationDate'] = $expirationDate;
        $map['type'] = $type;
        $map['amount'] = $amount;
        $map['channels'] = $channels;

        $url = ApiURLs::VOUCHER_CREATE;

        $request = static::postJSONRequest($url, false, $map);

        $objRequest = new static($request);

        $data = parent::executeRequest($objRequest, ApiURLs::VOUCHER_CREATE, self::ERR_MSG_CREATE);

        $result = new CampaignResponse($data);

        return $result;
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
            throw new PlenigoException('Voucher Service execution failed!', $exc->getCode(), $exc);
        }

        return $response;
    }

}
