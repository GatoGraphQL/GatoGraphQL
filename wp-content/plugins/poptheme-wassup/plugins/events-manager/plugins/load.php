<?php

require_once 'pop-frontendengine/load.php';

if (defined('QTX_VERSION'))
	require_once 'qtranslate-x/load.php';

if (class_exists('coauthors_plus'))	
	require_once 'co-authors-plus/load.php';

if (class_exists('User_Role_Editor'))
	require_once 'user-role-editor/load.php';