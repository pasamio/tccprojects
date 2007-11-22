<?php
/**
* Selected Newsflash Class
* @author Samuel Moffatt <pasamio@pasamio.id.au>
* @copyright Copyright (C) 2006 Samuel Moffatt. All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class pasPersonalContent extends mosDBTable {
/** @var int Primary key */
	var $uid					= null;
/** @var int */
	var $cid				= null;

/**
* @param database A database connector object
*/
	function pasPersonalContent( &$db ) {
		$this->mosDBTable( '#__personal_content', 'id', $db );
	}
}
?>