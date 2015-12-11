<?php

namespace plenigo\models;

/**
 * IterableBase
 * 
 * <p>
 * This is a base class for all iterable object models
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
class IterableBase implements \Iterator {

    protected $elements = [];

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
        $key = key($this->elements);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }

}
