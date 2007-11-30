<?php
/**
* Personal Content Class
* @package Personal-Content
* @subpackage Component
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
		$this->mosDBTable( '#__personal_content', 'uid', $db );
	}
	
	function store( $updateNulls=false ) {
		if($this->cid) {
			$this->_db->setQuery('SELECT uid FROM #__personal_content WHERE uid = '. $this->uid );
			$result = intval($this->_db->loadResult());
			if($result) { 
				$ret = $this->_db->updateObject($this->_tbl, $this, $this->_tbl_key, $updateNulls);
			} else {
				$ret = $this->_db->insertObject($this->_tbl, $this, $this->_tbl_key);
			}
			
			if (!$ret) {
				$this->_error = strtolower(get_class($this))."::store failed <br />" . $this->_db->getErrorMsg();
				return false;
			} else {
				return true;
			}
		} else {
			$this->delete($this->uid);
			return true;
		}
	}
}
?>