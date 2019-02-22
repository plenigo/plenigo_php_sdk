<?php
/**
 * Created by IntelliJ IDEA.
 * User: soenke
 * Date: 2019-02-22
 * Time: 13:24
 */

namespace plenigo\internal\utils;



use plenigo\internal\exceptions\EncryptionException;

class MCryptUtils
{
    /**
     * Encryption algorithm to use
     */
    private static $cryptoAlgorithm = MCRYPT_RIJNDAEL_128;

    /**
     * Encryption encoding mode to use
     */
    private static $cryptoEncoding = 'ctr';

    /**
     * Path to the MCrypt library in case it's not
     * on the default location (null)
     */
    private static $mCryptLibraryPath = null;

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
        if (self::hasEncryptionAlgorithm(self::$cryptoAlgorithm, self::$mCryptLibraryPath) === false) {
            throw new EncryptionException("Encryption Algorythm is not available (" . self::$cryptoAlgorithm . ")");
        }

        if (is_null($customIV)) {
            $ivKey = self::createIVKey();
        } else {
            $ivKey = hex2bin($customIV);
        }
        $preparedKey = self::prepareKey($key);

        $encryptedData = mcrypt_encrypt(
            self::$cryptoAlgorithm, $preparedKey, $data, self::$cryptoEncoding, $ivKey
        );

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
        if (self::hasEncryptionAlgorithm(self::$cryptoAlgorithm, self::$mCryptLibraryPath) === false) {
            throw new EncryptionException("Encryption Algorythm is not available (" . self::$cryptoAlgorithm . ")");
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

        return mcrypt_decrypt(
            self::$cryptoAlgorithm, $preparedKey, $encryptedData, self::$cryptoEncoding, $ivKey
        );
    }


    /**
     * <p>
     * Determines if an specific algorithm is available on the host.
     * </p>
     *
     * @param string $algorithmName The name of the algorithm to look for.
     * @param string $libraryDir    The path of the mcrypt library on the host.
     *
     * @return bool Encryption method availability confirmation.
     */
    private static function hasEncryptionAlgorithm($algorithmName, $libraryDir = null)
    {
        $algorithms = mcrypt_list_algorithms($libraryDir);

        return in_array($algorithmName, $algorithms);
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
        $ivSize = self::getIVSize();
        return mcrypt_create_iv($ivSize, MCRYPT_RAND);
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
        return mcrypt_get_iv_size(self::$cryptoAlgorithm, self::$cryptoEncoding);
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
     * <p>
     * Sets the mCrypt Library path if different from the default location
     * </p>
     *
     * @param string $path The alternative path for the mCrypt library, NULL for default
     */
    public static function setMCryptLibraryPath($path)
    {
        self::$mCryptLibraryPath = $path;
    }

    /**
     * <p>
     * Sets the Encryption algorythm to use for this class from the moment this method is called
     * </p>
     *
     * @param string $algorythm the encryption algorythm, default 'rijndael-128' if parameter is null
     */
    public static function setCryptoAlgorithm($algorythm = null)
    {
        if (is_null($algorythm)) {
            self::$cryptoAlgorithm = 'MCRYPT_RIJNDAEL_128';
        } else {
            self::$cryptoAlgorithm = $algorythm;
        }
    }
}