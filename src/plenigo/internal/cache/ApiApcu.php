<?php

namespace plenigo\internal\cache;

/**
 * Class ApiApcu
 * @package plenigo\internal\cache
 */
class ApiApcu extends ApiDefault
{
    /**
     * Stores a key with the provided value.
     *
     * @param string $key store a key
     * @param mixed $value value related to the key
     * @param int $ttl Time to live time to live for the key-value pair
     *
     * @return array|bool
     */
    public static function store($key, $value, $ttl) {
        try {
            return apcu_store($key, $value, $ttl);
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
            return apcu_delete($key);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * Get the value of the provided key.
     *
     * @param string $key key to get
     *
     * @return bool|mixed value of the key
     */
    public static function get($key) {
        try {
            return apcu_fetch($key);
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
        return extension_loaded('apcu') && function_exists('apcu_store');
    }
}