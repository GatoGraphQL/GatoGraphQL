<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Custom Implementation Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

if (class_exists("RGForms"))	
	require_once 'gravityforms/load.php';

require_once 'poptheme-wassup-categoryprocessors/load.php';	
require_once 'poptheme-wassup-sectionprocessors/load.php';
if (class_exists('EM_Event'))	
	require_once 'events-manager/load.php';
	
if (class_exists('User_Role_Editor'))		
	require_once 'user-role-editor/load.php';