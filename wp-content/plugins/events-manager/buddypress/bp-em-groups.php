<?php
/**
 * Turns an event private if the event belongs to a private BP Group
 * @param EM_Event $EM_Event
 */
function bp_em_group_event_save($result, $EM_Event){
	if( is_object($EM_Event) && !empty($EM_Event->event_id) ){
		if( !empty($_REQUEST['group_id']) && is_numeric($_REQUEST['group_id']) && bp_is_active('groups') ){
		    //firstly, we check that the event has been published, otherwise users without publish rights can submit an event at a private group and event is marked private/published immediately
		    if( $EM_Event->event_status == 1 ){
    			//we have been requested an event creation tied to a group, so does this group exist, and does this person have admin rights to it?
    			if( groups_is_user_admin(get_current_user_id(), $_REQUEST['group_id']) ){
    				$EM_Event->group_id = $_REQUEST['group_id'];
    			}
    			if( !empty($EM_Event->group_id) ){
    				//if group is private, make it private
    				$group = groups_get_group(array('group_id'=>$EM_Event->group_id));
    				$is_member = groups_is_user_member(get_current_user_id(), $EM_Event->group_id) || groups_is_user_admin(get_current_user_id(), $EM_Event->group_id) || groups_is_user_mod(get_current_user_id(), $EM_Event->group_id);
    				if( $group->status != 'public' && $is_member ){
    					//Make sure event status is private and set post status to private
    					global $wpdb;
    					$EM_Event->event_private = 1;
    					$wpdb->update($wpdb->posts, array('post_status'=>'private'), array('ID'=>$EM_Event->post_id));
    					$wpdb->update(EM_EVENTS_TABLE, array('event_private'=>1), array('event_id'=>$EM_Event->event_id));
    				}
    			}
		    }
		}else{
			$EM_Event->group_id = null;
		}
	}
	return $result;
}
add_action('em_event_save','bp_em_group_event_save',1,2);

/**
 * Overrides the default capability of the user for another owner's event if the user is a group admin and the event belongs to a group. 
 * User must have the relevant permissions globally in order to inherit that capability for this event as well.
 * @param boolean $result
 * @param EM_Event $EM_Event
 */
function bp_em_group_event_can_manage( $result, $EM_Event, $owner_capability, $admin_capability, $user_to_check){
	if( !$result && $EM_Event->event_owner != get_current_user_id() && !empty($EM_Event->group_id) && bp_is_active('groups') ){ //only override if already false, incase it's true
	    //if the user is an admin of this group, and actually has the relevant permissions globally, they can manage this event
	    $EM_Object = new EM_Object(); //create new object to prevent infinite loop should we call $EM_Event->can_manage();
		if( groups_is_user_admin(get_current_user_id(),$EM_Event->group_id) && $EM_Object->can_manage($owner_capability, $admin_capability, $user_to_check) ){
			//This user is an admin of the owner's group, so they can edit this event.
			array_pop($EM_Event->errors); //remove last error
			return true;
		}else{
		    $EM_Event->add_error($EM_Object->get_errors()); //add any applicable errors
		}
	}
	return $result;
}
add_filter('em_event_can_manage','bp_em_group_event_can_manage',1,5);


function bp_em_group_events_accepted_searches($searches){
	if( bp_is_active('groups') ){
		$searches[] = 'group';
	}
	return $searches;
}
add_filter('em_accepted_searches','bp_em_group_events_accepted_searches',1,1);

function bp_em_group_events_get_default_search($searches, $array){
	if( !empty($array['group']) && (is_numeric($array['group']) || $array['group'] == 'my' || $array['group'] == 'this') && bp_is_active('groups') ){
		if($array['group'] == 'this'){ //shows current group, if applicable
			if( is_numeric(bp_get_current_group_id()) ){
				$searches['group'] = bp_get_current_group_id();
			}
		}else{
			$searches['group'] = $array['group'];
		}
	}
	return $searches;
}
add_filter('em_events_get_default_search','bp_em_group_events_get_default_search',1,2);

/*
 * Privacy Functions
 */
function bp_em_group_events_build_sql_conditions( $conditions, $args ){
	if( !empty($args['group']) && is_numeric($args['group']) ){
		$conditions['group'] = "( `group_id`={$args['group']} )";
	}elseif( !empty($args['group']) && $args['group'] == 'my' ){
		$groups = groups_get_user_groups(get_current_user_id());
		if( count($groups) > 0 ){
			$conditions['group'] = "( `group_id` IN (".implode(',',$groups['groups']).") )";
		}
	}
	//deal with private groups and events
	if( is_user_logged_in() ){
		global $wpdb;
		//find out what private groups they belong to, and don't show private group events not in their memberships
		$group_ids = BP_Groups_Member::get_group_ids(get_current_user_id());
		if( $group_ids['total'] > 0){
			$conditions['group_privacy'] = "(`event_private`=0 OR (`event_private`=1 AND (`group_id` IS NULL OR `group_id` = 0)) OR (`event_private`=1 AND `group_id` IN (".implode(',',$group_ids['groups']).")))";
		}else{
			//find out what private groups they belong to, and don't show private group events not in their memberships
			$conditions['group_privacy'] = "(`event_private`=0 OR (`event_private`=1 AND (`group_id` IS NULL OR `group_id` = 0)))";
		} 
	}
	return $conditions;
}
add_filter('em_events_build_sql_conditions','bp_em_group_events_build_sql_conditions',1,2);

	
/**
 * Checks if the event is private and either belongs to a group or private group, as members of that group should be able to see the post even if not able to see private events. 
 * @param string $template
 * @return string
 */
function bp_em_private_event_check($template){
	global $post, $wpdb, $wp_query, $bp;
	if( $post->post_type == EM_POST_TYPE_EVENT ){
		$EM_Event = em_get_event($post);
		//echo "<pre>"; print_r($EM_Event); echo "</pre>"; die();
		if( !empty($EM_Event->event_private) && !empty($EM_Event->group_id) ){
			if( is_user_logged_in() ){
				//make sure user is a member of this group, whether private or not, private groups just aren't shown to non-members of a group
				$id_lookup = $wpdb->get_var( $wpdb->prepare( "SELECT m.group_id FROM {$bp->groups->table_name_members} m WHERE m.group_id = %s AND m.user_id = %d AND m.is_confirmed = 1 AND m.is_banned = 0", $EM_Event->group_id, get_current_user_id() ) );
				if($id_lookup != $EM_Event->group_id){
					unset($post);
					$wp_query->set_404();
					$template = locate_template(array('404.php'),false);
				}
			}else{
				unset($post);
				$wp_query->set_404();
				$template = locate_template(array('404.php'),false);
			}
		}
	}
	return $template;
}
add_filter('single_template','bp_em_private_event_check',20);

/*
 * Admin Meta Boxes
 */
function bp_em_meta_boxes(){
	add_meta_box('em-event-group', __('Group Ownership','events-manager'), 'bp_em_meta_box_group',EM_POST_TYPE_EVENT, 'side','low');
	add_meta_box('em-event-group', __('Group Ownership','events-manager'), 'bp_em_meta_box_group','event-recurring', 'side','low');
}
add_action('add_meta_boxes', 'bp_em_meta_boxes');
	
function bp_em_meta_box_group(){
	em_locate_template('forms/event/group.php',true);
}