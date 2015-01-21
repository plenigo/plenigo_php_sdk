<?php

namespace plenigo\internal\models;

/**
 * ActionPeriod
 * 
 * <p>
 * This class contains general attributes regarding a product's action period.
 *
 * An action period is a time where a customer can pay a reduced price for the product.
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'default' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalModels
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class ActionPeriod
{

    private $name;
    private $term;
    private $price;

    /**
     * Action Period constructor.
     * @param string $name The name of the action period
     * @param int    $term The term of the action period
     * @param double $price The price of the action period
     */
    public function __construct($name, $term, $price)
    {
        $this->name = $name;
        $this->term = $term;
        $this->price = $price;
    }

    /**
     * The name of the action period.
     * @return The name of the action period
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * The term of the action period.
     * @return The term of the action period
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * The price of the action period.
     * @return The price of the action period
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Creates a ActionPeriod instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return ActionPeriod instance.
     */
    public static function createFromMap($map)
    {
        $name = $map['actionPeriodName'];
        $term = $map['actionPeriodTerm'];
        $price = $map['actionPeriodPrice'];

        return new ActionPeriod($name, $term, $price);
    }

}
