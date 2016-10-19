<?php
/*
 * Template Tags
 * If you know what you're doing, you're probably better off using the EM Objects directly.
 */

/* 
 * ---------------------------------------------------------------------
 * Displaying Functions - Displays Lists, Page Links/URLs, etc.
 * ---------------------------------------------------------------------
 */

/**
 * Returns a html list of events filtered by the array or query-string of arguments supplied. 
 * @param array|string $args
 * @return string
 */
function em_get_events( $args = array() ){
	if ( is_string($args) && strpos ( $args, "=" )) {
		// allows the use of arguments without breaking the legacy code	
		$args = wp_parse_args ( $args, array() );
	}else{
		$args = (array) $args;
	}
	$args['ajax'] = isset($args['ajax']) ? $args['ajax']:(!defined('EM_AJAX') || EM_AJAX );
	$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_events_default_limit');
	if( empty($args['format']) && empty($args['format_header']) && empty($args['format_footer']) ){
		ob_start();
		if( !empty($args['ajax']) ){ echo '<div class="em-search-ajax">'; } //open AJAX wrapper
		em_locate_template('templates/events-list.php', true, array('args'=>$args));
		if( !empty($args['ajax']) ) echo "</div>"; //close AJAX wrapper
		$return = ob_get_clean();
	}else{
		$return = EM_Events::output( $args );
	}
	return $return;
}
/**
 * Prints out a list of events, takes same arguments as em_get_events.
 * @param array|string $args
 * @uses em_get_events()
 */
function em_events( $args = array() ){ echo em_get_events($args); }

/**
 * Returns a html list of locations filtered by the array or query-string of arguments supplied. 
 * @param array|string $args
 * @return string
 */
function em_get_locations( $args = array() ){
	if ( is_string($args) && strpos ( $args, "=" )) {
		// allows the use of arguments without breaking the legacy code	
		$args = wp_parse_args ( $args, array() );
	}else{
		$args = (array) $args;
	}
	$args['ajax'] = isset($args['ajax']) ? $args['ajax']:(!defined('EM_AJAX') || EM_AJAX );
	$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_locations_default_limit');
	if( empty($args['format']) && empty($args['format_header']) && empty($args['format_footer']) ){
		ob_start();
		if( !empty($args['ajax']) ){ echo '<div class="em-search-ajax">'; } //open AJAX wrapper
		em_locate_template('templates/locations-list.php', true, array('args'=>$args));
		if( !empty($args['ajax']) ) echo "</div>"; //close AJAX wrapper
		$return = ob_get_clean();
	}else{
		//no ajax allowed with custom on-the-fly formats
		$return = EM_Locations::output( $args );
	}
	return $return;
}
/**
 * Prints out a list of locations, takes same arguments as em_get_locations.
 * @param array|string $args
 * @uses em_get_locations()
 */
function em_locations( $args = array() ){ echo em_get_locations($args); }

/**
 * Returns an html calendar of events filtered by the array or query-string of arguments supplied. 
 * @param array|string $args
 * @return string
 */
function em_get_calendar( $args = array() ){
	if ( !is_array($args) && strpos ( $args, "=" )) {
		// allows the use of arguments without breaking the legacy code
		$defaults = EM_Calendar::get_default_search();		
		$args = wp_parse_args ( $args, $defaults );
	}
	return EM_Calendar::output($args);
}
/**
 * Prints out an html calendar, takes same arguments as em_get_calendar.
 * @param array|string $args
 * @uses em_get_calendar()
 */
function em_calendar( $args = array() ){ echo em_get_calendar($args); }


/**
 * Generate a grouped list of events by year, month, week or day.
 * @since 4.213
 * @param array $args
 * @return string
 */
function em_get_events_list_grouped( $args = array() ){
	if ( is_string($args) && strpos ( $args, "=" )) {
		// allows the use of arguments without breaking the legacy code	
		$args = wp_parse_args ( $args, array() );
	}else{
		$args = (array) $args;
	}
	$args['ajax'] = isset($args['ajax']) ? $args['ajax']:(!defined('EM_AJAX') || EM_AJAX );
	if( empty($args['format']) && empty($args['format_header']) && empty($args['format_footer']) ){
		ob_start();
		if( !empty($args['ajax']) ){ echo '<div class="em-search-ajax">'; } //open AJAX wrapper
		em_locate_template('templates/events-grouped.php', true, array('args'=>$args));
		if( !empty($args['ajax']) ) echo "</div>"; //close AJAX wrapper
		$return = ob_get_clean();
	}else{
		$return = EM_Events::output_grouped( $args );
	}
	return $return;
}

/**
 * Print a grouped list of events by year, month, week or day.
 * @since 4.213
 * @param array $args
 * @param string $format
 * @return string
 */
function em_events_list_grouped( $args = array() ){ echo em_get_events_list_grouped($args); }

/**
 * Creates an html link to the events page.
 * @param string $text
 * @return string
 */
function em_get_link( $text = '' ) {
	$text = ($text == '') ? get_option ( "dbem_events_page_title" ) : $text;
	$text = ($text == '') ? __('Events','events-manager') : $text; //In case options aren't there....
	return '<a href="'.esc_url(EM_URI).'" title="'.esc_attr($text).'">'.esc_html($text).'</a>';
}
/**
 * Prints the result of em_get_link()
 * @param string $text
 * @uses em_get_link()
 */
function em_link($text = ''){ echo em_get_link($text); }

/**
 * Creates an html link to the RSS feed
 * @param string $text
 * @return string
 */
function em_get_rss_link($text = "RSS") {
	$text = ($text == '') ? 'RSS' : $text;
	return '<a href="'.esc_url(EM_RSS_URI).'">'.esc_html($text).'</a>';
}
/**
 * Prints the result of em_get_rss_link()
 * @param string $text
 * @uses em_get_rss_link()
 */
function em_rss_link($text = "RSS"){ echo em_get_rss_link($text); }

/* 
 * ---------------------------------------------------------------------
 * User Interfaces - Forms, Tables etc.
 * ---------------------------------------------------------------------
 */

//Event Forms
/**
 * Outputs the event submission form for guests and members.
 * @param array $args
 */
function em_event_form($args = array()){
	global $EM_Event;
	if( get_option('dbem_css_editors') ) echo '<div class="css-event-form">';
	if( !is_user_logged_in() && get_option('dbem_events_anonymous_submissions') && em_locate_template('forms/event-editor-guest.php') ){
		em_locate_template('forms/event-editor-guest.php',true, array('args'=>$args));
	}else{
	    if( !empty($_REQUEST['success']) ){
	    	$EM_Event = new EM_Event(); //reset the event
	    }
		if( empty($EM_Event->event_id) ){
			$EM_Event = ( is_object($EM_Event) && get_class($EM_Event) == 'EM_Event') ? $EM_Event : new EM_Event();
			//Give a default location & category
			$default_cat = get_option('dbem_default_category');
			$default_loc = get_option('dbem_default_location');
			if( get_option('dbem_categories_enabled') && is_numeric($default_cat) && $default_cat > 0 && !empty($EM_Event->get_categories()->categories) ){
				$EM_Category = new EM_Category($default_cat);
				$EM_Event->get_categories()->categories[] = $EM_Category;
			}
			if( is_numeric($default_loc) && $default_loc > 0 && ( empty($EM_Event->get_location()->location_id) && empty($EM_Event->get_location()->location_name) && empty($EM_Event->get_location()->location_address) && empty($EM_Event->get_location()->location_town) ) ){
				$EM_Event->location_id = $default_loc;
				$EM_Event->location = new EM_Location($default_loc);
			}
		}
		em_locate_template('forms/event-editor.php',true, array('args'=>$args));
	}
	if( get_option('dbem_css_editors') ) echo '</div>';
}
/**
 * Retreives the event submission form for guests and members.
 * @param array $args
 */
function em_get_event_form( $args = array() ){
	ob_start();
	em_event_form($args);
	return ob_get_clean();
}

/**
 * Outputs table of events belonging to user
 * Additional search arguments:
 * * show_add_new - passes argument to template as $show_add_new whether to show the add new event button
 * @param array $args
 */
function em_events_admin($args = array()){
	global $EM_Event, $bp;
	if( is_user_logged_in() && current_user_can('edit_events') ){
		if( !empty($_GET['action']) && $_GET['action']=='edit' ){
			if( empty($_REQUEST['redirect_to']) ){
				$_REQUEST['redirect_to'] = em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>null, 'event_id'=>null));
			}
			em_event_form();
		}else{
			if( get_option('dbem_css_editors') ) echo '<div class="css-events-admin">';
			//template $args for different views
		    $args_views['pending'] = array('status'=>0, 'owner' =>get_current_user_id(), 'scope' => 'all', 'recurring'=>'include');
		    $args_views['draft'] = array('status'=>null, 'owner' =>get_current_user_id(), 'scope' => 'all', 'recurring'=>'include');
		    $args_views['past'] = array('status'=>'all', 'owner' =>get_current_user_id(), 'scope' => 'past');
		    $args_views['future'] = array('status'=>'1', 'owner' =>get_current_user_id(), 'scope' => 'future');
		    // Hack PoP Plug-in: allow to modify the $args_views (and show events also from co-authors)
			$args_views['pending'] = apply_filters('gd_em_events_admin_pending_count_args', $args_views['pending'], $EM_Event);
			$args_views['draft'] = apply_filters('gd_em_events_admin_draft_count_args', $args_views['draft'], $EM_Event);
			$args_views['past'] = apply_filters('gd_em_events_admin_past_count_args', $args_views['past'], $EM_Event);
			$args_views['future'] = apply_filters('gd_em_events_admin_future_count_args', $args_views['future'], $EM_Event);

		    //get listing options for $args
			$limit = ( !empty($_REQUEST['limit']) ) ? $_REQUEST['limit'] : 20;//Default limit
			$page = ( !empty($_REQUEST['pno']) ) ? $_REQUEST['pno']:1;
			$offset = ( $page > 1 ) ? ($page-1)*$limit : 0;
			$order = ( !empty($_REQUEST ['order']) ) ? $_REQUEST ['order']:'ASC';
			$search = ( !empty($_REQUEST['em_search']) ) ? $_REQUEST['em_search']:'';
			//deal with view or scope/status combinations
			$show_add_new = isset($args['show_add_new']) ? $args['show_add_new']:true;
			$args = array('order' => $order, 'search' => $search, 'owner' => get_current_user_id());
			if( !empty($_REQUEST['view']) && in_array($_REQUEST['view'], array('future','draft','past','pending')) ){
	    	    $args = array_merge($args, $args_views[$_REQUEST['view']]);
			}else{
				$scope_names = em_get_scopes();
				$args['scope'] = ( !empty($_REQUEST ['scope']) && array_key_exists($_REQUEST ['scope'], $scope_names) ) ? $_REQUEST ['scope']:'future';
				if( array_key_exists('status', $_REQUEST) ){
					$status = ($_REQUEST['status']) ? 1:0;
					if($_REQUEST['status'] == 'all') $status = 'all';
					if($_REQUEST['status'] == 'draft') $status = null;
				}else{
					$status = false;
				}
				$args['status'] = $status;
			}
			// Hack PoP Plug-in: allow to modify the $args (and show events also from co-authors)
			$args = apply_filters('gd_em_events_admin_args', $args, $EM_Event);	
			
			$events_count = EM_Events::count( $args ); //count events without limits for pagination
			$args['limit'] = $limit;
			$args['offset'] = $offset;
			$EM_Events = EM_Events::get( $args ); //now get the limited events to display
			$future_count = EM_Events::count( $args_views['future'] );
			$pending_count = EM_Events::count( $args_views['pending'] );
			$draft_count = EM_Events::count( $args_views['draft'] );
			$past_count = EM_Events::count( $args_views['past'] );
			em_locate_template('tables/events.php',true, array(
				'args'=>$args, 
				'EM_Events'=>$EM_Events, 
				'events_count'=>$events_count, 
				'future_count'=>$future_count,
				'pending_count'=>$pending_count,
				'draft_count'=>$draft_count,
				'past_count'=>$past_count,
				'page' => $page,
				'limit' => $limit,
				'offset' => $offset,
				'show_add_new' => $show_add_new
			));
			if( get_option('dbem_css_editors') ) echo '</div>';
		}
	}elseif( !is_user_logged_in() && get_option('dbem_events_anonymous_submissions') ){
		em_event_form($args);
	}else{
		if( get_option('dbem_css_editors') ) echo '<div class="css-events-admin">';
		echo '<div class="css-events-admin-login">'. apply_filters('em_event_submission_login', __("You must log in to view and manage your events.",'events-manager')) . '</div>';
		if( get_option('dbem_css_editors') ) echo '</div>';
	}
}
/**
 * Retreives table of events belonging to user
 * @param array $args
 */
function em_get_events_admin( $args = array() ){
	ob_start();
	em_events_admin($args);
	return ob_get_clean();
}

/**
 * Outputs the event search form.
 * @param array $args
 */
function em_event_search_form($args = array()){
	$args = em_get_search_form_defaults($args);
	$args['ajax'] = isset($args['ajax']) ? $args['ajax']:(!defined('EM_AJAX') || EM_AJAX );
	em_locate_template('templates/events-search.php',true, array('args'=>$args));
}
/**
 * Retreives the event search form.
 * @param array $args
 */
function em_get_event_search_form( $args = array() ){
	ob_start();
	em_event_search_form($args);
	return ob_get_clean();
}

//Location Forms
/**
 * Outputs the location submission form for guests and members.
 * @param array $args
 */
function em_location_form($args = array()){
	global $EM_Location;
	if( get_option('dbem_css_editors') ) echo '<div class="css-location-form">';
	$EM_Location = ( is_object($EM_Location) && get_class($EM_Location) == 'EM_Location') ? $EM_Location : new EM_Location();
	em_locate_template('forms/location-editor.php',true);
	if( get_option('dbem_css_editors') ) echo '</div>';
}
/**
 * Retreives the location submission form for guests and members.
 * @param array $args
 */
function em_get_location_form( $args = array() ){
	ob_start();
	em_location_form($args);
	return ob_get_clean();
}

/**
 * Outputs table of locations belonging to user
 * @param array $args
 */
function em_locations_admin($args = array()){
	global $EM_Location;
	if( is_user_logged_in() && current_user_can('edit_locations') ){
		if( !empty($_GET['action']) && $_GET['action']=='edit' ){
			if( empty($_REQUEST['redirect_to']) ){
				$_REQUEST['redirect_to'] = em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>null, 'location_id'=>null));
			}
			em_location_form();
		}else{
			if( get_option('dbem_css_editors') ) echo '<div class="css-locations-admin">';
			$limit = ( !empty($_REQUEST['limit']) ) ? $_REQUEST['limit'] : 20;//Default limit
			$page = ( !empty($_REQUEST['pno']) ) ? $_REQUEST['pno']:1;
			$offset = ( $page > 1 ) ? ($page-1)*$limit : 0;
			$order = ( !empty($_REQUEST ['order']) ) ? $_REQUEST ['order']:'ASC';
			if( array_key_exists('status', $_REQUEST) ){
				$status = ($_REQUEST['status']) ? 1:0;
			}else{
				$status = false;
			}
			$blog = false;
			if( EM_MS_GLOBAL && !get_site_option('dbem_ms_mainblog_locations') && !is_main_site() ){
			    //set current blog id if not on main site and using global mode whilst not forcing all locations to be on main blog
			    $blog = get_current_blog_id();
			}
			$args = array('limit'=>$limit, 'offset'=>$offset, 'status'=>$status, 'blog'=>$blog);
			//count locations
			$locations_mine_count = EM_Locations::count( array('owner'=>get_current_user_id(), 'blog'=>$blog, 'status'=>false) );
			$locations_all_count = current_user_can('read_others_locations') ? EM_Locations::count(array('blog'=>$blog, 'status'=>false, 'owner'=>false)):0;
			//get set of locations
			if( !empty($_REQUEST['view']) && $_REQUEST['view'] == 'others' && current_user_can('read_others_locations') ){
				$locations = EM_Locations::get($args);
				$locations_count = $locations_all_count;
			}else{
				$locations = EM_Locations::get( array_merge($args, array('owner'=>get_current_user_id())) );
				$locations_count = $locations_mine_count;
			}
			em_locate_template('tables/locations.php',true, array(
				'args'=>$args, 
				'locations'=>$locations, 
				'locations_count'=>$locations_count, 
				'locations_mine_count'=>$locations_mine_count,
				'locations_all_count'=>$locations_all_count,
				'page' => $page,
				'limit' => $limit,
				'offset' => $offset,
				'show_add_new' => true
			));
			if( get_option('dbem_css_editors') ) echo '</div>';
		}
	}else{
		if( get_option('dbem_css_editors') ) echo '<div class="css-locations-admin">';
		echo '<div class="css-locations-admin-login">'. __("You must log in to view and manage your locations.",'events-manager') .'</div>';
		if( get_option('dbem_css_editors') ) echo '</div>';
	}
}
/**
 * Retreives table of locations belonging to user
 * @param array $args
 */
function em_get_locations_admin( $args = array() ){
	ob_start();
	em_locations_admin($args);
	return ob_get_clean();
}

/**
 * Outputs the location search form.
 * @param array $args
 */
function em_location_search_form($args = array()){
	$args = em_get_search_form_defaults($args);
	$args['ajax'] = isset($args['ajax']) ? $args['ajax']:(!defined('EM_AJAX') || EM_AJAX );
	em_locate_template('templates/locations-search.php',true, array('args'=>$args));
}
/**
 * Retreives the event search form.
 * @param array $args
 */
function em_get_location_search_form( $args = array() ){
	ob_start();
	em_location_search_form($args);
	return ob_get_clean();
}

//Bookings Pages
function em_bookings_admin(){
	if( get_option('dbem_css_rsvpadmin') ) echo '<div class="css-bookings-admin">';
	if( is_user_logged_in() && current_user_can('manage_bookings') ){
		global $wpdb, $current_user, $EM_Notices;
		include_once(EM_DIR.'/admin/em-bookings.php');
		include_once(EM_DIR.'/admin/em-admin.php');
		include_once(EM_DIR.'/admin/bookings/em-cancelled.php');
		include_once(EM_DIR.'/admin/bookings/em-confirmed.php');
		include_once(EM_DIR.'/admin/bookings/em-events.php');
		include_once(EM_DIR.'/admin/bookings/em-pending.php');
		include_once(EM_DIR.'/admin/bookings/em-person.php');
		include_once(EM_DIR.'/admin/bookings/em-rejected.php');
		em_bookings_page();
	}else{
		echo '<div class="css-bookings-admin-login">'. __("You must log in to view and manage your bookings.",'events-manager') .'</div>';
	}
	if( get_option('dbem_css_rsvpadmin') ) echo '</div>';
}
function em_get_bookings_admin(){
	ob_start();
	em_bookings_admin();
	return ob_get_clean();
}

function em_my_bookings(){
	if( get_option('dbem_css_rsvp') ) echo '<div class="css-my-bookings">';
	em_locate_template('templates/my-bookings.php', true);
	if( get_option('dbem_css_rsvp') ) echo '</div>';
}
function em_get_my_bookings(){
	ob_start();
	em_my_bookings();
	return ob_get_clean();	
}

/* 
 * ---------------------------------------------------------------------
 * Conditionals - Yes/No functions
 * ---------------------------------------------------------------------
 */

/**
 * Returns true if there are any events that exist in the given scope (default is future events).
 * @param string $scope
 * @return boolean
 */
function em_are_events_available($scope = "future") {
	$scope = ($scope == "") ? "future":$scope;
	$events = EM_Events::get( array('limit'=>1, 'scope'=>$scope) );	
	return ( count($events) > 0 );
}

/**
 * Returns true if the page is the events page. this is now only an events page, before v4.0.83 this would be true for any multiple page (e.g. locations) 
 * @return boolean
 */
function em_is_events_page() {
	global $post;
	return em_get_page_type() == 'events';
}

/**
 * Is this a a single event page?
 * @return boolean
 */
function em_is_event_page(){
	return em_get_page_type() == 'event';
}


/**
 * Is this a a single calendar day page?
 * @return boolean
 */
function em_is_calendar_day_page(){
	return em_get_page_type() == 'calendar_day';
}

/**
 * Is this a a single category page?
 * @return boolean
 */
function em_is_category_page( $category = false ){
    if( !empty($category) ){
        global $wp_query, $post, $em_category_id;
        if( is_tax(EM_TAXONOMY_CATEGORY, $category) ){ return true; }
        if( !empty($wp_query->em_category_id) || ($post->ID == get_option('dbem_categories_page') && !empty($em_category_id)) ){
			$cat_id = !empty($wp_query->em_category_id) ? $wp_query->em_category_id:$em_category_id;
            $EM_Category = em_get_category($cat_id);
            if( is_array($category) ){
                $is_category = array();
                foreach( $category as $id_or_term ){
                    $is_category[] = is_numeric($id_or_term) ? $EM_Category->id == $id_or_term : ($EM_Category->slug == $id_or_term || $EM_Category->name == $id_or_term);
                }
                return in_array(true, $is_category);
            }else{
                $is_category = is_numeric($category) ? $EM_Category->id == $category : ($EM_Category->slug == $category  || $EM_Category->name == $category);
                return $is_category;
            }
            return false;
        }
        return false;
    }
	return em_get_page_type() == 'category';
}
/**
 * Is this a categories list page?
 * @return boolean
 */
function em_is_categories_page(){
	return em_get_page_type() == 'categories';
}

/**
 * Is this a a single category page?
 * @return boolean
 */
function em_is_tag_page( $tag = false ){
	if( !empty($tag) ){
		global $wp_query, $post, $em_tag_id;
		if( is_tax(EM_TAXONOMY_TAG, $tag) ){ return true; }
		if( !empty($wp_query->em_tag_id) || !empty($em_tag_id) ){
			$tag_id = !empty($wp_query->em_tag_id) ? $wp_query->em_tag_id:$em_tag_id;
			$EM_Tag = em_get_tag($tag_id);
			if( is_array($tag) ){
				$is_tag = array();
				foreach( $tag as $id_or_term ){
					$is_tag[] = is_numeric($id_or_term) ? $EM_Tag->id == $id_or_term : ($EM_Tag->slug == $id_or_term || $EM_Tag->name == $id_or_term);
				}
				return in_array(true, $is_tag);
			}else{
				$is_tag = is_numeric($tag) ? $EM_Tag->id == $tag : ($EM_Tag->slug == $tag || $EM_Tag->name == $tag);
				return $is_tag;
			}
			return false;
		}
		return false;
	}
	return em_get_page_type() == 'tag';
}

/**
 * Is this a a single location page?
 * @return boolean
 */
function em_is_location_page(){
	return em_get_page_type() == 'location';
}
/**
 * Is this a locations list page?
 * @return boolean
 */
function em_is_locations_page(){
	return em_get_page_type() == 'locations';
}

/**
 * Is this my bookings page?
 * @return boolean
 */
function em_is_my_bookings_page(){
	return em_get_page_type() == 'my_bookings';
}

/**
 * Returns true if this is a single events page and the event is RSVPable
 * @return boolean
 */
function em_is_event_rsvpable() {
	//We assume that we're on a single event (or recurring event) page here, so $EM_Event must be loaded
	global $EM_Event;
	return ( em_is_event_page() && $EM_Event->rsvp );
}
?>