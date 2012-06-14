<?php
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2005-2012 Stefan Galinski (stefan.galinski@gmail.com)
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
 * Usage:
 * $tinyMCE = t3lib_div::makeInstance('tinyMCE');
 * $tinyMCE->loadConfiguration($configuration);
 * $javascript = $tinyMCE->getJS();
 *
 * @author Stefan Galinski <stefan.galinski@gmail.com>
 * @package TYPO3
 * @subpackage tx_tinymce
 */
class tinyMCE {
	/**
	 * Internal extension configuration array
	 * 
	 * @var array
	 */
	protected $extensionConfiguration = array();

	/**
	 * TinyMCE configuration
	 * 
	 * @var array
	 */
	protected $tinymceConfiguration = array();

	/**
	 * Initialization flag
	 *
	 * @var bool
	 */
	static protected $init = FALSE;

	/**
	 * Constructor
	 */
	public function __construct() {
		$this->extensionConfiguration = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['tinymce']);
	}

	/**
	 * @param string $configuration file reference or configuration string (defaults to basic configuration)
	 * @param boolean $forceLanguage set this to true if you want to force your language set by the configuration
	 * @return void
	 */
	public function loadConfiguration($configuration = '', $forceLanguage = FALSE) {
		self::$init = FALSE;
		$this->tinymceConfiguration = $this->prepareTinyMCEConfiguration($configuration);

		if (!$forceLanguage) {
			$this->setLanguage();
		}

		if ($this->extensionConfiguration['compressed']) {
			$this->tinymceConfiguration['disk_cache'] = ($this->extensionConfiguration['diskCache'] ? 'true' : 'false');
		}
	}

	/**
	 * Calculates and sets the current language 
	 * 
	 * @return void
	 */
	protected function setLanguage() {
		$languageInstance = (TYPO3_MODE == 'FE' ? $GLOBALS['TSFE'] : $GLOBALS['LANG']);
		$languageKey = $languageInstance->lang;

		$groupOrUserProps = t3lib_BEfunc::getModTSconfig('', 'tx_tinyMCE');
		if (trim($groupOrUserProps['properties']['prefLang']) !== '') {
			$languageKey = $groupOrUserProps['properties']['prefLang'];
		}

			// language conversion from TLD to iso631
		if (array_key_exists($languageKey, $languageInstance->csConvObj->isoArray)) {
			$languageKey = $languageInstance->csConvObj->isoArray[$languageKey];
		}

		$languageFile = PATH_site . t3lib_extMgm::siteRelPath('tinymce') . 'tinymce/langs/' . $languageKey . '.js';
		if (!is_file($languageFile)) {
			$languageKey = 'en';
		}

		$this->tinymceConfiguration['language'] = $languageKey;
	}

	/**
	 * Returns a configuration string from the tinymce configuration array
	 *
	 * @return string
	 */
	protected function buildConfigString() {
		$configuration = $this->tinymceConfiguration['preJS'];
		$configuration .= ($this->extensionConfiguration['compressed'] ? 'tinyMCE_GZ' : 'tinyMCE');
		$configuration .= '.init({' . "\n";

		$configurationOptions = array();
		if (count($this->tinymceConfiguration)) {
			foreach ($this->tinymceConfiguration as $option => $value) {
				if (in_array($option, array('preJS', 'postJS'))) {
					continue;
				}

				if (!in_array($value, array('false', 'true'))) {
					$value = '\'' . $value . '\'';
				}

				$configurationOptions[] = "\t" . $option . ' : ' . $value;
			}
		}
		$configuration .= implode(",\n", $configurationOptions);
		$configuration .= "\n" . '});';
		$configuration .= $this->tinymceConfiguration['postJS'];

		return $configuration;
	}

	/**
	 * Returns the needed javascript inclusion code
	 *
	 * Note: This function can only be called once for each loaded configuration.
	 *
	 * @return string
	 */
	public function getJS() {
		$output = '';
		if (self::$init) {
			return $output;
		}
		self::$init = TRUE;

		$script = $GLOBALS['BACK_PATH'] . t3lib_extMgm::extRelPath('tinymce') . 'tinymce/' .
			($this->extensionConfiguration['compressed'] ? 'tiny_mce_gzip.js' : 'tiny_mce.js');
		$output = '<script type="text/javascript" src="' . $script . '"></script>
			<script type="text/javascript">' . "\n" . $this->buildConfigString() . "\n" . '</script>';

		return $output;
	}

	/**
	 * Parses and processes the tinyMCE configuration
	 *
	 * @param string $configuration file reference or configuration string
	 * @return array
	 */
	protected function prepareTinyMCEConfiguration($configuration) {
		$configurationArray = array();
		if (is_file($configuration)) {
			$configuration = file_get_contents($configuration);
		}

			// split config into first and last javascript parts (applied later again into the config variables)
			// additionally the config part is matched to get the options
		$start = '(.*)((tinyMCE|tinyMCE_GZ)\.init.*?\(.*?\{.*?';
		$end = '.*?\}.*?\).*?;)(.*)';
		$pattern = '/' . $start . $end . '/is';
		preg_match($pattern, $configuration, $matches);

			// add preJS and postJS
		$configurationArray['preJS'] = $matches[1];
		$configurationArray['postJS'] = $matches[4];

			// split options into an array (first time strings and the second call splits bool values)
		$pattern = '([[:print:]]+?)[\s]*?:[\s]*["|\']{1}(.*?)["|\']{1}[,|\n|}]{1}.*?';
		preg_match_all('/' . $pattern . '/i', $matches[2], $options);
		for ($i = 0; $i < count($options[1]); ++$i) {
			$configurationArray[$options[1][$i]] = $options[2][$i];
		}

		$options = array();
		$boolPattern = '([[:print:]]+?)[\s]*?:[\s]*(false|true)[,|\n|}]{1}.*?';
		preg_match_all('/' . $boolPattern . '/i', $matches[2], $options);
		for ($i = 0; $i < count($options[1]); ++$i) {
			$configurationArray[$options[1][$i]] = $options[2][$i];
		}

		return $configurationArray;
	}
}

?>