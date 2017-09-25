<?php

/**
 * Model
 *
 * <p>
 * Main model to be extended
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalModels
 * @author   plenigo support@plenigo.com
 * @link     https://www.plenigo.com
 */

namespace plenigo\models;


use plenigo\PlenigoException;

class Model
{

    /**
     * @var array all the data
     */
    protected $data = array();

    /**
     * Model constructor.
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * returns a Model property
     * @param string $key of return value
     * @return string value
     */
    protected function getValue($key) {
        return $this->data[$key] ?: '';
    }

    /**
     * sets a model property
     * @param string $key
     * @param mixed $value
     */
    protected function setValue($key, $value) {
        $this->data[$key] = $value;
    }

    /**
     * magic function to set and get properties
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws PlenigoException
     */
    public function __call($name, $arguments)
    {
        if (0 !== strpos($name,'get') && 0 !== strpos($name,'set')) {
            throw new PlenigoException("unknown Method call: {$name}");
        }

        $key = lcfirst(substr($name, 3));

        if ('get' === substr($name, 0, 3)) {
            return $this->getValue($key);
        }
        elseif ('set' === substr($name, 0, 3)) {
            $this->setValue($key, reset($arguments));
        }

    }

    /**
     * returns all Model-Data as array
     * @return array
     */
    public function getMap() {
        return $this->data;
    }


}