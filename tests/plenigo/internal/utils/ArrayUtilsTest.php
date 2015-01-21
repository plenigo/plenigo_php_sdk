<?php

require_once __DIR__ . '/../../../../src/plenigo/internal/utils/ArrayUtils.php';

use \plenigo\internal\utils\ArrayUtils;

class ArrayUtilsTest extends PHPUnit_Framework_Testcase
{

    public function testAddIfDefined()
    {
        $key = 'map-key';
        $value = 'map-value';

        $targetMap = array();
        $sourceMap = array($key => $value);

        ArrayUtils::addIfDefined($targetMap, $key, $sourceMap);

        $this->assertArrayHasKey($key, $targetMap);
        $this->assertEquals($targetMap[$key], $value);
    }

    public function testDontAddIfNotDefined()
    {
        $key = 'map-key';
        $value = 'map-value';

        $targetMap = array();
        $sourceMap = array($key => $value);

        ArrayUtils::addIfDefined($targetMap, 'another-key', $sourceMap);

        $this->assertArrayNotHasKey($key, $targetMap);
    }

    public function testAddIfNotNull()
    {
        $key = 'map-key';
        $value = 'map-value';

        $targetMap = array();
        $sourceMap = array($key => $value);

        ArrayUtils::addIfNotNull($targetMap, $key, $sourceMap[$key]);

        $this->assertArrayHasKey($key, $targetMap);
        $this->assertEquals($targetMap[$key], $value);
    }

    public function testDontAddIfNull()
    {
        $key = 'map-key';
        $value = null;

        $targetMap = array();
        $sourceMap = array($key => $value);

        ArrayUtils::addIfNotNull($targetMap, $key, $sourceMap[$key]);

        $this->assertArrayNotHasKey($key, $targetMap);
    }

}