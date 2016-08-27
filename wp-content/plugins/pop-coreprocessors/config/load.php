<?php
/**---------------------------------------------------------------------------------------------------------------
 * Global Variables and Configuration
 * ---------------------------------------------------------------------------------------------------------------*/

// High priority: allow the Theme and other plug-ins to set the values in advance.
add_action('init', 'pop_coreprocessors_init_constants', 100);
function pop_coreprocessors_init_constants() {
	
	require_once 'constants.php';
}

require_once 'config.php';
