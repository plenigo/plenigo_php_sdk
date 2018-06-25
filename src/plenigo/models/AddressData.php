<?php
/**
 * Created by IntelliJ IDEA.
 * User: soenke
 * Date: 25.06.18
 * Time: 08:57
 */

namespace plenigo\models;


class AddressData
{
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

    protected function __construct() {

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

}