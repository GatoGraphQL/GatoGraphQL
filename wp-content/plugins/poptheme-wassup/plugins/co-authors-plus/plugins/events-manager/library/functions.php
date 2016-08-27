<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Co-Authors Plus integration with Events Manager
 *
 * ---------------------------------------------------------------------------------------------------------------*/

/**---------------------------------------------------------------------------------------------------------------
 * Override can_manage allowing co-authors
 * ---------------------------------------------------------------------------------------------------------------*/

// Override the can_manage function from em-event and em-object: this one contrasts against owner (which is unique) instead of (multiple) authors, so it does not co-authors to edit the event
function gd_em_cap_can_manage($event, $owner_capability = false, $admin_capability = false, $user_to_check = false ){
	
	// copied from events-manager/classes/em-object.php
	// Then replaced `owner` with `author`
	global $em_capabilities_array;
	if( $user_to_check ){
		$user = new WP_User($user_to_check);
		if( empty($user->ID) ) $user = false;
	} 
	
	// $authors = apply_filters('gd_em_event_authors', array($event->post_author), $event);
	$authors = array_map('gd_get_the_id', get_coauthors($event->post_id));
	$is_author = ( (in_array(get_current_user_id(), $authors)) || (!empty($user) && in_array($user->ID, $authors)) );

	//now check capability
	$can_manage = false;
	if( $is_author && (current_user_can($owner_capability) || (!empty($user) && $user->has_cap($owner_capability))) ){
		//user owns the object and can therefore manage it
		$can_manage = true;
	}
	
	return $can_manage;	
}

// Override the can_manage function from em-event and em-object: this one contrasts against owner (which is unique) instead of (multiple) authors, so it does not co-authors to edit the event
add_filter('em_event_can_manage', 'gd_em_cap_event_can_manage', 10, 5);
function gd_em_cap_event_can_manage( $can_manage, $event, $owner_capability = false, $admin_capability = false, $user_to_check = false ){
	
	if ($can_manage) return true;
	
	return gd_em_cap_can_manage($event, $owner_capability, $admin_capability, $user_to_check);
}

add_filter('gd_em_can_manage_add_error', 'gd_em_can_manage_add_error', 10, 5);
function gd_em_can_manage_add_error( $add_error, $event, $owner_capability = false, $admin_capability = false, $user_to_check = false ){
	
	if (!$add_error) return false;
	
	return !gd_em_cap_can_manage($event, $owner_capability, $admin_capability, $user_to_check);
}


/**---------------------------------------------------------------------------------------------------------------
 * Include in the list of My Events also the co-authors events
 * ---------------------------------------------------------------------------------------------------------------*/    

add_filter('gd_em_events_admin_args', 'gd_em_events_admin_args_add_coauthors', 10, 2);
add_filter('gd_em_events_admin_pending_count_args', 'gd_em_events_admin_args_add_coauthors', 10, 2);
add_filter('gd_em_events_admin_draft_count_args', 'gd_em_events_admin_args_add_coauthors', 10, 2);
add_filter('gd_em_events_admin_past_count_args', 'gd_em_events_admin_args_add_coauthors', 10, 2);
add_filter('gd_em_events_admin_future_count_args', 'gd_em_events_admin_args_add_coauthors', 10, 2);
function gd_em_events_admin_args_add_coauthors($args, $event) {

	$post_id = gd_user_event_post_ids();

	if (!empty($post_id)) {
		
		$args['owner'] = false;
		$args['post_id'] = $post_id;
	}	
	
	return $args;
}

add_filter('gd_em_bookings_events_table_args', 'gd_em_get_bookings_limit_to_coauthored_events');
add_filter('gd_em_get_bookings_person_args', 'gd_em_get_bookings_limit_to_coauthored_events');
function gd_em_get_bookings_limit_to_coauthored_events($args) {

//	if (is_admin())
//		return $args;

	// Limit which events they can see: the ones they have access to through co-authoring
	$post_ids = gd_user_event_post_ids();

	if (!empty($post_ids)) {
	
		// Convert the list of post_ids to event_ids
		$event_ids = array();
		foreach ($post_ids as $post_id) {
		
			$event = em_get_event($post_id, 'post_id');
			$event_ids[] = $event->event_id;
		}
		$args['event'] = $event_ids;
		
	}	
	
	return $args;
}

add_filter('gd_em_bookings_events_table_args', 'gd_em_get_bookings_no_owner_args');
add_filter('gd_em_get_bookings_person_args', 'gd_em_get_bookings_no_owner_args');
add_filter('gd_em_get_bookings_event_args', 'gd_em_get_bookings_no_owner_args');
add_filter('gd_em_get_bookings_args', 'gd_em_get_bookings_no_owner_args');
function gd_em_get_bookings_no_owner_args($args) {


	// Allow for co-authors: owner = false => the profiles can see also events which are owned by others
	$args['owner'] = false;	
	
	return $args;
}

/**
 * When doing a .csv export, there is a bug from EM, so we need to get the person_id from the request and put it in the args to filter by person
 * Priority 20: do it after the other hooks, to give them the chance to execute first
 */
add_filter('gd_em_get_bookings_args', 'gd_em_get_bookings_csv_person_args', 20);
function gd_em_get_bookings_csv_person_args($args) {

	if (!empty($_REQUEST['person_id'])) {
	
		$args['person'] = $_REQUEST['person_id'];
	}

	// Check if the limiting by event was done, if not do it
	if (!isset($args['event'])) {
	
		$args = gd_em_get_bookings_limit_to_coauthored_events($args);
	}
	
	if (!empty($_REQUEST['event_id']) && !isset($args['event'])) {
	
		$args['event'] = $_REQUEST['event_id'];
	}	
	
	return $args;
}


// Allow other profiles to see co-authors bookings
add_filter('em_bookings_build_sql_conditions', 'gd_em_bookings_build_sql_conditions', 10, 2);
function gd_em_bookings_build_sql_conditions($conditions, $args) {

	// copied from function build_sql_conditions in em-bookings.php

	// Restrain the following:
	// 1. Person: only the person from the link	
	if( is_numeric($args['person']) ){
		$conditions['person'] = EM_BOOKINGS_TABLE.'.person_id='.$args['person'];
	}
	
	return $conditions;
}

