<?php

namespace plenigo\internal\utils;

require_once __DIR__ . '/CurlRequestInterface.php';
require_once __DIR__ . '/../../PlenigoManager.php';

use plenigo\PlenigoManager;

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
final class CurlRequest {

    /**
     * The cURL object created using curl_init
     */
    private $curl;
    private $optCache = array();

    /**
     * Initializes the cURL request at the given URL.
     * If no URL is passed on initialization, it can be
     * set as an option.
     *
     * @param string $url The URL where the request should take place.
     *
     * @return void
     */
    public function __construct($url = null) {
        $this->optCache = array();
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
    public function setOption($name, $value) {
        $this->optCache[$name] = $value;
        curl_setopt($this->curl, $name, $value);
    }

    /**
     * Get the option set for this request. This allow adding headers to the request before sending
     * 
     * @param string $name
     * @return mixed
     */
    public function getOption($name) {
        if (!isset($this->optCache[$name])) {
            return null;
        }
        return $this->optCache[$name];
    }

    /**
     * Executes the cURL request.
     *
     * @return void
     *
     * @throws \Exception on request error.
     */
    public function execute() {

        if (PlenigoManager::isDebug()) {
            $this->setOption(CURLOPT_VERBOSE, true);
            $verbose = fopen('php://temp', 'w+');
            $this->setOption(CURLOPT_STDERR, $verbose);
        }

        $result = curl_exec($this->curl);

        if ($result === false) {
            throw new \Exception(curl_error($this->curl), curl_errno($this->curl));
        }

        if (PlenigoManager::isDebug()) {
            rewind($verbose);
            $verboseLog = stream_get_contents($verbose);
            $clazz = get_class();
            PlenigoManager::notice($clazz, "cURL verbose:\n" . $verboseLog);
            fclose($verbose);
        }

        if (PlenigoManager::isDebug()) {
            $version = curl_version();
            extract(curl_getinfo($this->curl));
            $metrics = "\n"
                    . "URL....: $url\n"
                    . "Code...: $http_code ($redirect_count redirect(s) in $redirect_time secs)\n"
                    . "Content: $content_type Size: $download_content_length (Own: $size_download) Filetime: $filetime\n"
                    . "Time...: $total_time Start @ $starttransfer_time (DNS: $namelookup_time Connect: $connect_time Request: $pretransfer_time)\n"
                    . "Speed..: Down: $speed_download (avg.) Up: $speed_upload (avg.)\n"
                    . "Curl...: v{$version['version']}\n";
            PlenigoManager::notice($clazz, "cURL report:\n" . $metrics);   
        }

        $statusCode = $this->getInfo(CURLINFO_HTTP_CODE);
        if (!empty($statusCode)) {
            if ($statusCode < 200 || $statusCode >= 300) {
                throw new \Exception($statusCode . " HTTP Error detected", $statusCode);
            }
        }
        $this->optCache = array();
        return $result;
    }

    /**
     * Gets information about the executed request.
     *
     * @param string $name The name of the information to retrieve.
     *
     * @return The information requested.
     */
    public function getInfo($name) {
        return curl_getinfo($this->curl, $name);
    }

    /**
     * Closes the cURL connection.
     *
     * @return void
     */
    public function close() {
        curl_close($this->curl);
    }

}
