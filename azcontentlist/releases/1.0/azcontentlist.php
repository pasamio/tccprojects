<?php
/**
 * A-Z Content Listing Tool
 * 
 * This lists all published content items. 
 * 
 * PHP 4/5
 * MySQL 4/5
 *  
 * Created on Feb 14, 2007
 * 
 * @package azcontentlist
 * @author Sam Moffatt <Sam.Moffatt@toowoombarc.qld.gov.au>
 * @author Toowoomba Regional Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2008 Toowoomba Regional Council/Sam Moffatt 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see Joomla!Forge Project: http://forge.joomla.org
 */

$cache =& mosCache::getCache( 'azcontentlist');
$cache->call('showAZList');
function showAZList() {
	$now = _CURRENT_SERVER_TIME;
	$nullDate 	= $database->getNullDate();
	$database->setQuery("SELECT * FROM #__content WHERE access = 0 AND state = 1"
			. " AND ( publish_up = " . $database->Quote( $nullDate ) . " OR publish_up <= " . $database->Quote( $now ) . " )"
			. " AND ( publish_down = " . $database->Quote( $nullDate ) . " OR publish_down >= " . $database->Quote( $now ) . " )"
			. " ORDER BY title"
	);
	$results = $database->loadAssocList();
	echo '<p class="componentheading">A-Z Site Map</p>';
	echo '<ul>';
	foreach($results as $result) {
		$Itemid = $mainframe->getItemid( $result['id'] );
        if($Itemid) {
			echo '<li><a href="index.php?option=com_content&task=view&id='. $result['id']. '&Itemid='.$Itemid.'">'. $result['title'] . '</a></li>';
        }
	}
	echo '</ul>';
}

?>
