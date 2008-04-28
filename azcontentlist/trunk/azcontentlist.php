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
 * @see Joomla!Code Project: http://joomlacode.org/gf/project/tccprojects
 */

jimport('joomla.utilities.date');
$cache =& JFactory::getCache( 'azcontentlist');
$cache->call('showAZList');
function showAZList() {
        $date = new JDate();
        $now = $date->toMySQL();
        $database =& JFactory::getDBO();
        $nullDate       = $database->getNullDate();
        $database->setQuery('SELECT c.title AS title, c.id, c.catid, c.sectionid, cc.title AS category, s.title AS section, cc.alias AS cat_alias, c.alias AS alias'
						. ' FROM #__content AS c'
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
				
				$link = 'index.php?option=com_content&view=article&id='. $result['id']. '%3A'.$result['alias'].'&Itemid='.$Itemid.'&catid='.$result['catid']. '%3A'.$result['cat_alias'];
        		if($Itemid) {
                        echo '<li><a href="'. $link .'">'. $result['title'] . '</a> ('. $result['section'].'\\'.$result['category'].')</li>';
        		}
        }
        echo '</ul>';
}
