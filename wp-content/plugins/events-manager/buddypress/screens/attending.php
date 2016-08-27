<?php
/**
 * bp_em_screen_two()
 *
 * Sets up and displays the screen output for the sub nav item "em/screen-two"
 */
function bp_em_attending() {
	global $bp;
	/**
	 * If the user has not Accepted or Rejected anything, then the code above will not run,
	 * we can continue and load the template.
	 */
	do_action( 'bp_em_attending' );

	add_action( 'bp_template_title', 'bp_em_attending_title' );
	add_action( 'bp_template_content', 'bp_em_attending_content' );

	/* Finally load the plugin template file. */
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

	function bp_em_attending_title() {
		_e( 'Events I\'m Attending', 'events-manager');
	}

	function bp_em_attending_content() {
		//We can use the same template as the public user interface for non bp sites
		em_my_bookings();
	}