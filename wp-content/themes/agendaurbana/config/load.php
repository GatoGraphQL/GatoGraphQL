<?php

/**---------------------------------------------------------------------------------------------------------------
 * Website Implementations
 * ---------------------------------------------------------------------------------------------------------------*/
// Priority given to constants defined for each environmend (dev, prod)
// require_once PoP_ServerUtils::get_environment().'.config.php';
require_once 'config.php';
add_action('init', 'agendaurbana_popprocessors_init_constants', 100);
function agendaurbana_popprocessors_init_constants() {
	
	require_once 'constants.php';
}