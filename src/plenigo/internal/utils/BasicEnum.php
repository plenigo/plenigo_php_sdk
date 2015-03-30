<?php

namespace plenigo\internal\utils;

/**
 * <p>
 * This is an abstract class to handle a sort of Enumeration using constants and a little reflection magic if needed.
 * </p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalUtils
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
abstract class BasicEnum
{

    /**
     * The associative array of the constants on the Enum class
     */
    private static $constCache = null;

    /**
     * Creates or load an associative array of constants for this enum
     *
     * @return array An associative array of constants and their values
     */
    private static function getConstants()
    {
        if (self::$constCache === null) {
            $reflect = new ReflectionClass(get_called_class());
            self::$constCache = $reflect->getConstants();
        }

        return self::$constCache;
    }

    /**
     * <p>
     * This methods ensures that the provided key is contained in the Enum's constants. 
     * An optional parameter allows for strict as in (case-insensitive) check of the 
     * constant in the Enum class.
     * </p>
     *
     * @param string  $name key value, this is the name of the constant
     * @param bool $strict TRUE to set strict mode and check the exact key
     * @return TRUE if the name matches the constant list of the class
     */
    public static function isValidName($name, $strict = false)
    {
        $constants = self::getConstants();

        if ($strict) {
            return array_key_exists($name, $constants);
        }

        $keys = array_map('strtolower', array_keys($constants));
        return in_array(strtolower($name), $keys);
    }

    /**
     * <p>
     * This method ensures that the provided value is contained in the Enum's values.
     * </p>
     *
     * @param string $value this is the value to find
     * @return TRUE if the value is found on one of the constants
     */
    public static function isValidValue($value)
    {
        $values = array_values(self::getConstants());
        return in_array($value, $values, true);
    }

}