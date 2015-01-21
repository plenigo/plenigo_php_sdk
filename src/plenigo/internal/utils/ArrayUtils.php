<?php

namespace plenigo\internal\utils;

/**
 * ArrayUtils
 *
 * <p>
 * Static helper methods that deal with arrays.
 * </p>
 *
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalUtils
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class ArrayUtils
{
    /**
     * Adds a value to a specified map if the value
     * is defined in the source map.
     *
     * @param array  $map      The array map where the value is to be stored.
     * @param string $key       The key to be used for the map.
     * @param array  $source    The source map with the potential value.
     * @param string $sourceKey The key to look for in the source map.
     *                          If not defined the $key param will be used instead.
     *
     * @return void
     */
    public static function addIfDefined(&$map, $key, $source, $sourceKey = null)
    {
        if ($sourceKey === null) {
            $sourceKey = $key;
        }

        if (isset($source[$sourceKey])) {
            self::addIfNotNull($map, $key, $source[ $sourceKey ]);
        }
    }

    /**
     * Adds a value to a specified map if the value
     * is not null.
     *
     * @param array  $map  The array map where the value is to be stored.
     * @param string $key   The key to be used for the map.
     * @param mixed  $value The value to store inside the map.
     *
     * @return void
     */
    public static function addIfNotNull(&$map, $key, $value)
    {
        if ($value !== null) {
            $map[$key] = $value;
        }
    }
}