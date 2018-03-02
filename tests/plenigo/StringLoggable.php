<?php


use plenigo\models\Loggable;


/**
 * <b>
 * Test class for the loggable interface for testing purposes.
 * </b>
 */
class StringLoggable implements Loggable {
	private $logLines = "";

	public function logData( $msg ) {
		$this->logLines .= $msg;
	}

	public function getLogLines() {
		return $this->logLines;
	}
}