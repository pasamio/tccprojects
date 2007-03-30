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
 
include($mosConfig_absolute_path . '/administrator/components/com_jlogger/jlogger.class.php');
 
DEFINE('_JLOGGER_API',1);
?>
