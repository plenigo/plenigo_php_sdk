<?php

namespace plenigo\internal\models;

/**
 * Address
 *
 * <p>
 * User Data model that mirrors the information provided by
 * the Plenigo REST API.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternalModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class Address
{
    /**
     * The address' street.
     */
    private $street;

    /**
     * More address information / second line.
     */
    private $additionalAddressInfo;

    /**
     * The address' post code.
     */
    private $postCode;

    /**
     * The address' city.
     */
    private $city;

    /**
     * The address' country.
     */
    private $country;

    /**
     * The default constructor with all required parameters.
     *
     * @param string $street                The address' street.
     * @param string $additionalAddressInfo More address information.
     * @param string $postCode              The address' post code.
     * @param string $city                  The address' city.
     * @param string $country               The address' country.
     *
     * @return Address instance
     */
    public function __construct($street, $additionalAddressInfo, $postCode, $city, $country)
    {
        $this->street                   = $street;
        $this->additionalAddressInfo    = $additionalAddressInfo;
        $this->postCode                 = $postCode;
        $this->city                     = $city;
        $this->country                  = $country;
    }

    /**
     * Gets the address' street.
     *
     * @return The address' street.
     */
    public function getStreet()
    {
        return $this->street;
    }

    /**
     * Gets the address' additional information.
     *
     * @return The address' additional information.
     */
    public function getAdditionalAddressInfo()
    {
        return $this->additionalAddressInfo;
    }

    /**
     * Gets the address' post code.
     *
     * @return The address' post code.
     */
    public function getPostCode()
    {
        return $this->postCode;
    }

    /**
     * Gets the address' city.
     *
     * @return The address' city.
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Gets the address' country.
     *
     * @return The address' country.
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Generates a map from the Address instance properties.
     *
     * @return A map with the instance properties.
     */
    public function getMap()
    {
        $map = array(
            'street'                   => $this->getStreet(),
            'additionalAddressInfo'    => $this->getAdditionalAddressInfo(),
            'postCode'                 => $this->getPostCode(),
            'city'                     => $this->getCity(),
            'country'                  => $this->getCountry()
        );

        return $map;
    }

    /**
     * Creates an Address instance using properties defined inside a map.
     *
     * @param array $map The map of properties to create the Address instance.
     *
     * @return The Address instance.
     */
    public static function createFromMap(array $map)
    {

        return new Address(
            $map['street'],
            $map['additionalAddressInfo'],
            $map['postCode'],
            $map['city'],
            $map['country']
        );
    }
}