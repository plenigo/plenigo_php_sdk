<?php

namespace plenigo\models;

require_once __DIR__ . '/CompanyUser.php';

use \plenigo\models\CompanyUser;

/**
 * CompanyUserList
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
class CompanyUserList implements \Iterator {

    private $page = 0;
    private $size = 10;
    private $elements = [];

    /**
     * Private constructor for the CompanyUserList
     * 
     * @param array $array The array of CompanyUser elements if any
     * @param int $page The Page number  (starting from 0)
     * @param int $size The Size of the page (minimum 10, maximum 100)
     */
    private function __construct($array, $page = 0, $size = 10) {
        if (is_array($array)) {
            $this->elements = $array;
        }
        $this->page = $page;
        $this->size = $size;
    }

    public function rewind() {
        reset($this->elements);
    }

    public function current() {
        return current($this->elements);
    }

    public function key() {
        return key($this->elements);
    }

    public function next() {
        return next($this->elements);
    }

    public function valid() {
        $key = key($this->var);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }

    public function getPage() {
        return $this->page;
    }

    public function getSize() {
        return $this->size;
    }

    public function getElements() {
        return $this->elements;
    }

    /**
     * Creates a CompanyUserList instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return CompanyUserList instance.
     */
    public static function createFromMap(array $map) {
        $page = isset($map['page']) ? $map['page'] : 0;
        $size = isset($map['size']) ? $map['size'] : 10;

        $arrElements = isset($map['elements']) ? $map['elements'] : [];
        $arrResulting = [];
        foreach ($arrElements as $cpnyUser) {
            $user = CompanyUser::createFromMap((array) $cpnyUser);
            array_push($arrResulting, $user);
        }

        return new CompanyUserList($arrResulting, $page, $size);
    }

}
