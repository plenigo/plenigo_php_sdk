<?php

require_once __DIR__ . '/ProductServiceMock.php';
require_once __DIR__ . '/../internal/utils/PlenigoTestCase.php';
require_once __DIR__ . '/../../../src/plenigo/models/ProductData.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../src/plenigo/PlenigoException.php';
require_once __DIR__ . '/../../../src/plenigo/internal/utils/EncryptionUtils.php';
require_once __DIR__ . '/../../../src/plenigo/internal/utils/SdkUtils.php';
require_once __DIR__ . '/../../../src/plenigo/internal/ApiResults.php';

use \plenigo\models\ProductData;
use \plenigo\internal\utils\EncryptionUtils;
use \plenigo\internal\utils\SdkUtils;
use \plenigo\PlenigoManager;
use \plenigo\PlenigoException;
use \plenigo\internal\ApiResults;

/**
 * ProductServiceTest
 * 
 * <b>
 * Test class for ProductService
 * </b>
 *
 * @category Test
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     http://plenigo.com
 */
class ProductServiceTest extends PlenigoTestCase
{
    const CUSTOMER_ID = 'MAP27KCA3K2P';
    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';

    public function productServiceProvider()
    {
        $data = json_decode('{"id":"testProduct-123","subscription":false,"title":"Product Title nice feature",'
            . '"description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin consequat, '
            . 'turpis id eleifend mattis, turpis.","url":"http://fake.plenigo.com/products/testProduct-123/",'
            . '"choosePrice":false,"price":"29.99","maxParallelAppAccess":"2","customInfo":"666",'
            . '"type":"DIGITALNEWSPAPER","currency":"EUR","term":"2",'
            . '"cancellationPeriod":"5","autoRenewal":true,"actionPeriodName":"Summer Sale",'
            . '"actionPeriodTerm":"2","actionPeriodPrice":"15.99","videoPrequelTime":"45","collectible":false,'
            . '"images":[{"url":"http://fake.plenigo.com/products/testProduct-123/image1.jpg",'
            . '"description":"Front view of the product","altText":"Front view of the product"},'
            . '{"url":"http://fake.plenigo.com/products/testProduct-123/image2.jpg",'
            . '"description":"Rear view of the product","altText":"Rear view of the product"},'
            . '{"url":"http://fake.plenigo.com/products/testProduct-123/image3.jpg",'
            . '"description":"Unboxing view of the product","altText":"Unboxing view of the product"}]}');

        return array(array($data));
    }

    public function wrongDataProvider()
    {
        $data = json_decode('{"id":"testProduct-123","subscription":false,"title":"Product Title nice feature",'
            . '"description":"Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin consequat, '
            . 'turpis id eleifend mattis, turpis.","url":"http://fake.plenigo.com/products/testProduct-123/",'
            . '"choosePrice":false,"price":"XX.XX","type":"DIGITALNEWSPAPER","currency":"EUR","term":"F",'
            . '"cancellationPeriod":"5","autoRenewal":true,"actionPeriodName":"Summer Sale",'
            . '"actionPeriodTerm":"2","actionPeriodPrice":"15.99","collectible":false,'
            . '"images":[{"url":"http://fake.plenigo.com/products/testProduct-123/image1.jpg",'
            . '"description":"Front view of the product","altText":"Front view of the product"},'
            . '{"url":"http://fake.plenigo.com/products/testProduct-123/image2.jpg",'
            . '"description":"Rear view of the product","altText":"Rear view of the product"},'
            . '{"url":"http://fake.plenigo.com/products/testProduct-123/image3.jpg",'
            . '"description":"Unboxing view of the product","altText":"Unboxing view of the product"}]}');

        return array(array($data));
    }

    public function productListProvider()
    {
        $res = '{"totalElements":"100","size":"10","pageNumber":"0","elements":[';
        $rescont = null;
        for ($i = 0; $i < 10; $i++) {
            if (!is_null($rescont)) {
                $rescont.= ',';
            }
            $rescont.= '{"productId":"123456' . ($i + 1) . '","title":"Some Product","description":"Some Description"}';
        }
        $res.=$rescont;
        $res.= ']}';
        $data = json_decode($res);
        return array(array($data));
    }

    public function categoryListProvider()
    {
        $res = '{"totalElements":"100","size":"10","pageNumber":"0","elements":[';
        $rescont = null;
        for ($i = 0; $i < 10; $i++) {
            if (!is_null($rescont)) {
                $rescont.= ',';
            }
            $rescont.= '{"categorytId":"123456' . ($i + 1) . '","title":"Some Category"}';
        }
        $res.=$rescont;
        $res.= ']}';
        $data = json_decode($res);
        return array(array($data));
    }

    public function categoryServiceProvider()
    {
        $data = json_decode('{"id":"testCategory-123", "price":"29.99","type":"DIGITALNEWSPAPER","currency":"EUR","validityTime":"0"}');

        return array(array($data));
    }

    public static function setUpBeforeClass()
    {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    /**
     * @dataProvider productServiceProvider
     */
    public function testGetProductDataSuccess($data)
    {
        ProductServiceMock::$requestResponse = $data;

        $pData = ProductServiceMock::getProductData('testProduct-123');

        //print_r($pData);

        $this->assertError(E_USER_NOTICE, "Getting Product data for ProductID=");
    }

        /**
     * @dataProvider productServiceProvider
     */
    public function testGetProductDataPrequelSuccess($data)
    {
        ProductServiceMock::$requestResponse = $data;

        $pData = ProductServiceMock::getProductData('testProduct-123');

        $this->assertInstanceOf("\plenigo\models\ProductData", $pData);
        $this->assertEquals("45", $pData->getVideoPrequelTime());

        $this->assertError(E_USER_NOTICE, "Getting Product data for ProductID=");
    }

    
    public function testGetProductDataNotFound()
    {
        $data = json_decode('{"error":"404","description":"Product testProduct-123 not found"}');
        ProductServiceMock::$requestResponse = $data;

        try {
            $pData = ProductServiceMock::getProductData('testProduct-123');
        } catch (Exception $exc) {
            $this->assertInstanceOf("\plenigo\PlenigoException", $exc);
        }

        $this->assertError(E_USER_WARNING, "Product testProduct-123 not found");
    }

    public function testGetProductDataInvalidCompany()
    {
        $data = json_decode('{"error":"401","description":"Company ID and/or Secret ID not found"}');
        ProductServiceMock::$requestResponse = $data;

        try {
            $pData = ProductServiceMock::getProductData('testProduct-123');
        } catch (Exception $exc) {
            $this->assertInstanceOf("\plenigo\PlenigoException", $exc);
        }

        $this->assertError(E_USER_WARNING, "Company ID and/or Secret ID not found");
    }

    public function testGetProductDataBasRequest()
    {
        $data = json_decode('{"error":"400","description":"Product ID invalid parameter"}');
        ProductServiceMock::$requestResponse = $data;

        try {
            $pData = ProductServiceMock::getProductData('testProduct-123');
        } catch (Exception $exc) {
            $this->assertInstanceOf("\plenigo\PlenigoException", $exc);
        }

        $this->assertError(E_USER_WARNING, "Product ID invalid parameter");
    }

    /**
     * @dataProvider productListProvider
     */
    public function testGetProductList($data)
    {
        ProductServiceMock::$requestResponse = $data;

        $pData = ProductServiceMock::getProductList(10);

        $this->assertEquals(10, count($pData['elements']));

        $this->assertError(E_USER_NOTICE, "URL CALL=http");
    }

    /**
     * @dataProvider productListProvider
     */
    public function testGetProductListWithDetails($data)
    {
        ProductServiceMock::$requestResponse = $data;

        $pData = ProductServiceMock::getProductListWithDetails(10);

        $this->assertEquals(10, count($pData['elements']));

        $this->assertError(E_USER_NOTICE, "URL CALL=http");
    }

    /**
     * @dataProvider categoryServiceProvider
     */
    public function testGetCategoryDataSuccess($data)
    {
        ProductServiceMock::$requestResponse = $data;

        ProductServiceMock::getCategoryData('testCategory-123');

        $this->assertError(E_USER_NOTICE, "Getting Category data for CategoryID=");
    }

    public function testGetCategoryDataNotFound()
    {
        $data = json_decode('{"error":"404","description":"Category testCategory-123 not found"}');
        ProductServiceMock::$requestResponse = $data;

        try {
            ProductServiceMock::getCategoryData('testCategory-123');
        } catch (Exception $exc) {
            $this->assertInstanceOf("\plenigo\PlenigoException", $exc);
        }

        $this->assertError(E_USER_WARNING, "Category testCategory-123 not found");
    }

    public function testGetCategoryDataInvalidCompany()
    {
        $data = json_decode('{"error":"401","description":"Company ID and/or Secret ID not found"}');
        ProductServiceMock::$requestResponse = $data;

        try {
            ProductServiceMock::getCategoryData('testCategory-123');
        } catch (Exception $exc) {
            $this->assertInstanceOf("\plenigo\PlenigoException", $exc);
        }

        $this->assertError(E_USER_WARNING, "Company ID and/or Secret ID not found");
    }

    public function testGetCategoryDataBasRequest()
    {
        $data = json_decode('{"error":"400","description":"testCategory ID invalid parameter"}');
        ProductServiceMock::$requestResponse = $data;

        try {
            ProductServiceMock::getCategoryData('testCategory-123');
        } catch (Exception $exc) {
            $this->assertInstanceOf("\plenigo\PlenigoException", $exc);
        }

        $this->assertError(E_USER_WARNING, "testCategory ID invalid parameter");
    }

    /**
     * @dataProvider categoryListProvider
     */
    public function testGetCategoryList($data)
    {
        ProductServiceMock::$requestResponse = $data;

        $cData = ProductServiceMock::getCategoryList(10);

        $this->assertEquals(10, count($cData['elements']));

        $this->assertError(E_USER_NOTICE, "URL CALL=http");
    }

}
