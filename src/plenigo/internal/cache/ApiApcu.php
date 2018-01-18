<?php

namespace plenigo\internal\cache;


/**
 * Class ApiApcu
 * @package plenigo\internal\cache
 */
class ApiApcu extends ApiDefault
{

    /**
     * @param $key
     * @param $value
     * @return array|bool
     */
    public static function store($key, $value) {
        try {
            return apcu_store($key, $value, 10);
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
            return apcu_delete($key);
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