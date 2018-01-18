<?php

namespace plenigo\internal;


use plenigo\internal\cache\ApiApcu;
use plenigo\internal\cache\ApiDefault;

/**
 * Class Cache
 * Wrapper Class for CacheEngines
 * @package plenigo\internal
 */
class Cache
{

    /**
     * @var null Engine
     */
    private static $engine = null;

    /**
     * Choose an Engine
     * @return null|ApiApcu|ApiDefault
     */
    private static function getEngine() {

        if (!is_null(self::$engine)) {
            return self::$engine;
        }

        if (ApiApcu::isEnabled()) {
            self::$engine = new ApiApcu();
        } else {
            self::$engine = new ApiDefault();
        }

        return self::$engine;
    }

    /**
     * Check, if an array is a map or a list
     * @see https://stackoverflow.com/questions/173400/how-to-check-if-php-array-is-associative-or-sequential
     * @param array $arr
     * @return bool
     */
    private static function isAssoc(array $arr)
    {
        if (array() === $arr) {
            return false;
        }

        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    /**
     * Decodes a Cached JSON
     * @param $string
     * @return bool|mixed
     */
    private static function fromJSON($string) {
        if (empty($string)) {
            return false;
        }
        try {
            $data = json_decode($string);

            if ($data->type === 'array') {
                return get_object_vars($data->data);
            }

            return $data->data;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Encodes data to a cacheable JSON
     * @param mixed $mixed
     * @return bool|string
     * @throws \Exception
     */
    private static function toJSON($mixed) {
        if (empty($mixed)) {
            $data = array(
                'type' => 'string',
                'data' => $mixed
            );
        }
        elseif (is_array($mixed)) {
            $data = array(
                'type' => (self::isAssoc($mixed) ? 'array' : 'list'),
                'data' => $mixed
            );
        }
        elseif (is_object($mixed)) {
            $data = array(
                'type' => 'object',
                'data' => get_object_vars($mixed)
            );
        }
        return json_encode($data);
    }

    /**
     * Retrieves data from cache. Returns bool false on a miss
     * @param string $key
     * @return mixed
     */
    public static function get($key) {
        $engine = self::getEngine();
        return self::fromJSON($engine::get($key));
    }

    /**
     * Writes data into a cache. Returns success
     * @param string $key
     * @param mixed $value
     */
    public static function set($key, $value) {
        $engine = self::getEngine();
        return $engine::store($key, self::toJSON($value));
    }



}