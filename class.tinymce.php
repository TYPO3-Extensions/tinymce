<?php

/***************************************************************
 *  Copyright notice
 *
 *  (c) sgalinski Internet Services (http://www.sgalinski.de)
 *
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
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
 * Basic Configuration:
 *
 * tinymce.init({
 *    selector: 'textarea'
 * });
 */
class tinyMCE {
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
	}

	/**
	 * Calculates and sets the current language
	 *
	 * @return void
	 */
	protected function setLanguage() {
		/** @var $languageInstance language */
		$languageInstance = (TYPO3_MODE === 'FE' ? $GLOBALS['TSFE'] : $GLOBALS['LANG']);
		$languageKey = $languageInstance->lang;

		$groupOrUserProps = t3lib_BEfunc::getModTSconfig('', 'tx_tinyMCE');
		if (trim($groupOrUserProps['properties']['prefLang']) !== '') {
			$languageKey = $groupOrUserProps['properties']['prefLang'];
		}

		// language conversion from TLD to iso631
		if (class_exists('t3lib_l10n_Locales')) {
			/** @var $locales t3lib_l10n_Locales */
			$locales = t3lib_div::makeInstance('t3lib_l10n_Locales');
			$isoArray = $locales->getIsoMapping();
		} else {
			$isoArray = $languageInstance->csConvObj->isoArray;
		}

		if (array_key_exists($languageKey, $isoArray)) {
			$languageKey = $isoArray[$languageKey];
		}

		$languageFile = PATH_site . t3lib_extMgm::siteRelPath('tinymce') . 'tinymce/langs/' . $languageKey . '.js';
		if (!is_file($languageFile)) {
			$languageKey = 'en';
		}

		$this->addConfigurationOption('language', $languageKey);
	}

	/**
	 * Returns a configuration string from the tinymce configuration array
	 *
	 * @return string
	 */
	protected function buildConfigString() {
		$configuration = $this->tinymceConfiguration['preJS'];
		$configuration .= "\n" . 'tinymce.init({' . "\n";

//		$configurationOptions = array();
//		foreach ($this->tinymceConfiguration['strings'] as $option => $value) {
//			$value = '\'' . str_replace('\'', '\\\'', $value) . '\'';
//			$configurationOptions[] = "\t" . $option . ': ' . $value;
//		}
//
//		foreach ($this->tinymceConfiguration['boolAndInt'] as $option => $value) {
//			if (is_numeric($value)) {
//				if (strpos($value, '.')) {
//					$value = (float) $value;
//				} else {
//					$value = (int) $value;
//				}
//			}
//			$configurationOptions[] = "\t" . $option . ': ' . $value;
//		}
//
//		foreach ($this->tinymceConfiguration['arrays'] as $option => $value) {
//			$configurationOptions[] = "\t" . $option . ': ' . $value;
//		}
//
//		foreach ($this->tinymceConfiguration['objects'] as $option => $value) {
//			$configurationOptions[] = "\t" . $option . ': ' . $value;
//		}
//
//		foreach ($this->tinymceConfiguration['functions'] as $option => $value) {
//			$configurationOptions[] = "\t" . $option . ': ' . $value;
//		}
//		$configuration .= implode(",\n", $configurationOptions);

		$configuration .= $this->tinymceConfiguration['configurationData'];
		$configuration .= "\n" . '});' . "\n";
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
		if (!self::$init) {
			self::$init = TRUE;
			$script = $GLOBALS['BACK_PATH'] . t3lib_extMgm::extRelPath('tinymce') . 'tinymce/tinymce.min.js';
			$output = '<script type="text/javascript" src="' . $script . '"></script>';
			$output .= '<script type="text/javascript">' . "\n" . $this->buildConfigString() . "\n" . '</script>';
		}

		return $output;
	}

	/**
	 * Parses and processes the tinyMCE configuration
	 *
	 * Note: Unfortunately we didn't solved the riddle how to parse object and function blocks. So we can't parse
	 * the configuration in detail. Also the regexp has some other possible minor flaws. Recursion (?R) could be a
	 * possible way.
	 *
	 * @param string $configuration file reference or configuration string
	 * @return array
	 */
	protected function prepareTinyMCEConfiguration($configuration) {
		$configurationArray = array();

		// try to resolve a potential TYPO3 file path
		$configurationFile = t3lib_div::getFileAbsFileName($configuration);
		if (is_file($configurationFile)) {
			$configuration = file_get_contents($configurationFile);
		}

		// split config into first and last javascript parts (applied later again into the config variables)
		// additionally the config part is matched to get the options
		$pattern = '/(.*)tinymce\.init\s*\(\s*\{(.*?)\}\s*\)\s*;?(.*)/is';
		preg_match($pattern, $configuration, $matches);

		// add preJS and postJS
		$configurationArray['preJS'] = trim($matches[1]);
		$configurationArray['configurationData'] = trim($matches[2]);
		$configurationArray['postJS'] = trim($matches[3]);

		// split options into an array (four value types: values in quotes, int/booleans, arrays, objects, functions)
//		$pattern = '([^:\[\(\{]+?)\s*:\s*(?:(\[.*?\])|(\{.*\})|(function.*\})|["\']([^"\']*)["|\']\s*|([^,\n]*))[,\n]\n?';
//		preg_match_all('/' . $pattern . '/is', $matches[2] . "\n", $options);
//		for ($i = 0; $i < count($options[1]); ++$i) {
//			if (trim($options[2][$i]) !== '') {
//				// array
//				$configurationArray['arrays'][trim($options[1][$i])] = trim($options[2][$i]);
//			} elseif (trim($options[3][$i]) !== '') {
//				// object
//				$configurationArray['objects'][trim($options[1][$i])] = trim($options[3][$i]);
//			} elseif (trim($options[4][$i]) !== '') {
//				// function
//				$configurationArray['functions'][trim($options[1][$i])] = trim($options[4][$i]);
//			} elseif (trim($options[6][$i]) !== '') {
//				// int/bool
//				$configurationArray['boolAndInt'][trim($options[1][$i])] = trim($options[6][$i]);
//			} else {
//				// quoted value (value can be empty)
//				$configurationArray['strings'][trim($options[1][$i])] = trim($options[5][$i]);
//			}
//		}

		return $configurationArray;
	}

	/**
	 * Adds a basic configuration value to the parsed configuration
	 *
	 * @param string $key
	 * @param mixed $value
	 */
	public function addConfigurationOption($key, $value) {
		if (is_numeric($value)) {
			if (strpos($value, '.')) {
				$value = (float) $value;
			} else {
				$value = (int) $value;
			}
		} elseif (strpos(trim($value), '[') === FALSE && strpos(trim($value), '{') === FALSE &&
			strpos(trim($value), 'function') === FALSE
		) {
			$value = '\'' . $value . '\'';
		}

		$this->tinymceConfiguration['configurationData'] .= ",\n" . $key . ': ' . $value . "\n";
	}
}

?>