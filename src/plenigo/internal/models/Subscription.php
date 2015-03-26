<?php

namespace plenigo\internal\models;

/**
 * Subscription
 * 
 * <b>
 * This class contains general attributes regarding a product's subscription.
 *
 * A product with a subscription is something that is regularly paid for.
 * </b>
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
class Subscription {

    private $subscribable = false;
    private $term = 0;
    private $cancellationPeriod = 0;
    private $autoRenewed = false;

    /**
     * Subscription constructor.
     * @param bool $subscribable Flag indicating if a product represents a subscription
     * @param int     $term Subscription terms
     * @param int     $cancellationPeriod cancellation period
     * @param bool $autoRenewed Is the subscription autorenewed
     */
    public function __construct($subscribable, $term, $cancellationPeriod, $autoRenewed) {
        $this->subscribable = $subscribable;
        $this->term = $term;
        $this->cancellationPeriod = $cancellationPeriod;
        $this->autoRenewed = $autoRenewed;
    }

    /**
     * Flag indicating if the product represents a subscription.
     * @return A bool indicating if the product represents a subscription
     */
    public function isSubscribable() {
        return ($this->subscribable === true);
    }

    /**
     * The subscription term.
     * @return The subscription term
     */
    public function getTerm() {
        return $this->term;
    }

    /**
     * The cancellation period.
     * @return The cancellation period
     */
    public function getCancellationPeriod() {
        return $this->cancellationPeriod;
    }

    /**
     * Flag indicating if the product subscription is auto renewed.
     * @return bool indicating if the product subscription is auto renewed
     */
    public function isAutoRenewed() {
        return ($this->autoRenewed === true);
    }

    /**
     * Creates a Subscription instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return Subscription instance.
     */
    public static function createFromMap($map) {
        $subscribable = isset($map['subscription']) ? $map['subscription'] : null;
        $term = isset($map['term']) ? $map['term'] : null;
        $cancellationPeriod = isset($map['cancellationPeriod']) ? $map['cancellationPeriod'] : null;
        $autoRenewed = isset($map['autoRenewal']) ? $map['autoRenewal'] : null;

        return new Subscription($subscribable, $term, $cancellationPeriod, $autoRenewed);
    }

}
