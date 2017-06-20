<?php

namespace plenigo\models;

/**
 * <p>
 * This class contains the company user billing information.
 * </p>
 */
class CompanyUserBillingData {

    private $gender;
    private $firstName;
    private $name;
    private $company;
    private $vat;
    private $street;
    private $additionalAddressInfo;
    private $postCode;
    private $city;
    private $state;
    private $country;

    private function __construct() {
        
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

    public function getCompany() {
        return $this->company;
    }

    public function getVat() {
        return $this->vat;
    }

    public function getStreet() {
        return $this->street;
    }

    public function getAdditionalAddressInfo() {
        return $this->additionalAddressInfo;
    }

    public function getPostCode() {
        return $this->postCode;
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

    public function setGender($gender) {
        $this->gender = $gender;
    }

    public function setFirstName($firstName) {
        $this->firstName = $firstName;
    }

    public function setName($name) {
        $this->name = $name;
    }

    public function setCompany($company) {
        $this->company = $company;
    }

    public function setVat($vat) {
        $this->vat = $vat;
    }

    public function setStreet($street) {
        $this->street = $street;
    }

    public function setAdditionalAddressInfo($additionalAddressInfo) {
        $this->additionalAddressInfo = $additionalAddressInfo;
    }

    public function setPostCode($postCode) {
        $this->postCode = $postCode;
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

    /**
     * Creates a CompanyUserBillingData instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return CompanyUserBillingData instance.
     */
    public static function createFromMap($map) {
        $instance = new CompanyUserBillingData();
        $instance->setGender(isset($map['gender']) ? $map['gender'] : null);
        $instance->setFirstName(isset($map['firstName']) ? $map['firstName'] : null);
        $instance->setName(isset($map['name']) ? $map['name'] : null);
        $instance->setCompany(isset($map['company']) ? $map['company'] : null);
        $instance->setVat(isset($map['vatNumber']) ? $map['vatNumber'] : null);
        $instance->setStreet(isset($map['street']) ? $map['street'] : null);
        $instance->setAdditionalAddressInfo(isset($map['additionalAddressInfo']) ? $map['additionalAddressInfo'] : null);
        $instance->setPostCode(isset($map['postCode']) ? $map['postCode'] : null);
        $instance->setCity(isset($map['city']) ? $map['city'] : null);
        $instance->setState(isset($map['state']) ? $map['state'] : null);
        $instance->setCountry(isset($map['country']) ? $map['country'] : null);

        return $instance;
    }

}
