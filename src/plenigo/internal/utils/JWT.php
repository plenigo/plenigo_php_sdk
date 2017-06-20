<?php

namespace plenigo\internal\utils;

require_once __DIR__ . '/../../PlenigoManager.php';

use plenigo\PlenigoManager;

/**
 * <p>
 * This class implements the generation of JWT Tokens
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 */
class JWT {

    /**
     * Decodes a JWT string into a PHP object.
     *
     * @param string      $jwt    The JWT
     * @param string|null $key    The secret key
     * @param bool        $verify Don't skip verification process 
     *
     * @return object      The JWT's payload as a PHP object
     * @throws \Exception          Algorithm was not provided or the provided JWT was invalid
     * 
     * @uses jsonDecode
     * @uses urlsafeB64Decode
     */
    public static function decode($jwt, $key = null, $verify = true) {
        $tks = explode('.', $jwt);
        if (count($tks) != 3) {
            JWT::_handleRegularError('Wrong number of segments');
        }
        list($headb64, $bodyb64, $cryptob64) = $tks;
        if (null === ($header = JWT::jsonDecode(JWT::urlsafeB64Decode($headb64)))) {
            JWT::_handleRegularError('Invalid segment encoding (header)');
        }
        if (null === $payload = JWT::jsonDecode(JWT::urlsafeB64Decode($bodyb64))) {
            JWT::_handleRegularError('Invalid segment encoding (payload)');
        }
        $sig = JWT::urlsafeB64Decode($cryptob64);
        if ($verify) {
            if (empty($header->alg)) {
                JWT::_handleRegularError('Empty algorithm');
            }
            if ($sig != JWT::sign("$headb64.$bodyb64", $key)) {
                JWT::_handleRegularError('Signature verification failed');
            }
        }
        return $payload;
    }

    /**
     * Converts and signs a PHP object or array into a JWT string.
     *
     * @param object|array $payload PHP object or array
     * @param string       $key     The secret key
     *
     * @return string      A signed JWT
     * @uses jsonEncode
     * @uses urlsafeB64Encode
     */
    public static function encode($payload, $key) {
        $header = array('alg' => 'HS256');

        $segments = array();
        $segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($header));
        $segments[] = JWT::urlsafeB64Encode(JWT::jsonEncode($payload));
        $signing_input = implode('.', $segments);

        $signature = JWT::sign($signing_input, $key);
        $segments[] = JWT::urlsafeB64Encode($signature);

        return implode('.', $segments);
    }

    /**
     * Sign a string with a given key and algorithm.
     *
     * @param string $msg    The message to sign
     * @param string $key    The secret key
     *
     * @return string          An encrypted message
     * @throws \DomainException Unsupported algorithm was specified
     */
    public static function sign($msg, $key) {
        return hash_hmac('sha256', $msg, $key, true);
    }

    /**
     * Decode a JSON string into a PHP object.
     *
     * @param string $input JSON string
     *
     * @return object          Object representation of JSON string
     * @throws \Exception Provided string was invalid JSON
     */
    public static function jsonDecode($input) {
        $obj = json_decode($input);
        if (function_exists('json_last_error') && $errno = json_last_error()) {
            JWT::_handleJsonError($errno);
        } else if ($obj === null && $input !== 'null') {
            JWT::_handleRegularError('Null result with non-null input');
        }
        return $obj;
    }

    /**
     * Encode a PHP object into a JSON string.
     *
     * @param object|array $input A PHP object or array
     *
     * @return string          JSON representation of the PHP object or array
     * @throws \Exception Provided object could not be encoded to valid JSON
     */
    public static function jsonEncode($input) {
        $json = json_encode($input);
        if (function_exists('json_last_error') && $errno = json_last_error()) {
            JWT::_handleJsonError($errno);
        } else if ($json === 'null' && $input !== null) {
            JWT::_handleRegularError('Null result with non-null input');
        }
        return $json;
    }

    /**
     * Decode a string with URL-safe Base64.
     *
     * @param string $input A Base64 encoded string
     *
     * @return string A decoded string
     */
    public static function urlsafeB64Decode($input) {
        $remainder = strlen($input) % 4;
        if ($remainder) {
            $padlen = 4 - $remainder;
            $input .= str_repeat('=', $padlen);
        }
        return base64_decode(strtr($input, '-_', '+/'));
    }

    /**
     * Encode a string with URL-safe Base64.
     *
     * @param string $input The string you want encoded
     *
     * @return string The base64 encode of what you passed in
     */
    public static function urlsafeB64Encode($input) {
        return str_replace('=', '', strtr(base64_encode($input), '+/', '-_'));
    }

    /**
     * Helper method to create a JSON error.
     *
     * @param int $errno An error number from json_last_error()
     *
     * @throws \Exception
     * @return void
     */
    private static function _handleJsonError($errno) {
        $messages = array(
            JSON_ERROR_DEPTH => 'Maximum stack depth exceeded',
            JSON_ERROR_CTRL_CHAR => 'Unexpected control character found',
            JSON_ERROR_SYNTAX => 'Syntax error, malformed JSON'
        );
        $clazz = get_class();
        $errMsg = isset($messages[$errno]) ? $messages[$errno] : 'Unknown JSON error: ' . $errno;
        PlenigoManager::warn($clazz, $errMsg);
        throw new \Exception($errMsg);
    }

    /**
     * Helper method to create an exception.
     * 
     * @param string $errText the text in the message of the Exception
     * @throws \Exception
     * @return void
     */
    private static function _handleRegularError($errText) {
        $clazz = get_class();
        PlenigoManager::warn($clazz, $errText);
        throw new \Exception($errText);
    }

}
