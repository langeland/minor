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
class Tx_Minor_Hook_CacheHook {

	/**
	 * Clear the internal Less cache on "Clear all caches"
	 *
	 * Will be called by clearCachePostProc hook.
	 *
	 * @param array $parameters
	 * @param t3lib_TCEmain $tcemain
	 * @return void
	 * @author Jon Langeland <jl@typoconsult.dk>
	 */
    public function clearCachePostProc($parameters, $tcemain) {
		if ($parameters['cacheCmd'] === 'all') {
			$this->clearLessCache();
		}
	}

	/**
	 * Clear the internal Less cache on cache menu request
	 *
	 * @return void
	 * @author Jon Langeland <jl@typoconsult.dk>
	 */
	public function ajaxClearCache() {
		$tceMain = t3lib_div::makeInstance('t3lib_TCEmain');
		$tceMain->start(array(), array());
		$tceMain->clear_cacheCmd('pages');

		$this->clearLessCache();
	}

	/**
	 * @return void
	 */
	protected function clearLessCache() {
		t3lib_div::devLog ('Deleting css files in typo3temp/minor/', __CLASS__);
		array_map('unlink', glob(PATH_site . 'typo3temp/minor/*.css'));
	}

}
?>