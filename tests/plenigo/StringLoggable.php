<?php


use plenigo\models\Loggable;

class StringLoggable implements Loggable {
	private $logLines = "";

	public function logData( $msg ) {
		$this->logLines .= $msg;
	}

	public function getLogLines() {
		return $this->logLines;
	}
}