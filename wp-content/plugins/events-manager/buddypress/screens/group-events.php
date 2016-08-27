<?php
/**
 * Controller for the event views in BP (using mvc terms here)
 */
function bp_em_group_events() {
	global $bp;
	do_action( 'bp_em_group_events' );
	
	//plug into EM admin code (at least for now)
	include_once(EM_DIR.'/admin/em-admin.php');
	
	add_action( 'bp_template_title', 'bp_em_group_events_title' );
	add_action( 'bp_template_content', 'bp_em_group_events_content' );
	
	/* Finally load the plugin template file. */
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'groups/single/plugins' ) );
}

function bp_em_group_events_title() {
	_e( 'Group Events', 'events-manager');
}
/**
 * Determines whether to show event page or events page, and saves any updates to the event or events
 * @return null
 */
function bp_em_group_events_content() {
	em_locate_template('buddypress/group-events.php', true);
}

?>