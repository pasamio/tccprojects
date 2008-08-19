<?php
/**
 * @version		$Id:plugin.php 6961 2007-03-15 16:06:53Z tcp $
 * @package		JLibMan
 * @subpackage	Installer
 * @copyright	Copyright (C) 2008 Toowoomba Regional Council/Sam Moffatt
 * @copyright 	Copyright (C) 2005-2007 Open Source Matters (Portions)
 * @license		GNU/GPL, see LICENSE.php
 * Joomla! is free software. This version may have been modified pursuant
 * to the GNU General Public License, and as distributed it includes or
 * is derivative of works licensed under the GNU General Public License or
 * other free or open source software licenses.
 * See COPYRIGHT.php for copyright notices and details.
 */

// Check to ensure this file is within the rest of the framework
defined('JPATH_BASE') or die();

if(!defined('PATCH_PATH')) define('PATCH_PATH',JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_patch' . DS .'patches');

/**
 * Library installer
 *
 * @package		JLibMan
 * @subpackage	Installer
 * @since		1.5
 */
class JInstallerPatch extends JObject
{
	/**
	 * Constructor
	 *
	 * @access	protected
	 * @param	object	$parent	Parent object [JInstaller instance]
	 * @return	void
	 * @since	1.5
	 */
	function __construct(&$parent)
	{
		$this->parent =& $parent;
	}

	/**
	 * Custom install method
	 *
	 * @access	public
	 * @return	boolean	True on success
	 * @since	1.5
	 */
	function install()
	{
		// Get the extension manifest object
		$manifest =& $this->parent->getManifest();
		$this->manifest =& $manifest->document;
		$config =& JComponentHelper::getParams('com_patch');
		$patch_exec = $config->getValue('patch_path','/usr/bin/patch');

		/**
		 * ---------------------------------------------------------------------------------------------
		 * Manifest Document Setup Section
		 * ---------------------------------------------------------------------------------------------
		 */

		// Set the extensions name
		$name =& $this->manifest->getElementByPath('name');
		$name = JFilterInput::clean($name->data(), 'string');
		$this->set('name', $name);

		// Get the component description
		$description = & $this->manifest->getElementByPath('description');
		if (is_a($description, 'JSimpleXMLElement')) {
			$this->parent->set('message', $description->data());
		} else {
			$this->parent->set('message', '' );
		}

		/**
		 * Files~!
		 */
		$dir = getcwd();
		@chdir('..');
		if($dir == getcwd()) {
			$this->parent->abort(JText::_('Patch').' '.JText::_('Install').': '.JText::_('Could not change directory!'));
			return false;
		}
		
		$files =& $this->manifest->getElementByPath('files');
		$files = $files->children();
		$results = '';
		foreach($files as $file) {
			$cmd = $patch_exec .' -p0 < '. $this->parent->getPath('source').DS.$file->data();
			$results .= `$cmd`;
		}
		$this->parent->set('extension.message', '<pre>'.$results.'</pre>');
		return true;
	}

}

