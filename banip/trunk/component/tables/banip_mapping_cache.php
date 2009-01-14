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

class JTableBanIP_Mapping_Cache extends JTable {
	var $cache_id = null;
	var $ipaddress = null;
	var $dnsname = null;
	var $lastupdate = null;
	var $ttl = null;
	
	function __construct(&$db) {
		parent::__construct('#__banip_mapping_cache', 'cache_id', $db);
	}
}