<?php

require_once __DIR__ . '/../../src/plenigo/PlenigoException.php';

use \plenigo\PlenigoException;

class PlenigoExceptionTest extends PHPUnit_Framework_Testcase
{

    public function dataProvider()
    {
        $data = array(
            'code' => 404,
            'description' => 'This is a sample error'
        );

        return array(array($data));
    }

    /**
     * 
     * @dataProvider dataProvider
     */
    public function testConstructor($data)
    {
        $exc = new PlenigoException($data['description'], $data['code']);

        $this->assertEquals($data['description'], $exc->getMessage());
        $this->assertEquals($data['code'], $exc->getCode());
    }

    /**
     * 
     * @dataProvider dataProvider
     */
    public function testErrorDetails($data)
    {
        $exc = new PlenigoException($data['description'], $data['code']);

        $exc->addErrorDetail('123456', 'asdasdasd');
        $exc->addErrorDetail('123456', 'asdasdasd');
        $exc->addErrorDetail('123456', 'asdasdasd');

        $errArray = $exc->getErrors();
        if (is_array($errArray)) {
            $cntDetails = count($errArray);
            $this->assertEquals(3, $cntDetails);
        }
    }

    /**
     * 
     * @dataProvider dataProvider
     */
    public function testErrorDetailsCleared($data)
    {
        $exc = new PlenigoException($data['description'], $data['code']);

        $exc->addErrorDetail('123456', 'asdasdasd');
        $exc->addErrorDetail('123456', 'asdasdasd');
        $exc->addErrorDetail('123456', 'asdasdasd');

        $exc->clearErrorDetail();

        $errArray = $exc->getErrors();
        if (is_array($errArray)) {
            $cntDetails = count($errArray);
            $this->assertEquals(0, $cntDetails);
        }
    }
}
