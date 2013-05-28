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
 * Clear cache hook to clear Less cache on clear all cache.
 *
 * @package minor
 */
class Tx_Minor_Hook_Pagerenderer {


	/**
	 * stdWrapPreProcess
	 *
	 * @param array $configuration
	 * @param t3lib_PageRenderer $parentObject
	 * @return void
	 */
	public function preProcess($configuration, t3lib_PageRenderer $parentObject) {
		if (! is_array ( $configuration ['cssFiles'] )) {
			t3lib_div::devLog ( 'No files in includeCSS', __CLASS__ );
			return;
		}
		

		$globalVariables = array_merge ( ( array ) $GLOBALS ['TSFE']->pSetup ['config.'] ['less.'] ['variables.'], ( array ) $GLOBALS ['TSFE']->pSetup ['less.'] ['variables.'] );
		
		if (FALSE) {
			Tx_Extbase_Utility_Debugger::var_dump ( $configuration, '$configuration' );
			Tx_Extbase_Utility_Debugger::var_dump ( $GLOBALS ['TSFE'], '$GLOBALS [\'TSFE\']' );
			Tx_Extbase_Utility_Debugger::var_dump ( $GLOBALS ['TSFE']->pSetup, '$GLOBALS [\'TSFE\']->pSetup' );
			Tx_Extbase_Utility_Debugger::var_dump ( $globalVariables, '$globalVariables' );
			
			die ();
		}
		

		/**
		 * @var Tx_Minor_Service_LessCompiler
		 */
		$lessCompiler = t3lib_div::makeInstance ( 'Tx_Minor_Service_LessCompiler' );
		

		foreach ( $configuration ['cssFiles'] as $lessFile => $conf ) {
			$localVariables = array ();
			$compileVariables = array ();
			

			if (t3lib_div::compat_version ( 4.6 )) {
				$pathinfo = pathinfo ( $conf ['file'] );
			} else {
				$pathinfo = pathinfo ( $lessFile );
			}
			
			if ($pathinfo ['extension'] == 'less') {
				// t3lib_div::devLog ( 'Analysing includeCSS: ' . $lessFile );
				
				// search settings for less file
				foreach ( $GLOBALS ['TSFE']->pSetup ['includeCSS.'] as $key => $subconf ) {
					if ($GLOBALS ['TSFE']->pSetup ['includeCSS.'] [$key] == $lessFile) {
						if (isset ( $GLOBALS ['TSFE']->pSetup ['includeCSS.'] [$key . '.'] ['variables.'] )) {
							$localVariables = $GLOBALS ['TSFE']->pSetup ['includeCSS.'] [$key . '.'] ['variables.'];
						}
					}
				}
				
				$compileVariables = array_merge ( $globalVariables, $localVariables );
				$lessCompiler->setLessVariables ( $compileVariables );
				
				try {
					if ($cssFile = $lessCompiler->handleLessInclude ( $lessFile )) {
						$dataVar = array (
								'conf' => $conf,
								'globalVariables' => $globalVariables,
								'localVariables' => $localVariables,
								'compileVariables' => $compileVariables,
								'outputFile' => $cssFile 
						);
						t3lib_div::devLog ( 'Compiling includeCSS: ' . basename ( $lessFile ), __CLASS__, - 1, $dataVar );
					} else {
						$dataVar = array (
								'conf' => $conf,
								'globalVariables' => $globalVariables,
								'localVariables' => $localVariables,
								'compileVariables' => $compileVariables,
								'outputFile' => $cssFile 
						);
						t3lib_div::devLog ( 'File exixts, skipping: ' . basename ( $lessFile ), __CLASS__, - 1, $dataVar );
					}
					
					if (t3lib_div::compat_version ( 4.6 )) {
						$pathinfo = pathinfo ( $conf ['file'] );
					} else {
						$configuration ['cssFiles'] [$cssFile] = $configuration ['cssFiles'] [$lessFile];
						unset ( $configuration ['cssFiles'] [$lessFile] );
					}
					
					$configuration ['cssFiles'] [$lessFile] ['file'] = $cssFile;
				} catch ( Exception $e ) {
					t3lib_div::devLog ( $e->getMessage (), __CLASS__, $severity = 3 );
				}
			}
		}
	}

}

?>