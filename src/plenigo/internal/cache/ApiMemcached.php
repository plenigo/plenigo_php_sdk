<?php

namespace plenigo\internal\cache;


/**
 * Class ApiMemcached
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
     * @param string|array $host
     * @param int $port
     */
    public function __construct($host, $port)
    {
        self::$connection = new \Memcached();
        if (is_array($host)) {
            self::$connection->addServers($host);
        } else {
            self::$connection->addServer($host, $port);
        }
    }


    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
     * @return bool
     */
    public static function store($key, $value, $ttl) {
        try {
            return self::$connection->set($key, $value, $ttl);
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
        return extension_loaded('memcached') && class_exists('Memcached');
    }

}