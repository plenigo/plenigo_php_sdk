<?php

require_once __DIR__ . '/../../../../src/plenigo/internal/models/Configuration.php';
require_once __DIR__ . '/../../../../src/plenigo/internal/ApiURLs.php';
require_once __DIR__ . '/../../../../src/plenigo/Plenigo.php';

use plenigo\internal\models\Configuration;
use plenigo\internal\ApiURLs;

class ConfigurationTest extends PHPUnit_Framework_Testcase
{
    public function configurationProvider()
    {
        $data = array(
            'secret' => 'my-secret',
            'companyId' => 'my-company-id',
            'testMode'  => true,
            'url' => 'http://example.com'
        );

        $configuration = new Configuration(
            $data['secret'],
            $data['companyId'],
            $data['testMode'],
            $data['url']
        );

        return array(array($configuration, $data));
    }

    /**
     * @dataProvider configurationProvider
     */
    public function testConfigurationConstructor($configuration, $data)
    {
        $this->assertEquals($data['secret'], $configuration->getSecret());
        $this->assertEquals($data['companyId'], $configuration->getCompanyId());
        $this->assertEquals($data['testMode'], $configuration->isTestMode());
        $this->assertEquals($data['url'], $configuration->getUrl());
    }

    /**
     * @dataProvider configurationProvider
     */
    public function testConfigurationConstructorWithMissingParams($configuration, $data)
    {
        $configuration = new Configuration(
            $data['secret'],
            $data['companyId']
        );

        $this->assertEquals(false, $configuration->isTestMode());
        $this->assertEquals(ApiURLs::DEFAULT_PLENIGO_URL, $configuration->getUrl());
    }
}