<?php

require_once __DIR__ . '/../../../src/plenigo/models/LoginConfig.php';
require_once __DIR__ . '/../../../src/plenigo/models/AccessScope.php';

use \plenigo\models\LoginConfig;
use \plenigo\models\AccessScope;

class LoginConfigTest extends PHPUnit_Framework_Testcase
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