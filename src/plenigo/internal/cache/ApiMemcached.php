<?php

namespace plenigo\internal\cache;

use \Memcached;

/**
 * Class ApiMemcached
 * @package plenigo\internal\cache
 */
class ApiMemcached extends ApiDefault
{

    /**
     * Memcached connection.
     * @var \Memcached
     */
    private static $connection;

    /**
     * ApiMemcached constructor.
     *
     * @param string|array $host
     * @param int $port
     */
    public function __construct($host, $port) {
        self::$connection = new Memcached();
        if (is_array($host)) {
            self::$connection->addServers($host);
        } else {
            self::$connection->addServer($host, $port);
        }
    }

    /**
     * Stores a key with the provided value.
     *
     * @param string $key store a key
     * @param string $value value related to the key
     * @param int $ttl Time to live time to live for the key-value pair
     *
     * @return array|bool
     */
    public static function store($key, $value, $ttl) {
        try {
            return self::$connection->set($key, $value, $ttl);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Delete the provided key.
     *
     * @param string $key key to delete
     *
     * @return bool|string[] a flag indicating if it was deleted
     */
    public static function delete($key) {
        try {
            return self::$connection->delete($key);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Get the value of the provided key.
     *
     * @param $key string key to get
     *
     * @return bool|mixed value of the key
     */
    public static function get($key) {
        try {
            return self::$connection->get($key);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Flag indicating if the Api is enabled.
     *
     * @return bool true if its enabled, false otherwise
     */
    public static function isEnabled() {
        return extension_loaded('memcached') && class_exists('Memcached');
    }
}