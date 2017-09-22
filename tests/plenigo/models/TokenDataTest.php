<?php

require_once __DIR__ . '/../../../src/plenigo/models/TokenData.php';
require_once __DIR__ . '/../../../src/plenigo/models/TokenGrantType.php';

use PHPUnit\Framework\TestCase;
use \plenigo\models\TokenData;
use \plenigo\models\TokenGrantType;

class TokenDataTest extends TestCase
{
	public function tokenDataProvider()
	{
		$data = array(
			'access_token'	=> 'access-token',
			'expires_in'	=> '2015-01-01',
			'refresh_token'	=> 'refresh-token',
			'state'			=> 'csrf-token',
			'token_type'	=> TokenGrantType::AUTHORIZATION_CODE
		);

		return array(array($data));
	}

	/**
     * @dataProvider tokenDataProvider
     */
	public function testConstructor($data)
	{
		$tokenData = new TokenData(
			$data['access_token'],
			$data['expires_in'],
			$data['refresh_token'],
			$data['state'],
			$data['token_type']
		);

		$this->gettersTest($tokenData, $data);
	}

	/**
     * @dataProvider tokenDataProvider
     */
	public function testCreateFromMap($data)
	{
		$tokenData	= TokenData::createFromMap($data);

		$this->gettersTest($tokenData, $data);
	}

    public function gettersTest($tokenData, $data)
    {
        $this->assertEquals($data['access_token'], $tokenData->getAccessToken());
        $this->assertEquals($data['expires_in'], $tokenData->getExpiresIn());
        $this->assertEquals($data['refresh_token'], $tokenData->getRefreshToken());
        $this->assertEquals($data['state'], $tokenData->getState());
        $this->assertEquals($data['token_type'], $tokenData->getTokenType());
    }
}