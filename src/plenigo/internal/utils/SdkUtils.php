<?php

namespace plenigo\internal\utils;

/**
 * <p>
 * This class contains general SDK utilities that are required.
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 */
class SdkUtils {

    /**
     * The default entry separator used between key value pairs.
     */
    const ENTRY_SEPARATOR = '&';

    /**
     * The default separator between key and value.
     */
    const KEY_VALUE_SEPARATOR = '=>';

    /**
     * The default separator used for URL query strings.
     */
    const URL_QUERY_STRING_ENTRY_SEPARATOR = "&";

    /**
     * The default separator used for URL query strings.
     */
    const URL_QUERY_STRING_KEY_VALUE_SEPARATOR = "=";

    /**
     * This method parses a string with the format
     * that plenigo's API accepts, into an Associative array.
     *
     * @param string $data The data to parse
     * @return the corresponding associative array
     */
    public static function getMapFromString($data) {
        $map = array();

        if (strpos($data, self::ENTRY_SEPARATOR) !== false) {
            foreach (explode(self::ENTRY_SEPARATOR, $data) as $pair) {
                if (strpos($pair, self::KEY_VALUE_SEPARATOR) !== false) {
                    list($key, $value) = explode(self::KEY_VALUE_SEPARATOR, $pair);
                    $map[$key] = $value;
                }
            }
        }

        return $map;
    }

    /**
     * Clamps the value aof a number to its given limits
     * 
     * @param number $value The value to clamp
     * @param number $min The minimum value, any values lower than this will be clamped to the min value
     * @param number $max The maximum value, any values higher than this will be clamped to the max value
     * @return number
     */
    public static function clampNumber($value, $min = null, $max = null) {
        if (!is_null($max) && $value > $max) {
            return $max;
        }
        if (!is_null($min) && $value < $min) {
            return $min;
        }
        return $value;
    }

}
