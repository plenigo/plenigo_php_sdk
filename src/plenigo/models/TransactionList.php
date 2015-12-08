<?php

namespace plenigo\models;

require_once __DIR__ . '/IterableBase.php';
require_once __DIR__ . '/Transaction.php';

use \plenigo\models\IterableBase;
use \plenigo\models\Transaction;

/**
 * TransactionList
 * 
 * <p>
 * This class constitutes the data resulting from the getCompanyUsers call. 
 * This implementas Iterator so it can be userd in a foreach statement
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class TransactionList extends IterableBase {

    private $pageNumber = 0;
    private $size = 10;
    private $totalElements = 0;

    /**
     * Private constructor for the TransactionList
     * 
     * @param array $array The array of Transaction elements if any
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

    public function getPageNumber() {
        return $this->pageNumber;
    }

    public function getSize() {
        return $this->size;
    }

    public function getElements() {
        return $this->elements;
    }
    
    public function getTotalElements() {
        return $this->totalElements;
    }
        /**
     * Creates a TransactionList instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return TransactionList instance.
     */
    public static function createFromMap(array $map) {
        $pageNumber = isset($map['pageNumber']) ? $map['pageNumber'] : 0;
        $size = isset($map['size']) ? $map['size'] : 10;
        $totalElements = isset($map['totalElements']) ? $map['totalElements'] : 0;

        $arrElements = isset($map['elements']) ? $map['elements'] : [];
        $arrResulting = [];
        foreach ($arrElements as $cpnyUser) {
            $user = Transaction::createFromMap((array) $cpnyUser);
            array_push($arrResulting, $user);
        }

        return new TransactionList($arrResulting, $pageNumber, $size, $totalElements);
    }

}
