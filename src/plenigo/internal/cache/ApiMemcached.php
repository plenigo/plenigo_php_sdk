<?php

namespace plenigo\internal\cache;


/**
 * Class ApiMemcached
 * @package plenigo\internal\cache
 */
/**
 * Class ApiMemcache
 * @package plenigo\internal\cache
 */
class ApiMemcached extends ApiDefault
{

    /**
     * @var \Memcached
     */
    private static $connection;

    /**
     * ApiMemcached constructor.
     * @param string $host
     * @param int $port
     */
    public function __construct($host, $port)
    {
        self::$connection = new \Memcached();
        self::$connection->addServer($host, $port);
    }


    /**
     * @param string $key
     * @param string $value
     * @return bool
     */
    public static function store($key, $value) {
        try {
            return self::$connection->set($key, $value, 10);
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
        return extension_loaded('memcached') && class_exists('Memcached');
    }

}