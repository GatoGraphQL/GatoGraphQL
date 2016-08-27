<?php
/**
 * Controls how events are queried and displayed via the WordPress Custom Post APIs
 * @author marcus
 *
 */
class EM_Event_Post {
	
	public static function init(){
		global $wp_query;
		//Front Side Modifiers
		if( !is_admin() ){
			//override single page with formats? 
			add_filter('the_content', array('EM_Event_Post','the_content'));
			add_filter('the_excerpt_rss', array('EM_Event_Post','the_excerpt_rss'));
			if( get_option('dbem_cp_events_excerpt_formats') ){
			    add_filter('the_excerpt', array('EM_Event_Post','the_excerpt'));
			}
			//display as page template?
			if( get_option('dbem_cp_events_template') ){
				add_filter('single_template',array('EM_Event_Post','single_template'));
			}
			//add classes to body and post_class()
			if( get_option('dbem_cp_events_post_class') != '' ){
			    add_filter('post_class', array('EM_Event_Post','post_class'), 10, 3);
			}
			if( get_option('dbem_cp_events_body_class') != '' ){
			    add_filter('body_class', array('EM_Event_Post','body_class'), 10, 3);
			}
			//Override post template tags
			add_filter('the_date',array('EM_Event_Post','the_date'),10,2);
			add_filter('get_the_date',array('EM_Event_Post','the_date'),10,2);
			add_filter('the_time',array('EM_Event_Post','the_time'),10,2);
			add_filter('get_the_time',array('EM_Event_Post','the_time'),10,2);
			add_filter('the_category',array('EM_Event_Post','the_category'),10,3);
		}
		add_action('parse_query', array('EM_Event_Post','parse_query'));
		add_action('publish_future_post',array('EM_Event_Post','publish_future_post'),10,1);
	}
	
	public static function publish_future_post($post_id){
		global $wpdb, $EM_Event, $EM_Location, $EM_Notices;
		$post_type = get_post_type($post_id);
		$is_post_type = $post_type == EM_POST_TYPE_EVENT || $post_type == 'event-recurring';
		$saving_status = !in_array(get_post_status($post_id), array('trash','auto-draft')) && !defined('DOING_AUTOSAVE');
		if(!defined('UNTRASHING_'.$post_id) && $is_post_type && $saving_status ){
		    $EM_Event = em_get_event($post_id, 'post_id');
		    $EM_Event->set_status(1);
		}
	}
	
	/**
	 * Overrides the default post format of an event and can display an event as a page, which uses the page.php template.
	 * @param string $template
	 * @return string
	 */
	public static function single_template($template){
		global $post;
		if( !locate_template('single-'.EM_POST_TYPE_EVENT.'.php') && $post->post_type == EM_POST_TYPE_EVENT ){
			//do we have a default template to choose for events?
			if( get_option('dbem_cp_events_template') == 'page' ){
				$post_templates = array('page.php','index.php');
			}else{
			    $post_templates = array(get_option('dbem_cp_events_template'));
			}
			if( !empty($post_templates) ){
			    $post_template = locate_template($post_templates,false);
			    if( !empty($post_template) ) $template = $post_template;
			}
		}
		return $template;
	}
	
	public static function post_class( $classes, $class, $post_id ){
	    $post = get_post($post_id);
	    if( $post->post_type == EM_POST_TYPE_EVENT ){
	        foreach( explode(' ', get_option('dbem_cp_events_post_class')) as $class ){
	            $classes[] = esc_attr($class);
	        }
	    }
	    return $classes;
	}
	
	public static function body_class( $classes ){
	    if( em_is_event_page() ){
	        foreach( explode(' ', get_option('dbem_cp_events_body_class')) as $class ){
	            $classes[] = esc_attr($class);
	        }
	    }
	    return $classes;
	}
	
	/**
	 * Overrides the_excerpt if this is an event post type
	 */
	public static function the_excerpt($content){
		global $post;
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			$EM_Event = em_get_event($post);
			$output = !empty($EM_Event->post_excerpt) ? get_option('dbem_event_excerpt_format'):get_option('dbem_event_excerpt_alt_format');
			$content = $EM_Event->output($output);
		}
		return $content;
	}
	
	public static function the_excerpt_rss( $content ){
		global $post;
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			if( get_option('dbem_cp_events_formats') ){
				$EM_Event = em_get_event($post);
				$content = $EM_Event->output( get_option ( 'dbem_rss_description_format' ), "rss");
				$content = ent2ncr(convert_chars($content)); //Some RSS filtering
			}
		}
		return $content;
	}
	
	public static function the_content( $content ){
		global $post, $EM_Event;
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			if( is_archive() || is_search() ){
				if(get_option('dbem_cp_events_archive_formats')){
					$EM_Event = em_get_event($post);
					$content = $EM_Event->output(get_option('dbem_event_list_item_format'));
				}
			}else{
				if( get_option('dbem_cp_events_formats') && !post_password_required() ){
					$EM_Event = em_get_event($post);
					ob_start();
					em_locate_template('templates/event-single.php',true);
					$content = ob_get_clean();
				}elseif( !post_password_required() ){
					$EM_Event = em_get_event($post);
					if( $EM_Event->event_rsvp && (!defined('EM_DISABLE_AUTO_BOOKINGSFORM') || !EM_DISABLE_AUTO_BOOKINGSFORM) ){
					    $content .= $EM_Event->output('<h2>Bookings</h2>#_BOOKINGFORM');
					}
				}
			}
		}
		return $content;
	}
	
	public static function the_date( $the_date, $d = '' ){
		global $post;
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			$EM_Event = em_get_event($post);
			if ( '' == $d ){
				$the_date = date_i18n(get_option('date_format'), $EM_Event->start);
			}else{
				$the_date = date_i18n($d, $EM_Event->start);
			}
		}
		return $the_date;
	}
	
	public static function the_time( $the_time, $f = '' ){
		global $post;
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			$EM_Event = em_get_event($post);
			if ( '' == $f ){
				$the_time = date_i18n(get_option('time_format'), $EM_Event->start);
			}else{
				$the_time = date_i18n($f, $EM_Event->start);
			}
		}
		return $the_time;
	}
	
	public static function the_category( $thelist, $separator = '', $parents='' ){
		global $post, $wp_rewrite;
		if( $post->post_type == EM_POST_TYPE_EVENT ){
			$EM_Event = em_get_event($post);
			$categories = $EM_Event->get_categories();
			if( empty($categories) ) return '';
			
			/* Copied from get_the_category_list function, with a few minor edits to make urls work, and removing parent stuff (for now) */
			$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';

			$thelist = '';
			if ( '' == $separator ) {
				$thelist .= '<ul class="post-categories">';
				foreach ( $categories as $category ) {
					$thelist .= "\n\t<li>";
					switch ( strtolower( $parents ) ) {
						case 'multiple':
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
							break;
						case 'single':
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>';
							$thelist .= $category->name.'</a></li>';
							break;
						case '':
						default:
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
					}
				}
				$thelist .= '</ul>';
			} else {
				$i = 0;
				foreach ( $categories as $category ) {
					if ( 0 < $i )
						$thelist .= $separator;
					switch ( strtolower( $parents ) ) {
						case 'multiple':
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a>';
							break;
						case 'single':
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>';
							$thelist .= "$category->name</a>";
							break;
						case '':
						default:
							$thelist .= '<a href="' . $category->get_url() . '" title="' . esc_attr( sprintf( __( "View all posts in %s", 'events-manager'), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a>';
					}
					++$i;
				}
			}
			/* End copying */
		}
		return $thelist;
	}
	
	public static function parse_query(){
	    global $wp_query;
		//Search Query Filtering
		if( is_admin() ){
		    if( !empty($wp_query->query_vars[EM_TAXONOMY_CATEGORY]) && is_numeric($wp_query->query_vars[EM_TAXONOMY_CATEGORY]) ){
		        //sorts out filtering admin-side as it searches by id
		        $term = get_term_by('id', $wp_query->query_vars[EM_TAXONOMY_CATEGORY], EM_TAXONOMY_CATEGORY);
		        $wp_query->query_vars[EM_TAXONOMY_CATEGORY] = ( $term !== false && !is_wp_error($term) )? $term->slug:0;
		    }
		}
		//Scoping
		if( !empty($wp_query->query_vars['post_type']) && ($wp_query->query_vars['post_type'] == EM_POST_TYPE_EVENT || $wp_query->query_vars['post_type'] == 'event-recurring') && (empty($wp_query->query_vars['post_status']) || !in_array($wp_query->query_vars['post_status'],array('trash','pending','draft'))) ) {
			//Let's deal with the scope - default is future
			if( is_admin() ){
				$scope = $wp_query->query_vars['scope'] = (!empty($_REQUEST['scope'])) ? $_REQUEST['scope']:'future';
				//TODO limit what a user can see admin side for events/locations/recurring events
			}else{
				if( !empty($wp_query->query_vars['calendar_day']) ) $wp_query->query_vars['scope'] = $wp_query->query_vars['calendar_day'];
				if( empty($wp_query->query_vars['scope']) ){
					if( is_archive() ){
						$scope = $wp_query->query_vars['scope'] = get_option('dbem_events_archive_scope');
					}else{
						$scope = $wp_query->query_vars['scope'] = 'all'; //otherwise we'll get 404s for past events
					}
				}else{
					$scope = $wp_query->query_vars['scope'];
				}
			}
			$query = array();
			$time = current_time('timestamp');
			if ( preg_match ( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $scope ) ) {
				$today = strtotime($scope);
				$tomorrow = $today + 60*60*24-1;
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_start_ts', 'value' => array($today,$tomorrow), 'compare' => 'BETWEEN' );
				}else{
					$query[] = array( 'key' => '_start_ts', 'value' => $tomorrow, 'compare' => '<=' );
					$query[] = array( 'key' => '_end_ts', 'value' => $today, 'compare' => '>=' );
				}				
			}elseif ($scope == "future"){
				$today = strtotime(date('Y-m-d', $time));
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_start_ts', 'value' => $today, 'compare' => '>=' );
				}else{
					$query[] = array( 'key' => '_end_ts', 'value' => $today, 'compare' => '>=' );
				}
			}elseif ($scope == "past"){
				$today = strtotime(date('Y-m-d', $time));
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_start_ts', 'value' => $today, 'compare' => '<' );
				}else{
					$query[] = array( 'key' => '_end_ts', 'value' => $today, 'compare' => '<' );
				}
			}elseif ($scope == "today"){
				$today = strtotime(date('Y-m-d', $time));
				$tomorrow = strtotime(date('Y-m-d',$time+60*60*24));
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					//date must be only today
					$query[] = array( 'key' => '_start_ts', 'value' => array($today, $tomorrow), 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_start_ts', 'value' => $tomorrow, 'compare' => '<' );
					$query[] = array( 'key' => '_end_ts', 'value' => $today, 'compare' => '>=' );
				}
			}elseif ($scope == "tomorrow"){
				$tomorrow = strtotime(date('Y-m-d',$time+60*60*24));
				$after_tomorrow = $tomorrow + 60*60*24;
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					//date must be only tomorrow
					$query[] = array( 'key' => '_start_ts', 'value' => array($tomorrow, $after_tomorrow), 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_start_ts', 'value' => $after_tomorrow, 'compare' => '<' );
					$query[] = array( 'key' => '_end_ts', 'value' => $tomorrow, 'compare' => '>=' );
				}
			}elseif ($scope == "month"){
				$start_month = strtotime(date('Y-m-d',$time));
				$end_month = strtotime(date('Y-m-t',$time)) + 86399;
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_start_ts', 'value' => array($start_month,$end_month), 'type' => 'numeric', 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_start_ts', 'value' => $end_month, 'compare' => '<=' );
					$query[] = array( 'key' => '_end_ts', 'value' => $start_month, 'compare' => '>=' );
				}
			}elseif ($scope == "next-month"){
				$start_month_timestamp = strtotime('+1 month', $time); //get the end of this month + 1 day
				$start_month = strtotime(date('Y-m-1',$start_month_timestamp));
				$end_month = strtotime(date('Y-m-t',$start_month_timestamp)) + 86399;
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_start_ts', 'value' => array($start_month,$end_month), 'type' => 'numeric', 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_start_ts', 'value' => $end_month, 'compare' => '<=' );
					$query[] = array( 'key' => '_end_ts', 'value' => $start_month, 'compare' => '>=' );
				}
			}elseif( preg_match('/(\d\d?)\-months/',$scope,$matches) ){ // next x months means this month (what's left of it), plus the following x months until the end of that month.
				$months_to_add = $matches[1];
				$start_month = strtotime(date('Y-m-d',$time));
				$end_month = strtotime(date('Y-m-t',strtotime("+$months_to_add month", $time))) + 86399;
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_start_ts', 'value' => array($start_month,$end_month), 'type' => 'numeric', 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_start_ts', 'value' => $end_month, 'compare' => '<=' );
					$query[] = array( 'key' => '_end_ts', 'value' => $start_month, 'compare' => '>=' );
				}
			}
		  	if( !empty($query) && is_array($query) ){
				$wp_query->query_vars['meta_query'] = $query;
		  	}
		  	if( is_admin() ){
		  		//admin areas don't need special ordering, so make it simple
			  	$wp_query->query_vars['orderby'] = (!empty($_REQUEST['orderby'])) ? $_REQUEST['orderby']:'meta_value_num';
			  	$wp_query->query_vars['meta_key'] = '_start_ts'; 
				$wp_query->query_vars['order'] = (!empty($_REQUEST['order'])) ? $_REQUEST['order']:'ASC';
		  	}else{
			  	if( get_option('dbem_events_default_archive_orderby') == 'title'){
			  		$wp_query->query_vars['orderby'] = 'title';
					$wp_query->query_vars['order'] = get_option('dbem_events_default_archive_order','ASC');
			  	}else{
				  	$wp_query->query_vars['orderby'] = 'meta_value_num';
				  	$wp_query->query_vars['meta_key'] = '_start_ts';  		
			  	}
			  	$wp_query->query_vars['order'] = get_option('dbem_events_default_archive_order','ASC');
		  	}
		}elseif( !empty($wp_query->query_vars['post_type']) && $wp_query->query_vars['post_type'] == EM_POST_TYPE_EVENT ){
			$wp_query->query_vars['scope'] = 'all';
			if( $wp_query->query_vars['post_status'] == 'pending' ){
			  	$wp_query->query_vars['orderby'] = 'meta_value_num';
			  	$wp_query->query_vars['order'] = 'ASC';
			  	$wp_query->query_vars['meta_key'] = '_start_ts';
			}
		}
	}
}
EM_Event_Post::init();