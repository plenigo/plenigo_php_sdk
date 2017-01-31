<?php

namespace plenigo\models;

/**
 * CampaignRequest
 * 
 * <p>
 * A parameter object to request the creation of a Vouche Campaign.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class CampaignRequest {

    /**
     * @var string Campaign name.
     */
    public $name;

    /**
     * @var string The product id. 
     */
    public $productId;

    /**
     * @var string The start date in the following format: YYYY-MM-DD.
     */
    public $startDate;

    /**
     * @var string The expiration date in the following format: YYYY-MM-DD.
     */
    public $expirationDate;

    /**
     * @see CampaignRequest::SINGLE
     * @see CampaignRequest::MULTIPLE
     * @var string the voucher type, it can be SINGLE or MULTI
     */
    public $type;

    /**
     * Simgle Voucher campaign
     */
    const SINGLE = 'SINGLE';

    /**
     * Multiple voucher campaign
     */
    const MULTIPLE = 'MULTIPLE';

    /**
     * @return int The amount of vouchers, will always be 1 for SINGLE voucher types.
     */
    public $amount = 1;

    /**
     * @var array Array of channels 
     */
    public $channels = array();

    /**
     * Constructor with fields
     * 
     * @param string $name (optional) Campaign name.
     * @param string $productId (optional) The product id. 
     * @param string $startDate (optional) The start date in the following format: YYYY-MM-DD.
     * @param string $expirationDate (optional) The expiration date in the following format: YYYY-MM-DD.
     * @param string $type (default: 'SINGLE') the voucher type, it can be SINGLE or MULTI
     * @param int $amount (default: 1, max: 10000) The amount of vouchers, will always be 1 for SINGLE voucher types.
     * @param string[] $channels (optional) Array of channels 
     */
    public function __construct($name = null, $productId = null, $startDate = null, $expirationDate = null, $type = 'SINGLE', $amount = 1, $channels = array()) {
        $this->name = $name;
        $this->productId = $productId;
        $this->startDate = $startDate;
        $this->expirationDate = $expirationDate;
        $this->type = $type;
        $this->amount = $amount;
        $this->channels = $channels;
    }

    /**
     * Control the type and limits of the variables to be suitable for request
     */
    public function sanitize() {
        if ($this->amount < 1) {
            $this->amount = 1;
        }
        if ($this->amount > 10000) {
            $this->amount = 10000;
        }

        if ($this->type == CampaignRequest::SINGLE) {
            $this->amount = 1;
        }

        if ($this->type != CampaignRequest::SINGLE && $this->type != CampaignRequest::MULTIPLE) {
            if ($this->amount == 1) {
                $this->type = CampaignRequest::SINGLE;
            } else {
                $this->type = CampaignRequest::MULTIPLE;
            }
        }
    }

}
