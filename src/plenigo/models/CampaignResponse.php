<?php

namespace plenigo\models;

/**
 * <p>
 * The response object of the creation of a Voucher Campaign.
 * </p>
 */
class CampaignResponse {

    /**
     * @var string Campaign name 
     */
    private $name;

    /**
     * @var string The product id 
     */
    private $productId;

    /**
     * @var array Array of channels
     */
    private $channels;

    /**
     * @var array Array of vouchers per channel 
     */
    private $channelVouchers;

    /**
     * Constructor with optional JSON string
     * @param string $json
     */
    public function __construct($json = false) {
        if ($json) {
            $this->fromJSON((array) $json);
        }
    }

    /**
     * Campaign name getter
     * @return string Campaign name 
     */
    public function getName() {
        return $this->name;
    }

    /**
     * The product id getter
     * @return string The product id 
     */
    public function getProductId() {
        return $this->productId;
    }

    /**
     * Array of channels getter
     * @return array Array of channels
     */
    public function getChannels() {
        return $this->channels;
    }

    /**
     * Array of vouchers per channel getter
     * @return array Array of vouchers per channel 
     */
    public function getChannelVouchers() {
        return $this->channelVouchers;
    }

    /**
     * @param array $data Associative array representing the object
     */
    public function fromJSON($data) {
        foreach ($data AS $key => $value) {
            if (is_array($value)) {
                foreach ($value as $valueObj) {
                    if (is_object($valueObj)) {
                        $sub = new ChannelVouchers();
                        $sub->fromJSON((array) $valueObj);
                        $valueObj = $sub;
                    }
                    $this->{$key}[] = $valueObj;
                }
            } else {
                $this->{$key} = $value;
            }
        }
    }

}
