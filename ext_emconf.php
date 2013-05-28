<?php

########################################################################
# Extension Manager/Repository config file for ext "minor".
#
# Auto generated 08-01-2013 14:49
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'S9 // Minor LESS for TYPO3',
	'description' => 'An easy to use extbase extension for using LESScss in TYPO3. Based on leafo.net LESS-PHP-compiler. It is also possible to include compiled files and delete unused/old compiled files automaticaly.',
	'category' => 'plugin',
	'shy' => 0,
	'version' => '1.0.0',
	'dependencies' => '',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'stable',
	'uploadfolder' => 0,
	'createDirs' => 'typo3temp/minor',
	'modify_tables' => '',
	'clearcacheonload' => 0,
	'lockType' => '',
	'author' => 'Jon Klixbüll Langeland',
	'author_email' => 'jon@langeland.info',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'constraints' => array(
		'depends' => array(
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'_md5_values_when_last_written' => 'a:19:{s:21:"ext_conf_template.txt";s:4:"1d1f";s:12:"ext_icon.gif";s:4:"c1f8";s:12:"ext_icon.png";s:4:"08ff";s:17:"ext_localconf.php";s:4:"c530";s:14:"ext_tables.php";s:4:"e87d";s:37:"Classes/Controller/MinorController.php";s:4:"7034";s:29:"Classes/Hook/CacheActions.php";s:4:"d0a7";s:26:"Classes/Hook/CacheHook.php";s:4:"768b";s:29:"Classes/Hook/Pagerenderer.php";s:4:"a553";s:32:"Classes/Service/LessCompiler.php";s:4:"7986";s:38:"Configuration/TypoScript/constants.txt";s:4:"632e";s:34:"Configuration/TypoScript/setup.txt";s:4:"e17c";s:40:"Resources/Private/Language/locallang.xml";s:4:"eb3d";s:41:"Resources/Private/Lib/lessc.inc-0.3.3.php";s:4:"1a74";s:43:"Resources/Private/Lib/lessc.inc-0.3.4-2.php";s:4:"db5a";s:35:"Resources/Private/Lib/lessc.inc.php";s:4:"2878";s:42:"Resources/Private/Templates/Less/Base.html";s:4:"d41d";s:41:"Resources/Public/Icons/ClearLessCache.png";s:4:"08ff";s:37:"Resources/Public/Js/less-1.3.0.min.js";s:4:"ca73";}',
	'suggests' => array(
	),
);

?>