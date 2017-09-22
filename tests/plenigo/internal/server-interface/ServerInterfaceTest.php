<?php

require_once __DIR__ . '/../../../../src/plenigo/internal/server-interface/ServerInterface.php';

use PHPUnit\Framework\TestCase;

class ServerInterfaceTest extends TestCase
{

    public function interfaceProvider()
    {
        $interface = $this->getMockForAbstractClass('\plenigo\internal\serverInterface\ServerInterface');

        $interface->property1 = 'value1';
        $interface->property2 = 'value2';
        $interface->property3 = 'value3';

        return array(array($interface));
    }

    /**
     * @dataProvider interfaceProvider
     */
    public function testSetterSuccess($interface)
    {
        $expectedValue = 'value4';

        $interface->setProperty4($expectedValue);

        $this->assertEquals($expectedValue, $interface->property4);
    }

    /**
     * @dataProvider interfaceProvider
     */
    public function testGetterSuccess($interface)
    {
        $this->assertEquals('value1', $interface->getProperty1());
    }

    /**
     * @dataProvider interfaceProvider
     * @expectedException Exception
     */
    public function testForbidCallOfUndefinedMethods($interface)
    {
        $interface->undefinedMethod();
    }

    /**
     * @depends testSetterSuccess
     * @depends testGetterSuccess
     * @dataProvider interfaceProvider
     */
    public function testSetValueFromMapIfNotEmpty($interface)
    {
        //expose protected method:
        $method = new ReflectionMethod(
            '\plenigo\internal\serverInterface\ServerInterface', 'setValueFromMapIfNotEmpty'
        );

        $method->setAccessible(true);

        //test implementation:
        $map = array('property4' => 'value4');

        $method->invoke($interface, 'property4', $map);

        $this->assertEquals('value4', $interface->getProperty4());
        $this->assertEmpty($interface->getProperty5());
    }

    /**
     * @depends testSetterSuccess
     * @depends testGetterSuccess
     * @dataProvider interfaceProvider
     * @dataProvider interfaceProvider
     */
    public function testInsertIntoMapIfDefined($interface)
    {
        //expose protected method:
        $method = new ReflectionMethod(
            '\plenigo\internal\serverInterface\ServerInterface', 'insertIntoMapIfDefined'
        );

        $method->setAccessible(true);

        //test implementation:
        $map = array();

        $method->invokeArgs($interface, array(&$map, 'property2', 'prop2'));
        $method->invokeArgs($interface, array(&$map, 'property3'));
        $method->invokeArgs($interface, array(&$map, 'property4'));

        $this->assertArrayHasKey('prop2', $map);
        $this->assertArrayHasKey('property3', $map);
        $this->assertArrayNotHasKey('property4', $map);
        $this->assertEquals('value2', $map['prop2']);
        $this->assertEquals('value3', $map['property3']);
    }

    /**
     * @dataProvider interfaceProvider
     */
    public function testValidateNumberSuccess($interface)
    {
        //expose protected method:
        $method = new ReflectionMethod(
            '\plenigo\internal\serverInterface\ServerInterface', 'validateNumber'
        );

        $method->setAccessible(true);

        //test implementation:
        $success1 = $method->invoke($interface, 100.5);
        $success2 = $method->invoke($interface, '1.25');

        $this->assertTrue($success1);
        $this->assertTrue($success2);
    }

    /**
     * @dataProvider interfaceProvider
     */
    public function testValidateNumberFailure($interface)
    {
        //expose protected method:
        $method = new ReflectionMethod(
            '\plenigo\internal\serverInterface\ServerInterface', 'validateNumber'
        );

        $method->setAccessible(true);

        //test implementation:
        $success = $method->invoke($interface, 'USD$1.5');
        $this->assertFalse($success);
    }

    /**
     * @dataProvider interfaceProvider
     */
    public function testGetMap($interface)
    {
        $expectedMap = array(
            'property1' => 'value1',
            'property2' => 'value2',
            'property3' => 'value3'
        );

        $map = $interface->getMap();

        $this->assertSame($expectedMap, $map);
    }

    /**
     * @depends testSetterSuccess
     * @depends testGetterSuccess
     * @dataProvider interfaceProvider
     */
    public function testGetQueryString($interface)
    {
        //init:
        $interface2 = $this->getMockForAbstractClass('\plenigo\internal\serverInterface\ServerInterface');

        //normal test:
        $expectedQueryString1 = 'property1=>value1&property2=>value2&property3=>value3';
        $result1 = $interface->getQueryString();

        //empty test:
        $expectedQueryString2 = '';
        $result2 = $interface2->getQueryString();

        //encode test:
        /*
          $interface2->setProperty1('value=1');
          $expectedQueryString3   = 'property1=>value%3D1';
          $result3                = $interface2->getQueryString();
         */

        //asserts:
        $this->assertEquals($expectedQueryString1, $result1);
        $this->assertEquals($expectedQueryString2, $result2);
        //$this->assertEquals($expectedQueryString3, $result3);
    }

}
