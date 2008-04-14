<?php
/**
 * JLogger API
 * 
 * This file contains the API to JLogger and provides all front end functionality. 
 * 
 * PHP 4
 * MySQL 4
 *  
 * Created on Dec 18, 2006
 * 
 * @package JLogger
 * @subpackage API Layer
 * @author Samuel Moffatt <Sam.Moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see Joomla!Forge Project: http://forge.joomla.org
 */
 
/**
 * JLogger Class
 */
class JLogEntry extends mosDBTable {
	/** @var int logid Log Entry ID */
	var $logid = 0;
	/** @var string application Application responsible for log entry */
	var $application = '';
	/** @var string type Type of Entry */
	var $type = '';
	/** @var string priority Priority of entry ('panic', 'emerg', 'alert', 'crit', 'err', 'error', 'warn', 'warning', 'notice', 'info', 'debug', 'none') */
	var $priority = 'info';
	/** @var date entrydate Date of Entry */
	var $entrydate = '0000-00-00 00:00:00'; // YYYY-MM-DD HH:MM:SS
	/** @var string message Message to be logged */
	var $message = '';

    function JLogEntry(&$db) {
    	// LOL Pie!
    	$this->mosDBTable('#__jlogger_entries', 'logid', $db);
    	$this->entrydate = date('Y-m-d H:i:s');
    }
}

// Shouldn't require this check but it you never know...
if(!function_exists('addLogEntry')) {
	function addLogEntry($application, $type, $priority, $message) {
			global $database;
			$logentry = new JLogEntry($database);
			$logentry->application = $application;
			$logentry->type 		= $type;
			$logentry->priority 	= $priority;
			$logentry->message 	= $message;
			$logentry->store() or die('Log entry save failed');
	}
}
?>
