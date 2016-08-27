<?php

if( !class_exists('EM_Permalinks') ){
	class EM_Permalinks {
		static $em_queryvars = array(
			'event_id','event_slug', 'em_redirect',
			'location_id','location_slug',
			'person_id',
			'booking_id',
			'category_id', 'category_slug',
			'ticket_id',
			'calendar_day',
			'rss', 'ical','event_categories','event_locations'
		);
		
		public static function init(){
			add_filter('pre_update_option_dbem_events_page', array('EM_Permalinks','option_update'));
			if( get_option('dbem_flush_needed') ){
				add_filter('wp_loaded', array('EM_Permalinks','flush')); //flush after init, in case there are themes adding cpts etc.
			}
			add_filter('rewrite_rules_array',array('EM_Permalinks','rewrite_rules_array'));
			add_filter('query_vars',array('EM_Permalinks','query_vars'));
			add_action('parse_query',array('EM_Permalinks','init_objects'), 1);
			add_action('parse_query',array('EM_Permalinks','redirection'), 1);
			if( !defined('EM_EVENT_SLUG') ){ define('EM_EVENT_SLUG','event'); }
			if( !defined('EM_LOCATION_SLUG') ){ define('EM_LOCATION_SLUG','location'); }
			if( !defined('EM_LOCATIONS_SLUG') ){ define('EM_LOCATIONS_SLUG','locations'); }
			if( !defined('EM_CATEGORY_SLUG') ){ define('EM_CATEGORY_SLUG','category'); }
			if( !defined('EM_CATEGORIES_SLUG') ){ define('EM_CATEGORIES_SLUG','categories'); }
			add_filter('post_type_archive_link',array('EM_Permalinks','post_type_archive_link'),10,2);
		}
		
		public static function flush(){
			global $wp_rewrite;
			$wp_rewrite->flush_rules();
			delete_option('dbem_flush_needed');
		}
		
		public static function post_type_archive_link($link, $post_type){
			if( $post_type == EM_POST_TYPE_EVENT ){
				if( get_option('dbem_events_page') ){
					$new_link = get_permalink(get_option('dbem_events_page'));
				}
			}
			if( $post_type == EM_POST_TYPE_LOCATION ){
				if( get_option('dbem_locations_page') ){
					$new_link = get_permalink(get_option('dbem_locations_page'));
				}
			}
			if( !empty($new_link) ){
				$link = $new_link;
			}
			return $link;
		}
		
		/**
		 * will redirect old links to new link structures.
		 */
		public static function redirection(){
			global $wpdb, $wp_query;
			if( is_object($wp_query) && $wp_query->get('em_redirect') ){
				//is this a querystring url?
				if( $wp_query->get('event_slug') ){
					$event = $wpdb->get_row('SELECT event_id, post_id FROM '.EM_EVENTS_TABLE." WHERE event_slug='".$wp_query->get('event_slug')."' AND (blog_id=".get_current_blog_id()." OR blog_id IS NULL OR blog_id=0)", ARRAY_A);
					if( !empty($event) ){
						$EM_Event = em_get_event($event['event_id']);
						$url = get_permalink($EM_Event->post_id);
					}
				}elseif( $wp_query->get('location_slug') ){
					$location = $wpdb->get_row('SELECT location_id, post_id FROM '.EM_LOCATIONS_TABLE." WHERE location_slug='".$wp_query->get('location_slug')."' AND (blog_id=".get_current_blog_id()." OR blog_id IS NULL OR blog_id=0)", ARRAY_A);
					if( !empty($location) ){
						$EM_Location = em_get_location($location['location_id']);
						$url = get_permalink($EM_Location->post_id);
					}
				}elseif( $wp_query->get('category_slug') ){
					$url = get_term_link($wp_query->get('category_slug'), EM_TAXONOMY_CATEGORY);
				}
				if(!empty($url)){
					wp_redirect($url,301);
					exit();
				}
			}
		}

		// Adding a new rule
		public static function rewrite_rules_array($rules){
			global $wpdb;
			//get the slug of the event page
			$events_page_id = get_option ( 'dbem_events_page' );
			$events_page = get_post($events_page_id);
			$em_rules = array();
			if( is_object($events_page) ){
				$events_slug = urldecode(preg_replace('/\/$/', '', str_replace( trailingslashit(home_url()), '', get_permalink($events_page_id)) ));
				$events_slug = ( !empty($events_slug) ) ? trailingslashit($events_slug) : $events_slug;
				$em_rules[$events_slug.'(\d{4}-\d{2}-\d{2})$'] = 'index.php?pagename='.$events_slug.'&calendar_day=$matches[1]'; //event calendar date search
				if( $events_page_id != get_option('page_on_front') && EM_POST_TYPE_EVENT_SLUG != $events_slug ){ //ignore this rule if events page is the home page
					$em_rules[$events_slug.'rss/?$'] = 'index.php?post_type='.EM_POST_TYPE_EVENT.'&feed=feed'; //rss page
					$em_rules[$events_slug.'feed/?$'] = 'index.php?post_type='.EM_POST_TYPE_EVENT.'&feed=feed'; //compatible rss page
				}
				if( EM_POST_TYPE_EVENT_SLUG.'/' == $events_slug ){ //won't apply on homepage
					//make sure we hard-code rewrites for child pages of events
					$child_posts = $wpdb->get_results("SELECT ID, post_name FROM {$wpdb->posts} WHERE post_parent={$events_page->ID} AND post_type='page' AND post_status='publish'");
					foreach($child_posts as $child_post){
						$em_rules[$events_slug.urldecode($child_post->post_name).'/?$'] = 'index.php?page_id='.$child_post->ID; //single event booking form with slug    //check if child page has children
					    $grandchildren = $wpdb->get_results("SELECT ID, post_name FROM {$wpdb->posts} WHERE post_parent={$child_post->ID} AND post_type='page' AND post_status='publish'");
					    if( count( $grandchildren ) != 0 ) { 
					        foreach($grandchildren as $grandchild) {
					            $em_rules[$events_slug.urldecode($child_post->post_name).'/'.urldecode($grandchild->post_name).'/?$'] = 'index.php?page_id='.$grandchild->ID;
					        }
					    }
					}
				}elseif( empty($events_slug) ){ //hard code homepage child pages
					$child_posts = $wpdb->get_results("SELECT ID, post_name FROM {$wpdb->posts} WHERE post_parent={$events_page->ID} AND post_type='page' AND post_status='publish'");
					foreach($child_posts as $child_post){
						$em_rules[$events_page->post_name.'/'.urldecode($child_post->post_name).'/?$'] = 'index.php?page_id='.$child_post->ID; //single event booking form with slug    //check if child page has children
					    $grandchildren = $wpdb->get_results("SELECT ID, post_name FROM {$wpdb->posts} WHERE post_parent={$child_post->ID} AND post_type='page' AND post_status='publish'");
					    if( count( $grandchildren ) != 0 ) { 
					        foreach($grandchildren as $grandchild) {
					            $em_rules[$events_slug.urldecode($child_post->post_name).'/'.urldecode($grandchild->post_name).'/?$'] = 'index.php?page_id='.$grandchild->ID;
					        }
					    }
					}
				}
				//global links hard-coded
				if( EM_MS_GLOBAL && !get_site_option('dbem_ms_global_events_links', true) ){
					//MS Mode has slug also for global links
					$em_rules[$events_slug.get_site_option('dbem_ms_events_slug',EM_EVENT_SLUG).'/(.+)$'] = 'index.php?pagename='.$events_slug.'&em_redirect=1&event_slug=$matches[1]'; //single event from subsite
				}
				//add redirection for backwards compatability
				$em_rules[$events_slug.EM_EVENT_SLUG.'/(.+)$'] = 'index.php?pagename='.$events_slug.'&em_redirect=1&event_slug=$matches[1]'; //single event
				$em_rules[$events_slug.EM_LOCATION_SLUG.'/(.+)$'] = 'index.php?pagename='.$events_slug.'&em_redirect=1&location_slug=$matches[1]'; //single location page
				$em_rules[$events_slug.EM_CATEGORY_SLUG.'/(.+)$'] = 'index.php?pagename='.$events_slug.'&em_redirect=1&category_slug=$matches[1]'; //single category page slug
				//add a rule that ensures that the events page is found and used over other pages
				$em_rules[trim($events_slug,'/').'/?$'] = 'index.php?pagename='.trim($events_slug,'/') ;
			}else{
				$events_slug = EM_POST_TYPE_EVENT_SLUG;
				$em_rules[$events_slug.'/(\d{4}-\d{2}-\d{2})$'] = 'index.php?post_type='.EM_POST_TYPE_EVENT.'&calendar_day=$matches[1]'; //event calendar date search
				if( get_option('dbem_rsvp_enabled') ){
					if( !get_option( 'dbem_my_bookings_page') || !is_object(get_post(get_option( 'dbem_my_bookings_page'))) ){ //only added if bookings page isn't assigned
						$em_rules[$events_slug.'/my\-bookings$'] = 'index.php?post_type='.EM_POST_TYPE_EVENT.'&bookings_page=1'; //page for users to manage bookings
					}
				}
				//check for potentially conflicting posts with the same slug as events
				$conflicting_posts = get_posts(array('name'=>EM_POST_TYPE_EVENT_SLUG, 'post_type'=>'any', 'numberposts'=>0));
				if( count($conflicting_posts) > 0 ){ //won't apply on homepage
					foreach($conflicting_posts as $conflicting_post){
						//make sure we hard-code rewrites for child pages of events
						$child_posts = get_posts(array('post_type'=>'any', 'post_parent'=>$conflicting_post->ID, 'numberposts'=>0));
						foreach($child_posts as $child_post){
							$em_rules[EM_POST_TYPE_EVENT_SLUG.'/'.urldecode($child_post->post_name).'/?$'] = 'index.php?page_id='.$child_post->ID; //single event booking form with slug
							//check if child page has children
							$grandchildren = get_pages('child_of='.$child_post->ID);
							if( count( $grandchildren ) != 0 ) {
								foreach($grandchildren as $grandchild) {
									$em_rules[$events_slug.urldecode($child_post->post_name).'/'.urldecode($grandchild->post_name).'/?$'] = 'index.php?page_id='.$grandchild->ID;
								}
							}
						}
					}
				}
			}
			$em_rules = apply_filters('em_rewrite_rules_array_events', $em_rules, $events_slug);
			//make sure there's no page with same name as archives, that should take precedence as it can easily be deleted wp admin side
			$em_query = new WP_Query(array('pagename'=>EM_POST_TYPE_EVENT_SLUG));
			if( $em_query->have_posts() ){
				$em_rules[trim(EM_POST_TYPE_EVENT_SLUG,'/').'/?$'] = 'index.php?pagename='.trim(EM_POST_TYPE_EVENT_SLUG,'/') ;
				wp_reset_postdata();
			}
			//make sure there's no page with same name as archives, that should take precedence as it can easily be deleted wp admin side
			$em_query = new WP_Query(array('pagename'=>EM_POST_TYPE_LOCATION_SLUG));
			if( $em_query->have_posts() ){
				$em_rules[trim(EM_POST_TYPE_LOCATION_SLUG,'/').'/?$'] = 'index.php?pagename='.trim(EM_POST_TYPE_LOCATION_SLUG,'/') ;
				wp_reset_postdata();
			}
			//If in MS global mode and locations are linked on same site
			if( EM_MS_GLOBAL && !get_site_option('dbem_ms_global_locations_links', true) ){
				$locations_page_id = get_option ( 'dbem_locations_page' );
				$locations_page = get_post($locations_page_id);
				if( is_object($locations_page) ){
					$locations_slug = preg_replace('/\/$/', '', str_replace( trailingslashit(home_url()), '', get_permalink($locations_page_id) ));
					$locations_slug_slashed = ( !empty($locations_slug) ) ? trailingslashit($locations_slug) : $locations_slug;
					$em_rules[$locations_slug.'/'.get_site_option('dbem_ms_locations_slug',EM_LOCATION_SLUG).'/(.+)$'] = 'index.php?pagename='.$locations_slug_slashed.'&location_slug=$matches[1]'; //single event booking form with slug
				}					
			}
			//add ical CPT endpoints
			$em_rules[EM_POST_TYPE_EVENT_SLUG."/([^/]+)/ical/?$"] = 'index.php?'.EM_POST_TYPE_EVENT.'=$matches[1]&ical=1';
			if( get_option('dbem_locations_enabled') ){
				$em_rules[EM_POST_TYPE_LOCATION_SLUG."/([^/]+)/ical/?$"] = 'index.php?'.EM_POST_TYPE_LOCATION.'=$matches[1]&ical=1';
			}
			//add ical taxonomy endpoints
			$taxonomies = EM_Object::get_taxonomies();
			foreach($taxonomies as $tax_arg => $taxonomy_info){
				//set the dynamic rule for this taxonomy
				$em_rules[$taxonomy_info['slug']."/([^/]+)/ical/?$"] = 'index.php?'.$taxonomy_info['query_var'].'=$matches[1]&ical=1';
			}
			//add RSS location CPT endpoint
			if( get_option('dbem_locations_enabled') ){
				$em_rules[EM_POST_TYPE_LOCATION_SLUG."/([^/]+)/rss/?$"] = 'index.php?'.EM_POST_TYPE_LOCATION.'=$matches[1]&rss=1';
			}
			return $em_rules + $rules;
		}
		
		/**
		 * deprecated, use get_post_permalink() from now on or the output function with a placeholder
		 * Generate a URL. Pass each section of a link as a parameter, e.g. EM_Permalinks::url('event',$event_id); will create an event link.
		 * @return string 
		 */
		public static function url(){
			global $wp_rewrite;
			$args = func_get_args();
			$em_uri = get_permalink(get_option("dbem_events_page")); //PAGE URI OF EM
			if ( $wp_rewrite->using_permalinks() /*&& !defined('EM_DISABLE_PERMALINKS')*/ ) {
				$event_link = trailingslashit(trailingslashit($em_uri). implode('/',$args));
			}
			return $event_link;
		}
		
		/**
		 * checks if the events page has changed, and sets a flag to flush wp_rewrite.
		 * @param mixed $val
		 * @return mixed
		 */
		public static function option_update( $val ){
			if( get_option('dbem_events_page') != $val ){
				update_option('dbem_flush_needed',1);
			}
		   	return $val;
		}
		
		// Adding the id var so that WP recognizes it
		public static function query_vars($vars){
			foreach(self::$em_queryvars as $em_queryvar){
				array_push($vars, $em_queryvar);
			}
		    return $vars;
		}
		
		/**
		 * Not the "WP way" but for now this'll do!
		 */
		public static function init_objects(){
			global $wp_rewrite, $wp_query;
			//check some homepage conditions
			$events_page_id = get_option ( 'dbem_events_page' );
			if( is_object($wp_query) && $wp_query->is_home && !$wp_query->is_posts_page && 'page' == get_option('show_on_front') && get_option('page_on_front') == $events_page_id ){
				$wp_query->is_page = true;
				$wp_query->is_home = false;
				$wp_query->query_vars['page_id'] = $events_page_id;
			}
			if ( is_object($wp_query) && is_object($wp_rewrite) && $wp_rewrite->using_permalinks() ) {
				foreach(self::$em_queryvars as $em_queryvar){
					if( $wp_query->get($em_queryvar) ) {
						$_REQUEST[$em_queryvar] = $wp_query->get($em_queryvar);
					}
				}
		    }
			//dirty rss condition
			if( !empty($_REQUEST['rss']) ){
				$_REQUEST['rss_main'] = 'main';
			}
		}
	}
	EM_Permalinks::init();
}

//Specific links that aren't generated by objects

/**
 * returns the url of the my bookings page, depending on the settings page and if BP is installed.
 * @return string
 */
function em_get_my_bookings_url(){
	global $bp, $wp_rewrite;
	if( !empty($bp->events->link) ){
		//get member url
		return $bp->events->link.'attending/';
	}elseif( get_option('dbem_my_bookings_page') ){
		return get_permalink(get_option('dbem_my_bookings_page'));
	}else{
		if( $wp_rewrite->using_permalinks() && !defined('EM_DISABLE_PERMALINKS') ){
			return trailingslashit(EM_URI)."my-bookings/";
		}else{
			return preg_match('/\?/',EM_URI) ? EM_URI.'&bookings_page=1':EM_URI.'?bookings_page=1';
		}
	}
}
