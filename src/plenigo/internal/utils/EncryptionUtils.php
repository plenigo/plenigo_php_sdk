<?php

namespace plenigo\internal\utils;

require_once __DIR__ . '/../exceptions/EncryptionException.php';

use plenigo\internal\exceptions\EncryptionException;

/**
 * EncryptionUtils
 *
 * <p>
 * Provides different helper methods for working with encryption.
 * </p>
 *
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoUtils
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
final class EncryptionUtils
{

    /**
     * @var string Encryption algorithm to use
     */
    private static $openSSLAlgorithm = 'aes-128-ctr';

    /**
     * Hash algorithm for HMAC and SHA512.
     */
    const HMAC_ALGORITHM = 'sha512';

    /**
     * Encrypt given data string with AES.
     *
     * @param string $key  Key phrase for encryption.
     * @param string $data Data to encrypt.
     * @param string $customIV      [optional]If provided this initialization vector will be used.
     *
     * @return string Encrypted string.
     *
     * @throws EncryptionException When an error occurs during data encoding
     */
    public static function encryptWithAES($key, $data, $customIV = null)
    {
        if (!(self::useOpenSSL() && self::hasEncryptionAlgorithm())) {
            throw new EncryptionException("OpenSSL or cipher method " . self::$openSSLAlgorithm . " not installed!");
        }

        if (is_null($customIV)) {
            $ivKey = self::createIVKey();
        } else {
            $ivKey = hex2bin($customIV);
        }
        // we need a binary string, key has to be 32bit
        $preparedKey = self::prepareKey($key);

        $encryptedData = self::openSSLEncrypt($data, $preparedKey, $ivKey);

        return bin2hex($encryptedData . $ivKey);
    }

    /**
     * Decrypts an encrypted string using AES.
     *
     * @param string $key           Key phrase for encryption.
     * @param string $encryptedData The encrypted data.
     * @param string $customIV      [optional]If provided this initialization vector will be used.
     * @return string Decrypted string
     *
     * @throws EncryptionException When an error occurs during data encoding
     */
    public static function decryptWithAES($key, $encryptedData, $customIV = null)
    {
        if (!(self::useOpenSSL() && self::hasEncryptionAlgorithm())) {
            throw new EncryptionException("OpenSSL or cipher method " . self::$openSSLAlgorithm . " not installed!");
        }

        $binData = hex2bin($encryptedData);
        if (is_null($customIV)) {
            $ivSize = self::getIVSize();
            $ivKey = substr($binData, $ivSize * -1);
            $encryptedData = substr($binData, 0, $ivSize * -1);
        } else {
            $encryptedData = $binData;
            $ivKey = hex2bin($customIV);
        }
        $preparedKey = self::prepareKey($key);

        return self::openSSLDecrypt($encryptedData, $preparedKey, $ivKey);

    }

    /**
     * @param string $encryptedData
     * @param string $key
     * @param string $iv
     * @return string
     */
    private static function openSSLDecrypt($encryptedData, $key, $iv) {
        return openssl_decrypt( $encryptedData, self::$openSSLAlgorithm, $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * @param string $data
     * @param string $key
     * @param string $iv
     * @return string
     */
    private static function openSSLEncrypt($data, $key, $iv) {
        return openssl_encrypt( $data, self::$openSSLAlgorithm, $key, OPENSSL_RAW_DATA, $iv);
    }

    /**
     * <p>
     * Determines if an specific algorithm is available on the host.
     * </p>
     *
     * @return bool Encryption method availability confirmation.
     */
    private static function hasEncryptionAlgorithm()
    {
        $algorithms = openssl_get_cipher_methods(true);
        return in_array(self::$openSSLAlgorithm, $algorithms) ?: in_array(strtoupper(self::$openSSLAlgorithm), $algorithms);
    }

    /**
     * should we use openSSL lib?
     *
     * @return bool
     */
    private static function useOpenSSL() {
        return (function_exists('openssl_encrypt') && function_exists('openssl_decrypt'));
    }

    /**
     * <p>
     * Creates a random Initialization Vector (IV) using the default
     * algorithm and encoding.
     * </p>
     *
     * @return string The IVKey.
     */
    private static function createIVKey()
    {
        return openssl_random_pseudo_bytes(self::getIVSize());
    }

    /**
     * <p>
     * Generates an Initialization Vector size depending on
     * the default algorithm and encoding values.
     * </p>
     *
     * @return int The IVSize.
     */
    private static function getIVSize()
    {
        return openssl_cipher_iv_length(self::$openSSLAlgorithm);
    }

    /**
     * <p>
     * MD5 encodes the key and turn the result into a
     * binary stream.
     * </p>
     *
     * @param string $key The key to prepare.
     *
     * @return string The prepared key.
     */
    private static function prepareKey($key)
    {
        $strMD5 = md5($key);
        return hex2bin($strMD5);
    }

    /**
     * Generate hmac for data.
     * <p>
     * HMAC is generated using sha512 algorythm
     * </p>
     *
     * @param  string $data   data to create checksum for
     * @param  string $secret secret to use for hmac
     * @return string generated checksum
     */
    public static function calculateHMAC($data, $secret)
    {
        return hash_hmac(self::HMAC_ALGORITHM, $data, $secret);
    }

    /**
     * <p>
     * Sets the mCrypt Library path if different from the default location
     * </p>
     * 
     * @param string $path The alternative path for the mCrypt library, NULL for default
     * @throws EncryptionException
     * @deprecated
     */
    public static function setMCryptLibraryPath($path)
    {
        throw new EncryptionException("please don't use this method anymore");
    }

    /**
     * <p>
     * Sets the Encryption algorythm to use for this class from the moment this method is called
     * </p>
     * 
     * @param string $algorythm the encryption algorythm, default 'rijndael-128' if parameter is null
     * @deprecated
     * @throws EncryptionException
     */
    public static function setCryptoAlgorithm($algorythm = null)
    {
        throw new EncryptionException("please don't use this method anymore");
    }

}