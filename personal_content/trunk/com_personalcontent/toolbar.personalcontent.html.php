<?php
/**
* Personal Content toolbar - HTML
* @package Personal-Content
* @subpackage Component
* @author Samuel Moffatt <pasamio@pasamio.id.au>
* @copyright Copyright (C) 2006 Samuel Moffatt. All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
*/

// no direct access
defined( '_VALID_MOS' ) or die( 'Restricted access' );

class TOOLBAR_personalcontent  {
	function _DEFAULT() {
		mosMenuBar::startTable();
		mosMenuBar::spacer();
		mosMenuBar::deleteList();
		mosMenuBar::spacer();
		mosMenuBar::addNewX();
		mosMenuBar::endTable();
	}

	function _NEW() {
		mosMenuBar::startTable();
		mosMenuBar::save();
		mosMenuBar::spacer();
		mosMenuBar::cancel();
		mosMenuBar::endTable();
	}

}
?>