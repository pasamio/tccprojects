<?php
/**
 * Personal Content System
 * 
 * This handles the main logic of the application 
 * Code borrowed from Sam Moffatt's Selected Newsflash System
 * 
 * PHP4/5
 *  
 * Created on Nov 22, 2007
 * 
 * @package Personal-Content
 * @subpackage Component 
 * @author Sam Moffatt <sam.moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
// prevent access
defined('_VALID_MOS') or die('Direct access to this location is not permitted');


require_once( $mainframe->getPath( 'admin_html' ) );
require_once( $mainframe->getPath( 'class' ) );

$task 	= mosGetParam( $_REQUEST, 'task', '' );
$cid 	= mosGetParam( $_POST, 'cid', array(0) );
$uid 	= mosGetParam( $_GET, 'uid', 0 );
if (!is_array( $cid )) {
	$cid = array(0);
}

switch ($task) {

	case 'new':
		newPersonalContent( $option );
		break;

	case 'save':
		savePersonalContent( $option );
		break;

	case 'publish':
		publishPersonalContent( $cid, 1, $option );
		break;

	case 'unpublish':
		publishPersonalContent( $cid, 0, $option );
		break;

	case 'remove':
		removePersonalContent( $cid, $option );
		break;
	
	case 'edit':
		edit($uid, $option);
		break;
		
	default:
		showSelection( $option );
		break;
}

/**
* List the records
* @param string The current GET/POST option
*/
function showSelection( $option ) {
	global $database, $mainframe, $mosConfig_list_limit;

	$catid = $mainframe->getUserStateFromRequest( "catid{$option}", 'catid', 0 );
	$limit = $mainframe->getUserStateFromRequest( "viewlistlimit", 'limit', $mosConfig_list_limit );
	$limitstart = $mainframe->getUserStateFromRequest( "view{$option}limitstart", 'limitstart', 0 );

	// get the total number of records
	
	$query = "SELECT COUNT(*) FROM #__personal_content";
	$database->setQuery( $query );
	$total = $database->loadResult();

	require_once( $GLOBALS['mosConfig_absolute_path'] . '/administrator/includes/pageNavigation.php' );
	$pageNav = new mosPageNav( $total, $limitstart, $limit );

	// get the subset (based on limits) of required records
	$query = "SELECT b.uid, b.cid, a.title, c.username"
		."\n FROM #__content AS a"
		."\n INNER JOIN #__personal_content AS b ON b.cid = a.id"
		."\n LEFT JOIN #__users AS c ON b.uid = c.id"
		."\n WHERE state != 0"
		."\n ORDER BY a.ordering"
		. "\n LIMIT $pageNav->limitstart, $pageNav->limit";
	
	$database->setQuery( $query );

	$rows = $database->loadObjectList();
	if ($database->getErrorNum()) {
		echo $database->stderr();
		return false;
	}


	HTML_personalcontent::showSelection( $rows, $lists, $pageNav, $option );
}

/**
* Edits a records
* @param string The current GET/POST option
*/
function edit( $uid, $option ) {
	global $database, $mainframe;
	$row = new pasPersonalContent($database);
	$row->load($uid);
	xajaxstart();
	$users =  mosAdminMenus::UserSelect( 'uid', $row->uid, 1, null, 'name', 0 );
	HTML_personalcontent::newPersonalContent($option,$row, $users);
}

function xajaxstart() {
	global $mosConfig_absolute_path;
	// Sam Moffatt's AJAX Server Framework (based on XAJAX)
	include $mosConfig_absolute_path . '/administrator/components/com_ajaxserver/ajax/includes/xajax.common.php';
	ajax_generateServer($server);
	$server->printJavaScript();
}

/**
* Creates a new record
*/
function newPersonalContent( $option ) {
	global $database, $mainframe, $my, $mosConfig_absolute_path, $mosConfig_offset;
		
	xajaxstart();
	$row = new pasPersonalContent($database);
	$users =  mosAdminMenus::UserSelect( 'uid', $row->uid, 1, null, 'name', 0 );
	HTML_personalcontent::newPersonalContent( $option, $row, $users );
	
}

/**
* Saves the record from an edit form submit
* @param string The current GET/POST option
*/
function savePersonalContent( $option ) {
	global $database, $my;	

	$row = new paspersonalcontent( $database );
	if (!$row->bind( $_POST )) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	
	// save the changes
	if (!$row->store()) {
		echo "<script> alert('".$row->getError()."'); window.history.go(-1); </script>\n";
		exit();
	}
	mosRedirect( 'index2.php?option='. $option );
}


/**
* Removes records
* @param array An array of id keys to remove
* @param string The current GET/POST option
*/
function removePersonalContent( &$cid, $option ) {
	global $database;

	if (!is_array( $cid ) || count( $cid ) < 1) {
		echo "<script> alert('Select an item to delete'); window.history.go(-1);</script>\n";
		exit;
	}
	if (count( $cid )) {
		$cids = implode( ',', $cid );
		$query = "DELETE FROM #__personal_content"
		. "\n WHERE uid IN ( $cids )"		
		;
		
		$database->setQuery( $query );		
		if (!$database->query()) {
			echo "<script> alert('".$database->getErrorMsg()."'); window.history.go(-1); </script>\n";
		}
	}

	mosRedirect( 'index2.php?option='. $option );
}
