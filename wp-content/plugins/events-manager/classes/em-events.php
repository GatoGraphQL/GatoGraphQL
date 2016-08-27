<?php
//TODO EM_Events is currently static, better we make this non-static so we can loop sets of events, and standardize with other objects.
/**
 * Use this class to query and manipulate sets of events. If dealing with more than one event, you probably want to use this class in some way.
 *
 */
class EM_Events extends EM_Object {
	
	/**
	 * Returns an array of EM_Events that match the given specs in the argument, or returns a list of future evetnts in future 
	 * (see EM_Events::get_default_search() ) for explanation of possible search array values. You can also supply a numeric array
	 * containing the ids of the events you'd like to obtain 
	 * 
	 * @param array $args
	 * @return EM_Event array()
	 */
	public static function get( $args = array(), $count=false ) {
		global $wpdb;	 
		$events_table = EM_EVENTS_TABLE;
		$locations_table = EM_LOCATIONS_TABLE;
		
		//Quick version, we can accept an array of IDs, which is easy to retrieve
		if( self::array_is_numeric($args) ){ //Array of numbers, assume they are event IDs to retreive
			//We can just get all the events here and return them
			$events = array();
			foreach($args as $event_id){
				$events[$event_id] = em_get_event($event_id);
			}
			return apply_filters('em_events_get', $events, $args);
		}
		
		//We assume it's either an empty array or array of search arguments to merge with defaults			
		$args = self::get_default_search($args);
		$limit = ( $args['limit'] && is_numeric($args['limit'])) ? "LIMIT {$args['limit']}" : '';
		$offset = ( $limit != "" && is_numeric($args['offset']) ) ? "OFFSET {$args['offset']}" : '';
		$groupby_sql = '';
		
		//Get the default conditions
		$conditions = self::build_sql_conditions($args);
		//Put it all together
		$where = ( count($conditions) > 0 ) ? " WHERE " . implode ( " AND ", $conditions ):'';
		
		//Get ordering instructions
		$EM_Event = new EM_Event();
		$EM_Location = new EM_Location();
		$orderby = self::build_sql_orderby($args, array_keys(array_merge($EM_Event->fields, $EM_Location->fields)), get_option('dbem_events_default_order'));
		//Now, build orderby sql
		$orderby_sql = ( count($orderby) > 0 ) ? 'ORDER BY '. implode(', ', $orderby) : '';
		
		//Create the SQL statement and execute
		
		if( !$count && $args['array'] ){
			$selectors_array = array();
			foreach( array_keys($EM_Event->fields) as $field_selector){
				$selectors_array[] = $events_table.'.'.$field_selector;
			}
			$selectors = implode(',', $selectors_array);
		}elseif( EM_MS_GLOBAL ){
			$selectors = $events_table.'.post_id, '.$events_table.'.blog_id';
			$groupby_sql[] = $events_table.'.post_id, '. $events_table.'.blog_id';
		}else{
			$selectors = $events_table.'.post_id';
			$groupby_sql[] = $events_table.'.post_id'; //prevent duplicates showing in lists
		}
		if( $count ){
			$selectors = 'SQL_CALC_FOUND_ROWS *';
			$limit = 'LIMIT 1';
			$offset = 'OFFSET 0';
		}
		
		//add group_by if needed
		$groupby_sql = !empty($groupby_sql) && is_array($groupby_sql) ? 'GROUP BY '.implode(',', $groupby_sql):''; 
		
		$sql = apply_filters('em_events_get_sql',"
			SELECT $selectors FROM $events_table
			LEFT JOIN $locations_table ON {$locations_table}.location_id={$events_table}.location_id
			$where
			$groupby_sql $orderby_sql
			$limit $offset
		", $args);
				
		//If we're only counting results, return the number of results
		if( $count ){
			$wpdb->query($sql);
			return apply_filters('em_events_get_count', $wpdb->get_var('SELECT FOUND_ROWS()'), $args);		
		}
		$results = $wpdb->get_results( $sql, ARRAY_A);

		//If we want results directly in an array, why not have a shortcut here?
		if( $args['array'] == true ){
			return apply_filters('em_events_get_array',$results, $args);
		}
		
		//Make returned results EM_Event objects
		$results = (is_array($results)) ? $results:array();
		$events = array();
		
		if( EM_MS_GLOBAL ){
			foreach ( $results as $event ){
				$events[] = em_get_event($event['post_id'], $event['blog_id']);
			}
		}else{
			foreach ( $results as $event ){
				$events[] = em_get_event($event['post_id'], 'post_id');
			}
		}
		
		return apply_filters('em_events_get', $events, $args);
	}
	
	/**
	 * Returns the number of events on a given date
	 * @param $date
	 * @return int
	 */
	public static function count_date($date){
		global $wpdb;
		$table_name = EM_EVENTS_TABLE;
		$sql = "SELECT COUNT(*) FROM  $table_name WHERE (event_start_date  like '$date') OR (event_start_date <= '$date' AND event_end_date >= '$date');";
		return apply_filters('em_events_count_date', $wpdb->get_var($sql));
	}
	
	public static function count( $args = array() ){
		return apply_filters('em_events_count', self::get($args, true), $args);
	}
	
	/**
	 * Will delete given an array of event_ids or EM_Event objects
	 * @param unknown_type $id_array
	 */
	public static function delete( $array ){
		global $wpdb;
		//Detect array type and generate SQL for event IDs
		$results = array();
		if( !empty($array) && @get_class(current($array)) != 'EM_Event' ){
			$events = self::get($array);
		}else{
			$events = $array;
		}
		$event_ids = array();
		foreach ($events as $EM_Event){
		    $event_ids[] = $EM_Event->event_id;
			$results[] = $EM_Event->delete();
		}
		//TODO add better error feedback on events delete fails
		return apply_filters('em_events_delete',  in_array(false, $results), $event_ids);
	}
	
	
	/**
	 * Output a set of matched of events. You can pass on an array of EM_Events as well, in this event you can pass args in second param.
	 * Note that you can pass a 'pagination' boolean attribute to enable pagination, default is enabled (true). 
	 * @param array $args
	 * @param array $secondary_args
	 * @return string
	 */
	public static function output( $args ){
		global $EM_Event;
		$EM_Event_old = $EM_Event; //When looping, we can replace EM_Event global with the current event in the loop
		//get page number if passed on by request (still needs pagination enabled to have effect)
		$page_queryvar = !empty($args['page_queryvar']) ? $args['page_queryvar'] : 'pno';
		if( !empty($args['pagination']) && !array_key_exists('page',$args) && !empty($_REQUEST[$page_queryvar]) && is_numeric($_REQUEST[$page_queryvar]) ){
			$page = $args['page'] = $_REQUEST[$page_queryvar];
		}
		//Can be either an array for the get search or an array of EM_Event objects
		if( is_object(current($args)) && get_class((current($args))) == 'EM_Event' ){
			$func_args = func_get_args();
			$events = $func_args[0];
			$args = (!empty($func_args[1]) && is_array($func_args[1])) ? $func_args[1] : array();
			$args = apply_filters('em_events_output_args', self::get_default_search($args), $events);
			$limit = ( !empty($args['limit']) && is_numeric($args['limit']) ) ? $args['limit']:false;
			$offset = ( !empty($args['offset']) && is_numeric($args['offset']) ) ? $args['offset']:0;
			$page = ( !empty($args['page']) && is_numeric($args['page']) ) ? $args['page']:1;
			$events_count = count($events);
		}else{
			//Firstly, let's check for a limit/offset here, because if there is we need to remove it and manually do this
			$args = apply_filters('em_events_output_args', self::get_default_search($args));
			$limit = ( !empty($args['limit']) && is_numeric($args['limit']) ) ? $args['limit']:false;
			$offset = ( !empty($args['offset']) && is_numeric($args['offset']) ) ? $args['offset']:0;
			$page = ( !empty($args['page']) && is_numeric($args['page']) ) ? $args['page']:1;
			$args_count = $args;
			$args_count['limit'] = false;
			$args_count['offset'] = false;
			$args_count['page'] = false;
			$events_count = self::count($args_count);
			$events = self::get( $args );
		}
		//What format shall we output this to, or use default
		$format = ( empty($args['format']) ) ? get_option( 'dbem_event_list_item_format' ) : $args['format'] ;
		
		$output = "";
		$events = apply_filters('em_events_output_events', $events);
		if ( $events_count > 0 ) {
			foreach ( $events as $EM_Event ) {
				$output .= $EM_Event->output($format);
			} 
			//Add headers and footers to output
			if( $format == get_option( 'dbem_event_list_item_format' ) ){
			    //we're using the default format, so if a custom format header or footer is supplied, we can override it, if not use the default
			    $format_header = empty($args['format_header']) ? get_option('dbem_event_list_item_format_header') : $args['format_header'];
			    $format_footer = empty($args['format_footer']) ? get_option('dbem_event_list_item_format_footer') : $args['format_footer'];
			}else{
			    //we're using a custom format, so if a header or footer isn't specifically supplied we assume it's blank
			    $format_header = !empty($args['format_header']) ? $args['format_header'] : '' ;
			    $format_footer = !empty($args['format_footer']) ? $args['format_footer'] : '' ;
			}
			$output = $format_header .  $output . $format_footer;
			//Pagination (if needed/requested)
			if( !empty($args['pagination']) && !empty($limit) && $events_count > $limit ){
				$output .= self::get_pagination_links($args, $events_count);
			}
		} else {
			$output = get_option ( 'dbem_no_events_message' );
		}	
		
		//TODO check if reference is ok when restoring object, due to changes in php5 v 4
		$EM_Event = $EM_Event_old;
		$output = apply_filters('em_events_output', $output, $events, $args);
		return $output;		
	}
	
	/**
	 * Generate a grouped list of events by year, month, week or day.
	 * @since 5.4.4.2
	 * @param array $args
	 * @return string
	 */
	public static function output_grouped( $args = array() ){
		//Reset some args to include pagination for if pagination is requested.
		$args['limit'] = isset($args['limit']) ? $args['limit'] : get_option('dbem_events_default_limit');
		$args['page'] = (!empty($args['page']) && is_numeric($args['page']) )? $args['page'] : 1;
		$args['page'] = (!empty($args['pagination']) && !empty($_REQUEST['pno']) && is_numeric($_REQUEST['pno']) )? $_REQUEST['pno'] : $args['page'];
		$args['offset'] = ($args['page']-1) * $args['limit'];
		$args['orderby'] = 'event_start_date,event_start_time,event_name'; // must override this to display events in right cronology.

		$args['mode'] = !empty($args['mode']) ? $args['mode'] : get_option('dbem_event_list_groupby');
		$args['header_format'] = !empty($args['header_format']) ? $args['header_format'] :  get_option('dbem_event_list_groupby_header_format', '<h2>#s</h2>');
		$args['date_format'] = !empty($args['date_format']) ? $args['date_format'] :  get_option('dbem_event_list_groupby_format');
		//Reset some vars for counting events and displaying set arrays of events
		$atts = (array) $args;
		$atts['pagination'] = false;
		$atts['limit'] = false;
		$atts['page'] = false;
		$atts['offset'] = false;
		//decide what form of dates to show
		$events_count = self::count($atts);
		ob_start();
		if( $events_count > 0 ){
			$EM_Events = self::get($args);
			switch ( $args['mode'] ){
				case 'yearly':
					//go through the events and put them into a monthly array
					$format = (!empty($args['date_format'])) ? $args['date_format']:'Y';
					$events_dates = array();
					foreach($EM_Events as $EM_Event){
						$events_dates[date_i18n($format,$EM_Event->start)][] = $EM_Event;
					}
					foreach ($events_dates as $year => $events){
						echo str_replace('#s', $year, $args['header_format']);
						echo self::output($events, $atts);
					}
					break;
				case 'monthly':
					//go through the events and put them into a monthly array
					$format = (!empty($args['date_format'])) ? $args['date_format']:'M Y';
					$events_dates = array();
					foreach($EM_Events as $EM_Event){
						$events_dates[date_i18n($format, $EM_Event->start)][] = $EM_Event;
					}
					foreach ($events_dates as $month => $events){
						echo str_replace('#s', $month, $args['header_format']);
						echo self::output($events, $atts);
					}
					break;
				case 'weekly':
					$format = (!empty($args['date_format'])) ? $args['date_format']:get_option('date_format');
					$events_dates = array();
					foreach($EM_Events as $EM_Event){
			   			$start_of_week = get_option('start_of_week');
						$day_of_week = date('w',$EM_Event->start);
						$day_of_week = date('w',$EM_Event->start);
						$offset = $day_of_week - $start_of_week;
						if($offset<0){ $offset += 7; }
						$offset = $offset * 60*60*24; //days in seconds
						$start_day = strtotime($EM_Event->start_date);
						$events_dates[$start_day - $offset][] = $EM_Event;
					}
					foreach ($events_dates as $event_day_ts => $events){
						echo str_replace('#s', date_i18n($format,$event_day_ts). get_option('dbem_dates_separator') .date_i18n($format,$event_day_ts+(60*60*24*6)), $args['header_format']);
						echo self::output($events, $atts);
					}
					break;
				default: //daily
					//go through the events and put them into a daily array
					$format = (!empty($args['date_format'])) ? $args['date_format']:get_option('date_format');
					$events_dates = array();
					foreach($EM_Events as $EM_Event){
						$events_dates[strtotime($EM_Event->start_date)][] = $EM_Event;
					}
					foreach ($events_dates as $event_day_ts => $events){
						echo str_replace('#s', date_i18n($format,$event_day_ts), $args['header_format']);
						echo self::output($events, $atts);
					}
					break;
			}
			//Show the pagination links (unless there's less than $limit events)
			if( !empty($args['pagination']) && !empty($args['limit']) && $events_count > $args['limit'] ){
				echo self::get_pagination_links($args, $events_count, 'search_events_grouped');
			}
		}else{
			echo get_option ( 'dbem_no_events_message' );
		}
		return ob_get_clean();
	}
	
	public static function get_pagination_links($args, $count, $search_action = 'search_events', $default_args = array()){
		//get default args if we're in a search, supply to parent since we can't depend on late static binding until WP requires PHP 5.3 or later
		if( empty($default_args) && (!empty($args['ajax']) || !empty($_REQUEST['action']) && $_REQUEST['action'] == $search_action) ){
			$default_args = self::get_default_search();
			$default_args['limit'] = get_option('dbem_events_default_limit');
		}
		return parent::get_pagination_links($args, $count, $search_action, $default_args);
	}
	
	/* (non-PHPdoc)
	 * DEPRECATED - this class should just contain static classes,
	 * @see EM_Object::can_manage()
	 */
	function can_manage($event_ids = false , $admin_capability = false, $user_to_check = false ){
		global $wpdb;
		if( current_user_can('edit_others_events') ){
			return apply_filters('em_events_can_manage', true, $event_ids);
		}
		if( EM_Object::array_is_numeric($event_ids) ){
			$condition = implode(" OR event_id=", $event_ids);
			//we try to find any of these events that don't belong to this user
			$results = $wpdb->get_var("SELECT COUNT(*) FROM ". EM_EVENTS_TABLE ." WHERE event_owner != '". get_current_user_id() ."' event_id=$condition;");
			return apply_filters('em_events_can_manage', ($results == 0), $event_ids);
		}
		return apply_filters('em_events_can_manage', false, $event_ids);
	}
	
	public static function get_post_search($args = array(), $filter = false, $request = array(), $accepted_args = array()){
		//supply $accepted_args to parent argument since we can't depend on late static binding until WP requires PHP 5.3 or later
		$accepted_args = !empty($accepted_args) ? $accepted_args : array_keys(self::get_default_search());
		return apply_filters('em_events_get_post_search', parent::get_post_search($args, $filter, $request, $accepted_args));
	}

	/* Overrides EM_Object method to apply a filter to result
	 * @see wp-content/plugins/events-manager/classes/EM_Object#build_sql_conditions()
	 */
	public static function build_sql_conditions( $args = array() ){
	    self::$context = EM_POST_TYPE_EVENT;
		$conditions = parent::build_sql_conditions($args);
		if( !empty($args['search']) ){
			$like_search = array('event_name',EM_EVENTS_TABLE.'.post_content','location_name','location_address','location_town','location_postcode','location_state','location_country','location_region');
			$conditions['search'] = "(".implode(" LIKE '%{$args['search']}%' OR ", $like_search). "  LIKE '%{$args['search']}%')";
		}
		$conditions['status'] = "(`event_status` >= 0 )"; //shows pending & published if not defined
		if( array_key_exists('status',$args) ){
			if( is_numeric($args['status']) ){
				$conditions['status'] = "(`event_status`={$args['status']})"; //pending or published
			}elseif( $args['status'] == 'pending' ){
			    $conditions['status'] = "(`event_status`=0)"; //pending
			}elseif( $args['status'] == 'publish' ){
			    $conditions['status'] = "(`event_status`=1)"; //published
			}elseif( $args['status'] === null || $args['status'] == 'draft' ){
			    $conditions['status'] = "(`event_status` IS NULL )"; //show draft items
			}elseif( $args['status'] == 'trash' ){
			    $conditions['status'] = "(`event_status` = -1 )"; //show trashed items
			}elseif( $args['status'] == 'all'){
				$conditions['status'] = "(`event_status` >= 0 OR `event_status` IS NULL)"; //search all statuses that aren't trashed
			}elseif( $args['status'] == 'everything'){
				unset($conditions['status']); //search all statuses
			}
		}
		//private events
		if( empty($args['private']) ){
			$conditions['private'] = "(`event_private`=0)";			
		}elseif( !empty($args['private_only']) ){
			$conditions['private_only'] = "(`event_private`=1)";
		}
		if( EM_MS_GLOBAL && !empty($args['blog']) ){
		    if( is_numeric($args['blog']) ){
				if( is_main_site($args['blog']) ){
					$conditions['blog'] = "(".EM_EVENTS_TABLE.".blog_id={$args['blog']} OR ".EM_EVENTS_TABLE.".blog_id IS NULL)";
				}else{
					$conditions['blog'] = "(".EM_EVENTS_TABLE.".blog_id={$args['blog']})";
				}
		    }else{
		        if( !is_array($args['blog']) && preg_match('/^([\-0-9],?)+$/', $args['blog']) ){
		            $conditions['blog'] = "(".EM_EVENTS_TABLE.".blog_id IN ({$args['blog']}) )";
			    }elseif( is_array($args['blog']) && self::array_is_numeric($args['blog']) ){
			        $conditions['blog'] = "(".EM_EVENTS_TABLE.".blog_id IN (".implode(',',$args['blog']).") )";
			    }
		    }
		}
		if( $args['bookings'] === 'user' && is_user_logged_in()){
			//get bookings of user
			$EM_Person = new EM_Person(get_current_user_id());
			$booking_ids = $EM_Person->get_bookings(true);
			if( count($booking_ids) > 0 ){
				$conditions['bookings'] = "(event_id IN (SELECT event_id FROM ".EM_BOOKINGS_TABLE." WHERE booking_id IN (".implode(',',$booking_ids).")))";
			}else{
				$conditions['bookings'] = "(event_id = 0)";
			}
		}
		//post search
		if( !empty($args['post_id'])){
			if( is_array($args['post_id']) ){
				$conditions['post_id'] = "(".EM_EVENTS_TABLE.".post_id IN (".implode(',',$args['post_id'])."))";
			}else{
				$conditions['post_id'] = "(".EM_EVENTS_TABLE.".post_id={$args['post_id']})";
			}
		}
		return apply_filters( 'em_events_build_sql_conditions', $conditions, $args );
	}
	
	/* Overrides EM_Object method to apply a filter to result
	 * @see wp-content/plugins/events-manager/classes/EM_Object#build_sql_orderby()
	 */
	public static function build_sql_orderby( $args, $accepted_fields, $default_order = 'ASC' ){
	    self::$context = EM_POST_TYPE_EVENT;
		return apply_filters( 'em_events_build_sql_orderby', parent::build_sql_orderby($args, $accepted_fields, get_option('dbem_events_default_order')), $args, $accepted_fields, $default_order );
	}
	
	/* 
	 * Adds custom Events search defaults
	 * @param array $array_or_defaults may be the array to override defaults
	 * @param array $array
	 * @return array
	 * @uses EM_Object#get_default_search()
	 */
	public static function get_default_search( $array_or_defaults = array(), $array = array() ){
	    self::$context = EM_POST_TYPE_EVENT;
		$defaults = array(
			'orderby' => get_option('dbem_events_default_orderby'),
			'order' => get_option('dbem_events_default_order'),
			'bookings' => false, //if set to true, only events with bookings enabled are returned
			'status' => 1, //approved events only
			'town' => false,
			'state' => false,
			'country' => false,
			'region' => false,
			'blog' => get_current_blog_id(),
			'private' => current_user_can('read_private_events'),
			'private_only' => false,
			'post_id' => false
		);
		//sort out whether defaults were supplied or just the array of search values
		if( empty($array) ){
			$array = $array_or_defaults;
		}else{
			$defaults = array_merge($defaults, $array_or_defaults);
		}
		//specific functionality
		if( EM_MS_GLOBAL && (!is_admin() || defined('DOING_AJAX')) ){
			if( empty($array['blog']) && is_main_site() && get_site_option('dbem_ms_global_events') ){
			    $array['blog'] = false;
			}
		}
		if( is_admin() && !defined('DOING_AJAX') ){
			//figure out default owning permissions
			$defaults['owner'] = !current_user_can('edit_others_events') ? get_current_user_id() : false;
			if( !array_key_exists('status', $array) && current_user_can('edit_others_events') ){
				$defaults['status'] = false; //by default, admins see pending and live events
			}
		}
		return apply_filters('em_events_get_default_search', parent::get_default_search($defaults,$array), $array, $defaults);
	}
}
?>