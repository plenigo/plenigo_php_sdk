<?php

use PHPUnit\Framework\TestCase;

/**
 * <p>
 * This abstract class provides capabilities extending the PHPUnit_Framework_TestCase ones. 
 * It was created to provide a comprehensive solution to the error reporting problem for Unit Tests 
 * where NOTICE reporting causes the test to fail, but it may contain other functionalities as well
 * </p>
 *
 * @category SDK
 * @package  
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     http://plenigo.com
 */
abstract class PlenigoTestCase extends TestCase
{

    protected $errors = array();
    protected $errorsAsserted = false;

    protected function setUp():void
    {
        $this->errors = array();
        $this->errorsAsserted = false;
        set_error_handler(array($this, "errorHandler"));
    }

    /**
     * Collects all errors in the given test are store in a private array. Then 
     * 
     * @param int $errno the priority of the error E_USER_NOTICE, E_USER_WARNING, E_USER_ERROR
     * @param string $errstr the error Description
     * @param string $errfile the PHP file where the error happened
     * @param int $errline line number where the problem is located
     * @param array $errcontext array of arguments and variables in the scope at the moment of the error
     */
    public function errorHandler($errno, $errstr, $errfile, $errline, $errcontext)
    {
        $this->errors[] = compact("errno", "errstr", "errfile", "errline", "errcontext");
    }

    /**
     * Asserts that an error occurred previously. Valid Error types are E_USER_NOTICE, E_USER_WARNING, E_USER_ERROR
     * 
     * @param int $errno the priority of the error E_USER_NOTICE, E_USER_WARNING, E_USER_ERROR
     * @param string $errstr the needle to search for in the error description
     * @return void only returns if the specific text was found on the Notice/Warnign/Error, fails otherwise
     * 
     */
    public function assertError($errno, $errstr)
    {
        $this->errorsAsserted = true;
        foreach ($this->errors as $error) {
            if ($this->isInString($error["errstr"], $errstr) && $error["errno"] === $errno) {
                $this->assertTrue(true);
                return;
            }
        }

        $this->fail($this->getErrorName($errno) . " with message '" . $errstr . "' not found!");
    }

    /**
     * Asserts that an error DIDNT occurred previously. Valid Error types are E_USER_NOTICE, E_USER_WARNING, E_USER_ERROR
     * 
     * @param int $errno the priority of the error E_USER_NOTICE, E_USER_WARNING, E_USER_ERROR
     * @param string $errstr the needle to search for in the error description
     * @return void only returns if the specific text was found on the Notice/Warnign/Error, fails otherwise
     * 
     */
    public function assertNotError($errno, $errstr)
    {
        $this->errorsAsserted = true;
        foreach ($this->errors as $error) {
            if ($this->isInString($error["errstr"], $errstr) && $error["errno"] === $errno) {
                $this->fail($this->getErrorName($errno) . " with message '" . $errstr . "' found!");
                return;
            }
        }

        $this->assertTrue(true);
    }

    protected function tearDown():void
    {
        restore_error_handler();
    }

    protected function assertPostConditions() :void
    {
        if ((count($this->errors) > 0) && ($this->errorsAsserted === false)) {
            $msgErrors = "";
            foreach ($this->errors as $error) {
                $msgErrors.= "-------------------------\n";
                $msgErrors.= strtoupper($this->getErrorName($error["errno"])) . ": " . $error["errstr"] . "\n\n";
            }
            $this->fail("Messages/Warnings/Errors not asserted: see below!!\n\n" . $msgErrors);
        }
    }

    private function getErrorName($errno)
    {
        switch ($errno) {
            case E_USER_NOTICE:
                return "Notice";
            case E_USER_WARNING:
                return "Warning";
            default:
                return "Error";
        }
    }

    private function isInString($haystack, $needle)
    {
        $result = strpos($haystack, $needle);
        if (is_bool($result) && $result === false) {
            return false;
        }
        if (is_int($result) && $result >= 0) {
            return true;
        }
        return false;
    }

}
