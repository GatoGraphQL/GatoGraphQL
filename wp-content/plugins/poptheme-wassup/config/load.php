<?php
/**---------------------------------------------------------------------------------------------------------------
 * Global Variables and Configuration
 * ---------------------------------------------------------------------------------------------------------------*/

require_once 'config.php';

// High priority: allow the Theme and other plug-ins to set the values in advance.
add_action('init', 'poptheme_wassup_init_constants', 100);
function poptheme_wassup_init_constants() {
	
	require_once 'constants.php';
}
