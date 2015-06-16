<?php

/**
 * Log writer
 *
 * The Log writer is a custom log class that transform slim log output
 * in app engine log output (https://cloud.google.com/appengine/docs/php/logs/)
 */
class LogWriter {

	/**
     * Write log message
     *
     * @return true
     */
	public function write($message, $level = null) {
		syslog(LOG_DEBUG, (string) $message);
		return true;
	}
}
