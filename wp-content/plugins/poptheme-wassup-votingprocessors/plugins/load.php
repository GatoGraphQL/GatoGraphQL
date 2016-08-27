<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

if (class_exists('EM_Event'))	
	require_once 'events-manager/load.php';

if (class_exists('User_Role_Editor'))
	require_once 'user-role-editor/load.php';

if (defined('AAL_POP_VERSION')) {
	require_once 'aryo-activity-log-pop/load.php';		
}