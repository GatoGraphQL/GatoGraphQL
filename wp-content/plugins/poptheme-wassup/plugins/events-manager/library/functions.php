<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Events Manager Implementations under Local Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('em_excerpt_more', 'gd_excerpt_more', 10000, 1);

function gd_em_return_event_posttype($items) {

	$items[] = EM_POST_TYPE_EVENT;
	return $items;
}

function gd_em_has_category($event, $cat) {

	$categories = $event->get_categories();
	return isset($categories->categories[$cat]);
}

add_filter('gd_get_categories', 'gd_em_get_categories', 10, 2);
function gd_em_get_categories($categories, $post_id = null) {
	
	if (get_post_type($post_id) == EM_POST_TYPE_EVENT) {
		
		$event = em_get_event($post_id, 'post_id');
		return array_keys($event->get_categories()->categories);
	}

	return $categories;
}


add_filter('gd_templatemanager:multilayout_labels', 'gd_em_custom_multilayout_labels');
function gd_em_custom_multilayout_labels($labels) {

	$label = '<span class="label label-%s">%s</span>';
	return array_merge(
		array(
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_FUTURE => sprintf(
				$label,
				'future-events',
				gd_navigation_menu_item(POPTHEME_WASSUP_EM_PAGE_EVENTS, true).__('Upcoming Event', 'poptheme-wassup')
			),
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_CURRENT => sprintf(
				$label,
				'current-events',
				gd_navigation_menu_item(POPTHEME_WASSUP_EM_PAGE_EVENTS, true).__('Current Event', 'poptheme-wassup')
			),
			EM_POST_TYPE_EVENT.'-'.POPTHEME_WASSUP_EM_CAT_PAST => sprintf(
				$label,
				'past-events',
				gd_navigation_menu_item(POPTHEME_WASSUP_EM_PAGE_PASTEVENTS, true).__('Past Event', 'poptheme-wassup')
			)
		),
		$labels
	);
}


/**---------------------------------------------------------------------------------------------------------------
 * Integration with Latest Everything Block
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('gd_dataload:post_types', 'gd_em_return_event_posttype');
add_filter('gd_template:allcontent:tax_query_items', 'gd_em_template_everything_taxquery');
add_filter('gd_template:latestcounts:tax_query_items', 'gd_em_template_everything_taxquery');
function gd_em_template_everything_taxquery($tax_query_items) {
	$tax_query_items[] = array(
		'taxonomy' => EM_TAXONOMY_CATEGORY,
		'terms' => array(POPTHEME_WASSUP_EM_CAT_ALL)
	);

	return $tax_query_items;
}


/**---------------------------------------------------------------------------------------------------------------
 * rss feed
 * ---------------------------------------------------------------------------------------------------------------*/

// Add Events to the RSS feeds
// Comment Leo 07/08/2016: Commented since instead of including post_types we now include taxonomies
// add_filter('gd_posts_types_to_include_in_rss', 'gd_em_return_event_posttype');


// Function that returns true if the event has attendees
function gd_event_has_attendees($event) {

	foreach($event->get_bookings() as $EM_Booking){
			
		if($EM_Booking->status == 1) return true;
	}
	
	return false;
}


// This is to return the message in the selected language when using ajax
add_filter('em_object_json_encode_pre', 'gd_pre_em_object_json_encode_add_qtrans');
function gd_pre_em_object_json_encode_add_qtrans($array) {
		
	if (isset($array['message'])) $array['message'] = apply_filters("gd_translate", $array['message']);
	if (isset($array['errors'])) $array['errors'] = apply_filters("gd_translate", $array['errors']);

	return $array;
}



// Indicates if the event takes place on day $day
function gd_em_event_event_on_given_day($day, $event) {
	
	$event_dates = array();
	$event_start_date = strtotime($event->start_date);
	$event_end_date = mktime(0,0,0,$month_post,date('t', $event_start_date),$year_post );
	if( $event_end_date == '' ) $event_end_date = $event_start_date;
	while( $event_start_date <= $event->end ){
		//Ensure date is within event dates, if so add to eventful days array
		$event_eventful_date = date('Y-m-d', $event_start_date);
		$event_dates[] = $event_eventful_date;
		$event_start_date += (86400); //add a day		
	}
	
	return in_array($day, $event_dates);
}



/* ------------------------------------------------------------------------------------
 * There is a bug in forms/event-editor.php: the wp_editor prints without styles throught hook the_content
 * So instead we generate the content of wp_editor in the content and then replace the wildcard "__GD_EM_WP_EDITOR_CONTENT__" with this content
 * ------------------------------------------------------------------------------------ */


/* ------------------------------------------------------------------------------------
 * My Events
 * ------------------------------------------------------------------------------------ */

// There is a bug in em-bookings-table.php function get_booking_actions (and in all other places too):
// In My Events, when listing the Bookings in the frontend, and then filtering, it brings again content with the wrong href
// This href points to wp-admin/admin-ajax.php. That is because the href is built using em_add_get_params($_SERVER['REQUEST_URI'], ...)
// So when filtering with Ajax the Original page (eg: /events/my_events.php) is replaced with this admin-ajax.php
// Priority 100: fix this everywhere, so execute last
if (!is_admin()) {
	apply_filters('em_bookings_table_cols_col_action', 'gd_em_bookings_table_cols_col_action', 100, 2);
}
function gd_em_bookings_table_cols_col_action($booking_actions, $EM_Booking) {

	if ($booking_actions) {
		foreach ($booking_actions as $key => $value) {
		
			$booking_actions[$key] = str_replace($_SERVER['REQUEST_URI'], get_permalink(POPTHEME_WASSUP_EM_PAGE_MANAGEBOOKINGS), $value);
		}
	}
	
	return $booking_actions;
}


/* ------------------------------------------------------------------------------------
 * Strip tags off #_EVENTEXCERPT
 * ------------------------------------------------------------------------------------ */

// Remove tags and shortcodes, and shorten the excerpt
add_filter('dbem_notes_excerpt','gd_dbem_notes_excerpt_strip_tags_excerpt');
function gd_dbem_notes_excerpt_strip_tags_excerpt($result){
	$result = strip_tags($result);
	return limit_string($result, null, null, true);
}


/**---------------------------------------------------------------------------------------------------------------
 * attachments/functions.php
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('gd_attachments_resources_post_type', 'gd_em_return_event_posttype');


/**---------------------------------------------------------------------------------------------------------------
 * Whenever uploading a file in Frontend, right now post_parent is assigned to the Edit Event page, and not to the actual post.
 * Replace this Edit Event Page id with the post id
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter( 'media_view_settings', 'gd_em_media_view_settings', 10, 2);
function gd_em_media_view_settings ($settings, $post) {

	// Change it if we're in frontend and the ID of the post is the one of the Edit Event page
	if (!is_admin() && $post->ID == get_option('dbem_edit_events_page')) {
	
		// Do it only when Editing Event. When creating the Event, keep it with this id, since we don't have yet the id of the Event to be
		if ($_REQUEST['event_id']) {
	
			$event = em_get_event($_REQUEST['event_id']);
			$settings['post']['id'] = $event->post_id;
		}
	}

	return $settings;
}

/**
 * Immediately after executing gd_save_add_media_as_resource_metadata, we can save the Media as Resources
 */
/*
add_action( 'em_event_save_meta_pre', 'gd_em_attach_file_to_post', 2);
function gd_em_attach_file_to_post( $event ) {

	gd_attach_file_to_post($event->post_id);
}
*/


/**---------------------------------------------------------------------------------------------------------------
 * disclaimer.php
 * ---------------------------------------------------------------------------------------------------------------*/

// add_filter('gd_disclaimer_posttypes', 'gd_em_return_event_posttype'); 
add_filter('gd_content_posttypes', 'gd_em_return_event_posttype'); 


/**---------------------------------------------------------------------------------------------------------------
 * Install extra capabilities for the roles
 * ---------------------------------------------------------------------------------------------------------------*/
						
add_filter('gd_users_install_extra_capabilities', 'gd_em_install_extra_capabilities');
function gd_em_install_extra_capabilities($capabilities) {

	$events_cap = array(
		'publish_events' => true, 
		'delete_events' => true, 
		'edit_events' => true, 
		'read_private_events' => true, 
		'delete_recurring_events' => true, 
		'edit_recurring_events' => true, 
		'edit_locations' => true, 
		'read_private_locations' => true, 
		'read_others_locations' => true, 
		'manage_bookings' => true, 
		'upload_event_images' => true,
		'publish_locations' => true // Needed to allow users to create Locations for Actions and be published immediately
	);
	
	return array_merge($capabilities, $events_cap);
}



/**---------------------------------------------------------------------------------------------------------------
 * Integration between WP Super Cache and Events Manager (delete cache when booking)
 * ---------------------------------------------------------------------------------------------------------------*/

// add_action('em_bookings_added', 'gd_cache_flush', 10, 0);
// add_action("em_booking_form_after_tickets", "gd_cache_print_path");


/**---------------------------------------------------------------------------------------------------------------
 * Doing Ajax overriding
 * ---------------------------------------------------------------------------------------------------------------*/

// Events Manager add param em_ajax or em_ajax_action to pretend they are ajax calls
add_filter('gd_doing_ajax', 'gd_em_doing_ajax');
function gd_em_doing_ajax($doing_ajax) {

	return $doing_ajax || !empty($_REQUEST['em_ajax']) || !empty($_REQUEST['em_ajax_action']);
}

/**---------------------------------------------------------------------------------------------------------------
 * Add class to the Events Widget <ul>
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('gd_em_events_widget_ul_style', 'gd_util_add_list_unstyled_class');


// http://wp-events-plugin.com/blog/2013/10/06/5-5-2-released/
// 5.5.2 contains an important security patch related to the booking form and an XSS vulnerability. Updating is advised! For those who don’t want to update for whatever reason, add this somewhere in events-manager.php:
add_filter('em_booking_form_action_url','esc_url');





/**---------------------------------------------------------------------------------------------------------------
 * Do not validate the location address (a city alone is also valid)
 * Do validate Lat and Lng
 * ---------------------------------------------------------------------------------------------------------------*/
add_action('em_location', 'gd_em_location', 10, 1);
function gd_em_location($EM_Location) {
	unset($EM_Location->required_fields['location_address']);				
}
add_filter('em_location_validate', 'gd_em_validate_latlng', 10, 2);
function gd_em_validate_latlng($valid, $EM_Location) {
	
	// No need to ask for both lat and lng, one of them is already enough (they are both full or empty)
	// Check only if the validation is true (which means, it does have an address)
	if( $valid && empty($EM_Location->location_latitude) ){
		$valid = false;
		$EM_Location->add_error( __('The address was not found on the map','poptheme-wassup') );
	}

	return $valid;
}


/**---------------------------------------------------------------------------------------------------------------
 * Status: allow for arrays also
 * ---------------------------------------------------------------------------------------------------------------*/

add_filter('em_events_build_sql_conditions', 'gd_em_events_build_sql_conditions_status', 10, 2);
function gd_em_events_build_sql_conditions_status($conditions, $args) {

	// Copied from plugins/events-manager/classes/em-events.php
	if( is_array($args['status']) ){

		$status_conditions = array();
		if( in_array('draft', $args['status']) ){
			$status_conditions[] = "`event_status` IS NULL"; //pending
		}
		if( in_array('pending', $args['status']) ){
			$status_conditions[] = "`event_status`=0"; //pending
		}
		if( in_array('publish', $args['status']) ){
			$status_conditions[] = "`event_status`=1"; //pending
		}

		if ($status_conditions) {

			$conditions['status'] = sprintf('(%s)', implode(' OR ', $status_conditions));
		}
	}

	return $conditions;
}


/**---------------------------------------------------------------------------------------------------------------
 * Addition to post__not_in when searching events, to allow to exclude events 
 * Otherwise not possible right now (http://wordpress.org/support/topic/exclude-current-event-from-listing)
 * ---------------------------------------------------------------------------------------------------------------*/
add_filter('em_events_get_default_search', 'gd_em_events_get_default_search_exclude_post_id', 10, 3);
function gd_em_events_get_default_search_exclude_post_id($defaults, $array, $super_defaults) {

	if (!empty($array['post__not_in'])) {
		$defaults['post__not_in'] = $array['post__not_in'];
	}
	return $defaults;
}

add_filter('em_events_build_sql_conditions', 'gd_em_events_build_sql_conditions_exclude_post_id', 10, 2);
function gd_em_events_build_sql_conditions_exclude_post_id($conditions, $args) {

	if( !empty($args['post__not_in'])){
		if( is_array($args['post__not_in']) ){
			$conditions['post__not_in'] = "(".EM_EVENTS_TABLE.".post_id NOT IN (".implode(',',$args['post__not_in'])."))";
		}else{
			$conditions['post__not_in'] = "(".EM_EVENTS_TABLE.".post_id!={$args['post__not_in']})";
		}
	}

	return $conditions;
}


/**---------------------------------------------------------------------------------------------------------------
 * Remove the <h2>Bookings</h2> at the bottom of each event
 * ---------------------------------------------------------------------------------------------------------------*/

if( !is_admin() ){
	//override single page with formats? 
	remove_filter('the_content', array('EM_Event_Post','the_content'));
}

/**---------------------------------------------------------------------------------------------------------------
 * Allow new users to create locations while creating their profile
 * This actually doesn't work, we must enable "Allow anonymous event submissions?" Always on EM Settings
 * ---------------------------------------------------------------------------------------------------------------*/
// add_filter('em_location_save', 'gd_return_true', 1000);

/**---------------------------------------------------------------------------------------------------------------
 * Single Event functions
 * ---------------------------------------------------------------------------------------------------------------*/
function gd_em_single_event_is_future($post_or_post_id = null) {

	// Comment Leo 07/07/2014: do not use variable global $EM_Event, since it has not been initialized properly
	// by the time we need to call this function
	
	// global $EM_Event;
	if (!$post_or_post_id) {
		$vars = GD_TemplateManager_Utils::get_vars();
		$post_or_post_id = $vars['global-state']['post']/*global $post*/;
	}
	if (!is_object($post_or_post_id)) {
		$EM_Event = em_get_event($post_or_post_id, 'post_id');
	}
	else {
		$EM_Event = $post_or_post_id;
	}
	
	return POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/ < $EM_Event->end;
}

// function gd_em_single_event_is_past($post_or_post_id = null) {

// 	return !gd_em_single_event_is_future($post_or_post_id);
// }


/**---------------------------------------------------------------------------------------------------------------
 * Needed to add the "All" category to all events, to list them for the Latest Everything Block
 * ---------------------------------------------------------------------------------------------------------------*/

// Do always add the 'All' Category when adding a new event
add_action('em_event_save_pre', 'gd_em_event_save_pre_add_all_category', 10, 1);
function gd_em_event_save_pre_add_all_category($EM_Event){
	
	$category_all = POPTHEME_WASSUP_EM_CAT_ALL;
	if (!$EM_Event->get_categories()->categories[$category_all]) {
		$EM_Event->get_categories()->categories[$category_all] = new EM_Category($category_all);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Add a fictitious Future/Past Category to future/past events for multilayout purposes
 * ---------------------------------------------------------------------------------------------------------------*/
// Do always add the 'All' Category when adding a new event
add_filter('em_event_get_categories', 'gd_em_event_get_categories_addtimeframecategory', 10, 2);
function gd_em_event_get_categories_addtimeframecategory($EM_Categories, $EM_Event) {

	// Add future or past category?
	$now = POP_CONSTANT_CURRENTTIMESTAMP;//current_time('timestamp');
	$is_future = $now < $EM_Event->end;
	$is_current = ($EM_Event->start <= $now) && $is_future;
	$future_cat = POPTHEME_WASSUP_EM_CAT_FUTURE;
	$past_cat = POPTHEME_WASSUP_EM_CAT_PAST;
	$current_cat = POPTHEME_WASSUP_EM_CAT_CURRENT;

	$timeframe_cat = $is_current ? $current_cat : ($is_future ? $future_cat : $past_cat);
	
	// Add the 'fictitious' category
	if (!$EM_Categories->categories[$timeframe_cat]) {
		$EM_Categories->categories[$timeframe_cat] = new EM_Category($timeframe_cat);
	}

	// Make sure it doesn't have the other category (just in case, for if the category was saved and then retrieved back)
	$remove_cats = array_diff(
		array($future_cat, $current_cat, $past_cat),
		array($timeframe_cat)
	);
	foreach ($remove_cats as $remove_timeframe_cat) {
		unset($EM_Categories->categories[$remove_timeframe_cat]);
	}
		
	return $EM_Categories;
}

add_filter('gd_get_the_main_category', 'gd_em_get_category', 10, 3);
function gd_em_get_category($cat, $post_id, $return_id) {

	if (get_post_type($post_id) == EM_POST_TYPE_EVENT) {

		$event = em_get_event($post_id, 'post_id');

		// Check for priority: Future/Past categories have priority over All
		$categories = $event->get_categories();
		if ($categories->categories[POPTHEME_WASSUP_EM_CAT_CURRENT]) {
			
			if ($return_id) {
				return POPTHEME_WASSUP_EM_CAT_CURRENT;
			}
			return $categories->categories[POPTHEME_WASSUP_EM_CAT_CURRENT];
		}
		elseif ($categories->categories[POPTHEME_WASSUP_EM_CAT_FUTURE]) {
			
			if ($return_id) {
				return POPTHEME_WASSUP_EM_CAT_FUTURE;
			}
			return $categories->categories[POPTHEME_WASSUP_EM_CAT_FUTURE];
		}
		elseif ($categories->categories[POPTHEME_WASSUP_EM_CAT_PAST]) {
			
			if ($return_id) {
				return POPTHEME_WASSUP_EM_CAT_PAST;
			}
			return $categories->categories[POPTHEME_WASSUP_EM_CAT_PAST];
		}
		
		if ($return_id) {
			return $event->output('#_CATEGORYID');
		}
		return $categories[0];
	}

	return $cat;
}


add_filter('gd_postname', 'gd_em_postname_impl', 10, 2);
function gd_em_postname_impl($name, $post_id) {

	if (get_post_type($post_id) == EM_POST_TYPE_EVENT) {
		
		return __('Event', 'poptheme-wassup');
	}

	return $name;
}
add_filter('gd_posticon', 'gd_em_posticon_impl', 10, 2);
function gd_em_posticon_impl($icon, $post_id) {

	if (get_post_type($post_id) == EM_POST_TYPE_EVENT) {
		
		return gd_navigation_menu_item(POPTHEME_WASSUP_EM_PAGE_EVENTS, false);
	}

	return $icon;
}

add_filter('gd_post_parentpageid', 'gd_em_post_parentpageid_impl', 10, 2);
function gd_em_post_parentpageid_impl($pageid, $post_id) {

	if (get_post_type($post_id) == EM_POST_TYPE_EVENT) {

		if (gd_em_single_event_is_future($post_id)) {
			return POPTHEME_WASSUP_EM_PAGE_EVENTS;
		}
		return POPTHEME_WASSUP_EM_PAGE_PASTEVENTS;
	}

	return $pageid;
}

add_filter('gd-createupdateutils:post_type:edit-url', 'gd_createupdateutils_posttype_edit_url', 10, 3);
function gd_createupdateutils_posttype_edit_url($url, $post_type, $post_id) {

	switch ($post_type) {

		case EM_POST_TYPE_EVENT:

			$event = em_get_event($post_id, 'post_id');
			if (gd_em_has_category($event, POPTHEME_WASSUP_EM_CAT_EVENTLINKS)) {

				return get_permalink(POPTHEME_WASSUP_EM_PAGE_EDITEVENTLINK);	
			}
			
			return get_permalink(POPTHEME_WASSUP_EM_PAGE_EDITEVENT);

	}

	return $url;
}