<?php

namespace plenigo\internal\cache;


class ApiDefault
{

    /**
     * @param $key
     * @param $value
     * @return array|bool
     */
    public static function store($key, $value) {
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