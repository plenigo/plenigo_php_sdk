<?php

namespace plenigo\internal\models;

/**
 * <p>
 * This class represents a user in the plenigo platform. An user can be any
 * person that wants to buy digital content.
 * </p>
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
class Customer
{

    private $customerId = null;
    private $timestamp = 0;

    /**
     * Constructor for the Customer class.
     *
     * @param string $cookieCustomerId The Customer ID string for this customer
     * @param int    $cookieTimestamp The expiration date of the Timestamp
     */
    public function __construct($cookieCustomerId, $cookieTimestamp)
    {
        $this->customerId = $cookieCustomerId;
        $this->timestamp = $cookieTimestamp;
    }

    /**
     * Returns the customer id.
     *
     * @return The id of the customer
     */
    public function getCustomerId()
    {
        return $this->customerId;
    }

    /**
     * Returns the current timestamp of the cookie in milliseconds.
     *
     * @return The cookie timestamp
     */
    public function getTimestamp()
    {
        return $this->timestamp;
    }

}
