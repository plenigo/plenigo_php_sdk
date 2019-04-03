<?php

require_once( __DIR__ . '/../../../../src/plenigo/internal/utils/EncryptionUtils.php' );
require_once( __DIR__ . '/../../../../src/plenigo/internal/exceptions/EncryptionException.php' );

use PHPUnit\Framework\TestCase;
use \plenigo\internal\utils\EncryptionUtils;

/**
 * <p>
 * Test for {@link plenigo.utils.EncryptionUtils }
 * </p>
 */
class EncryptionUtilsTest extends TestCase
{

    /**
     * <p>
     * Test case for decryption method
     * </p>
     */
    public function testDecryptWithAES()
    {
        $privateKey = "HKb6IdTh1WZElKDYqNhtJEY6JNWlxlfjg5Nk9bSz";
        $encryptedCookie = "e114381cba6ce7873c966ffb6fd7bf23d62711a19b744c809ed31cb7e6936356d36c17f5ab8bec985fc6bc1784dc67cb9f";
        $expectedDecryptedCookie = "ci=>S9NMA0GZ5BNJts=>1398729327349";

        $decryptedCookie = EncryptionUtils::decryptWithAES($privateKey, $encryptedCookie);

        $this->assertEquals($expectedDecryptedCookie, $decryptedCookie);
    }

    /**
     * <p>
     * Test case for decryption method with custom IV
     * </p>
     */
    public function testDecryptWithAESAndIV()
    {
        $privateKey = "h7evZBaXvhaLVHYRTIHD";
        $encryptedCookie = "2ef6395ccbac5370e765142ee2d1e2e31325433f7c01b5cc88dc06c61d925"
            . "9805612abb189c7e1d2ff2e96d4af69430b341d04093aa"
            . "65b2cc003e54de80a9ca2a40fc7666f3e03b165f5048d";
        $expectedDecryptedCookie = "f6cbc774104b00b56007f12d48e0e06d|false|0|0|true|false|false|false|null|false";
        $customIV = "7a134cc376d05cf6bc116e1e53c8801e";

        $decryptedCookie = EncryptionUtils::decryptWithAES($privateKey, $encryptedCookie, $customIV);
        $this->assertEquals($expectedDecryptedCookie, $decryptedCookie);
    }

    /**
     * <p>
     * Test case for encryption method.
     * </p>
     */
    public function testEncryptWithAES()
    {
        $privateKey = "HKb6IdTh1WZElKDYqNhtJEY6JNWlxlfjg5Nk9bSz";
        $expectedDecryptedCookie = "ci=>S9NMA0GZ5BNJts=>1398729327349";

        $encryptedCookie = EncryptionUtils::encryptWithAES($privateKey, $expectedDecryptedCookie);
        $decryptedCookie = EncryptionUtils::decryptWithAES($privateKey, $encryptedCookie);

        $this->assertEquals($expectedDecryptedCookie, $decryptedCookie);
    }


    public function tearDown()
    {
        EncryptionUtils::setMCryptLibraryPath(null);
        EncryptionUtils::setCryptoAlgorithm(null);
    }

}
