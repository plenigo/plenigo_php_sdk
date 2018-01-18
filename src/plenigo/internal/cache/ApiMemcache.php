<?php

namespace plenigo\internal\cache;


/**
 * Class ApiMemcache
 * @package plenigo\internal\cache
 */
/**
 * Class ApiMemcache
 * @package plenigo\internal\cache
 */
class ApiMemcache extends ApiDefault
{

    /**
     * @var \Memcache
     */
    private static $connection;

    /**
     * ApiMemcache constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct($host, $port)
    {
        self::$connection = new \Memcache();
        self::$connection->pconnect($host, $port);
    }


    /**
     * @param $key
     * @param $value
     * @return array|bool
     */
    public static function store($key, $value) {
        try {
            return self::$connection->set($key, $value, false, 10);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param $key
     * @return bool|string[]
     */
    public static function delete($key) {
        try {
            return self::$connection->delete($key);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public static function get($key) {
        try {
            return self::$connection->get($key);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function isEnabled() {
        return extension_loaded('memcache') && class_exists('Memcache');
    }

}