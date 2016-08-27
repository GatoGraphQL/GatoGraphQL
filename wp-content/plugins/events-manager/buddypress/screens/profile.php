<?php
/**
 * bp_em_screen_one()
 *
 * Sets up and displays the screen output for the sub nav item "em/screen-one"
 */
function bp_em_events() {
	global $bp, $EM_Notices;
	
	if( bp_is_my_profile() ){
		$EM_Notices->add_info( __('You are currently viewing your public page, this is what other users will see.', 'events-manager') );
	}

	/* Add a do action here, so your component can be extended by others. */
	do_action( 'bp_em_events' );

	add_action( 'bp_template_title', 'bp_em_events_title' );
	add_action( 'bp_template_content', 'bp_em_events_content' );
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
	//bp_core_load_template( apply_filters( 'bp_em_template_screen_one', 'em/screen-one' ) );
}
	/***
	 * The second argument of each of the above add_action() calls is a function that will
	 * display the corresponding information. The functions are presented below:
	 */
	function bp_em_events_title() {
		_e( 'Events', 'events-manager');
	}

	function bp_em_events_content() {
		em_locate_template('buddypress/profile.php',true);
	}