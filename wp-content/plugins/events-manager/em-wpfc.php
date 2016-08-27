<?php
/**
 * Integration with WP FullCalendar - http://wordpress.org/plugins/wp-fullcalendar/
 * Initiallizes EM stuff by overriding some shortcodes, filters and actions in the WP FullCalendar plugin
 * Admin functions are located in admin/wpfc-admin.php
 */
//overrides the ajax calls for event data
if( defined('DOING_AJAX') && DOING_AJAX && !empty($_REQUEST['type']) && $_REQUEST['type'] == EM_POST_TYPE_EVENT ){ //only needed during ajax requests anyway
	remove_action('wp_ajax_WP_FullCalendar', array('WP_FullCalendar','ajax'));
	remove_action('wp_ajax_nopriv_WP_FullCalendar', array('WP_FullCalendar','ajax'));
	add_action('wp_ajax_WP_FullCalendar', 'wpfc_em_ajax');
	add_action('wp_ajax_nopriv_WP_FullCalendar', 'wpfc_em_ajax');
}
//overrides some EM stuff with FullCalendar depending on some extra settings
if ( get_option('dbem_emfc_override_shortcode') ){
	remove_shortcode('events_calendar');
	add_shortcode('events_calendar', array('WP_FullCalendar','calendar'));
}
if( get_option('dbem_emfc_override_calendar') ){
	add_filter ('em_content_pre', 'wpfc_em_content',10,2);
}

/**
 * Replaces the event page with the FullCalendar if requested in settings
 * @param unknown_type $page_content
 * @return Ambigous <mixed, string>
 */
function wpfc_em_content($content = '', $page_content=''){
	global $wpdb, $post;
	if ( em_is_events_page() ){
		$calendar_content = WP_FullCalendar::calendar();
		//Now, we either replace CONTENTS or just replace the whole page
		if( preg_match('/CONTENTS/', $page_content) ){
			$content = str_replace('CONTENTS',$calendar_content,$page_content);
		}else{
			$content = $calendar_content;
		}
	}
	return $content;
}

/**
 * Adds extra non-taxonomy fields to the calendar search
 * @param array $args
 */
function wpfc_em_calendar_search($args){
	if( defined('EM_VERSION') && $args['type'] == 'event' ){
		$country = !empty($args['country']) ? $args['country']:'';
		?>
		<?php if( empty($country) ): ?>
		<!-- START Country Search -->
		<select name="country" class="em-events-search-country wpfc-taxonomy">
			<option value=''><?php echo get_option('dbem_search_form_countries_label'); ?></option>
			<?php
			//get the counties from locations table
			global $wpdb;
			$countries = em_get_countries();
			$em_countries = $wpdb->get_results("SELECT DISTINCT location_country FROM ".EM_LOCATIONS_TABLE." WHERE location_country IS NOT NULL AND location_country != '' ORDER BY location_country ASC", ARRAY_N);
			foreach($em_countries as $em_country):
			?>
			 <option value="<?php echo $em_country[0]; ?>" <?php echo (!empty($country) && $country == $em_country[0]) ? 'selected="selected"':''; ?>><?php echo $countries[$em_country[0]]; ?></option>
			<?php endforeach; ?>
		</select>
		<!-- END Country Search -->
		<?php endif; ?>

		<?php if( !empty($country) ): ?>
		<!-- START Region Search -->
		<select name="region" class="em-events-search-region wpfc-taxonomy">
			<option value=''><?php echo get_option('dbem_search_form_regions_label'); ?></option>
			<?php
			if( !empty($country) ){
				//get the counties from locations table
				global $wpdb;
				$em_states = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT location_region FROM ".EM_LOCATIONS_TABLE." WHERE location_region IS NOT NULL AND location_region != '' AND location_country=%s ORDER BY location_region", $country), ARRAY_N);
				foreach($em_states as $state){
					?>
					 <option <?php echo (!empty($_REQUEST['region']) && $_REQUEST['region'] == $state[0]) ? 'selected="selected"':''; ?>><?php echo $state[0]; ?></option>
					<?php
				}
			}
			?>
		</select>
		<!-- END Region Search -->

		<!-- START State/County Search -->
		<select name="state" class="em-events-search-state wpfc-taxonomy">
			<option value=''><?php echo get_option('dbem_search_form_states_label'); ?></option>
			<?php
			if( !empty($country) ){
				//get the counties from locations table
				global $wpdb;
				$cond = !empty($_REQUEST['region']) ? $wpdb->prepare(" AND location_region=%s ", $_REQUEST['region']):'';
				$em_states = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT location_state FROM ".EM_LOCATIONS_TABLE." WHERE location_state IS NOT NULL AND location_state != '' AND location_country=%s $cond ORDER BY location_state", $country), ARRAY_N);
				foreach($em_states as $state){
					?>
					 <option <?php echo (!empty($_REQUEST['state']) && $_REQUEST['state'] == $state[0]) ? 'selected="selected"':''; ?>><?php echo $state[0]; ?></option>
					<?php
				}
			}
			?>
		</select>
		<!-- END State/County Search -->

		<!-- START City Search -->
		<select name="town" class="em-events-search-town wpfc-taxonomy">
			<option value=''><?php echo get_option('dbem_search_form_towns_label'); ?></option>
			<?php
			if( !empty($country) ){
				//get the counties from locations table
				global $wpdb;
				$cond = !empty($_REQUEST['region']) ? $wpdb->prepare(" AND location_region=%s ", $_REQUEST['region']):'';
				$cond .= !empty($_REQUEST['state']) ? $wpdb->prepare(" AND location_state=%s ", $_REQUEST['state']):'';
				$em_towns = $wpdb->get_results($wpdb->prepare("SELECT DISTINCT location_town FROM ".EM_LOCATIONS_TABLE." WHERE location_town IS NOT NULL AND location_town != '' AND location_country=%s $cond ORDER BY location_town", $country), ARRAY_N);
				foreach($em_towns as $town){
					?>
					 <option <?php echo (!empty($_REQUEST['town']) && $_REQUEST['town'] == $town[0]) ? 'selected="selected"':''; ?>><?php echo $town[0]; ?></option>
					<?php
				}
			}
			?>
		</select>
		<!-- END City Search -->
		<?php endif;
	}
}
//this never worked because the action was never correctly called, until we add a setting for this in the options page, uncomment the line below or paste it in your functions.php file
//add_action('wpfc_calendar_search','wpfc_em_calendar_search', 10, 1);

/**
 * Replaces the normal WPFC ajax and uses the EM query system to provide event specific results. 
 */
function wpfc_em_ajax() {
    $_REQUEST['month'] = false; //no need for these two, they are the original month and year requested
    $_REQUEST['year'] = false;
    
    //get the year and month to show, which would be the month/year between start and end request params
    $month_diff =  $_REQUEST['end'] - $_REQUEST['start'];
    $month_ts = $_REQUEST['start'] + ($month_diff/2); //get a 'mid-month' timestamp to get year and month
    $year = (int) date ( "Y", $month_ts );
    $month = (int) date ( "m", $month_ts );

	$args = array ('month'=>$month, 'year'=>$year, 'owner'=>false, 'status'=>1, 'orderby'=>'event_start_time, event_name'); //since wpfc handles date sorting we only care about time and name ordering here
	$args['number_of_weeks'] = 6; //WPFC always has 6 weeks
	$limit = $args['limit'] = get_option('wpfc_limit',3);
	$args['long_events'] = ( isset($_REQUEST['long_events']) && $_REQUEST['long_events'] == 0 ) ? 0:1; //long events are enabled, unless explicitly told not to in the shortcode
	
	//do some corrections for EM query
	if( isset($_REQUEST[EM_TAXONOMY_CATEGORY]) || empty($_REQUEST['category']) ) $_REQUEST['category'] = !empty($_REQUEST[EM_TAXONOMY_CATEGORY]) ? $_REQUEST[EM_TAXONOMY_CATEGORY]:false;
	$_REQUEST['tag'] = !empty($_REQUEST[EM_TAXONOMY_TAG]) ? $_REQUEST[EM_TAXONOMY_TAG]:false;
	$args = apply_filters('wpfc_fullcalendar_args', array_merge($_REQUEST, $args));
	$calendar_array = EM_Calendar::get($args);
	
	$parentArray = $events = $event_ids = $event_date_counts = $event_dates_more = $event_day_counts = array();

	//get day link template
	global $wp_rewrite;
	if( get_option("dbem_events_page") > 0 ){
		$event_page_link = get_permalink(get_option("dbem_events_page")); //PAGE URI OF EM
		if( $wp_rewrite->using_permalinks() ){ $event_page_link = trailingslashit($event_page_link); } 
	}else{
		if( $wp_rewrite->using_permalinks() ){
			$event_page_link = trailingslashit(home_url()).EM_POST_TYPE_EVENT_SLUG.'/'; //don't use EM_URI here, since ajax calls this before EM_URI is defined.
		}else{
			$event_page_link = home_url().'?post_type='.EM_POST_TYPE_EVENT; //don't use EM_URI here, since ajax calls this before EM_URI is defined.
		}
	}
	if( $wp_rewrite->using_permalinks() && !defined('EM_DISABLE_PERMALINKS') ){
		$event_page_link .= "%s/";
	}else{
		$joiner = (stristr($event_page_link, "?")) ? "&" : "?";
		$event_page_link .= $joiner."calendar_day=%s";
	}
	
	foreach ( $calendar_array['cells'] as $date => $cell_data ) {
		if( empty($event_day_counts[$date]) ) $event_day_counts[$date] = 0;
		/* @var $EM_Event EM_Event */
		$orig_color = get_option('dbem_category_default_color');
		foreach( $cell_data['events'] as $EM_Event ){
			$color = $borderColor = $orig_color;
			$textColor = '#fff';
			if ( !empty ( $EM_Event->get_categories()->categories )) {
				foreach($EM_Event->get_categories()->categories as $EM_Category){
					/* @var $EM_Category EM_Category */
					if( $EM_Category->get_color() != '' ){
						$color = $borderColor = $EM_Category->get_color();
						if( preg_match("/#fff(fff)?/i",$color) ){
							$textColor = '#777';
							$borderColor = '#ccc';
						}
						break;
					}
				}
			}
			if( !in_array($EM_Event->event_id, $event_ids) ){
				//count events for all days this event may span
				if( $EM_Event->event_start_date != $EM_Event->event_end_date ){
					for( $i = $EM_Event->start; $i <= $EM_Event->end; $i = $i + 86400 ){
						$idate = date('Y-m-d',$i);
						empty($event_day_counts[$idate]) ? $event_day_counts[$idate] = 1 : $event_day_counts[$idate]++;
					}
				}else{
					$event_day_counts[$date]++;
				}
				if( $event_day_counts[$date] <= $limit ){
					$title = $EM_Event->output(get_option('dbem_emfc_full_calendar_event_format', '#_EVENTNAME'), 'raw');
					$event_array = array ("title" => $title, "color" => $color, 'textColor'=>$textColor, 'borderColor'=>$borderColor, "start" => date('Y-m-d\TH:i:s', $EM_Event->start), "end" => date('Y-m-d\TH:i:s', $EM_Event->end), "url" => $EM_Event->get_permalink(), 'post_id' => $EM_Event->post_id, 'event_id' => $EM_Event->event_id, 'allDay' => $EM_Event->event_all_day == true );
					if( $args['long_events'] == 0 ) $event_array['end'] = $event_array['start']; //if long events aren't wanted, make the end date same as start so it shows this way on the calendar
					$events[] = apply_filters('wpfc_events_event', $event_array, $EM_Event);
					$event_ids[] = $EM_Event->event_id;
				}
			}
		}
		if( $cell_data['events_count'] > $limit ){
			$event_dates_more[$date] = 1;
			$day_ending = $date."T23:59:59";
			$events[] = apply_filters('wpfc_events_more', array ("title" => get_option('wpfc_limit_txt','more ...'), "color" => get_option('wpfc_limit_color','#fbbe30'), "start" => $day_ending, "url" => str_replace('%s',$date,$event_page_link), 'post_id' => 0, 'event_id' => 0 , 'className' => 'wpfc-more'), $date);
		}
	}
	echo EM_Object::json_encode( apply_filters('wpfc_events', $events) );
	die();
}

/**
 * Overrides the original qtip_content function and provides Event Manager formatted event information
 * @param string $content
 * @return string
 */
function wpfc_em_qtip_content( $content='' ){
	if( !empty($_REQUEST['event_id'] ) && trim(get_option('dbem_emfc_qtips_format')) != '' ){
		global $EM_Event;
		$EM_Event = em_get_event($_REQUEST['event_id']);
		if( !empty($EM_Event->event_id) ){
			$content = $EM_Event->output(get_option('dbem_emfc_qtips_format', '#_EXCERPT'));
		}
	}
	return $content;
}
add_filter('wpfc_qtip_content', 'wpfc_em_qtip_content');

/**
 * Changes the walker object so we can inject color values into the options
 * @param array $args
 * @param object $taxonomy
 * @return WPFC_EM_Categories_Walker
 */
function wpmfc_em_taxonomy_args($args, $taxonomy){
	if( $taxonomy->name == EM_TAXONOMY_CATEGORY ){
		$args['walker'] = new WPFC_EM_Categories_Walker;
	}
	return $args;
}
add_filter('wpmfc_calendar_taxonomy_args', 'wpmfc_em_taxonomy_args',10,2);

/**
 * Copy and alteration of the Walker_CategoryDropdown object
 * @author marcus
 *
 */
class WPFC_EM_Categories_Walker extends Walker {
	var $tree_type = EM_TAXONOMY_CATEGORY;
	var $db_fields = array ('parent' => 'parent', 'id' => 'term_id');

	/**
	 * @see Walker::start_el()
	 */
	function start_el(&$output, $object, $depth = 0, $args = array(), $current_object_id = 0){
		global $wpdb;
		$pad = str_repeat('&nbsp;', $depth * 3);

		$cat_name = apply_filters('list_cats', $object->name, $object);
		$color = $wpdb->get_var('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id='{$object->term_id}' AND meta_key='category-bgcolor' LIMIT 1");
		$color = ($color != '') ? $color:'#a8d144';
		$output .= "<option class=\"level-$depth\" value=\"".$object->term_id."\"";
		if ( $object->term_id == $args['selected'] )
			$output .= ' selected="selected"';
		$output .= '>';
		$output .= $pad.$color.' - '.$cat_name;
		if ( !empty($args['show_count']) )
			$output .= '&nbsp;&nbsp;('. $object->count .')';
		if ( !empty($args['show_last_update']) ) {
			$format = 'Y-m-d';
			if( !empty($object->last_update_timestamp) ){
				$output .= '&nbsp;&nbsp;' . gmdate($format, $object->last_update_timestamp);
			}
		}
		$output .= "</option>";
	}
}

/**
 * Deprecated - changed default taxonomy defenitions to EM taxonomies, not needed as automatically deduced. If you really need this, use
 * add_filter('wpfc_fullcalendar_args', 'wpfc_em_fullcalendar_args'); 
 * @param array $args
 * @return array
 */
function wpfc_em_fullcalendar_args($args){
    if( !empty($args['type']) && $args['type'] == 'event'){
	    if( !empty($args['category']) ){
		    $args[EM_TAXONOMY_CATEGORY] = $args['category'];
	    }
	    if( !empty($args['tag']) ){
		    $args[EM_TAXONOMY_TAG] = $args['tag'];
	    }
    }
    return $args;
}

if( is_admin() ){
	include('admin/settings/wpfc-admin.php');
}