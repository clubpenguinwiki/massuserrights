<?php
/**
 *
 * @subpackage Extensions
 *
 * @author Rouslan Zenetl
 * @author Yuriy Ilkiv
 * @license You are free to use this extension for any reason and mutilate it to your heart's liking.
 */

if (!defined('MEDIAWIKI')) die();

$wgExtensionCredits['specialpage'][] = array(
	'path' => __FILE__,
	'name' => 'MassAddGroups',
	'author' => array('thepeskygeek'),
	'url' => 'http://dev.clubpenguinwiki.info/wiki/MassUserRights',
	'descriptionmsg' => 'massuserrights-desc',
);


$dir = dirname(__FILE__) . '/';
$wgSpecialPages['MassUserRights'] = 'SpecialMassAddGroups';
$wgSpecialPageGroups['MassUserRighys'] = 'users';
$wgAutoloadClasses['SpecialMassAddGroups'] = $dir . 'MassAddGroups_body.php';
$wgExtensionMessagesFiles['MassAddGroups'] = $dir . 'MassAddGroups.i18n.php';
$wgExtensionAliasesFiles['MassAddGroups'] = $dir . 'MassAddGroups.alias.php';
