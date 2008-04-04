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
 * @author Sam Moffatt <Sam.Moffatt@toowoomba.qld.gov.au>
 * @author Toowoomba City Council Information Management Branch
 * @license GNU/GPL http://www.gnu.org/licenses/gpl.html
 * @copyright 2007 Toowoomba City Council/Developer Name 
 * @version SVN: $Id:$
 * @see Project Documentation DM Number: #???????
 * @see Gaza Documentation: http://gaza.toowoomba.qld.gov.au
 * @see Joomla!Forge Project: http://forge.joomla.org
 */

$cache =& JFactory::getCache( 'azcontentlist');
$cache->call('showAZList');
function showAZList() {
        $date = new JDate();
        $now = $date->toMySQL();
        $database =& JFactory::getDBO();
        $nullDate       = $database->getNullDate();
        $database->setQuery("SELECT c.title AS title, c.id, c.catid, c.sectionid, cc.title AS category, s.title AS section FROM #__content AS c"
        				. ' LEFT JOIN #__categories AS cc ON cc.id = c.catid'
				 		. ' LEFT JOIN #__sections AS s ON s.id = c.sectionid'
						. " WHERE c.access = 0 AND c.state = 1"
						. ' AND cc.access = 0'
						. ' AND s.access = 0'
                        . " AND ( publish_up = " . $database->Quote( $nullDate ) . " OR publish_up <= " . $database->Quote( $now ) . " )"
                        . " AND ( publish_down = " . $database->Quote( $nullDate ) . " OR publish_down >= " . $database->Quote( $now ) . " )"
                        . " ORDER BY title"
        );
        $results = $database->loadAssocList();
        echo '<p class="componentheading">A-Z Site Map</p>';
        echo '<ul>';
        foreach($results as $result) {
                //$app =& JFactory::getApplication();
                //$Itemid = $app->getItemid( $result['id'] );
				require_once JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php';
		
				$needles = array(
					'article'  => (int) $result['id'],
					'category' => (int) $result['catid'],
					'section'  => (int) $result['sectionid'],
				);
		
				$item	= ContentHelperRoute::_findItem($needles);
				$Itemid	= is_object($item) ? $item->id : null;
        		if($Itemid) {
                        echo '<li><a href="index.php?option=com_content&task=view&id='. $result['id']. '&Itemid='.$Itemid.'">'. $result['title'] . '</a> ('. $result['section'].'\\'.$result['category'].')</li>';
        		}
        }
        echo '</ul>';
}
