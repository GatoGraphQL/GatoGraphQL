<?php

//-------------------------------------------------------------------------------------
// Load Plugin-specific Libraries
//-------------------------------------------------------------------------------------

require_once 'pop-frontendengine/load.php';
require_once 'pop-coreprocessors/load.php';

if (defined('QTX_VERSION'))
	require_once 'qtranslate-x/load.php';

// if (function_exists('wpsupercache_site_admin') && defined('WP_CACHE') && WP_CACHE)
// 	require_once 'wp-super-cache/load.php';	

if (defined('POP_CDNCORE_INITIALIZED')) {
	require_once 'pop-cdn-core/load.php';		
}

if (defined('POP_MULTIDOMAIN_INITIALIZED')) {
	require_once 'pop-multidomain/load.php';		
}