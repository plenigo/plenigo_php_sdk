<?php

require_once __DIR__ . '/../../../src/plenigo/models/SnippetConfig.php';
require_once __DIR__ . '/../../../src/plenigo/models/SnippetType.php';
require_once __DIR__ . '/../../../src/plenigo/builders/PlenigoSnippetBuilder.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';

use \plenigo\models\SnippetConfig;
use \plenigo\models\SnippetType;
use \plenigo\builders\PlenigoSnippetBuilder;

class PlenigoSnippetBuilderTest extends PlenigoTestCase {

    public function plenigoSnippetBuilderProvider() {
        $data = array(
            'elementId' => 'someCrazyElement',
            'snippetId' => SnippetType::PERSONAL_DATA,
            'loggedOutRedirectUrl' => "http://www.google.com/login"
        );

        $snippetConfig = new SnippetConfig($data['elementId'], $data['snippetId'], $data['loggedOutRedirectUrl']);

        return array(
            array($snippetConfig, $data)
        );
    }

    public function plenigoSnippetBuilderNullProvider() {
        $snippetConfigNull = new SnippetConfig();

        return array(
            array($snippetConfigNull)
        );
    }

    public function plenigoSnippetBuilderLoginProvider() {
        $data = array(
            'elementId' => null,
            'snippetId' => SnippetType::PERSONAL_DATA,
            'loggedOutRedirectUrl' => "http://www.google.com/login",
            'loginToken' => 'bdc0867dbdc08DSDBC8DCBb'
        );

        $snippetConfig = new SnippetConfig($data['elementId'], $data['snippetId'], $data['loggedOutRedirectUrl'], $data['loginToken']);

        return array(
            array($snippetConfig, $data)
        );
    }

    /**
     * @dataProvider plenigoSnippetBuilderProvider
     */
    public function testBuild($snippetConfig, $data) {
        $plenigoSnippet = new PlenigoSnippetBuilder($snippetConfig);

        $expectedString = sprintf('plenigo.renderSnippet("%s', $data['elementId']);
        $expectedTag = '<script';

        $snippetString = $plenigoSnippet->build();

        $this->assertContains($expectedString, $snippetString, "The snippet doesn't look right");
        $this->assertContains($expectedTag, $snippetString, "The snippet doesn't even contains the tag");
    }

    /**
     * @dataProvider plenigoSnippetBuilderNullProvider
     */
    public function testBuildWithNoData($snippetConfig) {
        $plenigoSnippet = new PlenigoSnippetBuilder($snippetConfig);

        $expectedString = 'plenigo.renderSnippet("plenigoSnippet';
        $expectedTag = '<div';

        $snippetString = $plenigoSnippet->build();

        $this->assertContains($expectedString, $snippetString, "The snippet doesn't look right");
        $this->assertContains($expectedTag, $snippetString, "The snippet doesn't even contains the tag");
        $this->assertContains("http://test.plenigo.com/", $snippetString, "The default login URL is not present");
    }

    /**
     * @dataProvider plenigoSnippetBuilderLoginProvider
     */
    public function testBuildWithLoginAndNullElement($snippetConfig, $data) {
        $plenigoSnippet = new PlenigoSnippetBuilder($snippetConfig);

        $expectedString = sprintf('plenigo.renderSnippet("%s', $data['elementId']);
        $expectedTag = '<div';

        $snippetString = $plenigoSnippet->build();

        $this->assertContains($expectedString, $snippetString, "The snippet doesn't look right");
        $this->assertContains($expectedTag, $snippetString, "The snippet doesn't render own DIV tag");
        $this->assertContains($data['loginToken'], $snippetString, "The snippet is not sending the login token");
    }

}
