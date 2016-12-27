<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Events Manager Placeholders
 *
 * ---------------------------------------------------------------------------------------------------------------*/



// Extension: adding more conditionals: has_attendees and no_attendees
add_filter('em_event_output_show_condition', 'gd_em_event_output_show_condition', 10, 4);
function gd_em_event_output_show_condition($show_condition = false, $condition, $conditional_value, $event) {
	
	if ($condition == 'has_attendees') {
	
		return gd_event_has_attendees($event);
	}
	else if ($condition == 'no_attendees') {
	
		return !gd_event_has_attendees($event);
	}
	
	return $show_condition;
}


/*
 * Check for if the Event has a given Attribute
 */
add_filter('em_event_output_show_condition', 'gd_em_event_output_show_condition_add_att_condition', 1, 4);
function gd_em_event_output_show_condition_add_att_condition($show_condition = false, $condition, $conditional_value, $event) {

	if ( preg_match('/^has_att_([a-zA-Z0-9_\-]+)$/', $condition, $tag_match)){
		//event has this attribute
		$att = $tag_match[1];
		$attributes = $event->event_attributes;
	   
		$show_condition = is_array($event->event_attributes) && array_key_exists($att, $attributes) && $attributes[$att];
	}
	elseif ( preg_match('/^no_att_([a-zA-Z0-9_\-]+)$/', $condition, $tag_match)){
	   //event doesn't have this attribute
		$att = $tag_match[1];
		$attributes = $event->event_attributes;
	   
		$show_condition = !(is_array($event->event_attributes) && array_key_exists($att, $attributes) && $attributes[$att]);
	}
	
	return $show_condition;
}

/*
 * Check for if the Event is held either today or tomorrow
 */
add_filter('em_event_output_show_condition', 'gd_em_event_output_show_condition_add_date_condition', 11, 4);
function gd_em_event_output_show_condition_add_date_condition($show_condition = false, $condition, $conditional_value, $event) {

	if ($condition == 'is_today'){

		$today = date('Y-m-d', POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/);		
		$show_condition = gd_em_event_event_on_given_day($today, $event);
	}
	elseif ($condition == 'is_tomorrow'){

		$tomorrow = date('Y-m-d', POP_CONSTANT_CURRENTTIMESTAMP/*current_time('timestamp')*/+86400);	// add a day
		$show_condition = gd_em_event_event_on_given_day($tomorrow, $event);
	}
	
	return $show_condition;
}


// add_filter('em_event_output_placeholder', 'gd_em_event_output_event_featured_image', 10, 4);
// function gd_em_event_output_event_featured_image ($attString, $event, $format, $target) {

// 	preg_match_all("/(#@?_?[A-Za-z0-9]+)({([a-zA-Z0-9_,]+)})?/", $format, $placeholders);
// 	foreach($placeholders[1] as $key => $result) {

// 		switch( $result ){
			
// 			case '#_EVENTFEATUREDIMAGE':
					
// 				$thumb = $placeholders[3][$key];
				
// 				// IMPORTANT: add $alt and $title, otherwise arras_get_thumbnail executes get_the_excerpt and get_the_title and it crashes in the Online Newsletter page.
// 				$attString = arras_get_thumbnail($thumb.'-thumb', $event->post_id, $event->output("#_EVENTEXCERPT"), $event->output("#_EVENTNAME"));
// 			break;
// 		}
// 	}

// 	return $attString;
// }

add_filter('em_event_output_placeholder', 'gd_em_event_output_event_author', 10, 4);
function gd_em_event_output_event_author($output, $event, $format, $target) {

	preg_match_all("/(#@?_?[A-Za-z0-9]+)({([a-zA-Z0-9_,]+)})?/", $format, $placeholders);
	foreach($placeholders[1] as $key => $result) {

		switch( $result ){
			
			case '#_EVENTAUTHOR':

				$author = $event->event_owner;
				$name = get_the_author_meta('display_name', $author);
				$url = get_author_posts_url($author);
				// $url = GD_TemplateManager_Utils::add_tab($url, POP_COREPROCESSORS_PAGE_DESCRIPTION);
				$output = sprintf(
					'<a href="%s">%s</a>',
					$url,
					$name
				);
				break;

			case '#_EVENTAUTHORNAME':

				$author = $event->event_owner;
				$output = get_the_author_meta('display_name', $author);
				break;

			case '#_EVENTAUTHORURL':

				$author = $event->event_owner;
				$url = get_author_posts_url($author);
				// $url = GD_TemplateManager_Utils::add_tab($url, POP_COREPROCESSORS_PAGE_DESCRIPTION);
				$output = $url;
				break;
		}
	}

	return $output;
}

/*
 * For the FullCalendar
 */
add_filter('em_event_output_placeholder', 'gd_em_event_output_event_dates', 10, 4);
function gd_em_event_output_event_dates ($attString, $event, $format, $target) {

	preg_match_all("/(#@?_?[A-Za-z0-9]+)({([a-zA-Z0-9_,]+)})?/", $format, $placeholders);
	foreach($placeholders[1] as $key => $result) {

		switch( $result ){
			
			case '#_EVENTDATESTART':
			case '#_EVENTDATEEND':
					
				//$date_format = ( get_option('dbem_date_format') ) ? get_option('dbem_date_format'):get_option('date_format');
				// Possible Formats required: http://arshaw.com/fullcalendar/docs/event_data/Event_Object/
				// WP Formats: http://codex.wordpress.org/Formatting_Date_and_Time
				$date_format = 'c'; // ISO8601 

				if ($result == '#_EVENTDATESTART') {
					$date = $event->start;
				}
				else {
					$date = $event->end;	
				}
				$attString = date_i18n($date_format, $date);
				break;

			case '#_EVENTALLDAY':
					
				$attString = $event->event_all_day;
				break;
		}
	}

	return $attString;
}


// Override the Location Balloon: if no address, don't show it
add_filter('em_location_output_placeholder','gd_em_location_output_placeholder_noaddress',10,4);
function gd_em_location_output_placeholder_noaddress($result,$location,$placeholder,$target='html'){

	switch( $placeholder ){

		case '#_LOCATIONFULLLINE':
		case '#_LOCATIONFULLBR':		
			$results = array();
			if (!empty($location->location_address)) $results[] = $location->location_address;
			if (!empty($location->location_town)) $results[] = $location->location_town;
			if (!empty($location->location_state)) $results[] = $location->location_state;
			if (!empty($location->location_postcode)) $results[] = $location->location_postcode;
			if (!empty($location->location_region)) $results[] = $location->location_region;
			if ($placeholder == '#_LOCATIONFULLLINE') $sep = ', ';
			else $sep = '<br/>';
			$result = implode($sep, $results);
			break;
	}

	return $result;
}






// Translate Event's fields after the output, either content or URLs
add_filter('em_event_output_placeholder','gd_em_event_output_placeholder_translate',10,4);
function gd_em_event_output_placeholder_translate($result,$event,$placeholder,$target='html'){

	switch( $placeholder ){
		// Events
		case '#_NAME':
		case '#_EVENTNAME':
		case '#_NOTES':
		case '#_EXCERPT':
		case '#_EVENTNOTES':
		case '#_EVENTEXCERPT':
		
			$result = apply_filters('gd_translate', $result);
			break;
	
		case '#_EVENTPAGEURL':
		case '#_EVENTURL':
		case '#_BOOKINGSURL':		
			$result = apply_filters('gd_translate_url', $result);
			break;
		
		// Events
		case '#_LINKEDNAME':
		case '#_EVENTLINK':
		
			$event_link = esc_url(apply_filters('gd_translate_url', $event->get_permalink()));
			$event_name = esc_attr(apply_filters('gd_translate', $event->event_name));
			$result = '<a href="'.$event_link.'" title="'.$event_name.'">'.$event_name.'</a>';
			break;

		case '#_BOOKINGSLINK':
			if( $event->can_manage('manage_bookings','manage_others_bookings') ){
				$event_name = apply_filters('gd_translate', $event->event_name);
				$bookings_link = esc_url($event->get_bookings_url());
				$result = '<a href="'.$bookings_link.'" title="'.esc_attr($event_name).'">'.esc_html($event_name).'</a>';
			}
			break;		

		}

	return $result;
}

// Translate Location's fields after the output, either content or URLs
add_filter('em_location_output_placeholder','gd_em_location_output_placeholder_translate',10,4);
function gd_em_location_output_placeholder_translate($result,$location,$placeholder,$target='html'){

	switch( $placeholder ){

		// Locations
		case '#_NAME':		
		case '#_EXCERPT':		
		case '#_LOCATION':
		case '#_LOCATIONNAME':
		case '#_DESCRIPTION':
		case '#_LOCATIONNOTES':
		case '#_LOCATIONEXCERPT':

			$result = apply_filters('gd_translate', $result);
			break;
	
		// Locations
		case '#_LOCATIONURL':
		case '#_LOCATIONPAGEURL':
			
			$result = apply_filters('gd_translate_url', $result);
			break;
					
		// Locations
		case '#_LOCATIONLINK':
		
			$link = esc_url(apply_filters('gd_translate_url', $location->get_permalink()));
			$location_name = esc_attr(apply_filters('gd_translate', $location->location_name));
			$result = '<a href="'.$link.'" title="'.$location_name.'">'.$location_name.'</a>';
			break;
	}

	return $result;
}

// Translate Category's fields after the output, either content or URLs
add_filter('em_category_output_placeholder','gd_em_category_output_placeholder_translate',10,4);
function gd_em_category_output_placeholder_translate($result,$category,$placeholder,$target='html'){

	switch( $placeholder ){

		// Category
		case '#_CATEGORYNAME':		
		case '#_CATEGORYNOTES':
		case '#_CATEGORYDESCRIPTION':

			$result = apply_filters('gd_translate', $result);
			break;
	
		// Category
		case '#_CATEGORYURL':
			$result = apply_filters('gd_translate_url', $result);
			break;
					
		// Category
		case '#_CATEGORYLINK':
		
			$link = apply_filters('gd_translate_url', $category->get_url());
			$category_name = esc_html(apply_filters('gd_translate', $category->name));
			$result = '<a href="'.$link.'">'.$category_name.'</a>';
			break;			
	}

	return $result;
}
