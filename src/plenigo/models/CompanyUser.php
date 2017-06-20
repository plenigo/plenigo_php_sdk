<?php

namespace plenigo\models;

require_once __DIR__ . '/CompanyUserBillingData.php';

/**
 * <p>
 * This class contains the company user information.
 * </p>
 */
class CompanyUser {

    private $customerId;
    private $externalCustomerId;
    private $email;
    private $username;
    private $language;
    private $gender;
    private $firstName;
    private $name;
    private $mobileNumber;
    private $userState;
    private $birthday;
    private $postCode;
    private $street;
    private $additionalAddressInfo;
    private $city;
    private $state;
    private $country;
    private $agreementState;
    private $billingAddress = null;

    private function __construct() {
        
    }

    public function getCustomerId() {
        return $this->customerId;
    }

    public function getExternalCustomerId()
    {
        return $this->externalCustomerId;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getLanguage() {
        return $this->language;
    }

    public function getGender() {
        return $this->gender;
    }

    public function getFirstName() {
        return $this->firstName;
    }

    public function getName() {
        return $this->name;
    }

    public function getMobileNumber() {
        return $this->mobileNumber;
    }

    public function getUserState() {
        return $this->userState;
    }

    public function getBirthday() {
        return $this->birthday;
    }

    public function getPostCode() {
        return $this->postCode;
    }

    public function getStreet() {
        return $this->street;
    }

    public function getAdditionalAddressInfo() {
        return $this->additionalAddressInfo;
    }

    public function getCity() {
        return $this->city;
    }

    public function getState() {
        return $this->state;
    }

    public function getCountry() {
        return $this->country;
    }

    public function getAgreementState() {
        return $this->agreementState;
    }

    public function getBillingAddress() {
        return $this->billingAddress;
    }

    public function setCustomerId($customerId) {
        $this->customerId = $customerId;
    }

    public function setExternalCustomerId($externalCustomerId)
    {
        $this->externalCustomerId = $externalCustomerId;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setLanguage($language) {
        $this->language = $language;
    }

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setMobileNumber($mobileNumber) {
        $this->mobileNumber = $mobileNumber;
    }

    public function setUserState($userState) {
        $this->userState = $userState;
    }

    public function setBirthday($birthday) {
        $this->birthday = $birthday;
    }

    public function setPostCode($postCode) {
        $this->postCode = $postCode;
    }

    public function setStreet($street) {
        $this->street = $street;
    }

    public function setAdditionalAddressInfo($additionalAddressInfo) {
        $this->additionalAddressInfo = $additionalAddressInfo;
    }

    public function setCity($city) {
        $this->city = $city;
    }

    public function setState($state) {
        $this->state = $state;
    }

    public function setCountry($country) {
        $this->country = $country;
    }

    public function setAgreementState($agreementState) {
        $this->agreementState = $agreementState;
    }

    public function setBillingAddress($billingAddress) {
        $this->billingAddress = $billingAddress;
    }

    /**
     * Creates a CompanyUser instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return CompanyUser instance.
     */
    public static function createFromMap($map) {
        $instance = new CompanyUser();
        $instance->setCustomerId(isset($map['customerId']) ? $map['customerId'] : null);
        $instance->setExternalCustomerId(isset($map['externalCustomerId']) ? $map['externalCustomerId'] : null);
        $instance->setEmail(isset($map['email']) ? $map['email'] : null);
        $instance->setUsername(isset($map['username']) ? $map['username'] : null);
        $instance->setLanguage(isset($map['language']) ? $map['language'] : null);
        $instance->setGender(isset($map['gender']) ? $map['gender'] : null);
        $instance->setFirstName(isset($map['firstName']) ? $map['firstName'] : null);
        $instance->setName(isset($map['name']) ? $map['name'] : null);
        $instance->setMobileNumber(isset($map['mobileNumber']) ? $map['mobileNumber'] : null);
        $instance->setUserState(isset($map['userState']) ? $map['userState'] : null);
        $instance->setBirthday(isset($map['birthday']) ? $map['birthday'] : null);
        $instance->setPostCode(isset($map['postCode']) ? $map['postCode'] : null);
        $instance->setStreet(isset($map['street']) ? $map['street'] : null);
        $instance->setAdditionalAddressInfo(isset($map['additionalAddressInfo']) ? $map['additionalAddressInfo'] : null);
        $instance->setCity(isset($map['city']) ? $map['city'] : null);
        $instance->setState(isset($map['state']) ? $map['state'] : null);
        $instance->setCountry(isset($map['country']) ? $map['country'] : null);
        $instance->setAgreementState(isset($map['agreementState']) ? $map['agreementState'] : null);
        if (isset($map['billingAddresses']) && !is_null($map['billingAddresses']) && count(($map['billingAddresses'])) > 0) {
            $instance->setBillingAddress(CompanyUserBillingData::createFromMap((array)$map['billingAddresses'][0]));
        }
        return $instance;
    }

}
