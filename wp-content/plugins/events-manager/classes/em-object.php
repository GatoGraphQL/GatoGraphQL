<?php
/**
 * Base class which others extend on. Contains functions shared across all EM objects.
 *
 */
class EM_Object {
	var $fields = array();
	var $required_fields = array();
	var $feedback_message = "";
	var $errors = array();
	var $mime_types = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
	
	private static $taxonomies_array; //see self::get_taxonomies()
	protected static $context = 'event'; //this should be overridden to the db table name for deciding on ambiguous fields to look up 
	
	/**
	 * Takes the array and provides a clean array of search parameters, along with details
	 * @param array $defaults
	 * @param array $array
	 * @return array
	 */
	public static function get_default_search($defaults=array(), $array = array()){
		global $wpdb;
		//TODO accept all objects as search options as well as ids (e.g. location vs. location_id, person vs. person_id)
		//Create minimal defaults array, merge it with supplied defaults array
		$super_defaults = array(
			'limit' => false,
			'scope' => 'future',
			'order' => 'ASC', //hard-coded at end of this function
			'orderby' => false,
			'format' => '', 
			'format_header' => '', //custom html above the list
			'format_footer' => '', //custom html below the list
			'category' => 0,
			'tag' => 0,
			'location' => false,
			'event' => false, 
			'offset'=>0,
			'page'=>1,//basically, if greater than 0, calculates offset at end
			'page_queryvar'=>null,
			'recurrence'=>0,
			'recurrences'=>null,
			'recurring'=>false,
			'month'=>'',
			'year'=>'',
			'pagination'=>false,
			'array'=>false,
			'owner'=>false,
			'rsvp'=>false, //deprecated for bookings
			'bookings'=>false,
			'search'=>false,
			'geo'=>false, //reserved for future searching via name
			'near'=>false, //lat,lng coordinates in array or comma-separated format
			'near_unit'=>get_option('dbem_search_form_geo_unit_default'), //mi or km
			'near_distance'=>get_option('dbem_search_form_geo_distance_default'), //distance from near coordinates - currently the default is the same as for the search form
			'ajax'=> (defined('EM_AJAX') && EM_AJAX) //considered during pagination
		);
		//auto-add taxonomies to defaults
		foreach( self::get_taxonomies() as $item => $item_data ){ $super_defaults[$item] = false; }
		
		//Return default if nothing passed
		if( empty($defaults) && empty($array) ){
			return $super_defaults;
		}
		//TODO decide on search defaults shared across all objects and then validate here
		$defaults = array_merge($super_defaults, $defaults);
		
		if(is_array($array)){
			//We are still dealing with recurrence_id, location_id, category_id in some place, so we do a quick replace here just in case
			if( array_key_exists('recurrence_id', $array) && !array_key_exists('recurrence', $array) ) { $array['recurrence'] = $array['recurrence_id']; }
			if( array_key_exists('location_id', $array) && !array_key_exists('location', $array) ) { $array['location'] = $array['location_id']; }
			if( array_key_exists('category_id', $array) && !array_key_exists('category', $array) ) { $array['category'] = $array['category_id']; }
		
			//Clean all id lists
			$clean_ids_array = array('location', 'event', 'post_id');
			if( !empty($array['owner']) && $array['owner'] != 'me') $clean_ids_array[] = 'owner'; //clean owner attribute if not 'me'
			$array = self::clean_id_atts($array, $clean_ids_array);

			//Clean taxonomies
			$taxonomies = self::get_taxonomies();
			foreach( $taxonomies as $item => $item_data ){ //tags and cats turned into an array regardless
			    if( !empty($array[$item]) && !is_array($array[$item]) ){
			    	$array[$item] = str_replace(array('&amp;','&#038;'), '&', $array[$item]); //clean & modifiers
					$array[$item] = preg_replace(array('/^[&,]/','/[&,]$/'),'', $array[$item]); //trim , and & from ends
			    }
			}
					    
			//Near
			if( !empty($array['near']) ){
				if( is_array($array['near']) ){
					$array = self::clean_id_atts($array,array('naer'));
				}elseif( is_string($array['near']) && preg_match('/^( ?[\-0-9\.]+ ?,?)+$/', $array['near']) ){
					$array['near'] = explode(',',$array['near']);
				}else{
					//assume it's a string to geocode, not supported yet
					unset($array['near']);
				}
				$array['near_unit'] = !empty($array['near_unit']) && in_array($array['near_unit'], array('km','mi')) ? $array['near_unit']:$defaults['near_unit']; //default is 'mi'
				$array['near_distance'] = !empty($array['near_distance']) && is_numeric($array['near_distance']) ? absint($array['near_distance']) : $defaults['near_distance']; //default is 25
			}
			//Country - Turn into array for multiple search if comma-separated 
			if( !empty($array['country']) && is_string($array['country']) && preg_match('/^( ?.+ ?,?)+$/', $array['country']) ){
			    $array['country'] = explode(',',$array['country']);
			}
			
			//OrderBy - can be a comma-separated array of field names to order by (field names of object, not db)
			if( array_key_exists('orderby', $array)){
				if( !is_array($array['orderby']) && preg_match('/,/', $array['orderby']) ) {
					$array['orderby'] = explode(',', $array['orderby']);
				}
			}
			//TODO validate search query array
			//Clean the supplied array, so we only have allowed keys
			foreach( array_keys($array) as $key){
				if( !array_key_exists($key, $defaults) && !array_key_exists($key, $taxonomies) ) unset($array[$key]);		
			}
			//return clean array
			$defaults = array_merge ( $defaults, $array ); //No point using WP's cleaning function, we're doing it already.
		}
		
		//Do some spring cleaning for known values
		//Month & Year - may be array or single number
		$month_regex = '/^[0-9]{1,2}$/';
		$year_regex = '/^[0-9]{4}$/';
		if( is_array($defaults['month']) ){
			$defaults['month'] = ( preg_match($month_regex, $defaults['month'][0]) && preg_match($month_regex, $defaults['month'][1]) ) ? $defaults['month']:''; 
		}else{
			$defaults['month'] = preg_match($month_regex, $defaults['month']) ? $defaults['month']:'';	
		}
		if( is_array($defaults['year']) ){
			$defaults['year'] = ( preg_match($year_regex, $defaults['year'][0]) && preg_match($year_regex, $defaults['year'][1]) ) ? $defaults['year']:'';
		}else{
			$defaults['year'] = preg_match($year_regex, $defaults['year']) ? $defaults['year']:'';
		}
		//Deal with scope and date searches
		if ( !is_array($defaults['scope']) && preg_match ( "/^([0-9]{4}-[0-9]{2}-[0-9]{2})?,([0-9]{4}-[0-9]{2}-[0-9]{2})?$/", $defaults['scope'] ) ) {
			//This is to become an array, so let's split it up
			$defaults['scope'] = explode(',', $defaults['scope']);
		}
		if( is_array($defaults['scope']) ){
			//looking for a date range here, so we'll verify the dates validate, if not get the default.
			if ( !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $defaults['scope'][0]) ){
				$defaults['scope'][0] = '';
			}
			if( !preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $defaults['scope'][1]) ) {
				$defaults['scope'][1] = '';
			}
			if( empty($defaults['scope'][0]) && empty($defaults['scope'][1]) ){
				$defaults['scope'] = $super_defaults['scope'];
			}
		}
		//Order - it's either ASC or DESC, so let's just validate
		if( !is_array($defaults['order']) && preg_match('/,/', $defaults['order']) ) {
			$defaults['order'] = explode(',', $defaults['order']);
		}elseif( !in_array($defaults['order'], array('ASC','DESC','asc','desc')) ){
			$defaults['order'] = $super_defaults['order'];
		}
		//ORDER BY, split if an array
		if( !is_array($defaults['orderby']) && preg_match('/,/', $defaults['orderby']) ) {
			$defaults['orderby'] = explode(',', $defaults['orderby']);
		}
		//TODO should we clean format of malicious code over here and run everything thorugh this?
		$defaults['array'] = ($defaults['array'] == true);
		$defaults['pagination'] = ($defaults['pagination'] == true);
		$defaults['limit'] = (is_numeric($defaults['limit'])) ? $defaults['limit']:$super_defaults['limit'];
		$defaults['offset'] = (is_numeric($defaults['offset'])) ? $defaults['offset']:$super_defaults['offset'];
		$defaults['recurring'] = $defaults['recurring'] === 'include' ?  $defaults['recurring']:($defaults['recurring'] == true);
		$defaults['search'] = ($defaults['search']) ? trim($defaults['search']):false;
		//Calculate offset if event page is set
		if($defaults['page'] > 1){
			$defaults['offset'] = $defaults['limit'] * ($defaults['page']-1);	
		}else{
			$defaults['page'] = ($defaults['limit'] > 0 ) ? floor($defaults['offset']/$defaults['limit']) + 1 : 1;
		}
		//reset the context
		self::$context = EM_POST_TYPE_EVENT;
		//return values
		return apply_filters('em_object_get_default_search', $defaults, $array, $super_defaults);
	}
	
	/**
	 * Builds an array of SQL query conditions based on regularly used arguments
	 * @param array $args
	 * @return array
	 */
	public static function build_sql_conditions( $args = array() ){
		global $wpdb;
		$events_table = EM_EVENTS_TABLE;
		$locations_table = EM_LOCATIONS_TABLE;
		
		$args = apply_filters('em_object_build_sql_conditions_args',$args);
		
		//Format the arguments passed on
		$scope = $args['scope'];//undefined variable warnings in ZDE, could just delete this (but dont pls!)
		$recurring = $args['recurring'];
		$recurrence = $args['recurrence'];
		$recurrences = $args['recurrences'];
		$category = $args['category'];// - not used anymore, accesses the $args directly
		$tag = $args['tag'];// - not used anymore, accesses the $args directly
		$location = $args['location'];
		$bookings = $args['rsvp'];
		$bookings = !empty($args['bookings']) ? $args['bookings']:$bookings;
		$owner = $args['owner'];
		$event = $args['event'];
		$month = $args['month'];
		$year = $args['year'];
		$today = date('Y-m-d', current_time('timestamp'));
		//Create the WHERE statement
		
		//Recurrences
		$conditions = array();
		if( $recurring ){
			//we show recurring event templates here, if 'recurring' is 'include' then we show both recurring and normal events.
			if( $recurring !== 'include' ){
				$conditions['recurring'] = "`recurrence`=1";
			}
		}elseif( $recurrence > 0 ){
			$conditions['recurrence'] = $wpdb->prepare("`recurrence_id`=%d", $recurrence);
		}else{
		    if( $recurrences !== null ){
		    	$conditions['recurrences'] = $recurrences ? "(`recurrence_id` > 0 )":"(`recurrence_id` IS NULL OR `recurrence_id`=0 )";
		    }
			$conditions['recurring'] = "(`recurrence`!=1 OR `recurrence` IS NULL)";			
		}
		//Dates - first check 'month', and 'year', and adjust scope if needed
		if( !($month=='' && $year=='') ){
			//Sort out month range, if supplied an array of array(month,month), it'll check between these two months
			if( self::array_is_numeric($month) ){
				$date_month_start = $month[0];
				$date_month_end = $month[1];
			}else{
				if( !empty($month) ){
					$date_month_start = $date_month_end = $month;					
				}else{
					$date_month_start = 1;
					$date_month_end = 12;				
				}
			}
			//Sort out year range, if supplied an array of array(year,year), it'll check between these two years
			if( self::array_is_numeric($year) ){
				$date_year_start = $year[0];
				$date_year_end = $year[1];
			}else{
				$date_year_start = $date_year_end = $year;
			}
			$date_start = $date_year_start."-".$date_month_start."-01";
			$date_end = date('Y-m-t', mktime(0,0,0,$date_month_end,1,$date_year_end));
			$scope = array($date_start,$date_end); //just modify the scope here
		}
		//No date requested, so let's look at scope
		if ( is_array($scope) ) {
			//This is an array, let's split it up
			$date_start = $scope[0];
			$date_end = $scope[1];
			if( !empty($date_start) && empty($date_end) ){
				//do a from till infinity
				$conditions['scope'] = " event_start_date >= CAST('$date_start' AS DATE)";
			}elseif( empty($date_start) && !empty($date_end) ){
				//do past till $date_end
				if( get_option('dbem_events_current_are_past') ){
					$conditions['scope'] = " event_start_date <= CAST('$date_end' AS DATE)";
				}else{
					$conditions['scope'] = " event_end_date <= CAST('$date_end' AS DATE)";
				}
			}else{
				//date range
				if( get_option('dbem_events_current_are_past') ){
					$conditions['scope'] = "( event_start_date BETWEEN CAST('$date_start' AS DATE) AND CAST('$date_end' AS DATE) )";
				}else{
					$conditions['scope'] = "( event_start_date <= CAST('$date_end' AS DATE) AND event_end_date >= CAST('$date_start' AS DATE) )";
				}
				//$conditions['scope'] = " ( ( event_start_date <= CAST('$date_end' AS DATE) AND event_end_date >= CAST('$date_start' AS DATE) ) OR (event_start_date BETWEEN CAST('$date_start' AS DATE) AND CAST('$date_end' AS DATE)) OR (event_end_date BETWEEN CAST('$date_start' AS DATE) AND CAST('$date_end' AS DATE)) )";
			}
		} elseif ( preg_match ( "/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $scope ) ) {
			//Scope can also be a specific date. However, if 'day', 'month', or 'year' are set, that will take precedence
			if( get_option('dbem_events_current_are_past') ){
				$conditions['scope'] = "event_start_date = CAST('$scope' AS DATE)";
			}else{
				$conditions['scope'] = " ( event_start_date = CAST('$scope' AS DATE) OR ( event_start_date <= CAST('$scope' AS DATE) AND event_end_date >= CAST('$scope' AS DATE) ) )";
			}
		} else {
			if ($scope == "past"){
				if( get_option('dbem_events_current_are_past') ){
					$conditions['scope'] = " event_start_date < '$today'";
				}else{
					$conditions['scope'] = " event_end_date < '$today'";
				}  
			}elseif ($scope == "today"){
				$conditions['scope'] = " (event_start_date = CAST('$today' AS DATE))";
				if( !get_option('dbem_events_current_are_past') ){
					$conditions['scope'] .= " OR (event_start_date <= CAST('$today' AS DATE) AND event_end_date >= CAST('$today' AS DATE))";
				}
			}elseif ($scope == "tomorrow"){
				$tomorrow = date('Y-m-d',current_time('timestamp')+60*60*24);
				$conditions['scope'] = "(event_start_date = CAST('$tomorrow' AS DATE))";
				if( !get_option('dbem_events_current_are_past') ){
					$conditions['scope'] .= " OR (event_start_date <= CAST('$tomorrow' AS DATE) AND event_end_date >= CAST('$tomorrow' AS DATE))";
				}
			}elseif ($scope == "month"){
				$start_month = date('Y-m-d',current_time('timestamp'));
				$end_month = date('Y-m-t',current_time('timestamp'));
				$conditions['scope'] = " (event_start_date BETWEEN CAST('$start_month' AS DATE) AND CAST('$end_month' AS DATE))";
				if( !get_option('dbem_events_current_are_past') ){
					$conditions['scope'] .= " OR (event_start_date < CAST('$start_month' AS DATE) AND event_end_date >= CAST('$start_month' AS DATE))";
				}
			}elseif ($scope == "next-month"){
				$start_month_timestamp = strtotime('+1 month', current_time('timestamp')); //get the end of this month + 1 day
				$start_month = date('Y-m-1',$start_month_timestamp);
				$end_month = date('Y-m-t',$start_month_timestamp);
				$conditions['scope'] = " (event_start_date BETWEEN CAST('$start_month' AS DATE) AND CAST('$end_month' AS DATE))";
				if( !get_option('dbem_events_current_are_past') ){
					$conditions['scope'] .= " OR (event_start_date < CAST('$start_month' AS DATE) AND event_end_date >= CAST('$start_month' AS DATE))";
				}
			}elseif( preg_match('/([0-9]+)\-months/',$scope,$matches) ){ // next x months means this month (what's left of it), plus the following x months until the end of that month.
				$months_to_add = $matches[1];
				$start_month = date('Y-m-d',current_time('timestamp'));
				$end_month = date('Y-m-t',strtotime("+$months_to_add month", current_time('timestamp')));
				$conditions['scope'] = " (event_start_date BETWEEN CAST('$start_month' AS DATE) AND CAST('$end_month' AS DATE))";
				if( !get_option('dbem_events_current_are_past') ){
					$conditions['scope'] .= " OR (event_start_date < CAST('$start_month' AS DATE) AND event_end_date >= CAST('$start_month' AS DATE))";
				}
			}elseif ($scope == "future"){
				$conditions['scope'] = " event_start_date >= CAST('$today' AS DATE)";
				if( !get_option('dbem_events_current_are_past') ){
					$conditions['scope'] .= " OR (event_end_date >= CAST('$today' AS DATE) AND event_end_date != '0000-00-00' AND event_end_date IS NOT NULL)";
				}
			}
			if( !empty($conditions['scope']) ){
				$conditions['scope'] = '('.$conditions['scope'].')';
			}
		}
		
		//Filter by Location - can be object, array, or id
		if ( is_numeric($location) && $location > 0 ) { //Location ID takes precedence
			$conditions['location'] = " {$locations_table}.location_id = $location";
		}elseif ( $location === 0 ) { //only helpful is searching events
			$conditions['location'] = " {$events_table}.location_id = $location OR {$events_table}.location_id IS NULL";
		}elseif ( self::array_is_numeric($location) ){
			$conditions['location'] = "( {$locations_table}.location_id = " . implode(" OR {$locations_table}.location_id = ", $location) .' )';
		}elseif ( is_object($location) && get_class($location)=='EM_Location' ){ //Now we deal with objects
			$conditions['location'] = " {$locations_table}.location_id = $location->location_id";
		}elseif ( is_array($location) && @get_class(current($location)=='EM_Location') ){ //we can accept array of ids or EM_Location objects
			foreach($location as $EM_Location){
				$location_ids[] = $EM_Location->location_id;
			}
			$conditions['location'] = "( {$locations_table}.location_id=". implode(" {$locations_table}.location_id=", $location_ids) ." )";
		}
		
		//Filter by Event - can be object, array, or id
		if ( is_numeric($event) && $event > 0 ) { //event ID takes precedence
			$conditions['event'] = " {$events_table}.event_id = $event";
		}elseif ( self::array_is_numeric($event) ){ //array of ids
			$conditions['event'] = "( {$events_table}.event_id = " . implode(" OR {$events_table}.event_id = ", $event) .' )';
		}elseif ( is_object($event) && get_class($event)=='EM_Event' ){ //Now we deal with objects
			$conditions['event'] = " {$events_table}.event_id = $event->event_id";
		}elseif ( is_array($event) && @get_class(current($event)=='EM_Event') ){ //we can accept array of ids or EM_event objects
			foreach($event as $EM_Event){
				$event_ids[] = $EM_Event->event_id;
			}
			$conditions['event'] = "( {$events_table}.event_id=". implode(" {$events_table}.event_id=", $event_ids) ." )";
		}

		//Location specific filters
		//if we're searching near something, country etc. becomes irrelevant
		if( !empty($args['near']) && self::array_is_numeric($args['near']) ){
			$distance = !empty($args['near_distance']) && is_numeric($args['near_distance']) ? absint($args['near_distance']) : absint(get_option('dbem_search_form_geo_units',25));
			if( empty($args['near_unit']) ) $args['near_unit'] = get_option('dbem_search_form_geo_distance','mi');
			$unit = ( !empty($args['near_unit']) && $args['near_unit'] == 'km' ) ? 6371 /* kilometers */ : 3959 /* miles */;
			$conditions['near'] = "( $unit * acos( cos( radians({$args['near'][0]}) ) * cos( radians( location_latitude ) ) * cos( radians( location_longitude ) - radians({$args['near'][1]}) ) + sin( radians({$args['near'][0]}) ) * sin( radians( location_latitude ) ) ) ) < $distance";
		}else{
			//country lookup
			if( !empty($args['country']) ){
				$countries = em_get_countries();
				//we can accept country codes or names so we need to change names to country codes
				$country_arg = !is_array($args['country']) ? array($args['country']) : $args['country'];
			    foreach( $country_arg as $country ){
    			    if( array_key_exists($country, $countries) ){
        					//we have a country code
        				$countries_search[] = $country;					
        			}elseif( in_array($country, $countries) ){
        				//we have a country name, 
        				$countries_search[] = array_search($country, $countries);
    			    }
			    }
			    if( !empty($countries_search) ){
			        if( count($countries_search) > 1 ){
			            $conditions['country'] = "location_country IN ('".implode("','",$countries_search)."')";
			        }else{
			            $conditions['country'] = "location_country='".array_pop($countries_search)."'";
			        }
			    }
			}
			//state lookup
			if( !empty($args['state']) ){
				$conditions['state'] = $wpdb->prepare('location_state=%s', $args['state']);
			}
			//town lookup
			if( !empty($args['town']) ){
				$conditions['town'] = $wpdb->prepare('location_town=%s', $args['town']);
			}
			//region lookup
			if( !empty($args['region']) ){
				$conditions['region'] = $wpdb->prepare('location_region=%s', $args['region']);
			}
		}
		
		//START TAXONOMY FILTERS - can be id, slug, name or comma separated ids/slugs/names, if negative or prepended with a - then considered a negative filter
		//convert taxonomies to arrays
		$taxonomies = self::get_taxonomies();
		foreach( $taxonomies as $item => $item_data ){ //tags and cats turned into an array regardless
		    if( !empty($args[$item]) && !is_array($args[$item]) ){
				if( preg_match('/[,&]/', $args[$item]) !== false ){ //accepts numbers or words
					$args[$item] = explode('&', $args[$item]);
					foreach($args[$item] as $k=>$v){
						$args[$item][$k] = trim($v);
						$args[$item][$k] = explode(',', $v);
						foreach($args[$item][$k] as $k_x=>$v_x) $args[$item][$k][$k_x] = trim($v_x);
					}
				}else{
				    $args[$item] = array(trim($args[$item]));
				}
		    }
		}
		foreach($taxonomies as $tax_name => $tax_data){
			if( !empty($args[$tax_name]) && is_array($args[$tax_name]) ){
			    if( !empty($tax_data['ms']) ) self::ms_global_switch(); //if in ms global mode, switch here rather than on each EM_Category instance
			    $tax_conds = array();
			    //if a single array is supplied then we treat it as an OR type of query, if an array of arrays is supplied we condsider it to be many ANDs of ORs
			    //so here we wrap a single array into another array and there is only one 'AND' condition (therefore no AND within this tax search) 
			    foreach($args[$tax_name] as $k=>$v) if( is_array($v) ) $contains_array = true;
			    if( empty($contains_array) ) $args[$tax_name] = array($args[$tax_name]);
			    //go through taxonomy arg and generate relevant SQL
			    foreach($args[$tax_name] as $tax_id_set){
					//build array of term ids and negative ids from supplied argument
					$term_tax_ids = $term_ids = array();
					$term_tax_not_ids = $term_not_ids = array();
					foreach($tax_id_set as $tax_id){
					    $tax_id_clean = preg_replace('/^-/', '', $tax_id);
						if( !is_numeric($tax_id_clean) ){
							$term = get_term_by('slug', $tax_id_clean, $tax_data['query_var']);
							if( empty($term) ){
								$term = get_term_by('name', $tax_id_clean, $tax_data['query_var']);
							}
						}else{
							$term = get_term_by('id', $tax_id_clean, $tax_data['query_var']);
						}
						if( !empty($term->term_taxonomy_id) ){
							if( !preg_match('/^-/', $tax_id) ){
								$term_tax_ids[] = $term->term_taxonomy_id;
								if( EM_MS_GLOBAL && !empty($tax_data['ms']) ) $term_ids[] = $term->term_id;
							}else{
								$term_tax_not_ids[] = $term->term_taxonomy_id;
								if( EM_MS_GLOBAL && !empty($tax_data['ms']) ) $term_not_ids[] = $term->term_id;
							}
						}elseif( preg_match('/^-/', $tax_id) ){
						    //if they supply a negative term for a nonexistent custom taxonomy e.g. -1, we should still  
						    $ignore_cancel_cond = true;
						}
					}
				    if( !empty($tax_data['ms']) ) self::ms_global_switch_back(); //switch back if ms global mode
					//create sql conditions
					if( count($term_tax_ids) > 0 || count($term_tax_not_ids) > 0 ){
					    //figure out context - what table/field to search
					    $post_context = EM_EVENTS_TABLE.".post_id";
					    $ms_context = EM_EVENTS_TABLE.".event_id";
					    if( !empty($tax_data['context']) && self::$context == EM_POST_TYPE_LOCATION && in_array( self::$context, $tax_data['context']) ){
					        //context can be either locations or events, since those are the only two CPTs we deal with
						    $post_context = EM_LOCATIONS_TABLE.".post_id";
						    $ms_context = EM_LOCATIONS_TABLE.".event_id";
					    }
					    //build conditions
						if( EM_MS_GLOBAL && !empty($tax_data['ms']) ){ //by default only applies to categories
						    //we're directly looking for tax ids from within the em_meta table
							if( count($term_ids) > 0 ){
								$tax_conds[] = "$ms_context IN ( SELECT object_id FROM ".EM_META_TABLE." WHERE meta_value IN (".implode(',',$term_ids).") AND meta_key='{$tax_data['ms']}' )";
							}
							if( count($term_not_ids) > 0 ){
								$tax_conds[] = "$ms_context NOT IN ( SELECT object_id FROM ".EM_META_TABLE." WHERE meta_value IN (".implode(',',$term_not_ids).") AND meta_key='{$tax_data['ms']}' )";			
							} 
						}else{
					    	//normal taxonomy filtering
							if( count($term_tax_ids) > 0 ){
								$tax_conds[] = "$post_context IN ( SELECT object_id FROM ".$wpdb->term_relationships." WHERE term_taxonomy_id IN (".implode(',',$term_tax_ids).") )";
							}
							if( count($term_tax_not_ids) > 0 ){
								$tax_conds[] = "$post_context NOT IN ( SELECT object_id FROM ".$wpdb->term_relationships." WHERE term_taxonomy_id IN (".implode(',',$term_tax_not_ids).") )";			
							}
						}
					}elseif( empty($ignore_cancel_cond) ){
					    $tax_conds[] = '2=1'; //force a false, supplied taxonomies don't exist
					    break; //no point continuing this loop
					}
			    }
				if( count($tax_conds) > 0 ){
					$conditions[$tax_name] = '('. implode(' AND ', $tax_conds) .')';
				}
			    if( !empty($tax_data['ms']) ) self::ms_global_switch_back(); //if in ms global mode, switch back from previous switch
			}
		}
		//END TAXONOMY FILTERS
	
		//If we want rsvped items, we usually check the event
		if( $bookings == 1 ){
			$conditions['bookings'] = 'event_rsvp=1';
		}
		//Default ownership belongs to an event, child objects can just overwrite this if needed.
		if( is_numeric($owner) ){
			$conditions['owner'] = 'event_owner='.$owner;
		}elseif( $owner == 'me' && is_user_logged_in() ){
			$conditions['owner'] = 'event_owner='.get_current_user_id();
		}elseif( $owner == 'me' && !is_user_logged_in() ){
		    $conditions = array('owner'=>'1=2'); //no events to be shown
		}elseif( self::array_is_numeric($owner) ){
			$conditions['owner'] = 'event_owner IN ('.implode(',',$owner).')';
		}
		//reset the context
		self::$context = EM_POST_TYPE_EVENT;
		//return values
		return apply_filters('em_object_build_sql_conditions', $conditions);
	}
	
	public static function get_taxonomies(){
	    if( empty(self::$taxonomies_array) ){
	        //default taxonomies
	        $taxonomies_array = array(
        		'category' => array( 'name' => EM_TAXONOMY_CATEGORY, 'slug'=>EM_TAXONOMY_CATEGORY_SLUG, 'ms' => 'event-category', 'context'=> array(), 'query_var'=>EM_TAXONOMY_CATEGORY ),
        		'tag' => array( 'name'=> EM_TAXONOMY_TAG, 'slug'=>EM_TAXONOMY_TAG_SLUG, 'context'=> array(), 'query_var'=>EM_TAXONOMY_TAG )
	        );
	        //get additional taxonomies associated with locations and events and set context for default taxonomies
	        foreach( get_taxonomies(array(),'objects') as $tax_name => $tax){
                $event_tax = in_array(EM_POST_TYPE_EVENT, $tax->object_type);
                $loc_tax = in_array(EM_POST_TYPE_LOCATION, $tax->object_type);
	            if( $tax_name == EM_TAXONOMY_CATEGORY || $tax_name == EM_TAXONOMY_TAG ){
	            	//set the context for the default taxonomies, as they're already in the array
	                $tax_name = $tax_name == EM_TAXONOMY_CATEGORY ? 'category':'tag';
                    if( $event_tax ) $taxonomies_array[$tax_name]['context'][] = EM_POST_TYPE_EVENT;
                    if( $loc_tax ) $taxonomies_array[$tax_name]['context'][] = EM_POST_TYPE_LOCATION;
	            }else{
	            	//non default taxonomy, so create new item for the taxonomies array
	                $tax_name = str_replace('-','_',$tax_name);
					$prefix = !array_key_exists($tax_name, $taxonomies_array) ? '':'post_';
	                if( is_array($tax->object_type) ){
	                    if( $event_tax || $loc_tax ){
		                    $taxonomies_array[$prefix.$tax_name] = array('name'=>$tax_name, 'context'=>array(), 'slug'=> $tax->rewrite['slug'], 'query_var'=> $tax->query_var );
	                    }
	                    if( $event_tax ) $taxonomies_array[$prefix.$tax_name]['context'][] = EM_POST_TYPE_EVENT;
	                    if( $loc_tax ) $taxonomies_array[$prefix.$tax_name]['context'][] = EM_POST_TYPE_LOCATION;
	                }	                
	            }
	        }
	        //users can add even more to this if needed, e.g. MS compatability
	        self::$taxonomies_array = apply_filters('em_object_taxonomies', $taxonomies_array);
	    }
	    return self::$taxonomies_array;
	}
	
	/**
	 * WORK IN PROGRESS
	 * Builds an array of SQL query conditions based on regularly used arguments
	 * @param array $args
	 * @return array
	 */
	public static function build_wpquery_conditions( $args = array(), $wp_query ){
		global $wpdb;
		
		$args = apply_filters('em_object_build_sql_conditions_args',$args);
		
		//Format the arguments passed on
		$scope = $args['scope'];//undefined variable warnings in ZDE, could just delete this (but dont pls!)
		$recurring = $args['recurring'];
		$recurrence = $args['recurrence'];
		$category = $args['category'];
		$tag = $args['tag'];
		$location = $args['location'];
		$bookings = $args['rsvp'];
		$bookings = !empty($args['bookings']) ? $args['bookings']:$bookings;
		$owner = $args['owner'];
		$event = $args['event'];
		$month = $args['month'];
		$year = $args['year'];
		$today = date('Y-m-d', current_time('timestamp'));
		//Create the WHERE statement
		
		//Recurrences
		$query = array();
		if( $recurrence > 0 ){
			$query[] = array( 'key' => '_recurrence_id', 'value' => $recurrence, 'compare' => '=' );
		}
		//Dates - first check 'month', and 'year', and adjust scope if needed
		if( !($month=='' && $year=='') ){
			//Sort out month range, if supplied an array of array(month,month), it'll check between these two months
			if( self::array_is_numeric($month) ){
				$date_month_start = $month[0];
				$date_month_end = $month[1];
			}else{
				if( !empty($month) ){
					$date_month_start = $date_month_end = $month;					
				}else{
					$date_month_start = 1;
					$date_month_end = 12;				
				}
			}
			//Sort out year range, if supplied an array of array(year,year), it'll check between these two years
			if( self::array_is_numeric($year) ){
				$date_year_start = $year[0];
				$date_year_end = $year[1];
			}else{
				$date_year_start = $date_year_end = $year;
			}
			$date_start = $date_year_start."-".$date_month_start."-01";
			$date_end = date('Y-m-t', mktime(0,0,0,$date_month_end,1,$date_year_end));
			$scope = array($date_start,$date_end); //just modify the scope here
		}
		//No date requested, so let's look at scope
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
		}elseif ( is_array($scope) || preg_match( "/^[0-9]{4}-[0-9]{2}-[0-9]{2},[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $scope ) ) {
			if( !is_array($scope) ) $scope = explode(',',$scope);
			if( !empty($scope[0]) ){
				$start = strtotime(date('Y-m-d',$scope[0]));
				$end = !empty($scope[1]) ? strtotime(date('Y-m-t',$scope[1])):$start;
				if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
					$query[] = array( 'key' => '_start_ts', 'value' => array($start,$end), 'type' => 'numeric', 'compare' => 'BETWEEN');
				}else{
					$query[] = array( 'key' => '_start_ts', 'value' => $end, 'compare' => '<=' );
					$query[] = array( 'key' => '_end_ts', 'value' => $start, 'compare' => '>=' );
				}
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
			if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
				//date must be only today
				$query[] = array( 'key' => '_start_ts', 'value' => $today, 'compare' => '=');
			}else{
				$query[] = array( 'key' => '_start_ts', 'value' => $today, 'compare' => '<=' );
				$query[] = array( 'key' => '_end_ts', 'value' => $today, 'compare' => '>=' );
			}
		}elseif ($scope == "tomorrow"){
			$tomorrow = strtotime(date('Y-m-d',$time+60*60*24));
			if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
				//date must be only tomorrow
				$query[] = array( 'key' => '_start_ts', 'value' => $tomorrow, 'compare' => '=');
			}else{
				$query[] = array( 'key' => '_start_ts', 'value' => $tomorrow, 'compare' => '<=' );
				$query[] = array( 'key' => '_end_ts', 'value' => $tomorrow, 'compare' => '>=' );
			}
		}elseif ($scope == "month"){
			$start_month = strtotime(date('Y-m-d',$time));
			$end_month = strtotime(date('Y-m-t',$time));
			if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
				$query[] = array( 'key' => '_start_ts', 'value' => array($start_month,$end_month), 'type' => 'numeric', 'compare' => 'BETWEEN');
			}else{
				$query[] = array( 'key' => '_start_ts', 'value' => $end_month, 'compare' => '<=' );
				$query[] = array( 'key' => '_end_ts', 'value' => $start_month, 'compare' => '>=' );
			}
		}elseif ($scope == "next-month"){
			$start_month_timestamp = strtotime('+1 month', $time); //get the end of this month + 1 day
			$start_month = strtotime(date('Y-m-1',$start_month_timestamp));
			$end_month = strtotime(date('Y-m-t',$start_month_timestamp));
			if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
				$query[] = array( 'key' => '_start_ts', 'value' => array($start_month,$end_month), 'type' => 'numeric', 'compare' => 'BETWEEN');
			}else{
				$query[] = array( 'key' => '_start_ts', 'value' => $end_month, 'compare' => '<=' );
				$query[] = array( 'key' => '_end_ts', 'value' => $start_month, 'compare' => '>=' );
			}
		}elseif( preg_match('/(\d\d?)\-months/',$scope,$matches) ){ // next x months means this month (what's left of it), plus the following x months until the end of that month.
			$months_to_add = $matches[1];
			$start_month = strtotime(date('Y-m-d',$time));
			$end_month = strtotime(date('Y-m-t',strtotime("+$months_to_add month", $time)));
			if( get_option('dbem_events_current_are_past') && $wp_query->query_vars['post_type'] != 'event-recurring' ){
				$query[] = array( 'key' => '_start_ts', 'value' => array($start_month,$end_month), 'type' => 'numeric', 'compare' => 'BETWEEN');
			}else{
				$query[] = array( 'key' => '_start_ts', 'value' => $end_month, 'compare' => '<=' );
				$query[] = array( 'key' => '_end_ts', 'value' => $start_month, 'compare' => '>=' );
			}
		}
		
		//Filter by Location - can be object, array, or id
		if ( is_numeric($location) && $location > 0 ) { //Location ID takes precedence
			$query[] = array( 'key' => '_location_id', 'value' => $location, 'compare' => '=' );
		}elseif ( self::array_is_numeric($location) ){
			$query[] = array( 'key' => '_location_id', 'value' => $location, 'compare' => 'IN' );
		}elseif ( is_object($location) && get_class($location)=='EM_Location' ){ //Now we deal with objects
			$query[] = array( 'key' => '_location_id', 'value' => $location->location_id, 'compare' => '=' );
		}elseif ( is_array($location) && @get_class(current($location)=='EM_Location') ){ //we can accept array of ids or EM_Location objects
			foreach($location as $EM_Location){
				$location_ids[] = $EM_Location->location_id;
			}
			$query[] = array( 'key' => '_location_id', 'value' => $location_ids, 'compare' => 'IN' );
		}
		
		//Filter by Event - can be object, array, or id
		if ( is_numeric($event) && $event > 0 ) { //event ID takes precedence
			$query[] = array( 'key' => '_event_id', 'value' => $event, 'compare' => '=' );
		}elseif ( self::array_is_numeric($event) ){ //array of ids
			$query[] = array( 'key' => '_event_id', 'value' => $event, 'compare' => 'IN' );
		}elseif ( is_object($event) && get_class($event)=='EM_Event' ){ //Now we deal with objects
			$query[] = array( 'key' => '_event_id', 'value' => $event->event_id, 'compare' => '=' );
		}elseif ( is_array($event) && @get_class(current($event)=='EM_Event') ){ //we can accept array of ids or EM_event objects
			foreach($event as $EM_Event){
				$event_ids[] = $EM_Event->event_id;
			}
			$query[] = array( 'key' => '_event_id', 'value' => $event_ids, 'compare' => 'IN' );
		}
		//country lookup
		if( !empty($args['country']) ){
			$countries = em_get_countries();
			//we can accept country codes or names
			if( in_array($args['country'], $countries) ){
				//we have a country name, 
				$country = $countries[$args['country']]."'";	
			}elseif( array_key_exists($args['country'], $countries) ){
				//we have a country code
				$country = $args['country'];					
			}
			if(!empty($country)){
				//get loc ids
				$ids = $wpdb->get_col("SELECT post_id FROM ".$wpdb->postmeta." WHERE meta_key='_location_country' AND meta_value='$country'");
				$query[] = array( 'key' => '_location_id', 'value' => $ids, 'compare' => 'IN' );
			}
		}
		//state lookup
		if( !empty($args['state']) ){
			$ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM ".$wpdb->postmeta." WHERE meta_key='_location_country' AND meta_value='%s'", $args['state']));
			if( is_array($wp_query->query_vars['post__in']) ){
				//remove values not in this array.
				$wp_query->query_vars['post__in'] = array_intersect($wp_query->query_vars['post__in'], $ids);
			}else{
				$wp_query->query_vars['post__in'] = $ids;
			}
		}
		//state lookup
		if( !empty($args['town']) ){			
			$ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM ".$wpdb->postmeta." WHERE meta_key='_location_town' AND meta_value='%s'", $args['town']));
			if( is_array($wp_query->query_vars['post__in']) ){
				//remove values not in this array.
				$wp_query->query_vars['post__in'] = array_intersect($wp_query->query_vars['post__in'], $ids);
			}else{
				$wp_query->query_vars['post__in'] = $ids;
			}
		}
		//region lookup
		if( !empty($args['region']) ){	
			$ids = $wpdb->get_col($wpdb->prepare("SELECT post_id FROM ".$wpdb->postmeta." WHERE meta_key='_location_region' AND meta_value='%s'", $args['region']));
			if( is_array($wp_query->query_vars['post__in']) ){
				//remove values not in this array.
				$wp_query->query_vars['post__in'] = array_intersect($wp_query->query_vars['post__in'], $ids);
			}else{
				$wp_query->query_vars['post__in'] = $ids;
			}
		}
		//Add conditions for category selection
		//Filter by category, can be id or comma separated ids
		//TODO create an exclude category option
		if ( is_numeric($category) && $category > 0 ){
			//get the term id directly
			$term = new EM_Category($category);
			if( !empty($term->term_id) ){
				if( EM_MS_GLOBAL ){
					$event_ids = $wpdb->get_col($wpdb->prepare("SELECT object_id FROM ".EM_META_TABLE." WHERE meta_value=%d AND meta_key='event-category'", $term->term_id));
					$query[] = array( 'key' => '_event_id', 'value' => $event_ids, 'compare' => 'IN' );
				}else{
					if( !is_array($wp_query->query_vars['tax_query']) ) $wp_query->query_vars['tax_query'] = array();
					$wp_query->query_vars['tax_query'] = array('taxonomy' => EM_TAXONOMY_CATEGORY, 'field'=>'id', 'terms'=>$term->term_id);
				}
			} 
		}elseif( self::array_is_numeric($category) ){
			$term_ids = array();
			foreach($category as $category_id){
				$term = new EM_Category($category_id);
				if( !empty($term->term_id) ){
					$term_ids[] = $term->term_taxonomy_id;
				}
			}
			if( count($term_ids) > 0 ){
				if( EM_MS_GLOBAL ){
					$event_ids = $wpdb->get_col("SELECT object_id FROM ".EM_META_TABLE." WHERE meta_value IN (".implode(',',$term_ids).") AND meta_name='event-category'");
					$query[] = array( 'key' => '_event_id', 'value' => $event_ids, 'compare' => 'IN' );
				}else{
					if( !is_array($wp_query->query_vars['tax_query']) ) $wp_query->query_vars['tax_query'] = array();
					$wp_query->query_vars['tax_query'] = array('taxonomy' => EM_TAXONOMY_CATEGORY, 'field'=>'id', 'terms'=>$term_ids);
				}
			}
		}		
		//Add conditions for tags
		//Filter by tag, can be id or comma separated ids
		if ( !empty($tag) && !is_array($tag) ){
			//get the term id directly
			$term = new EM_Tag($tag);
			if( !empty($term->term_id) ){
				if( !is_array($wp_query->query_vars['tax_query']) ) $wp_query->query_vars['tax_query'] = array();
				$wp_query->query_vars['tax_query'] = array('taxonomy' => EM_TAXONOMY_TAXONOMY, 'field'=>'id', 'terms'=>$term->term_taxonomy_id);
			} 
		}elseif( is_array($tag) ){
			$term_ids = array();
			foreach($tag as $tag_data){
				$term = new EM_Tag($tag_data);
				if( !empty($term->term_id) ){
					$term_ids[] = $term->term_taxonomy_id;
				}
			}
			if( count($term_ids) > 0 ){
				if( !is_array($wp_query->query_vars['tax_query']) ) $wp_query->query_vars['tax_query'] = array();
				$wp_query->query_vars['tax_query'] = array('taxonomy' => EM_TAXONOMY_TAXONOMY, 'field'=>'id', 'terms'=>$term_ids);
			}
		}
	
		//If we want rsvped items, we usually check the event
		if( $bookings == 1 ){
			$query[] = array( 'key' => '_event_rsvp', 'value' => 1, 'compare' => '=' );
		}
		//Default ownership belongs to an event, child objects can just overwrite this if needed.
		if( is_numeric($owner) ){
			$wp_query->query_vars['author'] = $owner;
		}elseif( $owner == 'me' && is_user_logged_in() ){
			$wp_query->query_vars['author'] = get_current_user_id();
		}
	  	if( !empty($query) && is_array($query) ){
			$wp_query->query_vars['meta_query'] = $query;
	  	}
		return apply_filters('em_object_build_wp_query_conditions', $wp_query);
	}
	
	public static function build_sql_orderby( $args, $accepted_fields, $default_order = 'ASC' ){
		//First, ORDER BY
		$args = apply_filters('em_object_build_sql_orderby_args', $args);
		$orderby = array();
		if(is_array($args['orderby'])){
			//Clean orderby array so we only have accepted values
			foreach( $args['orderby'] as $key => $field ){
				if( array_key_exists($field, $accepted_fields) ){
					$orderby[] = $accepted_fields[$field];
				}elseif( in_array($field,$accepted_fields) ){
					$orderby[] = $field;
				}else{
					unset($args['orderby'][$key]);
				}
			}
		}elseif( $args['orderby'] != '' && array_key_exists($args['orderby'], $accepted_fields) ){
			$orderby[] = $accepted_fields[$args['orderby']];
		}elseif( $args['orderby'] != '' && in_array($args['orderby'], $accepted_fields) ){
			$orderby[] = $args['orderby'];
		}
		//ORDER
		//If order is an array, we'll go through the orderby array and match the order values (in order of array) with orderby values
		//If orders don't match up, or it's not ASC/DESC, the default events search in EM settings/options page will be used.
		foreach($orderby as $i => $field){
			$orderby[$i] .= ' ';
			if(is_array($args['order'])){
				if( in_array($args['order'][$i], array('ASC','DESC','asc','desc')) ){
					$orderby[$i] .= $args['order'][$i];
				}else{
					$orderby[$i] .= $default_order;
				}
			}else{
				$orderby[$i] .= ( in_array($args['order'], array('ASC','DESC','asc','desc')) ) ? $args['order'] : $default_order;
			}
		}
		return apply_filters('em_object_build_sql_orderby', $orderby);
	}
	
	/**
	 * Gets array of searchable variables that should be considered in a $_REQUEST variable
	 * @param array $args Arguments to include in returned array
	 * @param string $filter Filters out any unrecognized arguments already passed into $args
	 * @param array $request defaults to $_REQUEST if empty but can be an array of items to go through instead
	 * @param array $accepted_searches defaults to EM_Object::get_search_defaults(), objects should call self::get_search_defaults() to get around late static binding problems
	 * @return array
	 */
	public static function get_post_search($args = array(), $filter = false, $request = array(), $accepted_searches = array()){
		if( empty($request) ) $request = $_REQUEST;
		if( !empty($request['em_search']) && empty($args['search']) ) $request['search'] = $request['em_search']; //em_search is included to circumvent wp search GET/POST clashes
		$accepted_searches = !empty($accepted_searches) ? $accepted_searches : self::get_default_search();
		$accepted_searches = apply_filters('em_accepted_searches', $accepted_searches, $args);
		//merge variables from the $request into $args
		foreach($request as $post_key => $post_value){
			if( in_array($post_key, $accepted_searches) && !empty($post_value) ){
				if(is_array($post_value)){
					$post_value = implode(',',$post_value);
				}else{
				    $post_value =  wp_unslash($post_value);
				}
				if($post_value != ',' ){
					$args[$post_key] = $post_value;
				}elseif( $post_value == ',' && $post_key == 'scope' && empty($args['scope']) ){
					//unset the scope if no value is provided - ',' is an empty value
					unset($args['scope']);
				}
			}
		}
		if( $filter ){
			foreach($args as $arg_key => $arg_value){
				if( !in_array($arg_key, $accepted_searches) ){
					unset($args[$arg_key]);
				}
			}
		}
		return apply_filters('em_get_post_search', $args);
	}
	
	/**
	 * Generates pagination for classes like EM_Events based on supplied arguments and whether AJAX is enabled.
	 * 
	 * @param array $args The arguments being searched for
	 * @param integer $count The number of total items to paginate through
	 * @param string $search_action The name of the action query var used to trigger a search - used in AJAX requests and normal searches
	 * @param array $default_args The default arguments and values this object accepts, used to compare against $args to create a querystring
	 * @param array $accepted_args Variables that can be passed on via a querystring and should be added to pagination links, objects should make use of this since the default may be EM_Object::get_default_search() due to late static binding issues
	 * @return string
	 * @uses em_paginate()
	 */
	public static function get_pagination_links($args, $count, $search_action, $default_args = array()){
		$limit = ( !empty($args['limit']) && is_numeric($args['limit']) ) ? $args['limit']:false;
		$page = ( !empty($args['page']) && is_numeric($args['page']) ) ? $args['page']:1;
		$pno = !empty($args['page_queryvar']) ? $args['page_queryvar'] : 'pno';
		$default_pag_args = array($pno=>'%PAGE%', 'page'=>null, 'search'=>null, 'action'=>null, 'pagination'=>null); //clean out the bad stuff, set up page number template
		$page_url = $_SERVER['REQUEST_URI'];
		//$default_args are values that can be added to the querystring for use in searching events in pagination either in searches or ajax pagination
		if( !empty($_REQUEST['action']) && $_REQUEST['action'] == $search_action && empty($default_args) ){
			//due to late static binding issues in PHP, this'll always return EM_Object::get_default_search so this is a fall-back
			$default_args = self::get_default_search();
		}
		//go through default arguments (if defined) and build a list of unique non-default arguments that should go into the querystring
		$unique_args = array(); //this is the set of unique arguments we'll add to the querystring
		$ignored_args = array('offset', 'ajax', 'array', 'pagination','format','format_header','format_footer'); 
		foreach( $default_args as $arg_key => $arg_default_val){
			if( array_key_exists($arg_key, $args) && !in_array($arg_key, $ignored_args) ){
				//if array exists, implode it in case one value is already imploded for matching purposes
				$arg_val = is_array($args[$arg_key]) ? implode(',', $args[$arg_key]) : $args[$arg_key];
				$arg_default_val = is_array($arg_default_val) ? implode(',',$arg_default_val) : $arg_default_val;
				if( $arg_val != $arg_default_val ){
					$unique_args[$arg_key] = $arg_val;
				}
			}
		}
		if( !empty($unique_args['search']) ){ 
			$unique_args['em_search'] = $unique_args['search']; //special case, since em_search is used in links rather than search, which we remove below
			unset($unique_args['search']);
		}
		//build general page link with all arguments
		$pag_args = array_merge($unique_args, $default_pag_args);
		//if we're using ajax or already did an events search via a form, add the action here for pagination links
		if( !empty($args['ajax']) || (!empty($_REQUEST['action']) && $_REQUEST['action'] == $search_action ) ){
			$unique_args['action'] = $pag_args['action'] = $search_action;
		}
		//if we're in an ajax call, make sure we aren't calling admin-ajax.php
		if( defined('DOING_AJAX') ) $page_url = em_wp_get_referer();
		//finally, glue the url with querystring and pass onto pagination function
		$page_link_template = em_add_get_params($page_url, $pag_args, false); //don't html encode, so em_paginate does its thing;
		if( empty($args['ajax']) || defined('DOING_AJAX') ) $unique_args = array(); //don't use data method if ajax is disabled or if we're already in an ajax request (SERP irrelevenat)
		$return = apply_filters('em_object_get_pagination_links', em_paginate( $page_link_template, $count, $limit, $page, $unique_args ), $page_link_template, $count, $limit, $page);
		//if PHP is 5.3 or later, you can specifically filter by class e.g. em_events_output_pagination - this replaces the old filter originally located in the actual child classes
		if( function_exists('get_called_class') ){
			$return = apply_filters(strtolower(get_called_class()).'_output_pagination', $return, $page_link_template, $count, $limit, $page);
		}
		return $return;
	}
	
	/**
	 * Returns the id of a particular object in the table it is stored, be it Event (event_id), Location (location_id), Tag, Booking etc.
	 * @return int 
	 */
	function get_id(){
	    switch( get_class($this) ){
	        case 'EM_Event':
	            return $this->event_id;
	        case 'EM_Location':
	            return $this->location_id;
	        case 'EM_Category':
	            return $this->term_id;
	        case 'EM_Tag':
	            return $this->term_id;
	        case 'EM_Ticket':
	            return $this->ticket_id;
	        case 'EM_Ticket_Booking':
	            return $this->ticket_booking_id;
	        case 'EM_Ticket_Booking':
	            return $this->ticket_booking_id;
	    }
	    return 0;
	}
	
	/**
	 * Returns the user id for the owner (author) of a particular object in the table it is stored, be it Event (event_owner) or Location (location_owner).
	 * This function accounts for the fact that previously the property $this->owner was used by objects as a shortcut and consequently in code in EM_Object, which should now use this method instead.
	 * Extending classes should override this and provide the relevant user id that owns this object instance. 
	 * @return int
	 */	
	function get_owner(){
		if( !empty($this->owner) ) return $this->owner;
	    switch( get_class($this) ){
	        case 'EM_Event':
	            return $this->event_owner;
	        case 'EM_Location':
	            return $this->location_owner;
	    }
	    return 0;
	}
	
	/**
	 * Used by "single" objects, e.g. bookings, events, locations to verify if they have the capability to edit this or someone else's object. Relies on the fact that the object has an owner property with id of user (or admin capability must pass).
	 * @param string $owner_capability If the object has an owner property and the user id matches that, this capability will be checked for.
	 * @param string $admin_capability If the user isn't the owner of the object, this capability will be checked for.
	 * @return boolean
	 */
	function can_manage( $owner_capability = false, $admin_capability = false, $user_to_check = false ){
		global $em_capabilities_array;
		//if multisite and super admin, just return true
		if( is_multisite() && is_super_admin() ){ return true; }
		//set user to the desired user we're verifying, otherwise default to current user
	    if( $user_to_check ){
	    	$user = new WP_User($user_to_check);	
	    }
	    if( empty($user->ID) ) $user = wp_get_current_user();
		//do they own this?
		$owner_id = $this->get_owner();
		$is_owner = ( (!empty($owner_id) && ($owner_id == get_current_user_id()) || !$this->get_id() || (!empty($user) && $owner_id == $user->ID)) );
		//now check capability
		$can_manage = false;
		if( $is_owner && $owner_capability && $user->has_cap($owner_capability) ){
			//user owns the object and can therefore manage it
			$can_manage = true;
		}elseif( $owner_capability && array_key_exists($owner_capability, $em_capabilities_array) ){
			//currently user is not able to manage as they aren't the owner
			$error_msg = $em_capabilities_array[$owner_capability];
		}
		//admins have special rights
		if( !$admin_capability ) $admin_capability = $owner_capability;
		if( $admin_capability && $user->has_cap($admin_capability) ){
			$can_manage = true;
		}elseif( $admin_capability && array_key_exists($admin_capability, $em_capabilities_array) ){
			$error_msg = $em_capabilities_array[$admin_capability];
		}
		
		if( !$can_manage && !$is_owner && !empty($error_msg) ){
			// Hack PoP Plug-in: add a hook to not add the error message yet (since later on we overrid can_manage function)
			// $this->add_error($error_msg);
			$add_error = apply_filters('gd_em_can_manage_add_error', true, $this, $owner_capability, $admin_capability, $user_to_check);
			if ($add_error)
				$this->add_error($error_msg);
		}
		return $can_manage;
	}

	
	public static function ms_global_switch(){
		if( EM_MS_GLOBAL ){
			//If in multisite global, then get the main blog
			global $current_site;
			switch_to_blog($current_site->blog_id);
		}
	}
	
	public static function ms_global_switch_back(){
		if( EM_MS_GLOBAL ){
			restore_current_blog();
		}
	}
	
	/**
	 * Save an array into this class.
	 * If you provide a record from the database table corresponding to this class type it will add the data to this object.
	 * @param array $array
	 * @return null
	 */
	function to_object( $array = array(), $addslashes = false ){
		//Save core data
		if( is_array($array) ){
			$array = apply_filters('em_to_object', $array);
			foreach ( array_keys($this->fields) as $key ) {
				if(array_key_exists($key, $array)){
					if( !is_object($array[$key]) && !is_array($array[$key]) ){
						$array[$key] = ($addslashes) ? wp_unslash($array[$key]):$array[$key];
					}elseif( is_array($array[$key]) ){
						$array[$key] = ($addslashes) ? wp_unslash_deep($array[$key]):$array[$key];
					}
					$this->$key = $array[$key];
				}
			}
		}
	}
	
	/**
	 * Copies all the properties to shorter property names for compatability, do not use the old properties.
	 */
	function compat_keys(){
		foreach($this->fields as $key => $fieldinfo){
		    $field_name = $fieldinfo['name'];
			if(!empty($this->$key)) $this->$field_name = $this->$key;
		}
	}

	/**
	 * Returns this object in the form of an array, useful for saving directly into a database table.
	 * @return array
	 */
	function to_array($db = false){
		$array = array();
		foreach ( $this->fields as $key => $val ) {
			if($db){
				if( !empty($this->$key) || $this->$key === 0 || $this->$key === '0' || empty($val['null']) ){
					$array[$key] = $this->$key;
				}
			}else{
				$array[$key] = $this->$key;
			}
		}
		return apply_filters('em_to_array', $array);
	}
	

	/**
	 * Function to retreive wpdb types for all fields, or if you supply an assoc array with field names as keys it'll return an equivalent array of wpdb types
	 * @param array $array
	 * @return array:
	 */
	function get_types($array = array()){
		$types = array();
		if( count($array)>0 ){
			//So we look at assoc array and find equivalents
			foreach ($array as $key => $val){
				$types[] = $this->fields[$key]['type'];
			}
		}else{
			//Blank array, let's assume we're getting a standard list of types
			foreach ($this->fields as $field){
				$types[] = $field['type'];
			}
		}
		return apply_filters('em_object_get_types', $types, $this, $array);
	}	
	
	function get_fields( $inverted_array=false ){
		if( is_array($this->fields) ){
			$return = array();
			foreach($this->fields as $fieldName => $fieldArray){
				if($inverted_array){
					$return[$fieldArray['name']] = $fieldName;
				}else{
					$return[$fieldName] = $fieldArray['name'];
				}
			}
			return apply_filters('em_object_get_fields', $return, $this, $inverted_array);
		}
		return apply_filters('em_object_get_fields', array(), $this, $inverted_array);
	}

	/**
	 * Sanitize text before inserting into database
	 * @param string $value
	 * @return string
	 */
	function sanitize( $value ) {
		if( get_magic_quotes_gpc() ) 
	      $value = wp_unslash( $value );
	
		//check if this function exists
		if( function_exists( "mysql_real_escape_string" ) ) {
	    	$value = mysql_real_escape_string( $value );
			//for PHP version < 4.3.0 use addslashes
		} else {
	      $value = addslashes( $value );
		}
		return apply_filters('em_object_sanitize', $value);
	}
	
	/**
	 * Cleans arrays that contain id lists. Takes an array of items and will clean the keys passed in second argument so that if they keep numbers, explode comma-separated numbers, and unsets the key if there's any other value
	 * @param array $array
	 * @param array $id_atts
	 */
	public static function clean_id_atts( $array = array(), $id_atts = array() ){
		if( is_array($array) && is_array($id_atts) ){
			foreach( $array as $key => $string ){
				if( in_array($key, $id_atts) ){
					//This is in the list of atts we want cleaned
					if( is_numeric($string) ){
						$array[$key] = (int) $string;
					}elseif( self::array_is_numeric($string) ){
						$array[$key] = $string;
					}elseif( !is_array($string) && preg_match('/^( ?[\-0-9] ?,?)+$/', $string) ){
					    $array[$key] = explode(',', str_replace(' ','',$string));
					}else{
						//No format we accept
						unset($array[$key]);
					}
				}
			}
		}
		return $array;
	}
		
	/**
	 * Send an email and log errors in this object
	 * @param string $subject
	 * @param string $body
	 * @param string $email
	 * @return string
	 */
	function email_send($subject, $body, $email){
		global $EM_Mailer;
		if( !empty($subject) ){
			if( !is_object($EM_Mailer) ){
				$EM_Mailer = new EM_Mailer();
			}
			if( !$EM_Mailer->send($subject,$body,$email) ){
				if( is_array($EM_Mailer->errors) ){
					foreach($EM_Mailer->errors as $error){
						$this->errors[] = $error;
					}
				}else{
					$this->errors[] = $EM_Mailer->errors;
				}
				return false;
			}
		}
		return true;
	}
	
	/**
	 * Will return true if this is a simple (non-assoc) numeric array, meaning it has at one or more numeric entries and nothing else
	 * @param mixed $array
	 * @return boolean
	 */
	public static function array_is_numeric($array){
		$results = array();
		if(is_array($array)){
			foreach($array as $key => $item){
				$results[] = (is_numeric($item)&&is_numeric($key));
			}
		}
		return (!in_array(false, $results) && count($results) > 0);
	}	
	
	/**
	 * Returns an array of errors in this object
	 * @return array 
	 */
	function get_errors(){
		if(is_array($this->errors)){
			return $this->errors;
		}else{
			return array();
		}
	}
	
	/**
	 * Adds an error to the object
	 */
	function add_error($errors){
		if(!is_array($errors)){ $errors = array($errors); } //make errors var an array if it isn't already
		if(!is_array($this->errors)){ $this->errors = array(); } //create empty array if this isn't an array
		foreach($errors as $key => $error){			
			if( !in_array($error, $this->errors) ){
			    if( !is_array($error) ){
					$this->errors[] = $error;
			    }else{
			        $this->errors[] = array($key => $error);
			    }
			}
		}
	}
	
	/**
	 * Converts an array to JSON format, useful for outputting data for AJAX calls. Uses a PHP4 fallback function, given it doesn't support json_encode().
	 * @param array $array
	 * @return string
	 */
	public static function json_encode($array){
	    $array = apply_filters('em_object_json_encode_pre',$array);
		if( function_exists("json_encode") ){
			$return = json_encode($array);
		}else{
			$return = self::array_to_json($array);
		}
		if( isset($_REQUEST['callback']) && preg_match("/^jQuery[_a-zA-Z0-9]+$/", $_REQUEST['callback']) ){
			$return = $_REQUEST['callback']."($return)";
		}
		return apply_filters('em_object_json_encode', $return, $array);
	}
	
	/**
	 * Compatible json encoder function for PHP4
	 * @param array $array
	 * @return string
	 */
	function array_to_json($array){
		//PHP4 Comapatability - This encodes the array into JSON. Thanks go to Andy - http://www.php.net/manual/en/function.json-encode.php#89908
		if( !is_array( $array ) ){
	        $array = array();
	    }
	    $associative = count( array_diff( array_keys($array), array_keys( array_keys( $array )) ));
	    if( $associative ){
	        $construct = array();
	        foreach( $array as $key => $value ){
	            // We first copy each key/value pair into a staging array,
	            // formatting each key and value properly as we go.
	            // Format the key:
	            if( is_numeric($key) ){
	                $key = "key_$key";
	            }
	            $key = "'".addslashes($key)."'";
	            // Format the value:
	            if( is_array( $value )){
	                $value = self::array_to_json( $value );
	            }else if( is_bool($value) ) {
	            	$value = ($value) ? "true" : "false";
	            }else if( !is_numeric( $value ) || is_string( $value ) ){
	                $value = "'".addslashes($value)."'";
	            }
	            // Add to staging array:
	            $construct[] = "$key: $value";
	        }
	        // Then we collapse the staging array into the JSON form:
	        $result = "{ " . implode( ", ", $construct ) . " }";
	    } else { // If the array is a vector (not associative):
	        $construct = array();
	        foreach( $array as $value ){
	            // Format the value:
	            if( is_array( $value )){
	                $value = self::array_to_json( $value );
	            } else if( !is_numeric( $value ) || is_string( $value ) ){
	                $value = "'".addslashes($value)."'";
	            }
	            // Add to staging array:
	            $construct[] = $value;
	        }
	        // Then we collapse the staging array into the JSON form:
	        $result = "[ " . implode( ", ", $construct ) . " ]";
	    }		
	    return $result;
	}	
	
	/*
	 * START IMAGE UPlOAD FUNCTIONS
	 * Used for various objects, so shared in one place 
	 */
	/**
	 * Returns the type of image in lowercase, if $path is true, a base filename is returned which indicates where to store the file from the root upload folder.
	 * @param unknown_type $path
	 * @return mixed|mixed
	 */
	function get_image_type($path = false){
		$type = false;
		switch( get_class($this) ){
			case 'EM_Event':
				$dir = (EM_IMAGE_DS == '/') ? 'events/':'';
				$type = 'event';
				break;
			case 'EM_Location':
				$dir = (EM_IMAGE_DS == '/') ? 'locations/':'';
				$type = 'location';
				break;
			case 'EM_Category':
				$dir = (EM_IMAGE_DS == '/') ? 'categories/':'';
				$type = 'category';
				break;
		} 	
		if($path){
			return apply_filters('em_object_get_image_type',$dir.$type, $path, $this);
		}
		return apply_filters('em_object_get_image_type',$type, $path, $this);
	}
	
	function get_image_url($size = 'full'){
		$image_url = $this->image_url;
		if( !empty($this->post_id) && (empty($this->image_url) || $size != 'full') ){
			$post_thumbnail_id = get_post_thumbnail_id( $this->post_id );
			$src = wp_get_attachment_image_src($post_thumbnail_id, $size);
			if( !empty($src[0]) && $size == 'full' ){
				$image_url = $this->image_url = $src[0];
			}elseif(!empty($src[0])){
				$image_url = $src[0];
			}
			//legacy image finder, annoying, but must be done
			if( empty($image_url) ){
				$type = $this->get_image_type();
				if( get_class($this) == "EM_Event" ){
					$id = ( $this->is_recurrence() ) ? $this->recurrence_id:$this->event_id; //quick fix for recurrences
				}elseif( get_class($this) == "EM_Location" ){
				    $id = $this->location_id;
				}else{
				    $id = $this->id;
				}
				if( $type ){
				  	foreach($this->mime_types as $mime_type) {
						$file_name = $this->get_image_type(true)."-{$id}.$mime_type";
						if( file_exists( EM_IMAGE_UPLOAD_DIR . $file_name) ) {
				  			$image_url = $this->image_url = EM_IMAGE_UPLOAD_URI.$file_name;
						}
					}
				}
			}
		}
		return apply_filters('em_object_get_image_url', $image_url, $this);
	}
	
	function image_delete($force_delete=true) {
		$type = $this->get_image_type();
		if( $type ){
            $this->image_url = '';
			if( $this->get_image_url() == '' ){
				$result = true;
			}else{
				$post_thumbnail_id = get_post_thumbnail_id( $this->post_id );
				//check that this image isn't being used by another CPT
                global $wpdb;
                $sql = $wpdb->prepare('SELECT count(*) FROM '.$wpdb->postmeta." WHERE meta_key='_thumbnail_id' AND meta_value=%d", array($post_thumbnail_id));
				if( $wpdb->get_var($sql) <= 1 ){
				    //not used by any other CPT, so just delete the image entirely (would usually only be used via front-end which has no media manager)
				    //@todo add setting option to delete images from filesystem/media if not used by other posts
    				$delete_attachment = wp_delete_attachment($post_thumbnail_id, $force_delete);
    				if( false === $delete_attachment ){
    					//check legacy image
    					$type_id_name = $type.'_id';
    					$file_name= EM_IMAGE_UPLOAD_DIR.$this->get_image_type(true)."-".$this->$type_id_name;
    					$result = false;
    					foreach($this->mime_types as $mime_type) { 
    						if (file_exists($file_name.".".$mime_type)){
    					  		$result = unlink($file_name.".".$mime_type);
    						}
    					}
    				}else{
    				    $result = true;
    				}
				}else{
				    //just delete image association
				    delete_post_meta($this->post_id, '_thumbnail_id');
				}
			}
		}
		return apply_filters('em_object_get_image_url', $result, $this);
	}
	
	function image_upload(){
		$type = $this->get_image_type();
		//Handle the attachment as a WP Post
		$attachment = '';
		$user_to_check = ( !is_user_logged_in() && get_option('dbem_events_anonymous_submissions') ) ? get_option('dbem_events_anonymous_user'):false;		
		if ( !empty($_FILES[$type.'_image']['size']) && file_exists($_FILES[$type.'_image']['tmp_name']) && $this->image_validate() && $this->can_manage('upload_event_images','upload_event_images', $user_to_check) ) {
			require_once(ABSPATH . "wp-admin" . '/includes/file.php');					
			require_once(ABSPATH . "wp-admin" . '/includes/image.php');
        	require_once( ABSPATH . 'wp-admin/includes/media.php' );
					
			$attachment_id = media_handle_upload( $type.'_image', $this->post_id );
			
			/* Attach file to item */
			if ( !is_wp_error($attachment_id) ){
				//delete the old attachment
				$this->image_delete();
				update_post_meta($this->post_id, '_thumbnail_id', $attachment_id);
				return apply_filters('em_object_image_upload', true, $this);
			}else{
			    //error uploading, pass error message on and return false
			    $error_string = __('There was an error uploading the image.','events-manager');
			    if( current_user_can('edit_others_events') && !empty($attachment_id->errors['upload_error']) ){
    			    $error_string .= ' <em>('. implode(' ', $attachment_id->errors['upload_error']) .')</em>';
			    }
			    $this->add_error( $error_string );
			    return apply_filters('em_object_image_upload', false, $this);
			}
		}elseif( !empty($_REQUEST[$type.'_image_delete']) ){
			$this->image_delete();
		}
		return apply_filters('em_object_image_upload', false, $this);
	}
	
	function image_validate(){
		$type = $this->get_image_type();
		if( $type ){
			if ( !empty($_FILES[$type.'_image']) && $_FILES[$type.'_image']['size'] > 0 ) { 
				if (is_uploaded_file($_FILES[$type.'_image']['tmp_name'])) {
			  		list($width, $height, $mime_type, $attr) = getimagesize($_FILES[$type.'_image']['tmp_name']);
					$maximum_size = get_option('dbem_image_max_size'); 
					if ($_FILES[$type.'_image']['size'] > $maximum_size){ 
				     	$this->add_error( __('The image file is too big! Maximum size:', 'events-manager')." $maximum_size");
					}
					$maximum_width = get_option('dbem_image_max_width'); 
					$maximum_height = get_option('dbem_image_max_height');
					$minimum_width = get_option('dbem_image_min_width'); 
					$minimum_height = get_option('dbem_image_min_height');  
				  	if (($width > $maximum_width) || ($height > $maximum_height)) { 
						$this->add_error( __('The image is too big! Maximum size allowed:','events-manager')." $maximum_width x $maximum_height");
				  	}
				  	if (($width < $minimum_width) || ($height < $minimum_height)) { 
						$this->add_error( __('The image is too small! Minimum size allowed:','events-manager')." $minimum_width x $minimum_height");
				  	}
				  	if ( empty($mime_type) || !array_key_exists($mime_type, $this->mime_types) ){ 
						$this->add_error(__('The image is in a wrong format!','events-manager'));
				  	}
		  		}
			}
		}
		return apply_filters('em_object_image_validate', count($this->errors) == 0, $this);
	}
	/*
	 * END IMAGE UPlOAD FUNCTIONS
	 */

	function sanitize_time( $time ){
		if( !empty($time) && preg_match ( '/^([01]\d|2[0-3]):([0-5]\d) ?(AM|PM)?$/', $time, $match ) ){
			if( !empty($match[3]) && $match[3] == 'PM' && $match[1] != 12 ){
				$match[1] = 12+$match[1];
			}elseif( !empty($match[3]) && $match[3] == 'AM' && $match[1] == 12 ){
				$match[1] = '00';
			} 
			$time = $match[1].":".$match[2].":00";
			return $time;
		}
		return '00:00:00';
	}

	/**
	 * Formats a price according to settings and currency
	 * @param double $price
	 * @return string
	 */
	function format_price( $price ){
		return em_get_currency_formatted( $price );
	}
	
	function get_tax_rate(){
		return get_option('dbem_bookings_tax');
	}
}