<?php

namespace plenigo\internal\serverInterface;

use \Exception;

/**
 * ServerInterface
 *
 * <p>
 * All server interface classes must inherit from this one.
 * </p>
 *
 * <p>
 * Server interfaces help prepare the parameters needed
 * for server requests.
 * </p>
 *
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalServerInterface
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
abstract class ServerInterface
{

    /**
     * This magic method helps define setters and getters not
     * defined by other classes.
     *
     * @param string $methodName      The method that was called.
     * @param array  $methodArguments The list of arguments passed to the method.
     *
     * @return void
     */
    public function __call($methodName, $methodArguments)
    {
        $match = array();

        if (preg_match('/^(set)(.*)/i', $methodName, $match)) {
            $propertyName = lcfirst($match[2]);

            $this->{ $propertyName } = $methodArguments[0];
        } elseif (preg_match('/^(get)(.*)/i', $methodName, $match)) {
            $propertyName = lcfirst($match[2]);

            if (isset($this->{ $propertyName })) {
                return $this->{ $propertyName };
            }
        } else {
            throw new \Exception("'{$methodName}', is not a valid method");
        }
    }

    /**
     * Accepts a property from a map so long as the property is defined and
     * not empty.
     *
     * @param string $fieldName The name of the field to set.
     * @param array  $map       The map with the field/value of interest.
     *
     * @return void
     */
    protected function setValueFromMapIfNotEmpty($fieldName, $map)
    {
        $methodName = 'set' . ucfirst($fieldName);

        if (isset($map[$fieldName])) {
            $value = $map[$fieldName];

            if (!is_null($value) && trim($value) !== '') {
                $this->{ $methodName }($value);
            }
        }
    }

    /**
     * Inserts a value from the instance into a specified map
     * so long as the value is not null.
     *
     * @param array  &$map      The map where the value is to be inserted.
     * @param string $fieldName The name of the field to check for the value.
     * @param string $alias     If provided, the value will be inserted with this
     *                          alias instead of the field name provided.
     *
     * @return void
     */
    protected function insertIntoMapIfDefined(&$map, $fieldName, $alias = null)
    {
        $methodName = 'get' . ucfirst($fieldName);

        if (isset($this->{ $fieldName }) && $this->{ $fieldName } !== null) {
            if ($alias === null) {
                $alias = $fieldName;
            }

            $value = $this->{ $methodName }();

            $map[$alias] = $value;
        }
    }

    /**
     * Validates if the value is a valid number,
     * otherwise throw an exception.
     *
     * @param float $value The value to validate as a number.
     *
     * @return void
     */
    protected function validateNumber($value)
    {
        if (!is_numeric($value)) {
            return false;
        }

        return true;
    }

    /**
     * Gets all properties that are not null and returns them
     * as a map.
     *
     * @return array The map of properties that are not null.
     */
    public function getMap()
    {
        $map = array();

        foreach ($this as $property => $value) {
            if (!empty($value)) {
                $map[$property] = $value;
            }
        }

        return $map;
    }

    /**
     * <p>
     * Returns a query string from non null values.
     * The format should be as follow:
     * </P>
     *
     * <p>
     * key1=>value1&key2=>value2
     * </p>
     *
     * @return string The resulting query string.
     */
    public function getQueryString()
    {
        $map = $this->getMap();

        $queryString = '';

        $sequence = 1;

        foreach ($map as $key => $value) {
            if (is_bool($value)) {
                $value = $value === true ? 'true' : 'false';
            }

            $queryString .= $key . '=>' . urlencode($value);

            if ($sequence < count($map)) {
                $queryString .= '&';
            }

            $sequence++;
        }

        return $queryString;
    }

}
