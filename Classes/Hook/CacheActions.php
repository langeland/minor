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

require_once (PATH_typo3 . 'interfaces/interface.backend_cacheActionsHook.php');

/**
 * Clear cache menu for minor
 *
 * @package	minor
 * @author Jon Langeland <jl@typoconsult.dk>
 */
class Tx_Minor_Hook_CacheActions implements backend_cacheActionsHook {


	/**
	 * modifies CacheMenuItems array
	 *
	 * @param	array	array of CacheMenuItems
	 * @param	array	array of AccessConfigurations-identifiers (typically  used by userTS with options.clearCache.identifier)
	 * @return
	 */
	public function manipulateCacheActions(&$cacheActions, &$optionValues) {
		if ($GLOBALS ['BE_USER']->isAdmin ()) {
			$title = $GLOBALS ['LANG']->sL ( 'LLL:EXT:minor/Resources/Private/Language/locallang.xml:Tx_Minor_Hook_CacheActions_ClearLessCache', TRUE );
			$cacheActions [] = array (
					'id' => 'minor',
					'title' => $title,
					'href' => 'ajax.php?ajaxID=tx_tcless::clearLessCache',
					'icon'  => '<img src="' . t3lib_extMgm::extRelPath('minor') . 'Resources/Public/Icons/ClearLessCache.png" width="16" height="16" title="'.htmlspecialchars($title).'" alt="'.htmlspecialchars($title).'" />'
					
			);
			$optionValues [] = 'clearLessCache';
		}
	}

}
?>