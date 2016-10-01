<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Custom Implementation Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// require_once 'pop-wpapi/load.php';
// require_once 'pop-coreprocessors/load.php';
// require_once 'events-manager-popprocessors/load.php';
require_once 'poptheme-wassup/load.php';
require_once 'poptheme-wassup-categoryprocessors/load.php';	
require_once 'poptheme-wassup-sectionprocessors/load.php';
// require_once 'poptheme-wassup-hosts/load.php';
// require_once 'aryo-activity-log-pop/load.php';

// if (defined('POP_AWS_VERSION')) {
// 	require_once 'pop-aws/load.php';
// }
if (class_exists('EM_Event'))	
	require_once 'events-manager/load.php';
	
if (defined('POP_USERAVATAR_AWS_VERSION')) {
	require_once 'pop-useravatar-aws/load.php';		
}

if (class_exists('User_Role_Editor'))		
	require_once 'user-role-editor/load.php';
