<?php

namespace plenigo\models;

require_once __DIR__ . '/../internal/models/Address.php';

use \plenigo\internal\models\Address;

/**
 * UserData
 *
 * <p>
 * User Data model that mirrors the information provided by
 * the Plenigo REST API.
 * </p>
 *
 * @category SDK
 * @package  PlenigoModels
 * @author   René Olivo <r.olivo@plenigo.com>
 * @link     https://www.plenigo.com
 */
class UserData
{

    /**
     * The user id.
     */
    private $id;

    /**
     * The user's email.
     */
    private $email;

    /**
     * The user's name.
     */
    private $name;

    /**
     * The username/nickname.
     */
    private $username;

    /**
     * The user's gender.
     */
    private $gender;

    /**
     * The user's last name.
     */
    private $lastName;

    /**
     * The user's first name.
     */
    private $firstName;

    /**
     * The user's address.
     *
     * {@link \plenigo\internal\models\Address }
     */
    private $address;

    /**
     * The default constructor with all required parameters.
     *
     * @param string  $id        The user's id.
     * @param string  $email     The user's email.
     * @param string  $name      The user's name.
     * @param string  $username  The username/nickname.
     * @param string  $gender    The user's gender.
     * @param string  $lastName  The user's last name.
     * @param string  $firstName The user's first name.
     * @param Address $address   The user's address {@link \plenigo\internal\models\Address}
     *
     * @return UserData instance
     */
    public function __construct($id, $email, $name, $username, $gender, $lastName, $firstName, Address $address)
    {
        $this->id = $id;
        $this->email = $email;
        $this->name = $name;
        $this->username = $username;
        $this->gender = $gender;
        $this->lastName = $lastName;
        $this->firstName = $firstName;
        $this->address = $address;
    }

    /**
     * Returns the user's id.
     *
     * @return user's id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns the user's email.
     *
     * @return user's email.
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Returns the user's name.
     *
     * @return user's name.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Returns the username/nickname.
     *
     * @return username/nickname.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Returns the user's gender.
     *
     * @return user's gender.
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Returns the user's last name.
     *
     * @return user's last name. 
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Returns the user's first name.
     *
     * @return user's first name.
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Returns the user's addres.
     *
     * @return user's address {@link \plenigo\internal\Address}.
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Generates a map with the UserData properties.
     *
     * @return UserData map.
     */
    public function getMap()
    {
        $map = array(
            'userId' => $this->getId(),
            'email' => $this->getEmail(),
            'name' => $this->getName(),
            'username' => $this->getUsername(),
            'gender' => $this->getGender(),
            'lastName' => $this->getLastName(),
            'firstName' => $this->getFirstName(),
        );

        $addressMap = $this->getAddress()->getMap();

        $map = array_merge($addressMap, $map);

        return $map;
    }

    /**
     * Creates a UserData instance using the provided map
     * properties.
     *
     * @param array $map The map with the properties to pass onto UserData.
     *
     * @return a UserData instance.
     */
    public static function createFromMap(array $map)
    {
        $address = Address::createFromMap($map);

        $lastName = (!isset($map['lastName']) || is_null($map['lastName'])) ? $map['name'] : $map['lastName'];
        $userId = (!isset($map['id']) || is_null($map['id'])) ? $map['userId'] : $map['id'];

        return new UserData($userId, $map['email'], $map['name'], $map['username'], $map['gender'], $lastName,
            $map['firstName'], $address);
    }

}
