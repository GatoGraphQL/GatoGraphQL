<?php

// High priority: allow the Theme and other plug-ins to set the values in advance.
add_action('init', 'pop_cmsmodel_init_constants', 10000);
function pop_cmsmodel_init_constants() {
	
	require_once 'constants.php';
}
