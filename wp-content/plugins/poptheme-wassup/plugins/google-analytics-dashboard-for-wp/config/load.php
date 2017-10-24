<?php
/**---------------------------------------------------------------------------------------------------------------
 * Global Variables and Configuration
 * ---------------------------------------------------------------------------------------------------------------*/

// High priority: allow the Theme and other plug-ins to set the values in advance.
add_action('init', 'poptheme_wassup_gadwp_init_constants', 50);
function poptheme_wassup_gadwp_init_constants() {
	
	require_once 'constants.php';
}