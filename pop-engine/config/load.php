<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
add_action('init', 'popengine_init_constants', 10000);
function popengine_init_constants() {
	
	require_once 'constants.php';
}
