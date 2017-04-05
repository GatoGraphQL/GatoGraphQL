<?php

if (class_exists('EM_Event'))	
	require_once 'events-manager/load.php';

if (class_exists('User_Role_Editor'))
	require_once 'user-role-editor/load.php';

if (defined('POP_CDNCORE_INITIALIZED')) {
	require_once 'pop-cdn-core/load.php';		
}
