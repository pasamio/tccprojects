<?php
/**
 * JLogger Administration Panel
 * 
 * Contains the view commands.
 * 
 * PHP 4
 * MySQL 4
 *  
 * Created on Dec 18, 2006
 * 
 * @package JLogger
 * @subpackage Administrator
 * @author Samuel Moffatt <Sam.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2006 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see Joomla!Forge Project: http://forge.joomla.org
 */
 
defined('_VALID_MOS') or die('Direct Access not allowed'); 
 
switch($task) {
	case 'configure':
		doConfigure();
		break;
	default:
		viewLog();
		break;
} 

function doConfigure() {
	// Blank line since eclipse enforces placing a new { if i hit enter above
	echo '<p class="adminHeading">Configuration Settings</p>';
	echo '<p>This is not written yet.</p>';
}

function viewLog() {
	// Wh00t!
	global $database;
	$database->setQuery("SELECT * FROM #__jlogger_entries ORDER BY logid DESC");
	$results = $database->loadAssocList();
	echo '<table class="adminlist"><tr><th class="title">Log ID</th><th>Entry Date</th><th>Application</th><th>Type</th><th>Priority</th><th>Message</th></tr>';
	$i = 0;
	foreach($results as $result) {
		if($i) { $i = 0; } else { $i = 1; }
		echo '<tr class="row'.$i.'"><td>'. $result['logid'] .'</td><td align="center">'. $result['entrydate'] .'</td><td>'. $result['application'].'</td><td>'. $result['type'] .'</td><td>'. $result['priority'] .'</td><td>'. $result['message'] . '</td></tr>';
	}
	echo '</table>';
	
}

?>
