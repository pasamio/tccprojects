<?php

/**
* Installer file
* Creates the AJAX Mambot
* @package Personal-Content
* @subpackage Component
* @author Toowoomba City Council Information Management Branch
* @license GNU/GPL http://www.gnu.org/licenses/gpl.html
* @copyright 2007 Toowoomba City Council/Sam Moffatt 
* @version SVN: $Id:$
* @see JoomlaCode Project: http://joomlacode.org/gf/project/
*/

function com_install() {
	global $mosConfig_absolute_path;
	mkdir($mosConfig_absolute_path . '/mambots/ajax');
} 
