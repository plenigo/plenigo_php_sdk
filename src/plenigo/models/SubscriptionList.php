<?php

namespace plenigo\models;

require_once __DIR__ . '/IterableBase.php';
require_once __DIR__ . '/Subscription.php';

/**
 * <p>
 * This class constitutes the data resulting from the getSubscriptions call.
 * This implements Iterator so it can be user in a foreach statement.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @link     https://www.plenigo.com
 */
class SubscriptionList extends IterableBase {

    private $pageNumber = 0;
    private $size = 10;
    private $totalElements = 0;

    /**
     * Private constructor for the OrderList.
     * 
     * @param array $array The array of Order elements if any
     * @param int $pageNumber The Page number  (starting from 0)
     * @param int $size The Size of the page (minimum 10, maximum 100)
     * @param int $totalElements The Size of the entire result set
     */
    private function __construct($array, $pageNumber = 0, $size = 10, $totalElements = 0) {
        if (is_array($array)) {
            $this->elements = $array;
        }
        $this->pageNumber = $pageNumber;
        $this->size = $size;
        $this->totalElements = $totalElements;
    }

    /**
     * Getter method.
     * 
     * @return int
     */
    public function getPageNumber() {
        return $this->pageNumber;
    }

    /**
     * Getter method.
     * 
     * @return int
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Getter method.
     * 
     * @return Subscription[]
     */
    public function getElements() {
        return $this->elements;
    }

    /**
     * Getter method.
     * 
     * @return int
     */
    public function getTotalElements() {
        return $this->totalElements;
    }

    /**
     * Creates a subscription list instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return SubscriptionList instance.
     */
    public static function createFromMap(array $map) {

        $arrElements = isset($map['elements']) ? $map['elements'] : array();
        $arrResulting = array();

        foreach ($arrElements as $cpnySubscription) {
            $subscription = Subscription::createFromMap((array) $cpnySubscription);
            array_push($arrResulting, $subscription);
        }

        $pageNumber = $map['pageNumber'] ?? 1;
        $size = $map['size'] ?? 100;
        $totalElements = $map['totalElements'] ?? count($arrResulting);

        return new SubscriptionList($arrResulting, $pageNumber, $size, $totalElements);
    }

    /**
     * Creates a subscription list instance from an array of maps.
     * 
     * @param array $subscriptionArray The array of maps to use for the instance creation.
     * 
     * @return \plenigo\models\SubscriptionList instance.
     */
    public static function createFromArray(array $subscriptionArray) {
        $pageNumber = 0;
        $size = count($subscriptionArray);
        $totalElements = count($subscriptionArray);

        $arrResulting = array();
        foreach ($subscriptionArray as $cpnySubscription) {
            $subscription = Subscription::createFromMap((array) $cpnySubscription);
            array_push($arrResulting, $subscription);
        }

        return new SubscriptionList($arrResulting, $pageNumber, $size, $totalElements);
    }
}