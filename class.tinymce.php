<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2005-2007 Stefan Galinski (stefan.galinski@gmail.com)
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/

/**
 * tinyMCE initialisation class 
 *
 * $Id$
 *
 * @author Stefan Galinski <stefan.galinski@gmail.com>
 */

/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 */

/** @var boolean initialisation flag to prevent multiple init code */
$tinyMCEInitFlag = false;

/**
 * tinyMCE initialisation class
 *
 * @author Stefan Galinski <stefan.galinski@gmail.com>
 * @package Typo3
 * @subpackage tx_tinymce
 */
class tinyMCE
{
	/**
	 * @var array configuration
	 * @see prepareConfig()
	 */
	var $extConfig = array();

	/**@+
	/** @var array configuration */
	var $config = array();
	var $GZconfig = array();
	/**#@-*/

	/**
	 * Constructor
	 * loads the tinyMCE configuration
	 *
	 * @param string configuration file or string (optional ... default is basic configuration)
	 * @param string gzip configuration file or string (optional ... default is basic configuration)
	 * @param boolean set to false if you dont want the automatic language replacement (default true)
	 * @return void
	 */
	function tinyMCE($config='', $GZconfig='', $autoLang=true)
	{
		// prepare extension and given tinyMCE configurations
		$this->prepareConfig();
		$this->prepareTinyMCEConfig($config, false);
		$this->prepareTinyMCEConfig($GZconfig, true);
		
		// language replacement
		if($autoLang)
		{
			// get main object
			if(TYPO3_MODE == 'FE')
				$lang =& $GLOBALS['TSFE'];
			else
				$lang =& $GLOBALS['LANG'];
				
			// language conversion from TLD to iso631
			if(array_key_exists($this->extConfig['lang'], $lang->csConvObj->isoArray))
				$this->extConfig['lang'] = $lang->csConvObj->isoArray[$this->extConfig['lang']];
			if(!is_file(PATH_site . t3lib_extMgm::siteRelPath('tinymce') .
				'tinyMCE/langs/' . $this->extConfig['lang'] . '.js'))
	   	        $this->extConfig['lang'] = 'en';

			// language replacement
			$this->replaceInConfig(array('language' => $this->extConfig['lang']));
			if($this->extConfig['compressed'])
				$this->replaceInConfig(array('languages' => $this->extConfig['lang']), true);
		}

		// activate disk cache
		if($this->extConfig['compressed'] && $this->extConfig['diskCache'])
			$this->replaceInConfig(array('disk_cache' => 'true'), true);
		elseif($this->extConfig['compressed'] && !$this->extConfig['diskCache'])
			$this->replaceInConfig(array('disk_cache', 'false'), true);
	}

	/**
	 * Generates a configuration string from the array informations
	 *
	 * @param boolean set to true if the options should be set into the gzip config
	 * @return string generated configuration
	 */
	function buildConfigString($gzip)
	{
		$curConfig = ($gzip) ? $this->GZconfig : $this->config;

		// generate configuration strings from array
		$config = $curConfig['preJS'];
		$config .= (($gzip) ? 'tinyMCE_GZ' : 'tinyMCE') . '.init({' . "\n";
		$configOptions = array();
		if (count($curConfig)) {
			foreach($curConfig as $option => $value) {
				if ($option == 'preJS' || $option == 'postJS')
					continue;
				if ($value != 'false' && $value != 'true')
					$value = '\'' . $value . '\'';
				$configOptions[] = "\t" . $option . ' : ' . $value;
			}
		}
		$config .= implode(",\n", $configOptions);
		$config .= "\n" . '});';
		$config .= $curConfig['postJS'];

		return $config;
	}

	/**
	 * generates and returns the needed javascript inclusion code
	 *
	 * Note: this function can only be called one time
	 *
	 * @return string generated javascript inclusion code
	 */
	function getJS()
	{
		// check init flag
		if($GLOBALS['tinyMCEInitFlag'])
			return '';
		$GLOBALS['tinyMCEInitFlag'] = true;

		// build configuration strings
		$config = $this->buildConfigString(false);
		$GZconfig = $this->buildConfigString(true);

		// return javascript
		return '<script type="text/javascript" src="' . $GLOBALS['BACK_PATH'] .
			t3lib_extMgm::extRelPath('tinymce') . 'tinyMCE/' .
			($this->extConfig['compressed'] ? 'tiny_mce_gzip.js' : 'tiny_mce.js') . '"></script>
			<script type="text/javascript">' . "\n" . $GZconfig . "\n" . '</script>
			<script type="text/javascript">' . "\n" . $config . "\n" . '</script>';
	}

	/**
	 * Prepares a tinyMCE configuration
	 * All options, post and pre javascript is saved into the config or GZconfig array
	 *
	 * @param string file reference or configuration string
	 * @param boolean set to true if the options should be set into the gzip config
	 * @return void
	 */
	function prepareTinyMCEConfig($config, $gzip)
	{
		// get file contents if necessary
		if(is_file($config))
			$config = t3lib_div::getURL($config);

		// get config variable
		if ($gzip)
			$curConfig =& $this->GZconfig;
		else
			$curConfig =& $this->config;

		// split config into first and last javascript parts (applied later again into the config variables)
		// additionaly the config part is matched to get the options
		$start = '(.*)((tinyMCE|tinyMCE_GZ)\.init.*?\(.*?\{.*?';
        $end = '.*?\}.*?\).*?;)(.*)';
        $pattern = '/' . $start . $end . '/is';
		preg_match($pattern, $config, $matches);

		// add preJS and postJS
		$curConfig['preJS'] = $matches[1];
		$curConfig['postJS'] = $matches[4];

		// split options into an array (first time strings and the second call splits bool values)
		$pattern = '([[:print:]]+?)[\s]*?:[\s]*["|\']{1}(.*?)["|\']{1}[,|\n|}]{1}.*?';
		preg_match_all('/' . $pattern . '/i', $matches[2], $options);
		for ($i = 0; $i < count($options[1]); ++$i)
			$configOptions[$options[1][$i]] = $options[2][$i];

		$options = array();
		$boolPattern = '([[:print:]]+?)[\s]*?:[\s]*(false|true)[,|\n|}]{1}.*?';
		preg_match_all('/' . $boolPattern . '/i', $matches[2], $options);
		for ($i = 0; $i < count($options[1]); ++$i)
			$configOptions[$options[1][$i]] = $options[2][$i];

		// add config options
		if (is_array($configOptions))
			$curConfig = array_merge($curConfig, $configOptions);
	}

	/**
	 * replaces/adds an option in the configuration
	 *
	 * @param array option => value
	 * @param boolean set to true if the options should be set into the gzip config
	 * @return void
	 */
	function replaceInConfig($options, $gzip=false)
	{
		foreach ($options as $option => $value) {
			if($gzip)
				$this->GZconfig[$option] = $value;
			else
				$this->config[$option] = $value;
		}
	}
	
	/**
	 * return false if the browser isnt supported
	 *
	 * Note: Currently only Opera until version 9 and Konqueror are in the unsupported list.
	 * Browsers like lynx or IE4 are not in the list, because they are outdated and have only a very
	 * low market share or arent supported by TYPO3.
	 *
	 * Feel free to send other Browsers which should be added here.
	 *
	 * @return boolean return true if the browser is supported
	 */
	function checkBrowser()
	{
		$browser = t3lib_div::getIndpEnv('HTTP_USER_AGENT');
		if(preg_match('/Opera\\/[0-8]./', $browser) ||
			preg_match('/Mozilla.+Konqueror./', $browser))
			return false;

		return true;
	}

	/**
	 * preparation and check of the configuration
	 *
	 * Note that the default value will be set, if a option check fails.
	 *
	 * @return void
	 */
	function prepareConfig()
	{
		$this->extConfig = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tinymce']);
		
		// get current/forced language
		$groupOrUserProps = t3lib_BEfunc::getModTSconfig('', 'tx_tinyMCE');
		if(!empty($groupOrUserProps['properties']['prefLang']))
			$this->extConfig['lang'] = $groupOrUserProps['properties']['prefLang'];
		else
			$this->extConfig['lang'] = (TYPO3_MODE == 'FE' ? $GLOBALS['TSFE']->lang : $GLOBALS['LANG']->lang);
	}
}

// Default-Code for using XCLASS (dont touch)
if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tinymce/mod1/class.tinymce.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/tinymce/mod1/class.tinymce.php']);
}

?>
