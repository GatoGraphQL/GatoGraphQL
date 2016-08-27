<?php
/**
 * Controller for the event views in BP (using mvc terms here)
 */
function bp_em_my_events() {
	global $bp, $EM_Event;
	if( !is_object($EM_Event) && !empty($_REQUEST['event_id']) ){
		$EM_Event = new EM_Event($_REQUEST['event_id']);
	}
	
	do_action( 'bp_em_my_events' );
	
	$template_title = 'bp_em_my_events_title';
	$template_content = 'bp_em_my_events_content';

	if( !empty($_GET['action']) ){
		switch($_GET['action']){
			case 'edit':
				$template_title = 'bp_em_my_events_editor_title';
				break;
		}
	}

	add_action( 'bp_template_title', $template_title );
	add_action( 'bp_template_content', $template_content );
	
	/* Finally load the plugin template file. */
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function bp_em_my_events_title() {
	_e( 'My Events', 'events-manager');
}

/**
 * Determines whether to show event page or events page, and saves any updates to the event or events
 * @return null
 */
function bp_em_my_events_content() {
	em_locate_template('buddypress/my-events.php', true);
}

function bp_em_my_events_editor_title() {
	global $EM_Event;
	if( is_object($EM_Event) ){
		if($EM_Event->is_recurring()){
			echo __( "Reschedule Events", 'events-manager')." '{$EM_Event->event_name}'";
		}else{
			echo __( "Edit Event", 'events-manager') . " '" . $EM_Event->event_name . "'";
		}
	}else{
		_e( 'Add Event', 'events-manager');
	}
}
?>