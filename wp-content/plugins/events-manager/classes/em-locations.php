<?php
/**
 * Static class which will help bulk add/edit/retrieve/manipulate arrays of EM_Location objects. 
 * Optimized for specifically retreiving locations (whether eventful or not). If you want event data AND location information for each event, use EM_Events
 * 
 */
class EM_Locations extends EM_Object {
	
	/**
	 * Returns an array of EM_Location objects
	 * @param boolean $eventful
	 * @param boolean $return_objects
	 * @return array
	 */
	public static function get( $args = array(), $count=false ){
		global $wpdb;
		$events_table = EM_EVENTS_TABLE;
		$locations_table = EM_LOCATIONS_TABLE;
		$locations = array();
		
		//Quick version, we can accept an array of IDs, which is easy to retrieve
		if( self::array_is_numeric($args) ){ //Array of numbers, assume they are event IDs to retreive
			//We can just get all the events here and return them
			$locations = array();
			foreach($args as $location_id){
				$locations[$location_id] = em_get_location($location_id);
			}
			return apply_filters('em_locations_get', $locations, $args); //We return all the events matched as an EM_Event array. 
		}elseif( is_numeric($args) ){
			//return an event in the usual array format
			return apply_filters('em_locations_get', array(em_get_location($args)), $args);
		}elseif( is_array($args) && is_object(current($args)) && get_class((current($args))) == 'EM_Location' ){
		    //we were passed an array of EM_Location classes, so we just give it back
		    /* @todo do we really need this condition in EM_Locations::get()? */
			return apply_filters('em_locations_get', $args, $args);
		}	

		//We assume it's either an empty array or array of search arguments to merge with defaults			
		$args = self::get_default_search($args);
		$limit = ( $args['limit'] && is_numeric($args['limit'])) ? "LIMIT {$args['limit']}" : '';
		$offset = ( $limit != "" && is_numeric($args['offset']) ) ? "OFFSET {$args['offset']}" : '';
		
		//Get the default conditions
		$conditions = self::build_sql_conditions($args);
		
		//Put it all together
		$where = ( count($conditions) > 0 ) ? " WHERE " . implode ( " AND ", $conditions ):'';
		
		//Get ordering instructions
		$EM_Event = new EM_Event(); //blank event for below
		$EM_Location = new EM_Location(0); //blank location for below
		$accepted_fields = $EM_Location->get_fields(true);
		$accepted_fields = array_merge($EM_Event->get_fields(true),$accepted_fields);
		$orderby = self::build_sql_orderby($args, $accepted_fields, get_option('dbem_events_default_order'));
		//Now, build orderby sql
		$orderby_sql = ( count($orderby) > 0 ) ? 'ORDER BY '. implode(', ', $orderby) : '';
		
		if( EM_MS_GLOBAL ){
			$selectors = ( $count ) ?  'COUNT('.$locations_table.'.location_id)':$locations_table.'.post_id, '.$locations_table.'.blog_id';
		}else{
			$selectors = ( $count ) ?  'COUNT('.$locations_table.'.location_id)':$locations_table.'.post_id';
		}
		//Create the SQL statement and execute
		$sql = "
			SELECT $selectors FROM $locations_table
			LEFT JOIN $events_table ON {$locations_table}.location_id={$events_table}.location_id
			$where
			GROUP BY {$locations_table}.location_id
			$orderby_sql
			$limit $offset
		";
		
		//If we're only counting results, return the number of results
		if( $count ){
			return apply_filters('em_locations_get_array', count($wpdb->get_col($sql)), $args);	
		}
		// Hack GreenDrinks: somehow filter 'em_locations_get_sql' disappeared, we need it for the dataloader-locationlist, so here it's added again
		// $results = $wpdb->get_results($sql, ARRAY_A);
		$results = $wpdb->get_results(apply_filters('gd_em_locations_get_sql', $sql), ARRAY_A);
		
		//If we want results directly in an array, why not have a shortcut here?
		if( $args['array'] == true ){
			return apply_filters('em_locations_get_array', $results, $args);
		}
		
		if( EM_MS_GLOBAL ){
			foreach ( $results as $location ){
			    if( empty($location['blog_id']) ) $location['blog_id'] = get_current_site()->blog_id;
				$locations[] = em_get_location($location['post_id'], $location['blog_id']);
			}
		}else{
			foreach ( $results as $location ){
				$locations[] = em_get_location($location['post_id'], 'post_id');
			}
		}
		return apply_filters('em_locations_get', $locations, $args);
	}	
	
	public static function count( $args = array() ){
		return apply_filters('em_locations_count', self::get($args, true), $args);
	}
	
	/**
	 * Output a set of matched of events
	 * @param array $args
	 * @return string
	 */
	public static function output( $args ){
		global $EM_Location;
		$EM_Location_old = $EM_Location; //When looping, we can replace EM_Location global with the current event in the loop
		//Can be either an array for the get search or an array of EM_Location objects
		$page_queryvar = !empty($args['page_queryvar']) ? $args['page_queryvar'] : 'pno';
		if( !empty($args['pagination']) && !array_key_exists('page',$args) && !empty($_REQUEST[$page_queryvar]) && is_numeric($_REQUEST[$page_queryvar]) ){
			$page = $args['page'] = $_REQUEST[$page_queryvar];
		}
		if( is_object(current($args)) && get_class((current($args))) == 'EM_Location' ){
			$func_args = func_get_args();
			$locations = $func_args[0];
			$args = (!empty($func_args[1])) ? $func_args[1] : array();
			$args = apply_filters('em_locations_output_args', self::get_default_search($args), $locations);
			$limit = ( !empty($args['limit']) && is_numeric($args['limit']) ) ? $args['limit']:false;
			$offset = ( !empty($args['offset']) && is_numeric($args['offset']) ) ? $args['offset']:0;
			$page = ( !empty($args['page']) && is_numeric($args['page']) ) ? $args['page']:1;
			$locations_count = count($locations);
		}else{
			$args = apply_filters('em_locations_output_args', self::get_default_search($args) );
			$limit = ( !empty($args['limit']) && is_numeric($args['limit']) ) ? $args['limit']:false;
			$offset = ( !empty($args['offset']) && is_numeric($args['offset']) ) ? $args['offset']:0;
			$page = ( !empty($args['page']) && is_numeric($args['page']) ) ? $args['page']:1;
			$args_count = $args;
			$args_count['limit'] = 0;
			$args_count['offset'] = 0;
			$args_count['page'] = 1;
			$locations_count = self::count($args_count);
			$locations = self::get( $args );
		}
		//What format shall we output this to, or use default
		$format = empty($args['format']) ? get_option( 'dbem_location_list_item_format' ) : $args['format'] ;
		
		$output = "";
		$locations = apply_filters('em_locations_output_locations', $locations);	
		if ( count($locations) > 0 ) {
			foreach ( $locations as $EM_Location ) {
				$output .= $EM_Location->output($format);
			}
			//Add headers and footers to output
			if( $format == get_option( 'dbem_location_list_item_format' ) ){
			    //we're using the default format, so if a custom format header or footer is supplied, we can override it, if not use the default
			    $format_header = empty($args['format_header']) ? get_option('dbem_location_list_item_format_header') : $args['format_header'];
			    $format_footer = empty($args['format_footer']) ? get_option('dbem_location_list_item_format_footer') : $args['format_footer'];
			}else{
			    //we're using a custom format, so if a header or footer isn't specifically supplied we assume it's blank
			    $format_header = !empty($args['format_header']) ? $args['format_header'] : '' ;
			    $format_footer = !empty($args['format_footer']) ? $args['format_footer'] : '' ;
			}
			$output =  $format_header .  $output . $format_footer;
			
			//Pagination (if needed/requested)
			if( !empty($args['pagination']) && !empty($limit) && $locations_count > $limit ){
				//output pagination links
				$output .= self::get_pagination_links($args, $locations_count);
			}
		} else {
			$output = get_option ( 'dbem_no_locations_message' );
		}
		//FIXME check if reference is ok when restoring object, due to changes in php5 v 4
		$EM_Location_old= $EM_Location;
		return apply_filters('em_locations_output', $output, $locations, $args);		
	}
	
	public static function get_pagination_links($args, $count, $search_action = 'search_locations', $default_args = array()){
		//get default args if we're in a search, supply to parent since we can't depend on late static binding until WP requires PHP 5.3 or later
		if( empty($default_args) && (!empty($args['ajax']) || !empty($_REQUEST['action']) && $_REQUEST['action'] == $search_action) ){
			$default_args = self::get_default_search();
			$default_args['limit'] = get_option('dbem_locations_default_limit'); //since we're paginating, get the default limit, which isn't obtained from get_default_search()
		}
		return parent::get_pagination_links($args, $count, $search_action, $default_args);
	}
	
	public static function delete( $args = array() ){
	    $locations = array();
		if( !is_object(current($args)) ){
		    //we've been given an array or search arguments to find the relevant locations to delete
			$locations = self::get($args);
		}elseif( get_class(current($args)) == 'EM_Location' ){
		    //we're deleting an array of locations
			$locations = $args;
		}
		$results = array();
		foreach ( $locations as $EM_Location ){
			$results[] = $EM_Location->delete();
		}		
		return apply_filters('em_locations_delete', in_array(false, $results), $locations);
	}
	
	public static function get_post_search($args = array(), $filter = false, $request = array(), $accepted_args = array()){
		//supply $accepted_args to parent argument since we can't depend on late static binding until WP requires PHP 5.3 or later
		$accepted_args = !empty($accepted_args) ? $accepted_args : array_keys(self::get_default_search());
		$return = parent::get_post_search($args, $filter, $request, $accepted_args);
		//remove unwanted arguments or if not explicitly requested
		if( empty($_REQUEST['scope']) && empty($request['scope']) && !empty($return['scope']) ){
			unset($return['scope']);
		}
		return apply_filters('em_locations_get_post_search', $return);
	}
	
	/**
	 * Builds an array of SQL query conditions based on regularly used arguments
	 * @param array $args
	 * @return array
	 */
	public static function build_sql_conditions( $args = array(), $count=false ){
	    self::$context = EM_POST_TYPE_LOCATION;
		global $wpdb;
		$events_table = EM_EVENTS_TABLE;
		$locations_table = EM_LOCATIONS_TABLE;
		
		$conditions = parent::build_sql_conditions($args);
		//search locations
		if( !empty($args['search']) ){
			$like_search = array($locations_table.'.post_content','location_name','location_address','location_town','location_postcode','location_state','location_region','location_country');
			$conditions['search'] = "(".implode(" LIKE '%{$args['search']}%' OR ", $like_search). "  LIKE '%{$args['search']}%')";
		}
		//eventful locations
		if( true == $args['eventful'] ){
			$conditions['eventful'] = "{$events_table}.event_id IS NOT NULL AND event_status=1";
		}elseif( true == $args['eventless'] ){
			$conditions['eventless'] = "{$events_table}.event_id IS NULL";
			if( !empty($conditions['scope']) ) unset($conditions['scope']); //scope condition would render all queries return no results
		}
		//owner lookup
		if( !empty($args['owner']) && is_numeric($args['owner'])){
			$conditions['owner'] = "location_owner=".$args['owner'];
		}elseif( !empty($args['owner']) && $args['owner'] == 'me' && is_user_logged_in() ){
			$conditions['owner'] = 'location_owner='.get_current_user_id();
		}elseif( self::array_is_numeric($args['owner']) ){
			$conditions['owner'] = 'location_owner IN ('.implode(',',$args['owner']).')';
		}
		//blog id in events table
		if( EM_MS_GLOBAL && !empty($args['blog']) ){
		    if( is_numeric($args['blog']) ){
				if( is_main_site($args['blog']) ){
					$conditions['blog'] = "(".$locations_table.".blog_id={$args['blog']} OR ".$locations_table.".blog_id IS NULL)";
				}else{
					$conditions['blog'] = "(".$locations_table.".blog_id={$args['blog']})";
				}
		    }else{
		        if( !is_array($args['blog']) && preg_match('/^([\-0-9],?)+$/', $args['blog']) ){
		            $conditions['blog'] = "(".$locations_table.".blog_id IN ({$args['blog']}) )";
			    }elseif( is_array($args['blog']) && self::array_is_numeric($args['blog']) ){
			        $conditions['blog'] = "(".$locations_table.".blog_id IN (".implode(',',$args['blog']).") )";
			    }
		    }
		}
		//status
		$conditions['status'] = "(`location_status` >= 0)"; //pending and published if status is not explicitly defined (Default is 1)
		if( array_key_exists('status',$args) ){ 
		    if( is_numeric($args['status']) ){
				$conditions['status'] = "(`location_status`={$args['status']} )"; //trash (-1), pending, (0) or published (1)
			}elseif( $args['status'] == 'pending' ){
			    $conditions['status'] = "(`location_status`=0)"; //pending
			}elseif( $args['status'] == 'publish' ){
			    $conditions['status'] = "(`location_status`=1)"; //published
		    }elseif( $args['status'] === null || $args['status'] == 'draft' ){
			    $conditions['status'] = "(`location_status` IS NULL )"; //show draft items
			}elseif( $args['status'] == 'trash' ){
			    $conditions['status'] = "(`location_status` = -1 )"; //show trashed items
			}elseif( $args['status'] == 'all'){
				$conditions['status'] = "(`location_status` >= 0 OR `location_status` IS NULL)"; //search all statuses that aren't trashed
			}elseif( $args['status'] == 'everything'){
				unset($conditions['status']); //search all statuses
			}
		}
		//private locations
		if( empty($args['private']) ){
			$conditions['private'] = "(`location_private`=0)";
		}elseif( !empty($args['private_only']) ){
			$conditions['private_only'] = "(`location_private`=1)";
		}
		//post search
		if( !empty($args['post_id'])){
			if( self::array_is_numeric($args['post_id']) ){
				$conditions['post_id'] = "($locations_table.post_id IN (".implode(',',$args['post_id'])."))";
			}else{
				$conditions['post_id'] = "($locations_table.post_id={$args['post_id']})";
			}
		}
		return apply_filters('em_locations_build_sql_conditions', $conditions, $args);
	}
	
	/* Overrides EM_Object method to apply a filter to result
	 * @see wp-content/plugins/events-manager/classes/EM_Object#build_sql_orderby()
	 */
	public static function build_sql_orderby( $args, $accepted_fields, $default_order = 'ASC' ){
	    self::$context = EM_POST_TYPE_LOCATION;
		return apply_filters( 'em_locations_build_sql_orderby', parent::build_sql_orderby($args, $accepted_fields, get_option('dbem_events_default_order')), $args, $accepted_fields, $default_order );
	}
	
	/* 
	 * Generate a search arguments array from defalut and user-defined.
	 * @param array $array_or_defaults may be the array to override defaults
	 * @param array $array
	 * @return array
	 * @uses EM_Object#get_default_search()
	 */
	public static function get_default_search( $array_or_defaults = array(), $array = array() ){
	    self::$context = EM_POST_TYPE_LOCATION;
		$defaults = array(
			'eventful' => false, //Locations that have an event (scope will also play a part here
			'eventless' => false, //Locations WITHOUT events, eventful takes precedence
			'orderby' => 'location_name',
			'town' => false,
			'state' => false,
			'country' => false,
			'region' => false,
			'status' => 1, //approved locations only
			'scope' => 'all', //we probably want to search all locations by default, not like events
			'blog' => get_current_blog_id(),
			'private' => current_user_can('read_private_locations'),
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
		if( EM_MS_GLOBAL ){
			if( get_site_option('dbem_ms_mainblog_locations') ){
			    //when searching in MS Global mode with all locations being stored on the main blog, blog_id becomes redundant as locations are stored in one blog table set
			    $array['blog'] = false;
			}elseif( (!is_admin() || defined('DOING_AJAX')) && empty($array['blog']) && is_main_site() && get_site_option('dbem_ms_global_locations') ){
				//if enabled, by default we display all blog locations on main site
			    $array['blog'] = false;
			}
		}
		$array['eventful'] = ( !empty($array['eventful']) && $array['eventful'] == true );
		$array['eventless'] = ( !empty($array['eventless']) && $array['eventless'] == true );
		if( is_admin() && !defined('DOING_AJAX') ){
			$defaults['owner'] = !current_user_can('read_others_locations') ? get_current_user_id():false;
		}
		return apply_filters('em_locations_get_default_search', parent::get_default_search($defaults, $array), $array, $defaults);
	}
}
?>