<?php
/**
 * Personal Content Module
 * 
 * This module displays a content item based on the user 
 * Code borrowed from Sam Moffatt's Selected Newsflash module
 * 
 * PHP4/5
 *  
 * Created on Nov 22, 2007
 * 
 * @package Personal-Content
 * @subpackage Module
 * @author Sam Moffatt <s.moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
defined('_VALID_MOS') or die( 'Cant touch this!' );

global $my, $mosConfig_shownoauth, $mosConfig_offset, $acl;

$now = date( 'Y-m-d H:i:s', time()+$mosConfig_offset*60*60 );
$noauth = !$mainframe->getCfg( 'shownoauth' );
$nullDate = $database->getNullDate();

$query = 'SELECT cid ' 
."\n FROM #__content AS a"
."\n INNER JOIN #__personal_content AS p ON p.cid = a.id"
."\n WHERE state != 0"
."\n AND uid = ". $my->id
. ( $noauth ? "\n AND a.access <= $my->gid" : '' )
."\n AND (a.publish_up = '$nullDate' OR a.publish_up <= '$now' ) "
."\n AND (a.publish_down = '$nullDate' OR a.publish_down >= '$now' )"
."\n ORDER BY a.ordering";

$database->setQuery($query);
$result = intval($database->loadResult());
$default = $params->get( 'default_cid', 0 );
$anon = $params->get( 'anonymous_cid', 0 );

// Generic set up
require_once( $mainframe->getPath( 'front_html', 'com_content') );
$params->set( 'intro_only', 1 );
$params->set( 'hide_author', 1 );
$params->set( 'hide_createdate', 1 );
$params->set( 'hide_modifydate', 1 );
$params->set( 'readmore', 1 );
$params->set( 'item_title', 1 );
$params->set( 'pageclass_sfx', $params->get( 'moduleclass_sfx' ) );
$access = new stdClass();
$access->canEdit 	= 0;
$access->canEditOwn = 0;
$access->canPublish = 0;
$row = new mosContent( $database );

if($result) {
	// Display content
	
	$row->load( $result );
	$row->text = $row->introtext;
	$row->groups = '';
	$row->readmore = 1;
	HTML_content::show( $row, $params, $access, 0, 'com_content' );
} else if($my->id && ($default || $anon)) {
	if($default) {
		$row->load( $default );
	} else {
		$row->load( $anon );
	}
		
	$row->text = $row->introtext;
	$row->groups = '';
	$row->readmore = 1;
	HTML_content::show( $row, $params, $access, 0, 'com_content' );
} else if($anon) {
	$row->load( $anon );
	$row->text = $row->introtext;
	$row->groups = '';
	$row->readmore = 1;
	HTML_content::show( $row, $params, $access, 0, 'com_content' );
}
?>