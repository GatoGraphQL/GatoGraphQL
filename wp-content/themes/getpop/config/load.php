<?php

/**---------------------------------------------------------------------------------------------------------------
 * Website Implementations
 * ---------------------------------------------------------------------------------------------------------------*/
// Priority given to constants defined for each environmend (dev, prod)
// require_once PoP_ServerUtils::get_environment().'.config.php';
// require_once 'uris.php';
require_once 'config.php';
add_action('init', 'getpop_demo_popprocessors_init_constants', 100);
function getpop_demo_popprocessors_init_constants() {
	
	if (GetPoP_Utils::is_demo()) {
		require_once 'constants-demo.php';
	}
}
