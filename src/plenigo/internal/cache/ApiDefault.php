<?php

namespace plenigo\internal\cache;

/**
 * Class ApiDefault
 *
 * Basic Class for extending
 * Default class to disable Caching
 *
 * @package plenigo\internal\cache
 */
class ApiDefault
{

    /**
     * Stores a key with the provided value.
     *
     * @param $key store a key
     * @param $value value related to the key
     * @param int $ttl Time to live time to live for the key-value pair
     *
     * @return array|bool
     */
    public static function store($key, $value, $ttl) {
        return true;
    }

    /**
     * Delete the provided key.
     *
     * @param string $key key to delete
     *
     * @return bool|string[] a flag indicating if it was deleted
     */
    public static function delete($key) {
        return true;
    }

    /**
     * Get the value of the provided key.
     *
     * @param string $key key to get
     *
     * @return bool|mixed value of the key
     */
    public static function get($key) {
        return null;
    }

    /**
     * Flag indicating if the Api is enabled.
     *
     * @return bool true if its enabled, false otherwise
     */
    public static function isEnabled() {
        return true;
    }
}