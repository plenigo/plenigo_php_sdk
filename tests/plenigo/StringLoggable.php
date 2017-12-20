<?php


use plenigo\models\Loggable;
/**
 * VoucherServiceMock
 * 
 * <b>
 * Mock and override class for VoucherService
 * </b>
 */
class StringLoggable implements Loggable {
    private $logLines = "";

    public function logData($msg) {
        $this->logLines .= $msg;
    }

    public function getLogLines() {
        return $this->logLines;
    }
}