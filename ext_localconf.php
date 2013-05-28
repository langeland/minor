<?php
if (! defined ( 'TYPO3_MODE' )) {
	die ( 'Access denied.' );
}
/*
Tx_Extbase_Utility_Extension::configurePlugin ( $_EXTKEY, 'Less', array(
		'Less' => 'base'
), // non-cacheable actions
array(
		'Less' => 'Base'
) );
*/



if (TYPO3_MODE === 'FE') {
	// Register page renderer hook.
	$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_pagerenderer.php']['render-preProcess'][] = 'EXT:minor/Classes/Hook/Pagerenderer.php:Tx_Minor_Hook_Pagerenderer->preProcess';
}



if (TYPO3_MODE === 'BE') {
	$extConf = unserialize ( $TYPO3_CONF_VARS['EXT']['extConf']['minor'] );
	if (! isset ( $extConf['enableClearAllCacheHook'] ) || ( boolean ) $extConf['enableClearAllCacheHook']) {
		// Remove Less files if all caches are cleared
		$TYPO3_CONF_VARS['SC_OPTIONS']['t3lib/class.t3lib_tcemain.php']['clearCachePostProc']['minor'] = 'EXT:minor/Classes/Hook/CacheHook.php:&Tx_Minor_Hook_CacheHook->clearCachePostProc';
	} else {
		$TYPO3_CONF_VARS['SC_OPTIONS']['additionalBackendItems']['cacheActions'][] = 'EXT:minor/Classes/Hook/CacheActions.php:&Tx_Minor_Hook_CacheActions';
		$TYPO3_CONF_VARS['BE']['AJAX']['tx_minor::clearLessCache'] = 'EXT:minor/Classes/Hook/CacheHook.php:&Tx_Minor_Hook_CacheHook->ajaxClearCache';
	}
}

?>