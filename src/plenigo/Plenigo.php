<?php

spl_autoload_register('Plenigo_autoloader', true);

/**
 * Plenigo Autoloader function
 *
 * <p>
 * This function is registered with the purpose of
 * easing the loading of classes that are part of the
 * Plenigo SDK.
 * </p>
 *
 * @param string $className the namespace of the class to load.
 *
 * @return void
 */
function Plenigo_autoloader($className)
{
    $matches = array();

    $isInsidePlenigoSDK = preg_match('/^plenigo\\\\(.+)/', $className, $matches);

    if ($isInsidePlenigoSDK === 1) {

        $filePath = str_replace('\\', DIRECTORY_SEPARATOR, $matches[1]);
        include_once __DIR__ . DIRECTORY_SEPARATOR . $filePath . '.php';
    }
}

/**
 * drop-in replacement for the boolval function that's only available in PHP >=5.5
 * 
 * @param mixed $val The value to be considered bool
 * @return bool The bool value after casting
 */
function safe_boolval($val)
{
    if (is_string($val)) {
        if (strtolower(trim($val)) === 'true') {
            return true;
        }
        if (strtolower(trim($val)) === 'false') {
            return false;
        }
    }
    //////// PHP 5.3 Compatibility
    //IF this function doesnt exists in PHP, we create it
    if (!function_exists('boolval')) {
        return (bool) $val;
    } else {
        return boolval($val);
    }
}
