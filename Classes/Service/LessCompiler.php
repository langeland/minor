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
class Tx_Minor_Service_LessCompiler {
	
	/**
	 * folder for compiled files
	 * @var string $outputfolder
	 */
	protected $outputfolder = 'typo3temp/minor/';
	
	/**
	 * MD5-hash of the configuration. Used for generating the file names.
	 * @var string $configurationHash
	 */
	protected $configurationHash;
	
	/**
	 * @var array $lessVariables
	 */
	protected $lessVariables = array ();


	/**
	 * @param array $lessVariables
	 */
	public function __construct($lessVariables = array()) {
		$this->lessVariables = $lessVariables;
		$this->configurationHash = md5 ( serialize ( $lessVariables ) );
	}


	/**
	 * @return array:
	 */
	public function getLessVariables() {
		return $this->lessVariables;
	}


	/**
	 * @param array $lessVariables
	 */
	public function setLessVariables($lessVariables) {
		$this->lessVariables = $lessVariables;
		$this->configurationHash = md5 ( serialize ( $lessVariables ) );
	}


	/**
	 * @param string $lessInclude
	 * @return string
	 */
	public function handleLessInclude($lessInclude) {
		// t3lib_div::devLog ( 'Analysing lessInclude: ' . $lessInclude, __CLASS__, $severity = 0);
		$lessIncludePath = PATH_site . $lessInclude;
		if (is_file ( $lessIncludePath )) {
			return $this->handleLessIncludeFile ( $lessInclude );
		} elseif (is_dir ( $lessIncludePath )) {
			return $this->handleLessIncludeDirectore ( $lessInclude );
		} else {
			throw new Exception ( 'lessInclude: ' . $lessInclude . ' is not a file or directory' );
		}
	}


	/**
	 * @param string $lessInclude
	 * @return string
	 */
	private function handleLessIncludeFile($lessInclude) {
		if (! file_exists ( $lessInclude )) {
			throw new Exception ( 'Less file not found in: ' . $lessInclude );
		}
		
		$outputFile = $this->outputfolder . substr ( basename ( $lessInclude ), 0, - 5 ) . '_' . md5 ( $this->configurationHash . md5_file ( PATH_site . $lessInclude ) ) . '.css';
		
		if (! file_exists ( $outputFile )) {
			$less = new lessc ();
			$less->setFormatter ( "classic" );
			$less->setVariables ( $this->lessVariables );
			try {
				$cssContent = $less->compileFile ( $lessInclude );
				$dataVar = array (
						'lessInclude' => $lessInclude,
						'lessVariables' => $this->lessVariables 
				);
				t3lib_div::devLog ( 'Calling Lessc copliler', __CLASS__, 0, $dataVar );
			} catch ( Exception $e ) {
				t3lib_div::devLog ( 'lessphp fatal error: ' . $e->getMessage (), __CLASS__, $severity = 3 );
				throw new Exception ( $e->getMessage () );
			}
			
			if (file_put_contents ( PATH_site . $outputFile, $cssContent ) === FALSE) {
				t3lib_div::devLog ( 'Error writing css file: ' . PATH_site . $outputFile, __CLASS__, $severity = 3 );
				throw new Exception ( 'Error writing css file: ' . PATH_site . $outputFile );
			}
			return $outputFile;
		} else {
			// t3lib_div::devLog ( 'File exixts, skipping: ' . basename ( $lessInclude ), __CLASS__, $severity = 1 );
			return $outputFile;
		}
	}


	private function handleLessIncludeDirectore($lessPath) {
		t3lib_div::devLog ( 'lessInclude: ' . $lessPath . ' is a directory', __CLASS__, $severity = 2, array (
				$lessPath 
		) );
		
		// TODO: Handle directories
	}

}
?>