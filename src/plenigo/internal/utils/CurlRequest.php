<?php

namespace plenigo\internal\utils;

require_once __DIR__ . '/CurlRequestInterface.php';
require_once __DIR__ . '/../../PlenigoManager.php';

use \plenigo\internal\utils\CurlRequestInterface;

/**
 * CurlRequest
 *
 * <p>
 * A class wrapper that encapsulates basic cURL functions
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
final class CurlRequest
{

    /**
     * The cURL object created using curl_init
     */
    private $curl;

    /**
     * Initializes the cURL request at the given URL.
     * If no URL is passed on initialization, it can be
     * set as an option.
     *
     * @param string $url The URL where the request should take place.
     *
     * @return void
     */
    public function __construct($url = null)
    {
        $this->curl = curl_init($url);
    }

    /**
     * Adds an option to the cURL request.
     *
     * @param string $name  The option name.
     * @param any    $value The option value.
     *
     * @return void
     */
    public function setOption($name, $value)
    {
        curl_setopt($this->curl, $name, $value);
    }

    /**
     * Executes the cURL request.
     *
     * @return void
     *
     * @throws \Exception on request error.
     */
    public function execute()
    {
        $result = curl_exec($this->curl);

        if ($result === false) {
            throw new \Exception(curl_error($this->curl), curl_errno($this->curl));
        }
        $statusCode = $this->getInfo(CURLINFO_HTTP_CODE);
        if (!empty($statusCode)) {
            if ($statusCode != 200) {
                throw new \Exception($statusCode . " HTTP Error detected", $statusCode);
            }
        }

        return $result;
    }

    /**
     * Gets information about the executed request.
     *
     * @param string $name The name of the information to retrieve.
     *
     * @return The information requested.
     */
    public function getInfo($name)
    {
        return curl_getinfo($this->curl, $name);
    }

    /**
     * Closes the cURL connection.
     *
     * @return void
     */
    public function close()
    {
        curl_close($this->curl);
    }

}
