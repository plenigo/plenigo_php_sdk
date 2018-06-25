<?php

namespace plenigo\models;

/**
 * <p>
 * This class contains the company user billing information.
 * </p>
 */
class CompanyUserDeliveryData extends AddressData {

    /**
     * Creates a CompanyUserBillingData instance from an array map.
     *
     * @param array $map The array map to use for the instance creation.
     * @return CompanyUserDeliveryData instance.
     */
    public static function createFromMap($map) {
        $instance = new CompanyUserDeliveryData();
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
