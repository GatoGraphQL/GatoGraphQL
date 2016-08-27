<?php
/*
 * This file contains the event related hooks in the front end, as well as some event template tags
 */

/**
 * Filters for page content and if an event replaces it with the relevant event data.
 * @param $data
 * @return string
 */
function em_content($page_content) {
	global $post, $wpdb, $wp_query, $EM_Event, $EM_Location, $EM_Category;
	if( empty($post) ) return $page_content; //fix for any other plugins calling the_content outside the loop
	$events_page_id = get_option ( 'dbem_events_page' );
	$locations_page_id = get_option( 'dbem_locations_page' );
	$categories_page_id = get_option( 'dbem_categories_page' );
	$tags_page_id = get_option( 'dbem_tags_page' );
	$edit_events_page_id = get_option( 'dbem_edit_events_page' );
	$edit_locations_page_id = get_option( 'dbem_edit_locations_page' );
	$edit_bookings_page_id = get_option( 'dbem_edit_bookings_page' );
	$my_bookings_page_id = get_option( 'dbem_my_bookings_page' );
	//general defaults
	$args = array(				
		'owner' => false,
		'pagination' => 1
	);
	$args['ajax'] = isset($args['ajax']) ? $args['ajax']:(!defined('EM_AJAX') || EM_AJAX );
	if( !post_password_required() && in_array($post->ID, array($events_page_id, $locations_page_id, $categories_page_id, $edit_bookings_page_id, $edit_events_page_id, $edit_locations_page_id, $my_bookings_page_id, $tags_page_id)) ){
		$content = apply_filters('em_content_pre', '', $page_content);
		if( empty($content) ){
			ob_start();
			if ( $post->ID == $events_page_id && $events_page_id != 0 ) {
				if ( !empty($_REQUEST['calendar_day']) ) {
					//Events for a specific day
					$args = EM_Events::get_post_search( array_merge($args, $_REQUEST) );
					em_locate_template('templates/calendar-day.php',true, array('args'=>$args));
				}elseif ( is_object($EM_Event)) {
					em_locate_template('templates/event-single.php',true, array('args'=>$args));	
				}else{
					// Multiple events page
					$args['orderby'] = get_option('dbem_events_default_orderby');
					$args['order'] = get_option('dbem_events_default_order');
					if (get_option ( 'dbem_display_calendar_in_events_page' )){
						$args['long_events'] = 1;
						em_locate_template('templates/events-calendar.php',true, array('args'=>$args));
					}else{
						//Intercept search request, if defined
						if( !empty($_REQUEST['action']) && ($_REQUEST['action'] == 'search_events' || $_REQUEST['action'] == 'search_events_grouped') ){
							$args = EM_Events::get_post_search( array_merge($args, $_REQUEST) );
						}
						if( empty($args['scope']) ){
						    $args['scope'] = get_option('dbem_events_page_scope');
						}
						if( get_option('dbem_events_page_search_form') ){
							//load the search form and pass on custom arguments (from settings page)
							$search_args = em_get_search_form_defaults();
							em_locate_template('templates/events-search.php', true, array('args'=>$search_args));
						}
						$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_events_default_limit');
						if( !empty($args['ajax']) ){ echo '<div class="em-search-ajax">'; } //AJAX wrapper open
						if( get_option('dbem_event_list_groupby') ){
							em_locate_template('templates/events-list-grouped.php', true, array('args'=>$args));
						}else{
							em_locate_template('templates/events-list.php', true, array('args'=>$args));
						}
						if( !empty($args['ajax']) ) echo "</div>"; //AJAX wrapper close
					}
				}
			}elseif( $post->ID == $locations_page_id && $locations_page_id != 0 ){
				$args['orderby'] = get_option('dbem_locations_default_orderby');
				$args['order'] = get_option('dbem_locations_default_order');
				$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_locations_default_limit');
				if( EM_MS_GLOBAL && is_object($EM_Location) ){
					em_locate_template('templates/location-single.php',true, array('args'=>$args));
				}else{
					//Intercept search request, if defined
					if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'search_locations' ){
						$args = EM_Locations::get_post_search( array_merge($args, $_REQUEST) );
					}
					if( get_option('dbem_locations_page_search_form') ){
						//load the search form and pass on custom arguments (from settings page)
						$search_args = em_get_search_form_defaults();
						//remove date and category
						$search_args['search_categories'] = $search_args['search_scope'] = false; 
						em_locate_template('templates/locations-search.php', true, array('args'=>$search_args));
					}
					if( !empty($args['ajax']) ){ echo '<div class="em-search-ajax">'; } //AJAX wrapper open
					em_locate_template('templates/locations-list.php',true, array('args'=>$args));
					if( !empty($args['ajax']) ) echo "</div>"; //AJAX wrapper close
				}
			}elseif( $post->ID == $categories_page_id && $categories_page_id != 0 ){
				$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_categories_default_limit');
				if( !empty($args['ajax']) ){ echo '<div class="em-search-ajax">'; } //AJAX wrapper open
				em_locate_template('templates/categories-list.php',true, array('args'=>$args));
				if( !empty($args['ajax']) ) echo "</div>"; //AJAX wrapper close
			}elseif( $post->ID == $tags_page_id && $tags_page_id != 0 ){
				$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_tags_default_limit');
				if( !empty($args['ajax']) ){ echo '<div class="em-search-ajax">'; } //AJAX wrapper open
				em_locate_template('templates/tags-list.php',true, array('args'=>$args));
				if( !empty($args['ajax']) ) echo "</div>"; //AJAX wrapper close
			}elseif( $post->ID == $edit_events_page_id && $edit_events_page_id != 0 ){
				em_events_admin();
			}elseif( $post->ID == $edit_locations_page_id && $edit_locations_page_id != 0 ){
				em_locations_admin();
			}elseif( $post->ID == $my_bookings_page_id && $my_bookings_page_id != 0 ){
				em_my_bookings();
			}elseif( $post->ID == $edit_bookings_page_id && $edit_bookings_page_id != 0 ){
				em_bookings_admin();
			}
			$content = ob_get_clean();
			//If disable rewrite flag is on, then we need to add a placeholder here
			if( get_option('dbem_disable_title_rewrites') == 1 ){
				$content = str_replace('#_PAGETITLE', em_content_page_title(''),get_option('dbem_title_html')) . $content;
			}
			//Now, we either replace CONTENTS or just replace the whole page
			if( preg_match('/CONTENTS/', $page_content) ){
				$content = str_replace('CONTENTS',$content,$page_content);
			}
			if(get_option('dbem_credits')){
				$content .= '<p style="color:#999; font-size:11px;">Powered by <a href="http://wp-events-plugin.com" style="color:#999;" target="_blank">Events Manager</a></p>';
			}
		}
		return apply_filters('em_content', '<div id="em-wrapper">'.$content.'</div>');
	}
	return $page_content;
}
add_filter('the_content', 'em_content');

/**
 * Filter for titles when on event pages
 * @param $data
 * @return string
 */
function em_content_page_title($original_content, $id = null) {
	global $EM_Event, $EM_Location, $EM_Category, $wp_query, $post;
	if( empty($post) ) return $original_content; //fix for any other plugins calling the_content outside the loop
	if ($id && $id !== $post->ID) return $original_content;
	
	$events_page_id = get_option ( 'dbem_events_page' );
	$locations_page_id = get_option( 'dbem_locations_page' );
	$edit_events_page_id = get_option( 'dbem_edit_events_page' );
	$edit_locations_page_id = get_option( 'dbem_edit_locations_page' );
	$edit_bookings_page_id = get_option( 'dbem_edit_bookings_page' );
	if( !empty($post->ID) && in_array($post->ID, array($events_page_id, $locations_page_id, $edit_events_page_id, $edit_locations_page_id, $edit_bookings_page_id))){
		//override the titles with this filter if needed, preventing the following code from being run
	    $content = apply_filters('em_content_page_title_pre', '', $original_content);
		if( empty($content) ){
			$content =  $original_content; //leave untouched by default
			if ( $post->ID == $events_page_id ) {
				if ( !empty( $_REQUEST['calendar_day'] ) ) {
					$events = EM_Events::get(array('limit'=>2,'scope'=>$_REQUEST['calendar_day'],'owner'=>false));
					if ( count($events) != 1 || get_option('dbem_display_calendar_day_single') == 1 ) {
						//We only support dates for the calendar day list title, so we do a simple filter for the supplied calendar_day
						$content = get_option ('dbem_list_date_title');
						preg_match_all("/#[A-Za-z0-9]+/", $content, $placeholders);
						foreach($placeholders[0] as $placeholder) {
							// matches all PHP date and time placeholders
							if (preg_match('/^#[dDjlNSwzWFmMntLoYyaABgGhHisueIOPTZcrU]$/', $placeholder)) {
								$content = str_replace($placeholder, mysql2date(ltrim($placeholder, "#"), $_REQUEST['calendar_day']),$content );
							}
						}
					}else{
						$event = array_shift($events);
						$content =  $event->output( get_option('dbem_event_page_title_format') );
					}
				}elseif ( EM_MS_GLOBAL && is_object($EM_Event) && !get_option('dbem_ms_global_events_links') ) {
					// single event page
					$content =  $EM_Event->output ( get_option ( 'dbem_event_page_title_format' ) );
				}
			}elseif( $post->ID == $locations_page_id ){
				if( EM_MS_GLOBAL && is_object($EM_Location) && get_option('dbem_ms_global_locations_links') ){
					$content = $EM_Location->output(get_option( 'dbem_location_page_title_format' ));
				}
			}elseif( $post->ID == $edit_events_page_id ){
				if( !empty($_REQUEST['action']) && $_REQUEST['action'] = 'edit' ){
					if( is_object($EM_Event) && $EM_Event->event_id){					
						if($EM_Event->is_recurring()){
							$content = __( "Reschedule Events", 'events-manager')." '{$EM_Event->event_name}'";
						}else{
							$content = __( "Edit Event", 'events-manager') . " '" . $EM_Event->event_name . "'";
						}
					}else{
						$content = __( 'Add Event', 'events-manager');
					}
				}
			}elseif( $post->ID == $edit_locations_page_id ){
				if( !empty($_REQUEST['action']) && $_REQUEST['action'] = 'edit' ){
					if( empty($EM_Location) || !is_object($EM_Location) ){
						$content = __('Add Location', 'events-manager');
					}else{
						$content = __('Edit Location', 'events-manager');
					}
				}
			}elseif( $post->ID == $edit_bookings_page_id){ 
				if( is_object($EM_Event) ){
					$content = $EM_Event->name .' - '. $original_content;
				}
			}
			return apply_filters('em_content_page_title', $content);
		}
	}
	return $original_content;
}

function em_content_wp_title($title, $sep = '', $seplocation = ''){
	global $EM_Location, $post;
	if( empty($post) ) return $title; //fix for any other plugins calling the_content outside the loop
	//single event and location page titles get parsed for formats
	if( is_single() && !empty($post->post_type) ){
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			$EM_Event = em_get_event($post);
			return $EM_Event->output($title);
		}elseif( $post->post_type == EM_POST_TYPE_LOCATION ){
			$EM_Location = em_get_location($post);
			return $EM_Location->output($title);
		}
	}
	//we only rewrite titles on events and locations pages since they are 'special'
	$pages = array( get_option('dbem_events_page'), get_option( 'dbem_locations_page' ) );
	if( !empty($post->ID) && !in_array($post->ID,$pages) ){ return $title; }
	// Determines position of the separator and direction of the breadcrumb
	$new_title = em_content_page_title($title);
	if( $new_title != $title ){
	    if( $sep == '' ) $sep = '  ';
		$title_array = explode( $sep, $title );
		foreach($title_array as $title_item_key => $title_item){ if(trim($title_item) == ''){ unset($title_array[$title_item_key]); } } //remove blanks
		$title_array[] = $new_title;
		if ( 'right' == $seplocation ) { // sep on right, so reverse the order
			array_unshift($title_array, '');
			$title_array = array_reverse( $title_array );
		}else{
		    $title_array[] = '';
		}
	    if( $sep == '  ' ) $sep = '';
		$title = implode( " $sep ", $title_array );
	}
	return $title;
}
add_filter ( 'wp_title', 'em_content_wp_title',100,3 ); //override other plugin SEO due to way EM works.
add_filter( 'wpseo_title', 'em_content_wp_title', 100, 3 ); //WP SEO friendly

/**
 * Makes sure we're in "THE Loop", which is determinied by a flag set when the_post() (start) is first called, and when have_posts() (end) returns false.
 * @param string $data
 * @return string
 */
function em_wp_the_title($data, $id = null){
	global $post, $wp_query, $EM_Location, $EM_Event;
	if( empty($post) ) return $data; //fix for any other plugins calling the_content outside the loop
	//because we're only editing the main title of the page here, we make sure we're in the main query
	if( is_main_query() && $id == $post->ID ){
	    $events_page_id = get_option ( 'dbem_events_page' );
	    $locations_page_id = get_option( 'dbem_locations_page' );
	    $edit_events_page_id = get_option( 'dbem_edit_events_page' );
	    $edit_locations_page_id = get_option( 'dbem_edit_locations_page' );
	    $edit_bookings_page_id = get_option( 'dbem_edit_bookings_page' );
		if( !empty($post->ID) && in_array($post->ID, array($events_page_id, $locations_page_id, $edit_events_page_id, $edit_locations_page_id, $edit_bookings_page_id)) ){
			if ( $wp_query->in_the_loop ) {
				return apply_filters('em_wp_the_title', em_content_page_title($data, $id)) ;
			}
		}elseif( is_single() && !empty($post->post_type) ){
			if( $post->post_type == EM_POST_TYPE_EVENT ){
				$EM_Event = em_get_event($post);
				return apply_filters('em_wp_the_title', $EM_Event->output($data)) ;
			}elseif( $post->post_type == EM_POST_TYPE_LOCATION ){
				$EM_Location = em_get_location($post);
				return apply_filters('em_wp_the_title', $EM_Location->output($data)) ;
			}
		}
	}
	return $data;
}
add_filter ( 'the_title', 'em_wp_the_title',10, 2 );


function em_get_page_type(){
	global $EM_Location, $EM_Category, $EM_Event, $wp_query, $post, $em_category_id, $em_tag_id;
	$events_page_id = get_option ( 'dbem_events_page' );
	$locations_page_id = get_option( 'dbem_locations_page' );
	$categories_page_id = get_option( 'dbem_categories_page' );
	$has_post = is_object($post);
	if ( !empty($events_page_id) && $has_post && $post->ID == $events_page_id ) {
		if ( $wp_query->get('calendar_day') ) {
			return "calendar_day";
		}else{
			return is_object($EM_Event) ? "event" : "events";
		}
	}elseif( empty($events_page_id) ){
		if( $wp_query->get('calendar_day') ){
			return "calendar_day";
		}
	}
	if( is_single() && $has_post && $post->post_type == EM_POST_TYPE_EVENT  ){
		return 'event';
	}
	if( (!empty($locations_page_id) && $has_post && $post->ID == $locations_page_id) || (!is_single() && $wp_query->query_vars['post_type'] == EM_POST_TYPE_LOCATION) ){
		return is_object($EM_Location) ? "location":"locations";
	}elseif( is_single() && $post->post_type == EM_POST_TYPE_LOCATION ){
		return 'location';
	}
	if( (!empty($categories_page_id) && $has_post && $post->ID == $categories_page_id) ){
		return "categories";
	}elseif( is_tax(EM_TAXONOMY_CATEGORY) || !empty($wp_query->em_category_id) || ($has_post && $post->ID == get_option('dbem_categories_page') && !empty($em_category_id)) ){
		return "category";
	}elseif( is_tax(EM_TAXONOMY_TAG) || !empty($wp_query->em_tag_id) || !empty($em_tag_id) ){
		return "tag";
	}
}
?>