<?php

namespace plenigo\models;

require_once __DIR__ . '/IterableBase.php';
require_once __DIR__ . '/Order.php';

/**
 * <p>
 * This class constitutes the data resulting from the getOrders call. 
 * This implementas Iterator so it can be userd in a foreach statement.
 * </p>
 */
class OrderList extends IterableBase {

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
     * @return Order[]
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
     * Creates a OrderList instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return OrderList instance.
     */
    public static function createFromMap(array $map) {
        $pageNumber = isset($map['pageNumber']) ? $map['pageNumber'] : 0;
        $size = isset($map['size']) ? $map['size'] : 10;
        $totalElements = isset($map['totalElements']) ? $map['totalElements'] : 0;

        $arrElements = isset($map['elements']) ? $map['elements'] : array();
        $arrResulting = array();
        foreach ($arrElements as $cpnyOrder) {
            $order = Order::createFromMap((array) $cpnyOrder);
            array_push($arrResulting, $order);
        }

        return new OrderList($arrResulting, $pageNumber, $size, $totalElements);
    }

    /**
     * Creates a OrderList instance from an array of maps.
     * 
     * @param array $orderArray The array of maps to use for the instance creation.
     * 
     * @return \plenigo\models\OrderList instance.
     */
    public static function createFromArray(array $orderArray) {
        $pageNumber = 0;
        $size = count($orderArray);
        $totalElements = count($orderArray);

        $arrResulting = array();
        foreach ($orderArray as $cpnyOrder) {
            $order = Order::createFromMap((array) $cpnyOrder);
            array_push($arrResulting, $order);
        }

        return new OrderList($arrResulting, $pageNumber, $size, $totalElements);
    }
}