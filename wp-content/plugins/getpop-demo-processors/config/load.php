<?php

/**---------------------------------------------------------------------------------------------------------------
 * Website Implementations
 * ---------------------------------------------------------------------------------------------------------------*/
// Priority given to constants defined for each environmend (dev, prod)
add_action('init', 'getpopdemo_popprocessors_init_constants', 100);
function getpopdemo_popprocessors_init_constants() {
	
	require_once 'constants.php';
}
