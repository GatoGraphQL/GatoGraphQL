<?php

	/*
	 * General tab content
	 */
	 
	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
	
	require_once( GDE_PLUGIN_DIR . "libs/lib-profile.php" );
	gde_profile_form();
?>
