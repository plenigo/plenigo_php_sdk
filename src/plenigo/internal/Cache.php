<?php

namespace plenigo\internal;


use plenigo\internal\cache\ApiApcu;
use plenigo\internal\cache\ApiDefault;
use plenigo\internal\cache\ApiMemcache;
use plenigo\internal\cache\ApiMemcached;

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
     * Configure Cache. Each engine may have their own set of settings.
     * To choose an engine use $settings['engine']
     * Engines Memcache, Memcached and APCu are implemented yet.
     * If not set, we will use APCu if enabled or none
     *
     * @param array $settings
     */
    public static function configure(array $settings) {
        if (!isset($settings['engine']) || empty($settings['engine'])) {
            return;
        }

        switch ($settings['engine']) {
            case 'Memcache':
                if (ApiMemcache::isEnabled()) {
                    self::$engine = new ApiMemcache( !empty($settings['host']) ? $settings['host'] : 'localhost', !empty($settings['port']) ? $settings['port'] : 11211);
                }
                break;
            case 'Memcached':
                if (ApiMemcached::isEnabled()) {
                    self::$engine = new ApiMemcached( !empty($settings['host']) ? $settings['host'] : 'localhost', !empty($settings['port']) ? $settings['port'] : 11211);
                }
                break;
            case 'APCu':
                if (ApiApcu::isEnabled()) {
                    self::$engine = new ApiApcu();
                }
                break;
            case 'None':
                self::$engine = new ApiDefault();
                break;
        }

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
            return NULL;
        }
        try {
            $data = json_decode($string);

            if ($data->type === 'array') {
                return get_object_vars($data->data);
            }

            return $data->data;

        } catch (\Exception $e) {
            return NULL;
        }
    }

    /**
     * Encodes data to a cacheable JSON
     * @param mixed $mixed
     * @return string
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
     * @param int $ttl Time to live in the Cache
     *
     * @return bool Success
     */
    public static function set($key, $value, $ttl = 10) {
        $engine = self::getEngine();
        return $engine::store($key, self::toJSON($value), $ttl);
    }



}