<?php

require_once __DIR__ . '/PlenigoTestCase.php';
require_once __DIR__ . '/../../../../src/plenigo/PlenigoManager.php';
require_once __DIR__ . '/../../../../src/plenigo/internal/utils/JWT.php';

use \plenigo\internal\utils\JWT;
use \plenigo\PlenigoManager;

/**
 * JWTTest
 * 
 * <b>
 * Test class for  JWT
 * </b>
 *
 * @category SDK
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://plenigo.com
 */
class JWTTest extends PlenigoTestCase {

    const SECRET_ID = 'AMXzF7qJ9y0uuz2IawRIk6ZMLVeYKq9yXh7lURXQ';
    const COMPANY_ID = 'h7evZBaXvhaLVHYRTIHD';
    const RESULT_TOKEN = "eyJhbGciOiJIUzI1NiJ9.eyJjb21wYW55SWQiOiJoN2V2WkJhWHZoYUxWSFlSVElIRCIsImF1ZCI6InBsZW5pZ28iLCJqdGkiOiIwYzMwN2VkMy0zNDA0LTk0OWMtOTE1Ny02NjQ0YjEzNDc4N2EiLCJleHAiOjE0NDcxNTI1NDl9.RQX7v8TGHd5P7Ucs5eLNTzXqRAIc9ZldPnBCzJPAmv8";
    const RANDOM_JTI = "0c307ed3-3404-949c-9157-6644b134787a";

    public static function setUpBeforeClass() {
        PlenigoManager::configure(self::SECRET_ID, self::COMPANY_ID, true);
        PlenigoManager::setDebug(true);
    }

    public function payloadProvider() {
        $data = json_decode('{"companyId": "'.self::COMPANY_ID.'","aud": "plenigo","jti": "'.self::RANDOM_JTI.'","exp": 1447152549}');
        return array(array($data));
    }
    
    public function tokenProvider() {
        $data = self::RESULT_TOKEN;
        return array(array($data));
    }

    public function brokenTokenProvider() {
        $data = array(
            array("broken token"),
            array("broken.token"),
            array("eyJhbGciOiJIUzI1NiJ9.eyJjb21wYW55SWQiOiJoN2V2WkJhWHZo"));
        return $data;
    }

    public function brokenTokenSegProvider() {
        $data = array(
            array("broken.token.token"),
            array("broken.token."),
            array("eyJhbGciOiJIUzI1NiJ9.eyJjb21wYW55SWQiOiJoN2V2WkJhWHZo.s"));
        return $data;
    }

    /**
     * @dataProvider payloadProvider
     */
    public function testEncodeStuff($data) {

        $result = JWT::encode($data, self::SECRET_ID);
        
        $test = (self::RESULT_TOKEN===$result)?true:false;
        $this->assertTrue($test);
    }
    
    /**
     * @dataProvider payloadProvider
     */
    public function testEncodeStuffBadSecret($data) {

        $result = JWT::encode($data, "badSecret");
        
        $test = (self::RESULT_TOKEN===$result)?true:false;
        $this->assertFalse($test);
    }
    
    public function testEncodeStuffBadData() {

        $result = JWT::encode(json_decode('{"companyId": ""}'), self::SECRET_ID);
        
        $test = (self::RESULT_TOKEN===$result)?true:false;
        $this->assertFalse($test);
    }

    /**
     * @dataProvider tokenProvider
     */
    public function testDecodeStuff($data) {

        $result = JWT::decode($data, self::SECRET_ID, true);
        
        $this->assertFalse(is_null($result));
        $this->assertEquals($result->companyId,self::COMPANY_ID);
        $this->assertEquals($result->jti,self::RANDOM_JTI);
        $this->assertEquals($result->aud,"plenigo");
    }
    
    /**
     * @dataProvider brokenTokenProvider
     */
    public function testBrokenToken($data) {

        try{
            $result = JWT::decode($data, self::SECRET_ID, true);
        }catch(\Exception $exc){
            $this->assertFalse(false);
        }
        $this->assertError(E_USER_WARNING, "Wrong number of segments");
    }
    
    /**
     * @dataProvider brokenTokenSegProvider
     */
    public function testBrokenTokenSeg($data) {

        try{
            $result = JWT::decode($data, self::SECRET_ID, true);
        }catch(\Exception $exc){
            $this->assertFalse(false);
        }
        $this->assertError(E_USER_WARNING, "plenigo\internal\utils\JWT");
    }

    public function testBrokenSecret() {
        try{
            $result = JWT::decode(self::RESULT_TOKEN, "my broken secret", true);
        }catch(\Exception $exc){
            $this->assertFalse(false);
        }
        $this->assertError(E_USER_WARNING, "Signature verification failed");
    }
}
