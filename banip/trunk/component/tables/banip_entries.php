<?php
/**
 * Document Description
 * 
 * Document Long Description 
 * 
 * PHP4/5
 *  
 * Created on Jan 14, 2009
 * 
 * @package package_name
 * @author Your Name <author@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2009 Toowoomba Regional Council/Developer Name 
 * @version SVN: $Id:$
 * @see http://joomlacode.org/gf/project/   JoomlaCode Project:    
 */
 
 
jimport('joomla.database.table');

class JTableBanIP_Entries extends JTable {
	var $entry = null;
	var $type = null;
	
	function __construct(&$db) {
		parent::__construct('#__banip_entries', 'entry', $db);
	}
	
	/**
	 * Inserts a new row if id is zero or updates an existing row in the database table
	 *
	 * Can be overloaded/supplemented by the child class
	 *
	 * @access public
	 * @param boolean If false, null object variables are not updated
	 * @return null|string null if successful otherwise returns and error message
	 */
	function store($updateNulls=false) {
		// work out what the thing is
		$this->_findType(); 

		// we only do an insert anyway since we shouldn't update this as their isn't much to update
		$k = $this->_tbl_key;
		$ret = $this->_db->insertObject( $this->_tbl, $this, $this->_tbl_key );
		if( !$ret )
		{
			$this->setError(get_class( $this ).'::store failed - '.$this->_db->getErrorMsg());
			return false;
		}
		else
		{
			return true;
		}
		
	}
	
	/**
	 * Determine the type of the function
	 * Works on the value of entry setting the appropriate value based on the /
	 *
	 */
	function _findType() {
		if(preg_match('/[a-zA-Z]*/', $this->entry)) {
			$this->type = 3;
		} else if(strpos($this->entry, '/')) {
			$this->type = 2;
		} else {
			$this->type = 1;
		}
	}
}