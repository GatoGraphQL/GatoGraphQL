<?php
/**---------------------------------------------------------------------------------------------------------------
 * Global Variables and Configuration
 * ---------------------------------------------------------------------------------------------------------------*/

// High priority: allow the Theme and other plug-ins to set the values in advance.
add_action('init', 'genericforms_gf_init_constants', 50);
function genericforms_gf_init_constants() {
	
	require_once 'constants.php';
}
