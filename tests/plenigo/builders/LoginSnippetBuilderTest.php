<?php

require_once __DIR__ . '/../../../src/plenigo/models/LoginConfig.php';
require_once __DIR__ . '/../../../src/plenigo/models/AccessScope.php';
require_once __DIR__ . '/../../../src/plenigo/builders/LoginSnippetBuilder.php';

use PHPUnit\Framework\TestCase;
use \plenigo\models\LoginConfig;
use \plenigo\models\AccessScope;
use \plenigo\builders\LoginSnippetBuilder;

class LoginSnippetBuilderTest extends TestCase
{
    public function loginSnippetBuilderProvider()
    {
        $data       = array(
            'redirectUri'   => 'http://example.com/redirect?id=7&L=32&asd=123',
            'accessScope'   => AccessScope::PROFILE,
            'csrfToken'     => md5(uniqid())
        );

        $loginConfig    = new LoginConfig($data['redirectUri'], $data['accessScope']);

        return array(array($loginConfig, $data));
    }

    /**
     * @dataProvider loginSnippetBuilderProvider
     */
    public function testBuild($loginConfig, $data)
    {
        $loginSnippet = new LoginSnippetBuilder($loginConfig);

        $expectedString     = sprintf(
            "plenigo.login('%s','%s');",
            $data['redirectUri'],
            $data['accessScope']
        );

        $snippetString = $loginSnippet->build();
        
        $this->assertEquals($expectedString, $snippetString);
    }

    public function testBuildWithNoData()
    {
        $loginSnippet = new LoginSnippetBuilder(null);

        $expectedString     = "plenigo.login();";

        $snippetString = $loginSnippet->build();

        $this->assertEquals($expectedString, $snippetString);
    }
    
    /**
     * @dataProvider loginSnippetBuilderProvider
     */
    public function testBuildWithCSRFToken($loginConfig, $data)
    {
        $loginSnippet = new LoginSnippetBuilder($loginConfig);

        $expectedString     = sprintf(
            "plenigo.login('%s','%s','%s');",
            $data['redirectUri'],
            $data['accessScope'],
            $data['csrfToken']
        );

        $snippetString = $loginSnippet
            ->withCSRFToken($data['csrfToken'])
            ->build()
        ;

        $this->assertEquals($expectedString, $snippetString);
    }
}