<?php

/* * *************************************************************
 *  Copyright notice
 *
 *  (c) 2012
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
 * ************************************************************* */

/**
 *
 *
 * @package minor
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
require_once (t3lib_extMgm::extPath ( 'minor' ) . 'Resources/Private/Lib/lessc.inc.php');
class Tx_Minor_Controller_LessController extends Tx_Extbase_MVC_Controller_ActionController {
	
	/**
	 * configuration array from constants
	 * @var array $configuration
	 */
	protected $configuration;
	
	/**
	 * MD5-hash of the configuration. Used for generating the file names.
	 * @var string $configurationHash
	 */
	protected $configurationHash;
	
	/**
	 * @var array $lessVariables
	 */
	protected $lessVariables;


	/**
	 * action base
	 *
	 */
	public function baseAction() {
		
		$setup = $this->configurationManager->getConfiguration(Tx_Extbase_Configuration_ConfigurationManager::CONFIGURATION_TYPE_FULL_TYPOSCRIPT);
		$pluginName = 'tx_'.substr($this->configurationManager->getContentObject()->data['list_type'], 0, strpos($this->configurationManager->getContentObject()->data['list_type'], '_'));
		$pluginSetup = $setup['plugin.'][$pluginName.'.'];
		
		if(key_exists('includeCSS.', $pluginSetup)){
			$this->configuration ['globelVariables'] = array_merge ( ( array ) $GLOBALS ['TSFE']->pSetup ['config.'] ['less.'] ['variables.'], ( array ) $GLOBALS ['TSFE']->pSetup ['less.'] ['variables.'] );
			$lessCompiler = t3lib_div::makeInstance ( 'Tx_Minor_Service_LessCompiler' );
			foreach($pluginSetup['includeCSS.'] as $includeKey => $lessFile){
				if(substr($includeKey, -1) == '.')
					continue;
				$this->configuration['localVariables'] = $pluginSetup['includeCSS.'][$includeKey.'.']['variables.'];
				foreach($this->configurationManager->getContentObject()->data as $k => $v){
					if($v != ''){
						$this->configuration['dataVariables'][$k] = $v;
					}
				}
				$this->configuration['compileVariables'] = array_merge( (array) $this->configuration['dataVariables'], (array) $this->configuration['globelVariables'], (array)$this->configuration['localVariables']);
				$this->configuration['compileVariables']['cuid'] = $this->configurationManager->getContentObject()->data['uid'];
				$this->configurationHash = md5 ( serialize ( $this->configuration ) );
				/*
				t3lib_div::devLog ( 'Logging configuration', 'Tx_Minor_Controller_LessController', $severity = 0, array (
						'pluginName' => $pluginName,
						'pluginSetup' => $pluginSetup,
						'Globel lessVariables' => $this->configuration ['globelVariables'],
						'localLessVariables' => $this->configuration['localVariables'],
						'compileVariables' => $this->configuration['compileVariables'],
						'ConfigurationHash' => $this->configurationHash,
						'includeKey' => $includeKey,
						'file' => $lessFile
				) );
				*/
				$lessCompiler->setLessVariables ( $this->configuration['compileVariables'] );
				
				try {
					if ($cssFile = $lessCompiler->handleLessInclude ( $lessFile )) {
						$dataVar = array (
								'globalVariables' => $this->configuration ['globelVariables'],
								'localVariables' => $this->configuration ['localVariables'],
								'compileVariables' => $this->configuration ['compileVariables'],
								'outputFile' => $cssFile 
						);
						t3lib_div::devLog ( 'Compiling includeCSS: ' . basename ( $lessFile ), __CLASS__, - 1, $dataVar );
					} else {
						$dataVar = array (
								'globalVariables' => $this->configuration ['globelVariables'],
								'localVariables' => $this->configuration ['localVariables'],
								'compileVariables' => $this->configuration ['compileVariables'],
								'outputFile' => $cssFile 
						);
						t3lib_div::devLog ( 'File exixts, skipping: ' . basename ( $lessFile ), __CLASS__, - 1, $dataVar );
					}
					$this->addCssFile ( $cssFile );
				} catch ( Exception $e ) {
					t3lib_div::devLog ( $e->getMessage (), __CLASS__, $severity = 3 );
				}
			}
		}
		return;
	}


	private function addCssFile($outputFile) {
		t3lib_div::devLog ( 'Adding CSS file to page', 'Tx_Minor_Controller_LessController', $severity = 0, array (
				'Output File' => $outputFile 
		) );
		
		// TODO: Inserting fails...
		// $GLOBALS['TSFE']->getPageRenderer ()->addCssFile ( '/'.$outputFile );
		
		// $GLOBALS ['TSFE']->additionalHeaderData ['501'] = '<link media="all" rel="stylesheet" type="text/css" href="/' . $outputFile . '" />';
		
		$GLOBALS ['TSFE']->getPageRenderer ()->addCssFile ( $outputFile, $rel = 'stylesheet', $media = $tsOptions ['media'] ? $tsOptions ['media'] : 'all', $title = $tsOptions ['title'] ? $tsOptions ['title'] : '', $compress = $tsOptions ['compress'] >= '0' ? ( boolean ) $tsOptions ['compress'] : TRUE, $forceOnTop = $tsOptions ['forceOnTop'] >= '0' ? ( boolean ) $tsOptions ['forceOnTop'] : TRUE, $allWrap = $tsOptions ['allWrap'] ? $tsOptions ['allWrap'] : '', $excludeFromConcatenation = $tsOptions ['excludeFromConcatenation'] >= '0' ? ( boolean ) $tsOptions ['excludeFromConcatenation'] : FALSE );
	}

}

?>