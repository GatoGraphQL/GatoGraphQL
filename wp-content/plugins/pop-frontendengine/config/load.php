<?php
/**---------------------------------------------------------------------------------------------------------------
 * Global Variables and Configuration
 * ---------------------------------------------------------------------------------------------------------------*/

// High priority: allow the Theme and other plug-ins to set the values in advance.
add_action('init', 'pop_frontendengine_init_constants', 100);
function pop_frontendengine_init_constants() {
	
	require_once 'constants.php';
}
