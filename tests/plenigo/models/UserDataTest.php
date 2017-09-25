<?php

require_once __DIR__ . '/../../../src/plenigo/models/UserData.php';
require_once __DIR__ . '/../../../src/plenigo/internal/models/Address.php';

use PHPUnit\Framework\TestCase;
use \plenigo\models\UserData;
use \plenigo\internal\models\Address;

class UserDataTest extends TestCase
{

    public function userDataProvider()
    {
        $data = array(
            'userId' => '123',
            'email' => 'test@example.com',
            'gender' => 'female',
            'name' => 'Sm1th',
            'username' => 'AgentSmith',
            'lastName' => 'Smith',
            'firstName' => 'Ariel',
            'street' => 'some address',
            'additionalAddressInfo' => 'more information',
            'postCode' => '12345',
            'city' => 'some city',
            'country' => 'some country',
            'externalUserId' => '123456',
            'birthday' => '2017-04-01',
            'phoneNumber' => '08000000',
            'mobileNumber' => '08000000'
        );

        $address = Address::createFromMap($data);

//        $userData = new UserData(
//            $data['userId'], $data['email'], $data['name'], $data['username'], $data['gender'], $data['lastName'],
//            $data['firstName'], $address, $data['externalUserId'], $data['birthday'], $data['phoneNumber'], $data['mobileNumber']
//        );

        $userData = UserData::createFromMap($data);

        return array(array($userData, $data, $address));
    }

    /**
     * @dataProvider userDataProvider
     */
    public function testGetters(UserData $userData, $data, $address)
    {
        $this->assertEquals($data['userId'], $userData->getId());
        $this->assertEquals($data['email'], $userData->getEmail());
        $this->assertEquals($data['name'], $userData->getName());
        $this->assertEquals($data['username'], $userData->getUsername());
        $this->assertEquals($data['gender'], $userData->getGender());
        $this->assertEquals($data['lastName'], $userData->getLastName());
        $this->assertEquals($data['firstName'], $userData->getFirstName());
        $this->assertEquals($data['externalUserId'], $userData->getExternalUserId());
        $this->assertEquals($address, $userData->getAddress());
    }

    /**
     * @dataProvider userDataProvider
     */
    public function testGetMap($userData, $data)
    {
        $map = $userData->getMap();

        $this->assertEquals($data['userId'], $map['userId']);
        $this->assertEquals($data['email'], $map['email']);
        $this->assertEquals($data['gender'], $map['gender']);
        $this->assertEquals($data['name'], $map['name']);
        $this->assertEquals($data['username'], $map['username']);
        $this->assertEquals($data['lastName'], $map['lastName']);
        $this->assertEquals($data['firstName'], $map['firstName']);
        $this->assertEquals($data['street'], $map['street']);
        $this->assertEquals($data['additionalAddressInfo'], $map['additionalAddressInfo']);
        $this->assertEquals($data['postCode'], $map['postCode']);
        $this->assertEquals($data['city'], $map['city']);
        $this->assertEquals($data['country'], $map['country']);
        $this->assertEquals($data['externalUserId'], $map['externalUserId']);
    }

    /**
     * @dataProvider userDataProvider
     */
    public function testCreateFromMap($userData, $data)
    {
        $newUserData = UserData::createFromMap($data);

        $address = $newUserData->getAddress();

        $this->testGetters($newUserData, $data, $address);
    }

}
