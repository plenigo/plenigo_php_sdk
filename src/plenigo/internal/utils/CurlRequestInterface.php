<?php

namespace plenigo\internal\utils;

/**
 * CurlRequestInterface
 *
 * <p>
 * An interface that a CURL Request class must
 * implement.
 * </p>
 *
 * @category SDK
 * @package  PlenigoServices
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
interface CurlRequestInterface
{
    /**
     * Adds an option to the cURL request.
     *
     * @param string $name  The option name.
     * @param any    $value The option value.
     *
     * @return void
     */
    public function setOption($name, $value);

    /**
     * Executes the cURL request.
     *
     * @return void
     *
     * @throws \Exception on request error.
     */
    public function execute();

    /**
     * Gets information about the executed request.
     *
     * @param string $name The name of the information to retrieve.
     *
     * @return The information requested.
     */
    public function getInfo($name);

    /**
     * Closes the cURL connection.
     *
     * @return void
     */
    public function close();
}