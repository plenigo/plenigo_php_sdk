<?php

require_once __DIR__ . '/../../../src/plenigo/models/ProductId.php';

use PHPUnit\Framework\TestCase;
use \plenigo\models\ProductId;

class ProductIdTest extends TestCase
{
    public function productProvider()
    {
        $data       = array('id' => '123456789A123456789B');
        $product    = new ProductId($data['id']);

        return array(array($product, $data));
    }

    /**
     * @dataProvider productProvider
     */
    public function testConstructor($product, $data)
    {
        $this->assertEquals($data['id'], $product->getId());
    }

    /**
     * @dataProvider productProvider
     */
    public function testGetMap($product, $data)
    {
        $map = $product->getMap();

        $this->assertArrayHasKey('id', $map);
        $this->assertEquals($map['id'], $data['id']);
    }
}