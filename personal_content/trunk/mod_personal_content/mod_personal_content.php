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
 * @package PersonalContent
 * @subpackage Module
 * @author Sam Moffatt <s.moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see JoomlaCode Project: http://joomlacode.org/gf/project/
 */
 
defined('_VALID_MOS') or die( 'Cant touch this!' );

$database->setQuery('SELECT cid FROM #__personal_content WHERE uid = '. $my->id);
$result = intval($database->loadResult());
if($result) {
	// Display content
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
	$row->load( $result );
	$row->text = $row->introtext;
	$row->groups = '';
	$row->readmore = 1;
	HTML_content::show( $row, $params, $access, 0, 'com_content' );
}
?>