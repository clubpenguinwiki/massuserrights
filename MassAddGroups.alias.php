<?php
/**
 * Aliases for Special:ImportUsers
 *
 * @file
 * @ingroup Extensions
 */

$specialPageAliases = array();

/** English
 * @author Jon Harald Søby
 */
$specialPageAliases['en'] = array(
	'MassUserRights' => array( 'MassAddGroups', 'MassRemoveGroups', 'MassUserRights' ),
);

/**
 * For backwards compatibility with MediaWiki 1.15 and earlier.
 */
$aliases =& $specialPageAliases;