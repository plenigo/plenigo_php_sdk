<?php

require_once __DIR__ . '/../../../src/plenigo/models/ProductBase.php';

use \plenigo\models\ProductBase;

class ProductBaseTest extends PHPUnit_Framework_Testcase
{
    public function productProvider()
    {
        $data = array(
            'id'        => '123premium',
            'price'     => 1.5,
            'title'     => 'premium-read',
            'currency'  => 'USD',
            'categoryId'  => 'Some Category'
        );

        $product = new ProductBase(
            $data['id'],
            $data['title'],
            $data['price'],
            $data['currency']
        );
        $product->setCategoryId($data['categoryId']);

        return array(array($product, $data));
    }

    /**
     * @dataProvider productProvider
     */
    public function testConstructor($product, $data)
    {
        $this->assertEquals($data['id'], $product->getId());
        $this->assertEquals($data['title'], $product->getTitle());
        $this->assertEquals($data['price'], $product->getPrice());
        $this->assertEquals($data['currency'], $product->getCurrency());
    }

    /**
     * @dataProvider productProvider
     */
    public function testType($product)
    {
        $expectedResult = "EBOOK";

        $product->setType($expectedResult);

        $this->assertEquals($expectedResult, $product->getType());
    }

    /**
     * @dataProvider productProvider
     */
    public function testCustomAmount($product)
    {
        $expectedResult = true;

        $product->setCustomAmount($expectedResult);

        $this->assertEquals($expectedResult, $product->isCustomAmount());
    }

    /**
     * @dataProvider productProvider
     */
    public function testCategoryId($product)
    {
        $expectedResult = "Some other category";

        $product->setCategoryId($expectedResult);

        $this->assertEquals($expectedResult, $product->getCategoryId());
    }

    
    /**
     * @dataProvider productProvider
     */
    public function testGetMap($product, $data)
    {
        $data['type']     =  "EBOOK";
        $data['customAmount']   = true;

        $product->setType($data['type']);
        $product->setCustomAmount($data['customAmount']);

        $map                    = $product->getMap();

        $this->assertArrayHasKey('id', $map);
        $this->assertEquals($map['id'], $data['id']);
        $this->assertArrayHasKey('title', $map);
        $this->assertEquals($map['title'], $data['title']);
        $this->assertArrayHasKey('price', $map);
        $this->assertEquals($map['price'], $data['price']);
        $this->assertArrayHasKey('currency', $map);
        $this->assertEquals($map['currency'], $data['currency']);
        $this->assertArrayHasKey('categoryId', $map);
        $this->assertEquals($map['categoryId'], $data['categoryId']);
        $this->assertArrayHasKey('type', $map);
        $this->assertEquals($map['type'], $data['type']);
        $this->assertArrayHasKey('customAmount', $map);
        $this->assertEquals($map['customAmount'], $data['customAmount']);
    }
}