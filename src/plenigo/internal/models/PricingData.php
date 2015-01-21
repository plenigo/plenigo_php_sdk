<?php

namespace plenigo\internal\models;

/**
 * PricingData
 * 
 * <p>
 * This class contains general attributes regarding a product's price.
 *
 * A product's price is conformed by many variables such as currency, type and taxed price.
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class PricingData
{

    private $choosePrice = false;
    private $amount = 0.0;
    private $type = 'DIGITALNEWSPAPER';
    private $currency = 'USD';

    /**
     * Pricing Data constructor.
     * @param bool $choosePrice The choose price flag
     * @param double  $amount The price amount
     * @param string  $type prouct type
     * @param string  $currency The currency iso code
     */
    public function __construct($choosePrice, $amount, $type, $currency)
    {
        $this->choosePrice = $choosePrice;
        $this->amount = $amount;
        $this->type = $type;
        $this->currency = $currency;
    }

    /**
     * Returns if the price amount can be changed by the user.
     * @return boolean A bool indicating if the price amount can be changed by the user
     */
    public function isChoosePrice()
    {
        return $this->choosePrice;
    }

    /**
     * The price amount.
     * @return double price amount
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * The product type.
     *
     * @return string product type
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * The currency as an ISO Code, e.g, EUR.
     * @return string iso code
     */
    public function getCurrency()
    {
        return $this->currency;
    }

    /**
     * Creates a PricingData instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return PricingData instance.
     */
    public static function createFromMap($map)
    {
        $choosePrice = $map['choosePrice'];
        $amount = floatval($map['price']);
        $type = floatval($map['type']);
        $currency = $map['currency'];

        return new PricingData($choosePrice, $amount, $type, $currency);
    }

}
