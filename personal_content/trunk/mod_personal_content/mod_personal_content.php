<?php
/**
 * Personal Content Module
 * 
 * This module displays a content item based on the user 
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

echo '<p>'.$my->id.'</p>';

$database->setQuery('SELECT cid FROM #__personal_content WHERE uid = '. $my->id);
$result = intval($database->loadResult());
if($result) {
	// Display content
	echo '<p>'. $my->id . '</p>';
}
?>