<?php
// This file handles hooks/filter requiring activity stream publications

/**
 * bp_em_record_activity()
 *
 * If the activity stream component is installed, this function will record activity items for your
 * component.
 */
function bp_em_record_activity( $args = '' ) {
	if ( !function_exists( 'bp_activity_add' ) )
		return false;

	$defaults = array(
		'id' => false,
		'user_id' => '',
		'action' => '',
		'content' => '',
		'primary_link' => '',
		'component' => 'events-manager',
		'type' => false,
		'item_id' => false,
		'secondary_item_id' => false,
		'recorded_time' => gmdate( "Y-m-d H:i:s" ),
		'hide_sitewide' => false
	);

	$r = wp_parse_args( $args, $defaults );
	extract( $r );
	return bp_activity_add( array( 'id' => $id, 'user_id' => $user_id, 'action' => $action, 'content' => $content, 'primary_link' => $primary_link, 'component' => $component, 'type' => $type, 'item_id' => $item_id, 'secondary_item_id' => $secondary_item_id, 'recorded_time' => $recorded_time, 'hide_sitewide' => $hide_sitewide ) );
}

function em_bp_register_activity_actions() {

	if ( !bp_is_active( 'events' ) ) {
		return false;
	}

	$bp = buddypress();

	// @todo - Figure out why adding a second bp_activity_set_action creates two 'Bookings' actions in backend and different booking status front-end e.g. when friendships created/accepted do not
	bp_activity_set_action(
		$bp->events->id,
		'new_booking',
		__( 'Bookings', 'events-manager'),
		'em_bp_events_format_activity_action_bookings',
		__( 'Bookings', 'events-manager'),
		array( 'activity', 'member' )
	);
	/*
	bp_activity_set_action(
		$bp->events->id,
		'booking_cancelled',
		__( 'Booking Cancelled', 'events-manager'),
		'em_bp_events_format_activity_action_bookings',
		__( 'Bookings', 'events-manager'),
		array( 'activity', 'member' )
	);
	*/
	
	bp_activity_set_action(
		$bp->events->id,
		'new_event',
		__('New Event','events-manager'),
		'em_bp_events_format_activity_action_events',
		__( 'Events', 'events-manager'),
		array( 'activity', 'member' )
	);
}
add_action( 'bp_register_activity_actions', 'em_bp_register_activity_actions' );

/**
 * Not yet used fully - formats booking-related actions
 * @param string $action
 * @param object $activity
 * @return string
 */
function em_bp_events_format_activity_action_bookings( $action, $activity ) {
    return '';
    $member_link = bp_core_get_userlink( $activity->user_id );
	$EM_Booking = em_get_booking( $activity->item );

	$action = '';
	switch ($activity->type){
	    case 'new_booking':
	        if( $activity->component == 'groups' ){
				$action = sprintf(__('%s is attending %s of the group %s.','events-manager'), $member_link, $event_link, $group_link );
	        }else{
	            $action = sprintf(__('%s is attending %s.','events-manager'), $member_link, $event_link );
	        }
	        break;
	    case 'cancelled_booking':
	        if( $activity->component == 'groups' ){
	            $action = sprintf(__('%s will not be attending %s of group %s anymore.','events-manager'), $user_link, $event_link, $group_link );
	        }else{
	            $action = sprintf(__('%s will not be attending %s anymore.','events-manager'), $user_link, $event_link );
	        }
			break;
	}

	return apply_filters( 'bp_events_format_activity_action_bookings', $action, $activity );
}

/**
 * Not yet used fully - formats event-related actions
 * @param string $action
 * @param object $activity
 * @return string
 */
function em_bp_events_format_activity_action_events( $action, $activity ) {
    return '';
	$member_link = bp_core_get_userlink( $activity->user_id );
	$EM_Event = em_get_event( $activity->item_id );

	$action = sprintf(__('%s added the event %s','events-manager'), $member_link, $EM_Event->output('#_EVENTLINK') );

	return apply_filters( 'bp_events_format_activity_action_events', $action, $activity );
}

/**
 * Records new events to the activity stream.
 * @param unknown_type $result
 * @param unknown_type $EM_Event
 * @return unknown
 */
function bp_em_record_activity_event_save( $result, $EM_Event ){
	if( $result && $EM_Event->event_status == 1 && $EM_Event->get_previous_status() != 1 ){
		$user = get_userdata($EM_Event->event_owner);
		$member_link = bp_core_get_user_domain($user->ID);
		if( empty($EM_Event->group_id) ){
			bp_em_record_activity( array(
				'user_id' => $user->ID,
				'action' => sprintf(__('%s added the event %s','events-manager'), "<a href='".$member_link."'>".$user->display_name."</a>", $EM_Event->output('#_EVENTLINK') ),
				'primary_link' => $EM_Event->output('#_EVENTURL'),
				'type' => 'new_event',
				'item_id' => $EM_Event->event_id,
				'hide_sitewide' => $EM_Event->event_private
			));
		}else{
			//tis a group event
			$group = new BP_Groups_Group($EM_Event->group_id);
			bp_em_record_activity( array(
				'user_id' => $user->ID,
				'action' => sprintf(__('%s added the event %s to %s.','events-manager'), "<a href='".$member_link."'>".$user->display_name."</a>", $EM_Event->output('#_EVENTLINK'), '<a href="'.bp_get_group_permalink($group).'">'.bp_get_group_name($group).'</a>' ),
				'component' => 'groups',
				'type' => 'new_event',
				'item_id' => $EM_Event->group_id,
				'hide_sitewide' => $EM_Event->event_private
			));
		}
	}
	return $result;
}
add_filter('em_event_save','bp_em_record_activity_event_save', 10, 2);

/**
 * @param boolean $result
 * @param EM_Booking $EM_Booking
 * @return boolean
 */
function bp_em_record_activity_booking_save( $result, $EM_Booking ){
	if( !empty($EM_Booking->event_id) && $result ){
		$rejected_statuses = array(0,2,3); //these statuses apply to rejected/cancelled bookings
		$user = $EM_Booking->get_person();
		$member_link = bp_core_get_user_domain($user->ID);
		$user_link = "<a href='".$member_link."/'>".$user->display_name."</a>";
		$event_link = $EM_Booking->get_event()->output('#_EVENTLINK');
		$status = $EM_Booking->booking_status;
		$EM_Event = $EM_Booking->get_event();
		$action_type = 'new_booking';
		if( empty($EM_Event->group_id) ){
			if( $status == 1 || (!get_option('dbem_bookings_approval') && $status < 2) ){
				$action = sprintf(__('%s is attending %s.','events-manager'), $user_link, $event_link );
			}elseif( ($EM_Booking->previous_status == 1 || (!get_option('dbem_bookings_approval') && $EM_Booking->previous_status < 2)) && in_array($status, $rejected_statuses) ){
				$action = sprintf(__('%s will not be attending %s anymore.','events-manager'), $user_link, $event_link );
				//$action_type = 'cancelled_booking';
			}
		}else{
			$group = new BP_Groups_Group($EM_Event->group_id);
			$group_link = '<a href="'.bp_get_group_permalink($group).'">'.bp_get_group_name($group).'</a>';
			if( $status == 1 || (!get_option('dbem_bookings_approval') && $status < 2) ){
				$action = sprintf(__('%s is attending %s of the group %s.','events-manager'), $user_link, $event_link, $group_link );
			}elseif( ($EM_Booking->previous_status == 1 || (!get_option('dbem_bookings_approval') && $EM_Booking->previous_status < 2)) && in_array($status, $rejected_statuses) ){
				$action = sprintf(__('%s will not be attending %s of group %s anymore.','events-manager'), $user_link, $event_link, $group_link );
				//$action_type = 'cancelled_booking';
			}
		}
		if( !empty($action) ){
			if( empty($EM_Event->group_id) ){
				bp_em_record_activity( array(
					'user_id' => $EM_Booking->person->ID,
					'action' => $action,
					'primary_link' => $EM_Event->output('#_EVENTURL'),
					'type' => $action_type,
					'item_id' => $EM_Event->event_id,
					'secondary_item_id' => $EM_Booking->booking_id,
					'hide_sitewide' => $EM_Event->event_private
				));
			}else{
				//tis a group event
				bp_em_record_activity( array(
					'component' => 'groups',
					'user_id' => $EM_Booking->person->ID,
					'action' => $action,
					'primary_link' => $EM_Event->output('#_EVENTURL'),
					'type' => $action_type,
					'item_id' => $EM_Event->group_id,
					'secondary_item_id' => $EM_Booking->booking_id,
					'hide_sitewide' => $EM_Event->event_private
				));
			}
		}
	}
	return $result;
}
add_filter('em_booking_set_status','bp_em_record_activity_booking_save', 100, 2);
add_filter('em_booking_save','bp_em_record_activity_booking_save', 100, 2);
add_filter('em_booking_delete','bp_em_record_activity_booking_save', 100, 2);