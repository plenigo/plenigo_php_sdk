<?php

namespace plenigo\internal;

/**
 * <p>
 * This convenience class allows centralized method for error and information reporting. These methods will show
 * information on the PHP logger or custom log handler.
 * </p>
 * <p>
 * <b>IMPORTANT:</b> This class is part of the internal API, please do not use it, because it can
 * be removed in future versions of the SDK or access to such elements could
 * be changed from 'public' to 'protected' or less.
 * </p>
 *
 * @category SDK
 * @package  PlenigoInternal
 * @author   Sebastian Dieguez <s.dieguez@plenigo.com>
 * @link     https://www.plenigo.com
 */
final class PlenigoLogger
{

    const TEMPLATE_LOG = "[%25.25s] - %s - %s";
    const ENCODING_UTF8 = "UTF-8";

    /**
     * Convenience method for calling NOTICE info messages. The object reference is needed to show the referenced
     * class that is calling this method. An optional Exception can be sent so it outputs the entire stacktrace. 
     * 
     * @param mixed     $clazz can be an object, a string or any other variable, if its an object, it's class is shown
     * @param string    $msg the NOTICE message to send
     * @param Exception $exc an optional Exception object to show its stacktrace and messages
     * @return void
     */
    public static function notice($clazz, $msg, $exc = null)
    {
        $strDate = self::getDateString();
        $strClass = self::getStringFromObject($clazz);
        $strStack = self::getStackFromException($exc);
        $strMessage = htmlentities($msg, ENT_QUOTES, self::ENCODING_UTF8, false);
        $strFinal = sprintf(self::TEMPLATE_LOG, $strDate, $strClass, $strMessage);
        if (!is_null($strStack)) {
            $strFinal.="\n" . $strStack;
        }
        trigger_error($strFinal, E_USER_NOTICE);
        return;
    }

    /**
     * Convenience method for calling WARNING messages. The object reference is needed to show the referenced
     * class that is calling this method. An optional Exception can be sent so it outputs the entire stacktrace. 
     * 
     * @param mixed     $clazz can be an object, a string or any other variable, if its an object, it's class is shown
     * @param string    $msg the WARNING message to send
     * @param Exception $exc an optional Exception object to show its stacktrace and messages
     * @return void
     */
    public static function warn($clazz, $msg, $exc = null)
    {
        $strDate = self::getDateString();
        $strClass = self::getStringFromObject($clazz);
        $strStack = self::getStackFromException($exc);
        $strMessage = htmlentities($msg, ENT_QUOTES, self::ENCODING_UTF8, false);
        $strFinal = sprintf(self::TEMPLATE_LOG, $strDate, $strClass, $strMessage);
        if (!is_null($strStack)) {
            $strFinal.="\n" . $strStack;
        }
        trigger_error($strFinal, E_USER_WARNING);
        return;
    }

    /**
     * Convenience method for calling ERROR messages. The object reference is needed to show the referenced
     * class that is calling this method. An optional Exception can be sent so it outputs the entire stacktrace. 
     * 
     * @param mixed     $clazz can be an object, a string or any other variable, if its an object, it's class is shown
     * @param string    $msg the ERROR message to send
     * @param Exception $exc an optional Exception object to show its stacktrace and messages
     * @return void
     */
    public static function error($clazz, $msg, $exc = null)
    {
        $strDate = self::getDateString();
        $strClass = self::getStringFromObject($clazz);
        $strStack = self::getStackFromException($exc);
        $strMessage = htmlentities($msg, ENT_QUOTES, self::ENCODING_UTF8, false);
        $strFinal = sprintf(self::TEMPLATE_LOG, $strDate, $strClass, $strMessage);
        if (!is_null($strStack)) {
            $strFinal.="\n" . $strStack;
        }

        trigger_error($strFinal, E_USER_WARNING);
        return;
    }

    /**
     * Returns the current date/time in ISO 8601 format
     * 
     * @return string the Date in ISO 8601 formatting
     */
    private static function getDateString()
    {
        return date("c");
    }

    /**
     * Returns a representarion of the object or its class name to show in the logs
     * 
     * @param mixed $clazz can be an object, a string or any other variable, if its an object, it's class is shown
     * @return type
     */
    private static function getStringFromObject($clazz)
    {
        if (is_object($clazz)) {
            return get_class($clazz);
        } elseif (is_string($clazz)) {
            return $clazz;
        } else {
            return print_r($clazz, true);
        }
    }

    /**
     * Returns a representation (Java style) of the stacktrace, it recursivelly shows the "Caused by:" stacktrces
     * 
     * @param \Exception $exc Exception object to show its stacktrace and messages
     * @return string|null the full stacktrace or NULL
     */
    public static function getStackFromException($exc)
    {
        if (is_null($exc)) {
            return null;
        }
        if ($exc instanceof \Exception) {
            $trace = $exc->getTrace();
            $result = "Exception: '" . $exc->getMessage() . "'\n";
            foreach ($trace as $stackItem) {
                $result .= self::buildStackLine($stackItem);
            }
            if (!is_null($exc->getPrevious())) {
                $prev = $exc->getPrevious();
                $result .= "\n Caused by: \n" . self::getStackFromException($prev);
            }

            return $result;
        } else {
            return null;
        }
    }

    /**
     * Construct a stackLine using the associative array of a stack line.
     * 
     * @param array $stackItem an array with each line in the stack of calls
     * @return string The string representation of this stacktrace line
     */
    private static function buildStackLine($stackItem)
    {
        $result = " @";
        if (isset($stackItem['class']) && $stackItem['class'] != '') {
            $result .= $stackItem['class'] . '->';
        }
        $result .= $stackItem['function'] . "();";
        if (isset($stackItem['file'])) {
            $result .= " // " . basename($stackItem['file']) . ":" . $stackItem['line'];
        }
        $result .= "\n";

        return $result;
    }

}
