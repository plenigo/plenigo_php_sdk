<?php

namespace plenigo\internal\cache;


/**
 * Class ApiApcu
 * @package plenigo\internal\cache
 */
class ApiApcu extends ApiDefault
{

    /**
     * @param string $key
     * @param string $value
     * @param int $ttl
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
     * @param string $key
     * @return bool
     */
    public static function delete($key) {
        try {
            return apcu_delete($key);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @param string $key
     * @return bool|mixed a cached JSON of bool false
     */
    public static function get($key) {
        try {
            return apcu_fetch($key);
        } catch (\Exception $exception) {
            return false;
        }
    }

    /**
     * @return bool
     */
    public static function isEnabled() {
        return extension_loaded('apcu') && function_exists('apcu_store');
    }

}