<?php

namespace plenigo\models;

require_once __DIR__ . '/IterableBase.php';
require_once __DIR__ . '/CompanyUser.php';

/**
 * <p>
 * This class constitutes the data resulting from the getCompanyUsers call. 
 * This implementas Iterator so it can be userd in a foreach statement
 * </p>
 */
class CompanyUserList extends IterableBase {

    private $pageNumber = 0;
    private $size = 10;
    private $totalElements = 0;

    /**
     * Private constructor for the CompanyUserList
     * 
     * @param array $array The array of CompanyUser elements if any
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
     * @return int
     */
    public function getPageNumber() {
        return $this->pageNumber;
    }

    /**
     * @return int
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * @return CompanyUser[]
     */
    public function getElements() {
        return $this->elements;
    }

    /**
     * @return int
     */
    public function getTotalElements() {
        return $this->totalElements;
    }

    /**
     * Creates a CompanyUserList instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return CompanyUserList instance.
     */
    public static function createFromMap(array $map) {
        $pageNumber = isset($map['pageNumber']) ? $map['pageNumber'] : 0;
        $size = isset($map['size']) ? $map['size'] : 10;
        $totalElements = isset($map['totalElements']) ? $map['totalElements'] : 0;

        $arrElements = isset($map['elements']) ? $map['elements'] : array();
        $arrResulting = array();
        foreach ($arrElements as $cpnyUser) {
            $user = CompanyUser::createFromMap((array) $cpnyUser);
            array_push($arrResulting, $user);
        }

        return new CompanyUserList($arrResulting, $pageNumber, $size, $totalElements);
    }
    
    /**
     * Creates a CompanyUserList instance from an array of maps.
     * 
     * @param array $userArray The array of maps to use for the instance creation.
     * @return \plenigo\models\CompanyUserList instance.
     */
    public static function createFromArray(array $userArray) {
        $pageNumber = 0;
        $size = count($userArray);
        $totalElements = count($userArray);

        $arrResulting = array();
        foreach ($userArray as $cpnyUser) {
            $user = CompanyUser::createFromMap((array) $cpnyUser);
            array_push($arrResulting, $user);
        }

        return new CompanyUserList($arrResulting, $pageNumber, $size, $totalElements);
    }

}
