<?php
/**
 * bp_em_screen_two()
 *
 * Sets up and displays the screen output for the sub nav item "em/screen-two"
 */
function bp_em_my_bookings() {
	global $bp, $EM_Event;
	
	//assume any notifications here are considered viewed via this page
	if( function_exists('bp_notifications_delete_notifications_by_type') ){
		bp_notifications_delete_notifications_by_type(get_current_user_id(), 'events','pending_booking');
		bp_notifications_delete_notifications_by_type(get_current_user_id(), 'events','confirmed_booking');
		bp_notifications_delete_notifications_by_type(get_current_user_id(), 'events','cancelled_booking');
	}else{
		bp_core_delete_notifications_by_type(get_current_user_id(), 'events','pending_booking');
		bp_core_delete_notifications_by_type(get_current_user_id(), 'events','confirmed_booking');
		bp_core_delete_notifications_by_type(get_current_user_id(), 'events','cancelled_booking');
	}
	
	em_load_event();
	/**
	 * If the user has not Accepted or Rejected anything, then the code above will not run,
	 * we can continue and load the template.
	 */
	do_action( 'bp_em_my_bookings' );

	add_action( 'bp_template_title', 'bp_em_my_bookings_title' );
	add_action( 'bp_template_content', 'bp_em_my_bookings_content' );

	/* Finally load the plugin template file. */
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function bp_em_my_bookings_title() {
	_e( 'My Event Bookings', 'events-manager');
}

function bp_em_my_bookings_content() {
	em_locate_template('buddypress/my-bookings.php',true);
}