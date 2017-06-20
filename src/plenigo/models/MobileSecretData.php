<?php

namespace plenigo\models;

/**
 * <p>
 * This class contains the returned
 * mobile secret information.
 * </p>
 */
class MobileSecretData {

    /**
     * The email of the mobile user
     *
     * @var string 
     */
    private $email;

    /**
     * The mobile secret for this user
     *
     * @var string
     */
    private $mobileSecret;

    /**
     * Constructor with parameters
     * 
     * @param string $email The user email
     * @param string $mobileSecret The mobile secret to store
     */
    public function __construct($email, $mobileSecret) {
        $this->email = $email;
        $this->mobileSecret = $mobileSecret;
    }

    /**
     * Gets the user email
     * 
     * @return string the user email
     */
    public function getEmail() {
        return $this->email;
    }

    /**
     * Gets the mobile secret
     * 
     * @return string the mobile secret
     */
    public function getMobileSecret() {
        return $this->mobileSecret;
    }

    /**
     * Sets the new user email
     * 
     * @param string $email the user email
     */
    public function setEmail($email) {
        $this->email = $email;
    }

    /**
     * Sets the new mobile secret
     * 
     * @param string $mobileSecret the mobile secret
     */
    public function setMobileSecret($mobileSecret) {
        $this->mobileSecret = $mobileSecret;
    }

    /**
     * Creates a MobileSecretData instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     *
     * @return MobileSecretData instance.
     */
    public static function createFromMap(array $map) {
        $email = isset($map['email']) ? $map['email'] : null;
        $mobileSecret = isset($map['mobileAppSecret']) ? $map['mobileAppSecret'] : null;

        return new MobileSecretData($email, $mobileSecret);
    }

}