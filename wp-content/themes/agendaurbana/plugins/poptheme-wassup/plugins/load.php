<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Custom Implementation Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

if (class_exists('EM_Event'))	
	require_once 'events-manager/load.php';

if (class_exists('MLAData'))		
	require_once 'media-library-assistant/load.php';

if (class_exists("RGForms"))	
	require_once 'gravityforms/load.php';

if (defined('POP_SERVICEWORKERS_INITIALIZED')) {
	require_once 'pop-serviceworkers/load.php';		
}
// if (class_exists("JWP6_Plugin"))	
// 	require_once 'jw-player-plugin-for-wordpress/load.php';