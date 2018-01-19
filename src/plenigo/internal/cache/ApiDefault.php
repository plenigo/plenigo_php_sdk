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
     * @param $key
     * @param $value
     * @param int $ttl Time to live
     * @return array|bool
     */
    public static function store($key, $value, $ttl) {
       return true;
    }

    /**
     * @param $key
     * @return bool|string[]
     */
    public static function delete($key) {
        return true;
    }

    /**
     * @param $key
     * @return bool|mixed
     */
    public static function get($key) {
        return false;
    }

    /**
     * @return bool
     */
    public static function isEnabled() {
        return true;
    }

}