<?php
/**
 * Controller for the location views in BP (using mvc terms here)
 */
function bp_em_my_locations() {
	global $bp, $EM_Location;
	if( !is_object($EM_Location) && !empty($_REQUEST['location_id']) ){
		$EM_Location = new EM_Location($_REQUEST['location_id']);
	}
	
	do_action( 'bp_em_my_locations' );
	
	$template_title = 'bp_em_my_locations_title';
	$template_content = 'bp_em_my_locations_content';

	if( !empty($_GET['action']) ){
		switch($_GET['action']){
			case 'edit':
				$template_title = 'bp_em_my_locations_editor_title';
				break;
		}
	}

	add_action( 'bp_template_title', $template_title );
	add_action( 'bp_template_content', $template_content );
	
	/* Finally load the plugin template file. */
	bp_core_load_template( apply_filters( 'bp_core_template_plugin', 'members/single/plugins' ) );
}

function bp_em_my_locations_title() {
	_e( 'My Locations', 'events-manager');
}

/**
 * Determines whether to show location page or locations page, and saves any updates to the location or locations
 * @return null
 */
function bp_em_my_locations_content() {
	em_locate_template('buddypress/my-locations.php', true);
}

function bp_em_my_locations_editor_title() {
	global $EM_Location;
	if( empty($EM_Location) || !is_object($EM_Location) ){
		$title = __('Add Location', 'events-manager');
	}else{
		$title = __('Edit Location', 'events-manager');
	}
}
?>