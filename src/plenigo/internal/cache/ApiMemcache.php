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
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return array|bool
     */
    public static function store($key, $value, $ttl) {
        try {
            return self::$connection->set($key, $value, false, $ttl);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $key
     * @return bool
     */
    public static function delete($key) {
        try {
            return self::$connection->delete($key);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $key
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