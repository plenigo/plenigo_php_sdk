<?php

namespace plenigo\models;

/**
 * ChannelVouchers
 * 
 * <p>
 * The response object that represent a channel with vouchers.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class ChannelVouchers {

    /**
     * @var string Channel name 
     */
    private $channel;

    /**
     * @var array Array of voucher ids 
     */
    private $ids;

    /**
     * Default constructor
     */
    public function __construct() {
        
    }

    /**
     * Channel name getter
     * @return string Channel name 
     */
    public function getChannel() {
        return $this->channel;
    }

    /**
     * Array of voucher ids getter
     * @return array Array of voucher ids 
     */
    public function getIds() {
        return $this->ids;
    }

    /**
     * @param array $data Associative array representing the object
     */
    public function fromJSON($data) {
        foreach ($data AS $key => $value) {
            $this->{$key} = $value;
        }
    }

}
