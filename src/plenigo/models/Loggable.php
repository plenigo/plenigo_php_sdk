<?php

namespace plenigo\models;

/**
 * Loggable
 *
 * <p>
 * This class centralizes plenigo's logging implementation so that it can be flexible to use in different environments.
 * </p>
 *
 * @category SDK
 * @package  Plenigo
 * @author   Ricardo Torres <ricardo.torres@plenigo.com>
 * @link     https://www.plenigo.com
 */
interface Loggable
{
    /**
     * Log the information about an error event that happened.
     *
     * @param $data data to log
     * @return mixed
     */
    public function logData($data);
}