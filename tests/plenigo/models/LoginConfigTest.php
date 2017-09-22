<?php

require_once __DIR__ . '/../../../src/plenigo/models/LoginConfig.php';
require_once __DIR__ . '/../../../src/plenigo/models/AccessScope.php';

use PHPUnit\Framework\TestCase;
use \plenigo\models\LoginConfig;
use \plenigo\models\AccessScope;

class LoginConfigTest extends TestCase
{
    public function loginConfigProvider()
    {
        $data       = array(
            'redirectUri'   => 'http://example.com/redirect',
            'accessScope'   => AccessScope::PROFILE
        );

        return array(array($data));
    }

    /**
     * @dataProvider loginConfigProvider
     */
    public function testConstructor($data)
    {
        $loginConfig    = new LoginConfig(
            $data['redirectUri'],
            $data['accessScope']
        );

        $this->assertEquals($data['redirectUri'], $loginConfig->getRedirectUri());
        $this->assertEquals($data['accessScope'], $loginConfig->getAccessScope());
    }
}