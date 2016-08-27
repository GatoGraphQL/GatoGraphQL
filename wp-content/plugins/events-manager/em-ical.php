<?php
	/**
	 * generates an ical feed on init if url is correct
	 */
	function em_ical( ){
		//check if this is a calendar request for all events
		if ( preg_match('/events.ics$/', $_SERVER['REQUEST_URI']) || $_SERVER['REQUEST_URI'] == '/?ical=1' ) {
			header('Content-type: text/calendar; charset=utf-8');
			header('Content-Disposition: inline; filename="events.ics"');
			//send headers
			em_locate_template('templates/ical.php', true);
			die();
		}
	}
	add_action ( 'init', 'em_ical' );
	
	/**
	 * Generates an ics file for a single event 
	 */
	function em_ical_item(){
		global $wpdb, $wp_query, $wp_rewrite;
		//check if we're outputting an ical feed
		if( !empty($wp_query) && $wp_query->get('ical') ){
			$execute_ical = false;
			$filename = 'events';
			$args = array();
			//single event
			if( $wp_query->get(EM_POST_TYPE_EVENT) ){
				$event_id = $wpdb->get_var('SELECT event_id FROM '.EM_EVENTS_TABLE." WHERE event_slug='".$wp_query->get(EM_POST_TYPE_EVENT)."' AND event_status=1 LIMIT 1");
				if( !empty($event_id) ){
					$filename = $wp_query->get(EM_POST_TYPE_EVENT);
					$args['event'] = $event_id;
				}
			//single location
			}elseif( $wp_query->get(EM_POST_TYPE_LOCATION) ){
				$location_id = $wpdb->get_var('SELECT location_id FROM '.EM_LOCATIONS_TABLE." WHERE location_slug='".$wp_query->get(EM_POST_TYPE_LOCATION)."' AND location_status=1 LIMIT 1");
				if( !empty($location_id) ){
					$filename = $wp_query->get(EM_POST_TYPE_LOCATION);
					$args['location'] = $location_id;
				}
			//taxonomies
			}else{
				$taxonomies = EM_Object::get_taxonomies();
				foreach($taxonomies as $tax_arg => $taxonomy_info){
					$taxonomy_term = $wp_query->get($taxonomy_info['query_var']); 
					if( $taxonomy_term ){
						$filename = $taxonomy_term;
						$args[$tax_arg] = $taxonomy_term;
					}
				}
			}
			//only output the ical if we have a match from above
			if( count($args) > 0 ){
				//send headers and output ical
				header('Content-type: text/calendar; charset=utf-8');
				header('Content-Disposition: inline; filename="'.$filename.'.ics"');
				em_locate_template('templates/ical.php', true, array('args'=>$args));
				exit();
			}else{
				//no item exists, so redirect to original URL
				$url_to_redirect = preg_replace("/ical\/$/",'', esc_url_raw(add_query_arg(array('ical'=>null))));				
				wp_redirect($url_to_redirect, '302');
				exit();
			}
		}
	}
	add_action ( 'parse_query', 'em_ical_item' );
?>