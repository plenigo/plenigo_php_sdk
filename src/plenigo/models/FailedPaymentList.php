<?php

namespace plenigo\models;

require_once __DIR__ . '/IterableBase.php';
require_once __DIR__ . '/FailedPayment.php';

/**
 * FailedPaymentList
 * 
 * <p>
 * This class constitutes the data resulting from the getFailedPayments call. 
 * This implementas Iterator so it can be used in a foreach statement.
 * </p>
 */
class FailedPaymentList extends IterableBase {

    private $pageNumber = 0;
    private $size = 10;
    private $totalElements = 0;

    /**
     * Private constructor for the FailedPaymentList.
     * 
     * @param array $array The array of FailedPayment elements if any
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
     * Get the Page Number.
     * 
     * @return int
     */
    public function getPageNumber() {
        return $this->pageNumber;
    }

    /**
     * Get the Size.
     * 
     * @return int
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * Get the elements.
     * 
     * @return FailedPayment[]
     */
    public function getElements() {
        return $this->elements;
    }

    /**
     * Get total of elements.
     * 
     * @return int
     */
    public function getTotalElements() {
        return $this->totalElements;
    }

    /**
     * Creates a FailedPaymentList instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return FailedPaymentList instance.
     */
    public static function createFromMap(array $map) {
        $pageNumber = isset($map['pageNumber']) ? $map['pageNumber'] : 0;
        $size = isset($map['size']) ? $map['size'] : 10;
        $totalElements = isset($map['totalElements']) ? $map['totalElements'] : 0;

        $arrElements = isset($map['elements']) ? $map['elements'] : array();
        $arrResulting = array();
        foreach ($arrElements as $itemMap) {
            $item = FailedPayment::createFromMap((array) $itemMap);
            $arrResulting[] = $item;
        }

        return new FailedPaymentList($arrResulting, $pageNumber, $size, $totalElements);
    }

    /**
     * Creates a FailedPaymentList instance from an array of maps.
     * 
     * @param array $itemArray The array of maps to use for the instance creation.
     * 
     * @return \plenigo\models\FailedPaymentList instance.
     */
    public static function createFromArray(array $itemArray) {
        $pageNumber = 0;
        $size = count($itemArray);
        $totalElements = count($itemArray);
        $arrResulting = array();
        foreach ($itemArray as $itemMap) {
            $item = FailedPayment::createFromMap((array) $itemMap);
            $arrResulting[] = $item;
        }
        return new FailedPaymentList($arrResulting, $pageNumber, $size, $totalElements);
    }
}