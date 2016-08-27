<?php
/**
 * Get an event in a db friendly way, by checking globals and passed variables to avoid extra class instantiations
 * @param mixed $id
 * @param mixed $search_by
 * @return EM_Event
 */
function em_get_event($id = false, $search_by = 'event_id') {
	global $EM_Event;
	//check if it's not already global so we don't instantiate again
	if( is_object($EM_Event) && get_class($EM_Event) == 'EM_Event' ){
		if( is_object($id) && $EM_Event->post_id == $id->ID ){
			return apply_filters('em_get_event', $EM_Event);
		}elseif( !is_object($id) ){
			if( $search_by == 'event_id' && $EM_Event->event_id == $id ){
				return apply_filters('em_get_event', $EM_Event);
			}elseif( $search_by == 'post_id' && $EM_Event->post_id == $id ){
				return apply_filters('em_get_event', $EM_Event);
			}
		}
	}
	if( is_object($id) && get_class($id) == 'EM_Event' ){
		return apply_filters('em_get_event', $id);
	}else{
		return apply_filters('em_get_event', new EM_Event($id,$search_by));
	}
}
/**
 * Event Object. This holds all the info pertaining to an event, including location and recurrence info.
 * An event object can be one of three "types" a recurring event, recurrence of a recurring event, or a single event.
 * The single event might be part of a set of recurring events, but if loaded by specific event id then any operations and saves are 
 * specifically done on this event. However, if you edit the recurring group, any changes made to single events are overwritten.
 * 
 * @author marcus
 */
//TODO Can add more recurring functionality such as "also update all future recurring events" or "edit all events" like google calendar does.
//TODO Integrate recurrences into events table
//FIXME If you create a super long recurrence timespan, there could be thousands of events... need an upper limit here.
class EM_Event extends EM_Object{
	/* Field Names */
	var $event_id;
	var $post_id;
	var $event_slug;
	var $event_owner;
	var $event_name;
	var $event_start_time;
	var $event_end_time;
	var $event_all_day;
	var $event_start_date;
	var $event_end_date;
	var $post_content;
	var $event_rsvp;
	var $event_rsvp_date;
	var $event_rsvp_time = "00:00:00";
	var $event_rsvp_spaces;
	var $event_spaces;
	var $event_private;
	var $location_id;
	var $recurrence_id;
	var $event_status;
	var $blog_id;
	var $group_id;	
	/**
	 * Populated with the non-hidden event post custom fields (i.e. not starting with _) 
	 * @var array
	 */
	var $event_attributes = array();
	/* Recurring Specific Values */
	var $recurrence;
	var $recurrence_interval;
	var $recurrence_freq;
	var $recurrence_byday;
	var $recurrence_days = 0;
	var $recurrence_byweekno;
	var $recurrence_rsvp_days;
	/* anonymous submission information */
	var $event_owner_anonymous;
	var $event_owner_name;
	var $event_owner_email;
	/**
	 * Previously used to give this object shorter property names for db values (each key has a name) but this is now deprecated, use the db field names as properties. This propertey provides extra info about the db fields.
	 * @var array
	 */
	var $fields = array(
		'event_id' => array( 'name'=>'id', 'type'=>'%d' ),
		'post_id' => array( 'name'=>'post_id', 'type'=>'%d' ),
		'event_slug' => array( 'name'=>'slug', 'type'=>'%s', 'null'=>true ),
		'event_owner' => array( 'name'=>'owner', 'type'=>'%d', 'null'=>true ),
		'event_name' => array( 'name'=>'name', 'type'=>'%s', 'null'=>true ),
		'event_start_time' => array( 'name'=>'start_time', 'type'=>'%s', 'null'=>true ),
		'event_end_time' => array( 'name'=>'end_time', 'type'=>'%s', 'null'=>true ),
		'event_all_day' => array( 'name'=>'all_day', 'type'=>'%d', 'null'=>true ),
		'event_start_date' => array( 'name'=>'start_date', 'type'=>'%s', 'null'=>true ),
		'event_end_date' => array( 'name'=>'end_date', 'type'=>'%s', 'null'=>true ),
		'post_content' => array( 'name'=>'notes', 'type'=>'%s', 'null'=>true ),
		'event_rsvp' => array( 'name'=>'rsvp', 'type'=>'%d', 'null'=>true ), //has a default, so can be null/excluded
		'event_rsvp_date' => array( 'name'=>'rsvp_date', 'type'=>'%s', 'null'=>true ),
		'event_rsvp_time' => array( 'name'=>'rsvp_time', 'type'=>'%s', 'null'=>true ),
		'event_rsvp_spaces' => array( 'name'=>'rsvp_spaces', 'type'=>'%d', 'null'=>true ),
		'event_spaces' => array( 'name'=>'spaces', 'type'=>'%d', 'null'=>true),
		'location_id' => array( 'name'=>'location_id', 'type'=>'%d', 'null'=>true ),
		'recurrence_id' => array( 'name'=>'recurrence_id', 'type'=>'%d', 'null'=>true ),
		'event_status' => array( 'name'=>'status', 'type'=>'%d', 'null'=>true ),
		'event_private' => array( 'name'=>'status', 'type'=>'%d', 'null'=>true ),
		'event_attributes' => array( 'name'=>'attributes', 'type'=>'%s', 'null'=>true ),
		'blog_id' => array( 'name'=>'blog_id', 'type'=>'%d', 'null'=>true ),
		'group_id' => array( 'name'=>'group_id', 'type'=>'%d', 'null'=>true ),
		'recurrence' => array( 'name'=>'recurrence', 'type'=>'%d', 'null'=>true ), //every x day(s)/week(s)/month(s)
		'recurrence_interval' => array( 'name'=>'interval', 'type'=>'%d', 'null'=>true ), //every x day(s)/week(s)/month(s)
		'recurrence_freq' => array( 'name'=>'freq', 'type'=>'%s', 'null'=>true ), //daily,weekly,monthly?
		'recurrence_days' => array( 'name'=>'days', 'type'=>'%d', 'null'=>true ), //daily,weekly,monthly?
		'recurrence_byday' => array( 'name'=>'byday', 'type'=>'%s', 'null'=>true ), //if weekly or monthly, what days of the week?
		'recurrence_byweekno' => array( 'name'=>'byweekno', 'type'=>'%d', 'null'=>true ), //if monthly which week (-1 is last)
		'recurrence_rsvp_days' => array( 'name'=>'recurrence_rsvp_days', 'type'=>'%d', 'null'=>true ), //days before or after start date to generat bookings cut-off date
	);
	var $post_fields = array('event_slug','event_owner','event_name','event_attributes','post_id','post_content'); //fields that won't be taken from the em_events table anymore
	var $recurrence_fields = array('recurrence_interval', 'recurrence_freq', 'recurrence_days', 'recurrence_byday', 'recurrence_byweekno');
	
	var $image_url = '';
	/**
	 * Timestamp of start date/time
	 * @var int
	 */
	var $start;
	/**
	 * Timestamp of end date/time
	 * @var int
	 */
	var $end;
	/**
	 * Timestamp for booking cut-off date/time
	 * @var int
	 */
	var $rsvp_end;
	/**
	 * Created on timestamp, taken from DB, converted to TS
	 * @var int
	 */
	var $created;
	/**
	 * Created on timestamp, taken from DB, converted to TS
	 * @var int
	 */
	var $modified;
	
	/**
	 * @var EM_Location
	 */
	var $location;
	/**
	 * @var EM_Bookings
	 */
	var $bookings;
	/**
	 * The contact person for this event
	 * @var WP_User
	 */
	var $contact;
	/**
	 * The category object
	 * @var EM_Category
	 */
	var $category;
	/**
	 * If there are any errors, they will be added here.
	 * @var array
	 */
	var $errors = array();	
	/**
	 * If something was successful, a feedback message might be supplied here.
	 * @var string
	 */
	var $feedback_message;
	/**
	 * Any warnings about an event (e.g. bad data, recurrence, etc.)
	 * @var string
	 */
	var $warnings;
	/**
	 * Array of dbem_event field names required to create an event 
	 * @var array
	 */
	var $required_fields = array('event_name', 'event_start_date');
	var $mime_types = array(1 => 'gif', 2 => 'jpg', 3 => 'png'); 
	/**
	 * previous status of event when instantiated
	 * @access protected
	 * @var mixed
	 */
	var $previous_status = false;
	
	/* Post Variables - copied out of post object for easy IDE reference */
	var $ID;
	var $post_author;
	var $post_date;
	var $post_date_gmt;
	var $post_title;
	var $post_excerpt;
	var $post_status;
	var $comment_status;
	var $ping_status;
	var $post_password;
	var $post_name;
	var $to_ping;
	var $pinged;
	var $post_modified;
	var $post_modified_gmt;
	var $post_content_filtered;
	var $post_parent;
	var $guid;
	var $menu_order;
	var $post_type;
	var $post_mime_type;
	var $comment_count;
	var $ancestors;
	var $filter;
	
	/**
	 * Initialize an event. You can provide event data in an associative array (using database table field names), an id number, or false (default) to create empty event.
	 * @param mixed $event_data
	 * @param mixed $search_by default is post_id, otherwise it can be by event_id as well.
	 * @return null
	 */
	function __construct($id = false, $search_by = 'event_id') {
		global $wpdb;
		if( is_array($id) ){
			//deal with the old array style, but we can't supply arrays anymore
			$id = (!empty($id['event_id'])) ? $id['event_id'] : $id['post_id'];
			$search_by = (!empty($id['event_id'])) ? 'event_id':'post_id';
		}
		$is_post = !empty($id->ID) && ($id->post_type == EM_POST_TYPE_EVENT || $id->post_type == 'event-recurring');
		if( is_numeric($id) || $is_post ){ //only load info if $id is a number
			if($search_by == 'event_id' && !$is_post ){
				//search by event_id, get post_id and blog_id (if in ms mode) and load the post
				$results = $wpdb->get_row($wpdb->prepare("SELECT post_id, blog_id FROM ".EM_EVENTS_TABLE." WHERE event_id=%d",$id), ARRAY_A);
				if( !empty($results['post_id']) ){ $this->post_id = $results['post_id']; }
				if( is_multisite() && (is_numeric($results['blog_id']) || $results['blog_id']=='' ) ){
				    if( $results['blog_id']=='' )  $results['blog_id'] = get_current_site()->blog_id;
					$event_post = get_blog_post($results['blog_id'], $results['post_id']);
					$search_by = $this->blog_id = $results['blog_id'];
				}else{
					$event_post = get_post($results['post_id']);	
				}
			}else{
				if(!$is_post){
					if( is_multisite() && (is_numeric($search_by) || $search_by == '') ){
					    if( $search_by == '' ) $search_by = get_current_site()->blog_id;
						//we've been given a blog_id, so we're searching for a post id
						$event_post = get_blog_post($search_by, $id);
						$this->blog_id = $search_by;
					}else{
						//search for the post id only
						$event_post = get_post($id);
					}
				}else{
					$event_post = $id;
				}
				$this->post_id = !empty($id->ID) ? $id->ID : $id;
			}
			$this->load_postdata($event_post, $search_by);
		}
		$this->recurrence = $this->is_recurring() ? 1:0;
		//if(defined('trashtest')){ print_r($this); die("got here");}
		//Do it here so things appear in the po file.
		$this->status_array = array(
			0 => __('Pending','events-manager'),
			1 => __('Approved','events-manager')
		);
		do_action('em_event', $this, $id, $search_by);
	}
	
	function load_postdata($event_post, $search_by = false){
		if( is_object($event_post) ){
			//load post data - regardless
			$this->post_id = $event_post->ID;
			$this->event_name = $event_post->post_title;
			$this->event_owner = $event_post->post_author;
			$this->post_content = $event_post->post_content;
			$this->post_excerpt = $event_post->post_excerpt;
			$this->event_slug = $event_post->post_name;
			foreach( $event_post as $key => $value ){ //merge post object into this object
				$this->$key = $value;
			}
			$this->recurrence = $this->is_recurring() ? 1:0;
			//load meta data and other related information
			if( $event_post->post_status != 'auto-draft' ){
			    $event_meta = $this->get_event_meta($search_by);
				//Get custom fields and post meta
				foreach($event_meta as $event_meta_key => $event_meta_val){
					$field_name = substr($event_meta_key, 1);
					if($event_meta_key[0] != '_'){
						$this->event_attributes[$event_meta_key] = ( count($event_meta_val) > 1 ) ? $event_meta_val:$event_meta_val[0];					
					}elseif( is_string($field_name) && !in_array($field_name, $this->post_fields) ){
						if( array_key_exists($field_name, $this->fields) ){
							$this->$field_name = $event_meta_val[0];
						}elseif( in_array($field_name, array('event_owner_name','event_owner_anonymous','event_owner_email')) ){
							$this->$field_name = $event_meta_val[0];
						}
					}
				}
				//Start/End times should be available as timestamp
				$this->start = strtotime($this->event_start_date." ".$this->event_start_time, current_time('timestamp'));
				$this->end = strtotime($this->event_end_date." ".$this->event_end_time, current_time('timestamp'));
				if( !empty($this->event_rsvp_date ) ){
				    $this->rsvp_end = strtotime($this->event_rsvp_date." ".$this->event_rsvp_time, current_time('timestamp')); 
				}
				//quick compatability fix in case _event_id isn't loaded or somehow got erased in post meta
				if( empty($this->event_id) && !$this->is_recurring() ){
					global $wpdb;
					if( EM_MS_GLOBAL ){
						$event_array = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".EM_EVENTS_TABLE." WHERE post_id=%d && blog_id=%d",$this->post_id, $this->blog_id), ARRAY_A);
					}else{
						$event_array = $wpdb->get_row('SELECT * FROM '.EM_EVENTS_TABLE. ' WHERE post_id='.$this->post_id, ARRAY_A);	
					}
					if( !empty($event_array['event_id']) ){
						foreach($event_array as $key => $value){
							if( !empty($value) && empty($this->$key) ){
								update_post_meta($event_post->ID, '_'.$key, $value);
								$this->$key = $value;
							}
						}
					}
				}
			}
			$this->get_status();
			$this->compat_keys();
		}elseif( !empty($this->post_id) ){
			//we have an orphan... show it, so that we can at least remove it on the front-end
			global $wpdb;
			if( EM_MS_GLOBAL ){ //if MS Global mode enabled, make sure we search by blog too so there's no cross-post confusion
				$event_array = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".EM_EVENTS_TABLE." WHERE post_id=%d && blog_id=%d",$this->post_id, $this->blog_id), ARRAY_A);
			}else{
				$event_array = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".EM_EVENTS_TABLE." WHERE post_id=%d",$this->post_id), ARRAY_A);
			}
		    if( is_array($event_array) ){
				$this->orphaned_event = true;
				$this->post_id = $this->ID = $event_array['post_id'] = null; //reset post_id because it doesn't really exist
				$this->to_object($event_array);
		    }
			//Start/End times should be available as timestamp
			$this->start = strtotime($this->event_start_date." ".$this->event_start_time, current_time('timestamp'));
			$this->end = strtotime($this->event_end_date." ".$this->event_end_time, current_time('timestamp'));
		}
	}
	
	function get_event_meta($blog_id = false){
		if( is_numeric($blog_id) && $blog_id > 0 && is_multisite() ){
			// if in multisite mode, switch blogs quickly to get the right post meta.
			switch_to_blog($blog_id);
			$event_meta = get_post_meta($this->post_id);
			restore_current_blog();
			$this->blog_id = $blog_id;
		}else{
			$event_meta = get_post_meta($this->post_id);
		}
		return $event_meta;
	}
	
	/**
	 * Retrieve event information via POST (only used in situations where posts aren't submitted via WP)
	 * @return boolean
	 */
	function get_post($validate = true){	
		global $allowedposttags;
		do_action('em_event_get_post_pre', $this);
		//we need to get the post/event name and content.... that's it.
		$this->post_content = isset($_POST['content']) ? wp_kses( stripslashes($_POST['content']), $allowedposttags):'';
		$this->post_excerpt = !empty($this->post_excerpt) ? $this->post_excerpt:''; //fix null error
		$this->event_name = !empty($_POST['event_name']) ? htmlspecialchars_decode(wp_kses_data(htmlspecialchars_decode(stripslashes($_POST['event_name'])))):'';
		$this->post_type = ($this->is_recurring() || !empty($_POST['recurring'])) ? 'event-recurring':EM_POST_TYPE_EVENT;
		//don't forget categories!
		$this->get_categories()->get_post();
		//anonymous submissions and guest basic info
		if( !is_user_logged_in() && get_option('dbem_events_anonymous_submissions') && empty($this->event_id) ){
			$this->event_owner_anonymous = 1;
			$this->event_owner_name = !empty($_POST['event_owner_name']) ? wp_kses_data(stripslashes($_POST['event_owner_name'])):'';
			$this->event_owner_email = !empty($_POST['event_owner_email']) ? wp_kses_data($_POST['event_owner_email']):'';
		}
		//get the rest and validate (optional)
		$this->get_post_meta(false);
		$result = $validate ? $this->validate():true; //validate both post and meta, otherwise return true
		return apply_filters('em_event_get_post', $result, $this);		
	}
	
	/**
	 * Retrieve event post meta information via POST, which should be always be called when saving the event custom post via WP.
	 * @return boolean
	 */
	function get_post_meta(){
		do_action('em_event_get_post_meta_pre', $this);
		//Grab POST data	
		$this->event_start_date = ( !empty($_POST['event_start_date']) ) ? wp_kses_data($_POST['event_start_date']) : '';
		$this->event_end_date = ( !empty($_POST['event_end_date']) ) ? wp_kses_data($_POST['event_end_date']) : $this->event_start_date;
		//check if this is recurring or not
		if( !empty($_POST['recurring']) ){
			$this->recurrence = 1;
			$this->post_type = 'event-recurring';
		}
		//Get Location info
		if( !get_option('dbem_locations_enabled') || (!empty($_POST['no_location']) && !get_option('dbem_require_location',true)) || (empty($_POST['location_id']) && !get_option('dbem_require_location',true) && get_option('dbem_use_select_for_locations')) ){
			$this->location_id = 0;
		}elseif( !empty($_POST['location_id']) && is_numeric($_POST['location_id']) ){
			$this->location_id = $_POST['location_id'];	
		}else{
			//we're adding a new location, so create an empty location and populate
			$this->location_id = null;
			$this->get_location()->get_post(false);
			$this->get_location()->post_content = ''; //reset post content, as it'll grab the event description otherwise
		}
		//Sort out time
		$this->event_all_day = ( !empty($_POST['event_all_day']) ) ? 1 : 0;
		if( !$this->event_all_day ){
			$this->event_start_time = $this->event_end_time = '00:00:00';
		}
		foreach( array('event_start_time','event_end_time', 'event_rsvp_time') as $timeName ){
			$match = array();
			if( !empty($_POST[$timeName]) && preg_match ( '/^([01]\d|[0-9]|2[0-3])(:([0-5]\d))? ?(AM|PM)?$/', $_POST[$timeName], $match ) ){
				if( empty($match[3]) ) $match[3] = '00';
				if( strlen($match[1]) == 1 ) $match[1] = '0'.$match[1];
			    if( !empty($match[4]) && $match[4] == 'PM' && $match[1] != 12 ){
					$match[1] = 12+$match[1];
				}elseif( !empty($match[4]) && $match[4] == 'AM' && $match[1] == 12 ){
					$match[1] = '00';
				}
				$this->$timeName = $match[1].":".$match[3].":00";
			}else{
				$this->$timeName = ($timeName == 'event_start_time') ? "00:00:00":$this->event_start_time;
			}
		}
		//Start/End times should be available as timestamp
		$this->start = strtotime($this->event_start_date." ".$this->event_start_time);
		$this->end = strtotime($this->event_end_date." ".$this->event_end_time);
		//Bookings
		$can_manage_bookings = $this->can_manage('manage_bookings','manage_others_bookings');
		if( $can_manage_bookings && !empty($_POST['event_rsvp']) && $_POST['event_rsvp'] ){
			$this->get_bookings()->get_tickets()->get_post();
			$this->event_rsvp = 1;
			//RSVP cuttoff TIME is set up above where start/end times are as well 
			if( !$this->is_recurring() ){
				if( get_option('dbem_bookings_tickets_single') && count($this->get_tickets()->tickets) == 1 ){
					//single ticket mode will use the ticket end date/time as cut-off date/time
			    	$EM_Ticket = $this->get_tickets()->get_first();
			    	$this->event_rsvp_date = '';
			    	if( !empty($EM_Ticket->end_timestamp) ){
			    		$this->event_rsvp_date = date('Y-m-d', $EM_Ticket->end_timestamp);
			    		$this->event_rsvp_time = date('H:i:00', $EM_Ticket->end_timestamp);
			    	}else{
			    		//no default ticket end time, so make it default to event start date/time
			    		$this->event_rsvp_date = $this->event_start_date;
			    		$this->event_rsvp_time = $this->event_start_time;
			    		if( $this->event_all_day && empty($_POST['event_rsvp_date']) ){ $this->event_rsvp_time = '00:00:00'; } //all-day events start at 0 hour
			    	}
			    }else{
			    	//if no rsvp cut-off date supplied, make it the event start date
			    	$this->event_rsvp_date = ( !empty($_POST['event_rsvp_date']) ) ? wp_kses_data($_POST['event_rsvp_date']) : $this->event_start_date;
			    	if ( empty($_POST['event_rsvp_date']) ) $this->event_rsvp_time = $this->event_start_time;
			    	if( $this->event_all_day && empty($_POST['event_rsvp_date']) ){ $this->event_rsvp_time = '00:00:00'; } //all-day events start at 0 hour
			    }
			    //create timestamp
				if( empty($this->event_rsvp_date) ){ 
					//falback in case nothing gets set for rsvp cut-off
					$this->event_rsvp_time = '00:00:00';
					$this->rsvp_end = 0; //empty value but timestamp compatible 
				}else{
					$this->rsvp_end = strtotime($this->event_rsvp_date." ".$this->event_rsvp_time, current_time('timestamp'));
				}
			}else{
				//recurring events may have a cut-off date x days before or after the recurrence start dates
				$this->recurrence_rsvp_days = null;
				if( get_option('dbem_bookings_tickets_single') && count($this->get_tickets()->tickets) == 1 ){
					//if in single ticket mode then ticket cut-off date determines event cut-off date
					$EM_Ticket = $this->get_tickets()->get_first();
					if( !empty($EM_Ticket->ticket_meta['recurrences']) ){
						$this->recurrence_rsvp_days = $EM_Ticket->ticket_meta['recurrences']['end_days'];
						$this->event_rsvp_time = $EM_Ticket->ticket_meta['recurrences']['end_time'];
					}
				}else{
					if( array_key_exists('recurrence_rsvp_days', $_POST) ){
						if( !empty($_POST['recurrence_rsvp_days_when']) && $_POST['recurrence_rsvp_days_when'] == 'after' ){
							$this->recurrence_rsvp_days = absint($_POST['recurrence_rsvp_days']);
						}else{ //by default the start date is the point of reference
							$this->recurrence_rsvp_days = absint($_POST['recurrence_rsvp_days']) * -1;
						}
					}
				}
				//create timestamps and set rsvp date/time for a normal event
				if( !is_numeric($this->recurrence_rsvp_days) ){ 
					//falback in case nothing gets set for rsvp cut-off
					$this->event_rsvp_date = '';
					$this->event_rsvp_time = '00:00:00';
					$this->rsvp_end = 0; //empty value but timestamp compatible
				}else{
					$this->event_rsvp_date = date('Y-m-d', strtotime($this->recurrence_rsvp_days.' days', $this->start));
					$this->rsvp_end = strtotime($this->event_rsvp_date." ".$this->event_rsvp_time, current_time('timestamp'));
				}
			}
			$this->event_spaces = ( isset($_POST['event_spaces']) ) ? absint($_POST['event_spaces']):0;
			$this->event_rsvp_spaces = ( isset($_POST['event_rsvp_spaces']) ) ? absint($_POST['event_rsvp_spaces']):0;
		}elseif( $can_manage_bookings || !$this->event_rsvp ){
			$this->event_rsvp = 0;
			$this->event_rsvp_time = '00:00:00';
		}
		//Sort out event attributes - note that custom post meta now also gets inserted here automatically (and is overwritten by these attributes)
		if(get_option('dbem_attributes_enabled')){
			global $allowedtags;
			if( !is_array($this->event_attributes) ){ $this->event_attributes = array(); }
			$event_available_attributes = em_get_attributes();
			if( !empty($_POST['em_attributes']) && is_array($_POST['em_attributes']) ){
				foreach($_POST['em_attributes'] as $att_key => $att_value ){
					if( (in_array($att_key, $event_available_attributes['names']) || array_key_exists($att_key, $this->event_attributes) ) ){
						$this->event_attributes[$att_key] = '';
						$att_vals = count($event_available_attributes['values'][$att_key]);
						if( !empty($att_value) ){
							if( $att_vals <= 1 || ($att_vals > 1 && in_array($att_value, $event_available_attributes['values'][$att_key])) ){
								$this->event_attributes[$att_key] = stripslashes($att_value);
							}
						}
						if( empty($att_value) && $att_vals > 1){
							$this->event_attributes[$att_key] = stripslashes(wp_kses($event_available_attributes['values'][$att_key][0], $allowedtags));
						}
					}
				}
			}
		}
		//Set Blog ID
		if( is_multisite() ){
			$this->blog_id = get_current_blog_id();
		}
		//group id
		$this->group_id = (!empty($_POST['group_id']) && is_numeric($_POST['group_id'])) ? $_POST['group_id']:0;
		//Recurrence data
		if( $this->is_recurring() ){
			$this->recurrence = 1; //just in case
			$this->recurrence_freq = ( !empty($_POST['recurrence_freq']) && in_array($_POST['recurrence_freq'], array('daily','weekly','monthly','yearly')) ) ? $_POST['recurrence_freq']:'daily';
			if( !empty($_POST['recurrence_bydays']) && $this->recurrence_freq == 'weekly' && self::array_is_numeric($_POST['recurrence_bydays']) ){
				$this->recurrence_byday = str_replace(' ', '', implode( ",", $_POST['recurrence_bydays'] ));
			}elseif( !empty($_POST['recurrence_byday']) && $this->recurrence_freq == 'monthly' ){
				$this->recurrence_byday = wp_kses_data($_POST['recurrence_byday']);
			}
			$this->recurrence_interval = ( !empty($_POST['recurrence_interval']) && is_numeric($_POST['recurrence_interval']) ) ? $_POST['recurrence_interval']:1;
			$this->recurrence_byweekno = ( !empty($_POST['recurrence_byweekno']) ) ? wp_kses_data($_POST['recurrence_byweekno']):'';
			$this->recurrence_days = ( !empty($_POST['recurrence_days']) && is_numeric($_POST['recurrence_days']) ) ? (int) $_POST['recurrence_days']:0;
		}
		//categories in MS GLobal
		if(EM_MS_GLOBAL && !is_main_site()){
			$this->get_categories()->get_post(); //it'll know what to do
		}
		//validate (optional) and return result
		$this->compat_keys(); //compatability
		return apply_filters('em_event_get_post_meta', count($this->errors) == 0, $this);
	}
	
	function validate(){
		$validate_post = true;
		if( empty($this->event_name) ){
			$validate_post = false; 
			$this->add_error( sprintf(__("%s is required.", 'events-manager'), __('Event name','events-manager')) );
		}
		//anonymous submissions and guest basic info
		if( !empty($this->event_owner_anonymous) ){
			if( !is_email($this->event_owner_email) ){
				$this->add_error( sprintf(__("%s is required.", 'events-manager'), __('A valid email','events-manager')) );
			}
			if( empty($this->event_owner_name) ){
				$this->add_error( sprintf(__("%s is required.", 'events-manager'), __('Your name','events-manager')) );
			}
		}
		$validate_tickets = true; //must pass if we can't validate bookings
		if( $this->can_manage('manage_bookings','manage_others_bookings') ){
		    $validate_tickets = $this->get_bookings()->get_tickets()->validate();
		}
		$validate_image = $this->image_validate();
		$validate_meta = $this->validate_meta();
		return apply_filters('em_event_validate', $validate_post && $validate_image && $validate_meta && $validate_tickets, $this );		
	}
	
	function validate_meta(){
		$missing_fields = Array ();
		foreach ( array('event_start_date') as $field ) {
			if ( $this->$field == "") {
				$missing_fields[$field] = $field;
			}
		}
		if( preg_match('/\d{4}-\d{2}-\d{2}/', $this->event_start_date) && preg_match('/\d{4}-\d{2}-\d{2}/', $this->event_end_date) ){
			if( strtotime($this->event_start_date . $this->event_start_time) > strtotime($this->event_end_date . $this->event_end_time) ){
				$this->add_error(__('Events cannot start after they end.','events-manager'));
			}elseif( $this->is_recurring() && $this->recurrence_days == 0 && strtotime($this->event_start_date . $this->event_start_time) > strtotime($this->event_start_date . $this->event_end_time) ){
				$this->add_error(__('Events cannot start after they end.','events-manager').' '.__('For recurring events that end the following day, ensure you make your event last 1 or more days.'));
			}
		}else{
			if( !empty($missing_fields['event_start_date']) ) { unset($missing_fields['event_start_date']); }
			if( !empty($missing_fields['event_end_date']) ) { unset($missing_fields['event_end_date']); }
			$this->add_error(__('Dates must have correct formatting. Please use the date picker provided.','events-manager'));
		}
		if( $this->event_rsvp ){
		    if( !$this->get_bookings()->get_tickets()->validate() ){
		        $this->add_error($this->get_bookings()->get_tickets()->get_errors());
		    }
		    if( !empty($this->event_rsvp_date) && !preg_match('/\d{4}-\d{2}-\d{2}/', $this->event_rsvp_date) ){
				$this->add_error(__('Dates must have correct formatting. Please use the date picker provided.','events-manager'));
		    }
		}
		if( get_option('dbem_locations_enabled') && empty($this->location_id) ){ //location ids don't need validating as we're not saving a location
			if( get_option('dbem_require_location',true) || $this->location_id !== 0 ){
				if( !$this->get_location()->validate() ){
					$this->add_error($this->get_location()->get_errors());
				}
			}
		}
		if ( count($missing_fields) > 0){
			// TODO Create friendly equivelant names for missing fields notice in validation
			$this->add_error( __( 'Missing fields: ', 'events-manager') . implode ( ", ", $missing_fields ) . ". " );
		}
		if ( $this->is_recurring() ){
		    if( $this->event_end_date == "" || $this->event_end_date == $this->event_start_date){
		        $this->add_error( __( 'Since the event is repeated, you must specify an event end date greater than the start date.', 'events-manager'));
		    }
		    if( $this->recurrence_freq == 'weekly' && !preg_match('/^[0-9](,[0-9])*$/',$this->recurrence_byday) ){
		        $this->add_error( __( 'Please specify what days of the week this event should occur on.', 'events-manager'));
		    }
		}
		return apply_filters('em_event_validate_meta', count($this->errors) == 0, $this );
	}
	
	/**
	 * Will save the current instance into the database, along with location information if a new one was created and return true if successful, false if not.
	 * Will automatically detect whether it's a new or existing event. 
	 * @return boolean
	 */
	function save(){
		global $wpdb, $current_user, $blog_id, $EM_SAVING_EVENT;
		$EM_SAVING_EVENT = true; //this flag prevents our dashboard save_post hooks from going further
		if( !$this->can_manage('edit_events', 'edit_others_events') && !( get_option('dbem_events_anonymous_submissions') && empty($this->event_id)) ){
			//unless events can be submitted by an anonymous user (and this is a new event), user must have permissions.
			return apply_filters('em_event_save', false, $this);
		}
		do_action('em_event_save_pre', $this);
		$post_array = array();
		//Deal with updates to an event
		if( !empty($this->post_id) ){
			//get the full array of post data so we don't overwrite anything.
			if( !empty($this->blog_id) && is_multisite() ){
				$post_array = (array) get_blog_post($this->blog_id, $this->post_id);
			}else{
				$post_array = (array) get_post($this->post_id);
			}
		}
		//Overwrite new post info
		$post_array['post_type'] = ($this->recurrence && get_option('dbem_recurrence_enabled')) ? 'event-recurring':EM_POST_TYPE_EVENT;
		$post_array['post_title'] = $this->event_name;
		$post_array['post_content'] = $this->post_content;
		$post_array['post_excerpt'] = $this->post_excerpt;
		//decide on post status
		if( empty($this->force_status) ){
			if( count($this->errors) == 0 ){
				$post_array['post_status'] = ( $this->can_manage('publish_events','publish_events') ) ? 'publish':'pending';
			}else{
				$post_array['post_status'] = 'draft';
			}
		}else{
		    $post_array['post_status'] = $this->force_status;
		}
		//anonymous submission only
		if( !is_user_logged_in() && get_option('dbem_events_anonymous_submissions') && empty($this->event_id) ){
			$post_array['post_author'] = get_option('dbem_events_anonymous_user');
			if( !is_numeric($post_array['post_author']) ) $post_array['post_author'] = 0;
		}
		//Save post and continue with meta
		$post_id = wp_insert_post($post_array);
		$post_save = false;
		$meta_save = false;
		if( !is_wp_error($post_id) && !empty($post_id) ){
			$post_save = true;
			//refresh this event with wp post info we'll put into the db
			$post_data = get_post($post_id);
			$this->post_id = $post_id;
			$this->event_slug = $post_data->post_name;
			$this->event_owner = $post_data->post_author;
			$this->post_status = $post_data->post_status;
			$this->get_status();
			//Categories? note that categories will soft-fail, so no errors
			$this->get_categories()->event_id = $this->event_id;
			$this->categories->post_id = $this->post_id;
			$this->categories->save();
			//anonymous submissions should save this information
			if( !empty($this->event_owner_anonymous) ){
				update_post_meta($this->post_id, '_event_owner_anonymous', 1);
				update_post_meta($this->post_id, '_event_owner_name', $this->event_owner_name);
				update_post_meta($this->post_id, '_event_owner_email', $this->event_owner_email);
			}
			//save the image, errors here will surface during $this->save_meta()
			$this->image_upload();
			//now save the meta
			$meta_save = $this->save_meta();
		}
		$result = $meta_save && $post_save;
		if($result) $this->load_postdata($post_data, $blog_id); //reload post info
		//do a dirty update for location too if it's not published
		if( $this->is_published() && !empty($this->location_id) ){
			$EM_Location = $this->get_location();
			if( $EM_Location->location_status !== 1 ){
				//let's also publish the location
				$EM_Location->set_status(1, true);
			}
		}
		$return = apply_filters('em_event_save', $result, $this);
		$EM_SAVING_EVENT = false;
		return $return;
	}
	
	function save_meta(){
		global $wpdb;
		if( ( get_option('dbem_events_anonymous_submissions') && empty($this->event_id)) || $this->can_manage('edit_events', 'edit_others_events') ){
			do_action('em_event_save_meta_pre', $this);
			//first save location
			if( empty($this->location_id) && !($this->location_id === 0 && !get_option('dbem_require_location',true)) ){
			    //proceed with location save
				if( !$this->get_location()->save() ){ //soft fail
					global $EM_Notices;
					if( !empty($this->get_location()->location_id) ){
						$EM_Notices->add_error( __('There were some errors saving your location.','events-manager').' '.sprintf(__('It will not be displayed on the website listings, to correct this you must <a href="%s">edit your location</a> directly.'),$this->get_location()->output('#_LOCATIONEDITURL')), true);
					}
				}
				if( !empty($this->location->location_id) ){ //only case we don't use get_location(), since it will fail as location has an id, whereas location_id isn't set in this object
					$this->location_id = $this->location->location_id;
				}
			}
			//Update Post Meta
			foreach($this->fields as $key => $field_info){
				if( !in_array($key, $this->post_fields) && $key != 'event_attributes' ){
					update_post_meta($this->post_id, '_'.$key, $this->$key);
				}elseif($key == 'event_attributes'){
					//attributes get saved as individual keys
					$this->event_attributes = maybe_unserialize($this->event_attributes);
					foreach($this->event_attributes as $event_attribute_key => $event_attribute){
						if( !empty($event_attribute) ){
							update_post_meta($this->post_id, $event_attribute_key, $event_attribute);
						}else{
							delete_post_meta($this->post_id, $event_attribute_key);
						}
					}
				}
			}
			//update timestampes
			update_post_meta($this->post_id, '_start_ts', str_pad($this->start, 10, 0, STR_PAD_LEFT));
			update_post_meta($this->post_id, '_end_ts', str_pad($this->end, 10, 0, STR_PAD_LEFT));
			//sort out event status			
			$result = count($this->errors) == 0;
			$this->get_status();
			$this->event_status = ($result) ? $this->event_status:null; //set status at this point, it's either the current status, or if validation fails, null
			//Save to em_event table
			$event_array = $this->to_array(true);
			unset($event_array['event_id']);
			//decide whether or not event is private at this point
			$event_array['event_private'] = ( $this->post_status == 'private' ) ? 1:0;
			//save event_attributes just in case
			$event_array['event_attributes'] = serialize($this->event_attributes);
			//check if event truly exists, meaning the event_id is actually a valid event id
			if( !empty($this->event_id) ){
				$blog_condition = '';
				if( !empty($this->orphaned_event ) && !empty($this->post_id) ){
				    //we're dealing with an orphaned event in wp_em_events table, so we want to update the post_id and give it a post parent 
				    $event_truly_exists = true;
				}else{
					if( EM_MS_GLOBAL ){
					    if( is_main_site() ){
					        $blog_condition = " AND (blog_id='".get_current_blog_id()."' OR blog_id IS NULL)";
					    }else{
							$blog_condition = " AND blog_id='".get_current_blog_id()."' ";
					    }
					}
					$event_truly_exists = $wpdb->get_var('SELECT post_id FROM '.EM_EVENTS_TABLE." WHERE event_id={$this->event_id}".$blog_condition) == $this->post_id;
				}
			}else{
				$event_truly_exists = false;
			}
			//save all the meta
			if( empty($this->event_id) || !$event_truly_exists ){
				$this->previous_status = 0; //for sure this was previously status 0
				$this->event_date_created = $event_array['event_date_created'] = current_time('mysql');
				if ( !$wpdb->insert(EM_EVENTS_TABLE, $event_array) ){
					$this->add_error( sprintf(__('Something went wrong saving your %s to the index table. Please inform a site administrator about this.','events-manager'),__('event','events-manager')));
				}else{
					//success, so link the event with the post via an event id meta value for easy retrieval
					$this->event_id = $wpdb->insert_id;
					update_post_meta($this->post_id, '_event_id', $this->event_id);
					$this->feedback_message = sprintf(__('Successfully saved %s','events-manager'),__('Event','events-manager'));
					$just_added_event = true; //make an easy hook
					do_action('em_event_save_new', $this);
				}
			}else{
			    $event_array['post_content'] = $this->post_content; //in case the content was removed, which is acceptable
			    $this->get_previous_status();
				$this->event_date_modified = $event_array['event_date_modified'] = current_time('mysql');
				if ( $wpdb->update(EM_EVENTS_TABLE, $event_array, array('event_id'=>$this->event_id) ) === false ){
					$this->add_error( sprintf(__('Something went wrong updating your %s to the index table. Please inform a site administrator about this.','events-manager'),__('event','events-manager')));			
				}else{
					//Also set the status here if status != previous status
					if( $this->previous_status != $this->get_status() ) $this->set_status($this->get_status());
					$this->feedback_message = sprintf(__('Successfully saved %s','events-manager'),__('Event','events-manager'));
				}
				//check anonymous submission information
    			if( !empty($this->event_owner_anonymous) && get_option('dbem_events_anonymous_user') != $this->event_owner ){
    			    //anonymous user owner has been replaced with a valid wp user account, so we remove anonymous status flag but leave email and name for future reference
    			    update_post_meta($this->post_id, '_event_owner_anonymous', 0);
    			}elseif( get_option('dbem_events_anonymous_submissions') && get_option('dbem_events_anonymous_user') == $this->event_owner && is_email($this->event_owner_email) && !empty($this->event_owner_name) ){
    			    //anonymous user account has been reinstated as the owner, so we can restore anonymous submission status
    			    update_post_meta($this->post_id, '_event_owner_anonymous', 1);
    			}
			}
			//Add/Delete Tickets
			if($this->event_rsvp == 0){
				$this->get_bookings()->delete();
			}elseif( $this->can_manage('manage_bookings','manage_others_bookings') ){
				if( !$this->get_bookings()->get_tickets()->save() ){
					$this->add_error( $this->get_bookings()->get_tickets()->get_errors() );
				}
			}
			$result = count($this->errors) == 0;
			//If we're saving event categories in MS Global mode, we'll add them here, saving by term id (cat ids are gone now)
			if( EM_MS_GLOBAL && !is_main_site() ){
				$this->get_categories()->save(); //it'll know what to do
			}elseif( EM_MS_GLOBAL ){
				$this->get_categories()->save_index(); //just save to index, we assume cats are saved in $this->save();
			}
		    $this->compat_keys(); //compatability keys, loaded before saving recurrences
			//build recurrences if needed
			if( $this->is_recurring() && $result && $this->is_published() ){ //only save events if recurring event validates and is published
				global $EM_EVENT_SAVE_POST;
				//If we're in WP Admin and this was called by EM_Event_Post_Admin::save_post, don't save here, it'll be done later in EM_Event_Recurring_Post_Admin::save_post
				if( empty($EM_EVENT_SAVE_POST) ){
				 	if( !$this->save_events() ){
						$this->add_error(__ ( 'Something went wrong with the recurrence update...', 'events-manager'). __ ( 'There was a problem saving the recurring events.', 'events-manager'));
				 	}
				}
			}
			if( !empty($just_added_event) ){
				do_action('em_event_added', $this);
			}
		}
		return apply_filters('em_event_save_meta', count($this->errors) == 0, $this);
	}
	
	/**
	 * Duplicates this event and returns the duplicated event. Will return false if there is a problem with duplication.
	 * @return EM_Event
	 */
	function duplicate(){
		global $wpdb, $EZSQL_ERROR;
		//First, duplicate.
		if( $this->can_manage('edit_events','edit_others_events') ){
			$EM_Event = clone $this;
			$EM_Event->get_categories(); //before we remove event/post ids
			$EM_Event->get_bookings()->get_tickets(); //in case this wasn't loaded and before we reset ids
			$EM_Event->event_id = null;
			$EM_Event->post_id = null;
			$EM_Event->ID = null;
			$EM_Event->post_name = '';
			$EM_Event->location_id = (empty($EM_Event->location_id)  && !get_option('dbem_require_location')) ? 0:$EM_Event->location_id;
			$EM_Event->get_bookings()->event_id = null;
			$EM_Event->get_bookings()->get_tickets()->event_id = null;
			//if bookings reset ticket ids and duplicate tickets
			foreach($EM_Event->get_bookings()->get_tickets()->tickets as $EM_Ticket){
				$EM_Ticket->ticket_id = null;
				$EM_Ticket->event_id = null;
			}
			do_action('em_event_duplicate_pre', $EM_Event, $this);
			$EM_Event->duplicated = true;
			$EM_Event->force_status = 'draft';
			if( $EM_Event->save() ){
				$EM_Event->feedback_message = sprintf(__("%s successfully duplicated.", 'events-manager'), __('Event','events-manager'));
				//save tags here - eventually will be moved into part of $this->save();
				if( get_option('dbem_tags_enabled') ){
					$EM_Tags = new EM_Tags($this);
					$EM_Tags->event_id = $EM_Event->event_id;
					$EM_Tags->post_id = $EM_Event->post_id;
					$EM_Tags->save();
				}
			 	//other non-EM post meta inc. featured image
				$event_meta = $this->get_event_meta($this->blog_id);
				$new_event_meta = $EM_Event->get_event_meta($EM_Event->blog_id);
				$event_meta_inserts = array();
			 	//Get custom fields and post meta - adapted from $this->load_post_meta()
			 	foreach($event_meta as $event_meta_key => $event_meta_vals){
			 		if($event_meta_key[0] == '_' && is_array($event_meta_vals)){
			 		    $field_name = substr($event_meta_key, 1);
			 			if($field_name != 'event_attributes' && !array_key_exists($event_meta_key, $new_event_meta) &&  !in_array($field_name, array('edit_last', 'edit_lock', 'event_owner_name','event_owner_anonymous','event_owner_email')) ){
				 			foreach($event_meta_vals as $event_meta_val){
				 			    $event_meta_inserts[] = "({$EM_Event->post_id}, '{$event_meta_key}', '{$event_meta_val}')";
				 			}
			 			}
			 		}
			 	}
			 	//save in one SQL statement
			 	if( !empty($event_meta_inserts) ){
			 		$wpdb->query('INSERT INTO '.$wpdb->postmeta." (post_id, meta_key, meta_value) VALUES ".implode(', ', $event_meta_inserts));
			 	}
				if( array_key_exists('_event_approvals_count', $event_meta) ) update_post_meta($EM_Event->post_id, '_event_approvals_count', 0);
				//copy anything from the em_meta table too
				$wpdb->query('INSERT INTO '.EM_META_TABLE." (object_id, meta_key, meta_value) SELECT '{$EM_Event->event_id}', meta_key, meta_value FROM ".EM_META_TABLE." WHERE object_id='{$this->event_id}'");
			 	//set event to draft status
				return apply_filters('em_event_duplicate', $EM_Event, $this);
			}
		}
		//TODO add error notifications for duplication failures.
		return apply_filters('em_event_duplicate', false, $this);;
	}
	
	function duplicate_url($raw = false){
	    $url = add_query_arg(array('action'=>'event_duplicate', 'event_id'=>$this->event_id, '_wpnonce'=> wp_create_nonce('event_duplicate_'.$this->event_id)));
	    $url = apply_filters('em_event_duplicate_url', $url, $this);
	    $url = $raw ? esc_url_raw($url):esc_url($url);
	    return $url;
	}
	
	/**
	 * Delete whole event, including bookings, tickets, etc.
	 * @return boolean
	 */
	function delete($force_delete = false){ //atm wp seems to force cp deletions anyway
		if( $this->can_manage('delete_events', 'delete_others_events') ){
		    if( !is_admin() ){
				include_once('em-event-post-admin.php');
				if( !defined('EM_EVENT_DELETE_INCLUDE') ){
					EM_Event_Post_Admin::init();
					EM_Event_Recurring_Post_Admin::init();
					define('EM_EVENT_DELETE_INCLUDE',true);
				}
		    }
		    do_action('em_event_delete_pre', $this);
			if( $force_delete ){
				$result = wp_delete_post($this->post_id,$force_delete);
			}else{
				$result = wp_trash_post($this->post_id);
				if( !$result && $this->post_status == 'trash' ){
				    //we're probably dealing with a trashed post already, but the event_status is null from < v5.4.1
				    $this->set_status(-1);
				    $result = true;
				}
			}
			if( !$result && !empty($this->orphaned_event) ){
			    //this is an orphaned event, so the wp delete posts would have never worked, so we just delete the row in our events table
			    $this->delete_meta();
			}
		}else{
			$result = false;
		}
		return apply_filters('em_event_delete', $result != false, $this);
	}
	
	function delete_meta(){
		global $wpdb;
		$result = false;
		if( $this->can_manage('delete_events', 'delete_others_events') ){
			do_action('em_event_delete_meta_event_pre', $this);
			$result = $wpdb->query ( $wpdb->prepare("DELETE FROM ". EM_EVENTS_TABLE ." WHERE event_id=%d", $this->event_id) );
			if( $result !== false ){
				$this->delete_bookings();
				$this->delete_tickets();
				//Delete the recurrences then this recurrence event
				if( $this->is_recurring() ){
					$result = $this->delete_events(); //was true at this point, so false if fails
				}
				//Delete categories from meta if in MS global mode
				if( EM_MS_GLOBAL ){
					$wpdb->query('DELETE FROM '.EM_META_TABLE.' WHERE object_id='.$this->event_id." AND meta_key='event-category'");
				} 
			}
		}
		return apply_filters('em_event_delete_meta', $result !== false, $this);
	}
	
	/**
	 * Shortcut function for $this->get_bookings()->delete(), because using the EM_Bookings requires loading previous bookings, which isn't neceesary. 
	 */
	function delete_bookings(){
		global $wpdb;
		do_action('em_event_delete_bookings_pre', $this);
		$result = false;
		if( $this->can_manage('manage_bookings','manage_others_bookings') ){
			$result_bt = $wpdb->query( $wpdb->prepare("DELETE FROM ".EM_TICKETS_BOOKINGS_TABLE." WHERE booking_id IN (SELECT booking_id FROM ".EM_BOOKINGS_TABLE." WHERE event_id=%d)", $this->event_id) );
			$result = $wpdb->query( $wpdb->prepare("DELETE FROM ".EM_BOOKINGS_TABLE." WHERE event_id=%d", $this->event_id) );
		}
		return apply_filters('em_event_delete_bookings', $result !== false && $result_bt !== false, $this);
	}
	
	/**
	 * Shortcut function for $this->get_bookings()->delete(), because using the EM_Bookings requires loading previous bookings, which isn't neceesary. 
	 */
	function delete_tickets(){
		global $wpdb;
		do_action('em_event_delete_tickets_pre', $this);
		$result = false;
		if( $this->can_manage('manage_bookings','manage_others_bookings') ){
			$result_bt = $wpdb->query( $wpdb->prepare("DELETE FROM ".EM_TICKETS_BOOKINGS_TABLE." WHERE ticket_id IN (SELECT ticket_id FROM ".EM_TICKETS_TABLE." WHERE event_id=%d)", $this->event_id) );
			$result = $wpdb->query( $wpdb->prepare("DELETE FROM ".EM_TICKETS_TABLE." WHERE event_id=%d", $this->event_id) );
		}
		return apply_filters('em_event_delete_tickets', $result, $this);
	}
	
	/**
	 * Change the status of the event. This will save to the Database too. 
	 * @param int $status
	 * @param boolean $set_post_status
	 * @return string
	 */
	function set_status($status, $set_post_status = false){
		global $wpdb;
		if($status === null){ 
			$set_status='NULL'; 
			if($set_post_status){
				//if the post is trash, don't untrash it!
				$wpdb->update( $wpdb->posts, array( 'post_status' => 'draft' ), array( 'ID' => $this->post_id ) );
			}
			$this->post_status = 'draft'; //set post status in this instance
		}elseif( $status == -1 ){ //trashed post
			$set_status = -1;
			if($set_post_status){
				$wpdb->update( $wpdb->posts, array( 'post_status' => $this->post_status ), array( 'ID' => $this->post_id ) );
			}
			$this->post_status = 'trash'; //set post status in this instance
		}else{
			$set_status = $status ? 1:0;
			$post_status = $set_status ? 'publish':'pending';
			if($set_post_status){
				if($this->post_status == 'pending' && empty($this->post_name)){
					$this->post_name = sanitize_title($this->post_title);
				}
				$wpdb->update( $wpdb->posts, array( 'post_status' => $post_status, 'post_name' => $this->post_name ), array( 'ID' => $this->post_id ) );
			}
			$this->post_status = $post_status;
		}
		$this->get_previous_status();
		$result = $wpdb->query( $wpdb->prepare("UPDATE ".EM_EVENTS_TABLE." SET event_status=$set_status, event_slug=%s WHERE event_id=%d", array($this->post_name, $this->event_id)) );
		$this->get_status(); //reload status
		return apply_filters('em_event_set_status', $result !== false, $status, $this);
	}
	
	function is_published(){
		return apply_filters('em_event_is_published', ($this->post_status == 'publish' || $this->post_status == 'private'), $this);
	}
	
	function get_status($db = false){
		switch( $this->post_status ){
			case 'private':
				$this->event_private = 1;
				$this->event_status = $status = 1;
				break;
			case 'publish':
				$this->event_private = 0;
				$this->event_status = $status = 1;
				break;
			case 'pending':
				$this->event_private = 0;
				$this->event_status = $status = 0;
				break;
			case 'trash':
				$this->event_private = 0;
				$this->event_status = $status = -1;
				break;
			default: //draft or unknown
				$this->event_private = 0;
				$status = $db ? 'NULL':null;
				$this->event_status = null;
				break;
		}
		return $status;
	}
	
	function get_previous_status( $force = false ){
		global $wpdb;
		if( $this->event_id > 0 && ($this->previous_status === false || $force) ){
			$this->previous_status = $wpdb->get_var('SELECT event_status FROM '.EM_EVENTS_TABLE.' WHERE event_id='.$this->event_id); //get status from db, not post_status, as posts get saved quickly
		}
		return $this->previous_status;
	}
	
	/**
	 * Returns an EM_Categories object of the EM_Event instance.
	 * @return EM_Categories
	 */
	function get_categories() {
		if( empty($this->categories) ){
			$this->categories = new EM_Categories($this);
		}elseif(empty($this->categories->event_id)){
			$this->categories->event_id = $this->event_id;
			$this->categories->post_id = $this->post_id;			
		}
		return apply_filters('em_event_get_categories', $this->categories, $this);
	}
	
	/**
	 * Returns the location object this event belongs to.
	 * @return EM_Location
	 */
	function get_location() {
		global $EM_Location;
		if( is_object($EM_Location) && $EM_Location->location_id == $this->location_id ){
			$this->location = $EM_Location;
		}else{
			if( !is_object($this->location) || $this->location->location_id != $this->location_id ){
				$this->location = apply_filters('em_event_get_location', em_get_location($this->location_id), $this);
			}
		}
		return $this->location;
	}
	
	/**
	 * Returns the location object this event belongs to.
	 * @return EM_Person
	 */	
	function get_contact(){
		if( !is_object($this->contact) ){
			$this->contact = new EM_Person($this->event_owner);
			//if this is anonymous submission, change contact email and name
			if( $this->event_owner_anonymous ){
				$this->contact->user_email = $this->event_owner_email;
				$name = explode(' ',$this->event_owner_name);
				$first_name = array_shift($name);
				$last_name = (count($name) > 0) ? implode(' ',$name):'';
				$this->contact->user_firstname = $this->contact->first_name = $first_name;
				$this->contact->user_lastname = $this->contact->last_name = $last_name;
				$this->contact->display_name = $this->event_owner_name;
			}
		}
		return $this->contact;
	}
	
	/**
	 * Retrieve and save the bookings belonging to instance. If called again will return cached version, set $force_reload to true to create a new EM_Bookings object.
	 * @param boolean $force_reload
	 * @return EM_Bookings
	 */
	function get_bookings( $force_reload = false ){
		if( get_option('dbem_rsvp_enabled') ){
			if( (!$this->bookings || $force_reload) ){
				$this->bookings = new EM_Bookings($this);
			}
			$this->bookings->event_id = $this->event_id; //always refresh event_id
			$this->bookings = apply_filters('em_event_get_bookings', $this->bookings, $this);
		}else{
			return new EM_Bookings();
		}
		//TODO for some reason this returned instance doesn't modify the original, e.g. try $this->get_bookings()->add($EM_Booking) and see how $this->bookings->feedback_message doesn't change
		return $this->bookings;
	}
	
	/**
	 * Get the tickets related to this event.
	 * @param boolean $force_reload
	 * @return EM_Tickets
	 */
	function get_tickets( $force_reload = false ){
		return $this->get_bookings($force_reload)->get_tickets();
	}
	
	/*
	 * Provides the tax rate for this event.
	 * Currently a site-wide default, but this hook allows easy overriding of tax rates for specific events.
	 * @uses apply_filters() on 'em_event_get_tax_rate' before returning value
	 * @uses EM_Object::get_tax_rate()
	 */
	function get_tax_rate(){
		return apply_filters('em_event_get_tax_rate', parent::get_tax_rate(), $this);
	}
	
	/**
	 * Gets number of spaces in this event, dependent on ticket spaces or hard limit, whichever is smaller.
	 * @param boolean $force_refresh
	 * @return int 
	 */
	function get_spaces($force_refresh=false){
		return $this->get_bookings()->get_spaces($force_refresh);
	}
	
	/* 
	 * Extends the default EM_Object function by switching blogs as needed if in MS Global mode  
	 * @param string $size
	 * @return string
	 * @see EM_Object::get_image_url()
	 */
	function get_image_url($size = 'full'){
	    if( EM_MS_GLOBAL && get_current_blog_id() != $this->blog_id ){
	        switch_to_blog($this->blog_id);
	        $switch_back = true;
	    }
		$return = parent::get_image_url($size);
		if( !empty($switch_back) ){ restore_current_blog(); }
		return $return;
	}
	
	function get_edit_reschedule_url(){
		if( $this->is_recurrence() ){
			$EM_Event = em_get_event($this->recurrence_id);
			return $EM_Event->get_edit_url();
		}
	}
	
	function get_edit_url(){
		if( $this->can_manage('edit_events','edit_others_events') ){
			if( EM_MS_GLOBAL && get_site_option('dbem_ms_global_events_links') && !empty($this->blog_id) && is_main_site() && $this->blog_id != get_current_blog_id() ){
				if( get_blog_option($this->blog_id, 'dbem_edit_events_page') ){
					$link = em_add_get_params(get_permalink(get_blog_option($this->blog_id, 'dbem_edit_events_page')), array('action'=>'edit','event_id'=>$this->event_id), false);
				}
				if( empty($link))
					$link = get_admin_url($this->blog_id, "post.php?post={$this->post_id}&action=edit");
			}else{
				if( get_option('dbem_edit_events_page') && !is_admin() ){
					$link = em_add_get_params(get_permalink(get_option('dbem_edit_events_page')), array('action'=>'edit','event_id'=>$this->event_id), false);
				}
				if( empty($link))
					$link = admin_url()."post.php?post={$this->post_id}&action=edit";
			}
			return apply_filters('em_event_get_edit_url', $link, $this);
		}
	}
	
	function get_bookings_url(){
		if( get_option('dbem_edit_bookings_page') && (!is_admin() || !empty($_REQUEST['is_public'])) ){
			$my_bookings_page = get_permalink(get_option('dbem_edit_bookings_page'));
			$bookings_link = em_add_get_params($my_bookings_page, array('event_id'=>$this->event_id), false);
		}else{
			if( is_multisite() && $this->blog_id != get_current_blog_id() ){
				$bookings_link = get_admin_url($this->blog_id, 'edit.php?post_type='.EM_POST_TYPE_EVENT."&page=events-manager-bookings&event_id=".$this->event_id);
			}else{
				$bookings_link = EM_ADMIN_URL. "&page=events-manager-bookings&event_id=".$this->event_id;
			}
		}
		return apply_filters('em_event_get_bookings_url', $bookings_link, $this);
	}
	
	function get_permalink(){
		if( EM_MS_GLOBAL ){
			//if no blog id defined, assume it's the main blog
			$blog_id = empty($this->blog_id) ? get_current_site()->blog_id:$this->blog_id;
			//if we're not on the same blog as this event then decide whether to link to main blog or to source blog 
			if( $blog_id != get_current_blog_id() ){
				if( !get_site_option('dbem_ms_global_events_links') && is_main_site() &&  get_option('dbem_events_page') ){
					//if on main site, and events page exists and direct links are disabled then show link to main site
					$event_link = trailingslashit(get_permalink(get_option('dbem_events_page')).get_site_option('dbem_ms_events_slug',EM_EVENT_SLUG).'/'.$this->event_slug.'-'.$this->event_id);					
				}else{
					//linking directly to the source blog by default
					$event_link = get_blog_permalink( $blog_id, $this->post_id);
				}
			}
		}
		if( empty($event_link) ){
			$event_link = get_post_permalink($this->post_id);
		}
		return apply_filters('em_event_get_permalink', $event_link, $this);
	}
	
	function get_ical_url(){
		global $wp_rewrite;
		if( !empty($wp_rewrite) && $wp_rewrite->using_permalinks() ){
			$return = trailingslashit($this->get_permalink()).'ical/';
		}else{
			$return = em_add_get_params($this->get_permalink(), array('ical'=>1));
		}
		return apply_filters('em_event_get_ical_url', $return);
	}
	
	function is_free( $now = false ){
		$free = true;
		foreach($this->get_tickets() as $EM_Ticket){
		    /* @var $EM_Ticket EM_Ticket */
			if( $EM_Ticket->get_price() > 0 ){
				if( !$now || $EM_Ticket->is_available() ){	
				    $free = false;
				}
			}
		}
		return apply_filters('em_event_is_free',$free,$this);
	}
	
	/**
	 * Will output a single event format of this event. 
	 * Equivalent of calling EM_Event::output( get_option ( 'dbem_single_event_format' ) )
	 * @param string $target
	 * @return string
	 */
	function output_single($target='html'){
		$format = get_option ( 'dbem_single_event_format' );
		return apply_filters('em_event_output_single', $this->output($format, $target), $this, $target);
	}
	
	/**
	 * Will output a event in the format passed in $format by replacing placeholders within the format.
	 * @param string $format
	 * @param string $target
	 * @return string
	 */	
	function output($format, $target="html") {	
	 	$event_string = $format;
		//Time place holder that doesn't show if empty.
		//TODO add filter here too
		preg_match_all('/#@?_\{[^}]+\}/', $format, $results);
		foreach($results[0] as $result) {
			if(substr($result, 0, 3 ) == "#@_"){
				$date = 'end_date';
				$offset = 4;
			}else{
				$date = 'start_date';
				$offset = 3;
			}
			if( $date == 'end_date' && $this->event_end_date == $this->event_start_date ){
				$replace = __( apply_filters('em_event_output_placeholder', '', $this, $result, $target) );
			}else{
				$replace = __( apply_filters('em_event_output_placeholder', mysql2date(substr($result, $offset, (strlen($result)-($offset+1)) ), $this->$date), $this, $result, $target) );
			}
			$event_string = str_replace($result,$replace,$event_string );
		}
		//This is for the custom attributes
		preg_match_all('/#_ATT\{([^}]+)\}(\{([^}]+)\})?/', $event_string, $results);
		$attributes = em_get_attributes();
		foreach($results[0] as $resultKey => $result) {
			//Strip string of placeholder and just leave the reference
			$attRef = substr( substr($result, 0, strpos($result, '}')), 6 );
			$attString = '';
			if( is_array($this->event_attributes) && array_key_exists($attRef, $this->event_attributes) ){
				$attString = $this->event_attributes[$attRef];
			}elseif( !empty($results[3][$resultKey]) ){
				//Check to see if we have a second set of braces;
				$attString = $results[3][$resultKey];
			}elseif( !empty($attributes['values'][$attRef][0]) ){
			    $attString = $attributes['values'][$attRef][0];
			}
			$attString = apply_filters('em_event_output_placeholder', $attString, $this, $result, $target);
			$event_string = str_replace($result, $attString ,$event_string );
		}
	 	//First let's do some conditional placeholder removals
	 	for ($i = 0 ; $i < EM_CONDITIONAL_RECURSIONS; $i++){ //you can add nested recursions by modifying this setting in your wp_options table
			preg_match_all('/\{([a-zA-Z0-9_\-]+)\}(.+?)\{\/\1\}/s', $event_string, $conditionals);
			if( count($conditionals[0]) > 0 ){
				//Check if the language we want exists, if not we take the first language there
				foreach($conditionals[1] as $key => $condition){
					$show_condition = false;
					if ($condition == 'has_bookings') {
						//check if there's a booking, if not, remove this section of code.
						$show_condition = ($this->event_rsvp && get_option('dbem_rsvp_enabled'));
					}elseif ($condition == 'no_bookings') {
						//check if there's a booking, if not, remove this section of code.
						$show_condition = (!$this->event_rsvp && get_option('dbem_rsvp_enabled'));
					}elseif ($condition == 'no_location'){
						//does this event have a valid location?
						$show_condition = ( empty($this->location_id) || !$this->get_location()->location_status );
					}elseif ($condition == 'has_location'){
						//does this event have a valid location?
						$show_condition = ( !empty($this->location_id) && $this->get_location()->location_status );
					}elseif ($condition == 'has_image'){
						//does this event have an image?
						$show_condition = ( $this->get_image_url() != '' );
					}elseif ($condition == 'no_image'){
						//does this event have an image?
						$show_condition = ( $this->get_image_url() == '' );
					}elseif ($condition == 'has_time'){
						//are the booking times different and not an all-day event
						$show_condition = ( $this->event_start_time != $this->event_end_time && !$this->event_all_day );
					}elseif ($condition == 'no_time'){
						//are the booking times exactly the same and it's not an all-day event.
						$show_condition = ( $this->event_start_time == $this->event_end_time && !$this->event_all_day );
					}elseif ($condition == 'all_day'){
						//is it an all day event
						$show_condition = !empty($this->event_all_day);
					}elseif ($condition == 'logged_in'){
						//user is logged in
						$show_condition = is_user_logged_in();
					}elseif ($condition == 'not_logged_in'){
						//not logged in
						$show_condition = !is_user_logged_in();
					}elseif ($condition == 'has_spaces'){
						//there are still empty spaces
						$show_condition = $this->event_rsvp && $this->get_bookings()->get_available_spaces() > 0;
					}elseif ($condition == 'fully_booked'){
						//event is fully booked
						$show_condition = $this->event_rsvp && $this->get_bookings()->get_available_spaces() <= 0;
					}elseif ($condition == 'bookings_open'){
						//bookings are still open
						$show_condition = $this->event_rsvp && $this->get_bookings()->is_open();
					}elseif ($condition == 'bookings_closed'){
						//bookings are still closed
						$show_condition = $this->event_rsvp && !$this->get_bookings()->is_open();
					}elseif ($condition == 'is_free' || $condition == 'is_free_now'){
						//is it a free day event, if _now then free right now
						$show_condition = !$this->event_rsvp || $this->is_free( $condition == 'is_free_now' );
					}elseif ($condition == 'not_free' || $condition == 'not_free_now'){
						//is it a paid event, if _now then paid right now
						$show_condition = $this->event_rsvp && !$this->is_free( $condition == 'not_free_now' );
					}elseif ($condition == 'is_long'){
						//is it an all day event
						$show_condition = $this->event_start_date != $this->event_end_date;
					}elseif ($condition == 'not_long'){
						//is it an all day event
						$show_condition = $this->event_start_date == $this->event_end_date;
					}elseif ($condition == 'is_past'){
						//if event is past
						if( get_option('dbem_events_current_are_past') ){
						    $show_condition = $this->start <= current_time('timestamp');
						}else{
						    $show_condition = $this->end <= current_time('timestamp');
						}
					}elseif ($condition == 'is_future'){
						//if event is upcoming
						$show_condition = $this->start > current_time('timestamp');
					}elseif ($condition == 'is_current'){
						//if event is upcoming
						$ts = current_time('timestamp');
						$show_condition = $this->start <= $ts && $this->end >= $ts;
					}elseif ($condition == 'is_recurrence'){
						//if event is a recurrence
						$show_condition = $this->is_recurrence();
					}elseif ($condition == 'not_recurrence'){
						//if event is not a recurrence
						$show_condition = !$this->is_recurrence();
					}elseif ($condition == 'is_private'){
						//if event is a recurrence
						$show_condition = $this->event_private == 1;
					}elseif ($condition == 'not_private'){
						//if event is not a recurrence
						$show_condition = $this->event_private == 0;
					}elseif ( preg_match('/^has_category_([a-zA-Z0-9_\-]+)$/', $condition, $category_match)){
					    //event is in this category
					    $show_condition = has_term($category_match[1], EM_TAXONOMY_CATEGORY, $this->post_id);
					}elseif ( preg_match('/^no_category_([a-zA-Z0-9_\-]+)$/', $condition, $category_match)){
					    //event is NOT in this category
					    $show_condition = !has_term($category_match[1], EM_TAXONOMY_CATEGORY, $this->post_id);
					}elseif ( preg_match('/^has_tag_([a-zA-Z0-9_\-]+)$/', $condition, $tag_match)){
					    //event has this tag
					    $show_condition = has_term($tag_match[1], EM_TAXONOMY_TAG, $this->post_id);
					}elseif ( preg_match('/^no_tag_([a-zA-Z0-9_\-]+)$/', $condition, $tag_match)){
					   //event doesn't have this tag
					    $show_condition = !has_term($tag_match[1], EM_TAXONOMY_TAG, $this->post_id);
					}
					//other potential ones - has_attribute_... no_attribute_... has_categories_...
					$show_condition = apply_filters('em_event_output_show_condition', $show_condition, $condition, $conditionals[0][$key], $this);
					if($show_condition){
						//calculate lengths to delete placeholders
						$placeholder_length = strlen($condition)+2;
						$replacement = substr($conditionals[0][$key], $placeholder_length, strlen($conditionals[0][$key])-($placeholder_length *2 +1));
					}else{
						$replacement = '';
					}
					$event_string = str_replace($conditionals[0][$key], apply_filters('em_event_output_condition', $replacement, $condition, $conditionals[0][$key], $this), $event_string);
				}
			}
	 	}
		//Now let's check out the placeholders.
	 	preg_match_all("/(#@?_?[A-Za-z0-9]+)({([^}]+)})?/", $event_string, $placeholders);
	 	$replaces = array();
		foreach($placeholders[1] as $key => $result) {
			$match = true;
			$replace = '';
			$full_result = $placeholders[0][$key];
			switch( $result ){
				//Event Details
				case '#_EVENTID':
					$replace = $this->event_id;
					break;
				case '#_EVENTPOSTID':
					$replace = $this->post_id;
					break;
				case '#_NAME': //deprecated
				case '#_EVENTNAME':
					$replace = $this->event_name;
					break;
				case '#_NOTES': //deprecated
				case '#_EXCERPT': //deprecated
				case '#_EVENTNOTES':
				case '#_EVENTEXCERPT':
					$replace = $this->post_content;
					if($result == "#_EXCERPT" || $result == "#_EVENTEXCERPT"){
						if( !empty($this->post_excerpt) ){
							$replace = $this->post_excerpt;
						}else{
						    $excerpt_length = 55;
							$excerpt_more = apply_filters('em_excerpt_more', ' ' . '[...]');
						    if( !empty($placeholders[3][$key]) ){
						        $trim = true;
						        $ph_args = explode(',', $placeholders[3][$key]);
						        if( is_numeric($ph_args[0]) ) $excerpt_length = $ph_args[0];
						        if( !empty($ph_args[1]) ) $excerpt_more = $ph_args[1];
						    }
							if ( preg_match('/<!--more(.*?)?-->/', $replace, $matches) ) {
								$content = explode($matches[0], $replace, 2);
								$replace = force_balance_tags($content[0]);
							}
							if( !empty($trim) ){
							    //shorten content by supplied number - copied from wp_trim_excerpt
							    $replace = strip_shortcodes( $replace );
							    $replace = str_replace(']]>', ']]&gt;', $replace);
							    $replace = wp_trim_words( $replace, $excerpt_length, $excerpt_more );
							}
						}
					}
					break;
				case '#_EVENTIMAGEURL':
				case '#_EVENTIMAGE':
	        		if($this->get_image_url() != ''){
						if($result == '#_EVENTIMAGEURL'){
		        			$replace =  esc_url($this->image_url);
						}else{
							if( empty($placeholders[3][$key]) ){
								$replace = "<img src='".esc_url($this->image_url)."' alt='".esc_attr($this->event_name)."'/>";
							}else{
								$image_size = explode(',', $placeholders[3][$key]);
								$image_url = $this->image_url;
								if( self::array_is_numeric($image_size) && count($image_size) > 1 ){
								    //get a thumbnail
								    if( get_option('dbem_disable_thumbnails') ){
    								    $image_attr = '';
    								    $image_args = array();
    								    if( empty($image_size[1]) && !empty($image_size[0]) ){    
    								        $image_attr = 'width="'.$image_size[0].'"';
    								        $image_args['w'] = $image_size[0];
    								    }elseif( empty($image_size[0]) && !empty($image_size[1]) ){
    								        $image_attr = 'height="'.$image_size[1].'"';
    								        $image_args['h'] = $image_size[1];
    								    }elseif( !empty($image_size[0]) && !empty($image_size[1]) ){
    								        $image_attr = 'width="'.$image_size[0].'" height="'.$image_size[1].'"';
    								        $image_args = array('w'=>$image_size[0], 'h'=>$image_size[1]);
    								    }
								        $replace = "<img src='".esc_url(em_add_get_params($image_url, $image_args))."' alt='".esc_attr($this->event_name)."' $image_attr />";
								    }else{
    								    if( EM_MS_GLOBAL && get_current_blog_id() != $this->blog_id ){
    								        switch_to_blog($this->blog_id);
    								        $switch_back = true;
    								    }
								        $replace = get_the_post_thumbnail($this->ID, $image_size);
								        if( !empty($switch_back) ){ restore_current_blog(); }
								    }
								}else{
									$replace = "<img src='".esc_url($image_url)."' alt='".esc_attr($this->event_name)."'/>";
								}
							}
						}
	        		}
					break;
				//Times & Dates
				case '#_24HSTARTTIME':
				case '#_24HENDTIME':
					$time = ($result == '#_24HSTARTTIME') ? $this->event_start_time:$this->event_end_time;
					$replace = substr($time, 0,5);
					break;
				case '#_12HSTARTTIME':
				case '#_12HENDTIME':
					$time = ($result == '#_12HSTARTTIME') ? $this->event_start_time:$this->event_end_time;
					$replace = date('g:i A', strtotime($time));
					break;
				case '#_EVENTTIMES':
					//get format of time to show
					if( !$this->event_all_day ){
						$time_format = ( get_option('dbem_time_format') ) ? get_option('dbem_time_format'):get_option('time_format');
						if($this->event_start_time != $this->event_end_time ){
							$replace = date_i18n($time_format, $this->start). get_option('dbem_times_separator') . date_i18n($time_format, $this->end);
						}else{
							$replace = date_i18n($time_format, $this->start);
						}
					}else{
						$replace = get_option('dbem_event_all_day_message');
					}
					break;
				case '#_EVENTDATES':
					//get format of time to show
					$date_format = ( get_option('dbem_date_format') ) ? get_option('dbem_date_format'):get_option('date_format');
					if( $this->event_start_date != $this->event_end_date){
						$replace = date_i18n($date_format, $this->start). get_option('dbem_dates_separator') . date_i18n($date_format, $this->end);
					}else{
						$replace = date_i18n($date_format, $this->start);
					}
					break;
				//Links
				case '#_EVENTPAGEURL': //deprecated	
				case '#_LINKEDNAME': //deprecated
				case '#_EVENTURL': //Just the URL
				case '#_EVENTLINK': //HTML Link
					$event_link = esc_url($this->get_permalink());
					if($result == '#_LINKEDNAME' || $result == '#_EVENTLINK'){
						$replace = '<a href="'.$event_link.'" title="'.esc_attr($this->event_name).'">'.esc_attr($this->event_name).'</a>';
					}else{
						$replace = $event_link;	
					}
					break;
				case '#_EDITEVENTURL':
				case '#_EDITEVENTLINK':
					if( $this->can_manage('edit_events','edit_others_events') ){
						$link = esc_url($this->get_edit_url());
						if( $result == '#_EDITEVENTLINK'){
							$replace = '<a href="'.$link.'">'.esc_html(sprintf(__('Edit Event','events-manager'))).'</a>';
						}else{
							$replace = $link;
						}
					}	 
					break;
				//Bookings
				case '#_ADDBOOKINGFORM': //deprecated
				case '#_REMOVEBOOKINGFORM': //deprecated
				case '#_BOOKINGFORM':
					if( get_option('dbem_rsvp_enabled')){
						if( !defined('EM_XSS_BOOKINGFORM_FILTER') && locate_template('plugins/events-manager/placeholders/bookingform.php') ){
							//xss fix for old overridden booking forms
							add_filter('em_booking_form_action_url','esc_url');
							define('EM_XSS_BOOKINGFORM_FILTER',true);
						}
						ob_start();
						$template = em_locate_template('placeholders/bookingform.php', true, array('EM_Event'=>$this));
						EM_Bookings::enqueue_js();
						$replace = ob_get_clean();
					}
					break;
				case '#_BOOKINGBUTTON':
					if( get_option('dbem_rsvp_enabled') && $this->event_rsvp ){
						ob_start();
						$template = em_locate_template('placeholders/bookingbutton.php', true, array('EM_Event'=>$this));
						$replace = ob_get_clean();
					}
					break;
				case '#_EVENTPRICERANGEALL':				    
				    $show_all_ticket_prices = true; //continues below
				case '#_EVENTPRICERANGE':
					//get the range of prices
					$min = false;
					$max = 0;
					if( $this->get_bookings()->is_open() || !empty($show_all_ticket_prices) ){
						foreach( $this->get_tickets()->tickets as $EM_Ticket ){
							/* @var $EM_Ticket EM_Ticket */
							if( $EM_Ticket->is_available() || get_option('dbem_bookings_tickets_show_unavailable') || !empty($show_all_ticket_prices) ){
								if($EM_Ticket->get_price() > $max ){
									$max = $EM_Ticket->get_price();
								}
								if($EM_Ticket->get_price() < $min || $min === false){
									$min = $EM_Ticket->get_price();
								}						
							}
						}
					}
					if( $min === false ) $min = 0;
					if( $min != $max ){
						$replace = em_get_currency_formatted($min).' - '.em_get_currency_formatted($max);
					}else{
						$replace = em_get_currency_formatted($min);
					}
					break;
				case '#_EVENTPRICEMIN':
					//get the range of prices
					$min = false;
					foreach( $this->get_tickets()->tickets as $EM_Ticket ){
						/* @var $EM_Ticket EM_Ticket */
						if( $EM_Ticket->is_available()|| get_option('dbem_bookings_tickets_show_unavailable') ){
							if( $EM_Ticket->get_price() < $min || $min === false){
								$min = $EM_Ticket->get_price();
							}
						}
					}
					if( $min === false ) $min = 0;
					$replace = em_get_currency_formatted($min);
					break;
				case '#_EVENTPRICEMAX':
					//get the range of prices
					$max = 0;
					foreach( $this->get_tickets()->tickets as $EM_Ticket ){
						/* @var $EM_Ticket EM_Ticket */
						if( $EM_Ticket->is_available()|| get_option('dbem_bookings_tickets_show_unavailable') ){
							if( $EM_Ticket->get_price() > $max ){
								$max = $EM_Ticket->get_price();
							}
						}			
					}
					$replace = em_get_currency_formatted($max);
					break;
				case '#_AVAILABLESEATS': //deprecated
				case '#_AVAILABLESPACES':
					if ($this->event_rsvp && get_option('dbem_rsvp_enabled')) {
					   $replace = $this->get_bookings()->get_available_spaces();
					} else {
						$replace = "0";
					}
					break;
				case '#_BOOKEDSEATS': //deprecated
				case '#_BOOKEDSPACES':
					//This placeholder is actually a little misleading, as it'll consider reserved (i.e. pending) bookings as 'booked'
					if ($this->event_rsvp && get_option('dbem_rsvp_enabled')) {
						$replace = $this->get_bookings()->get_booked_spaces();
						if( get_option('dbem_bookings_approval_reserved') ){
							$replace += $this->get_bookings()->get_pending_spaces();
						}
					} else {
						$replace = "0";
					}
					break;
				case '#_PENDINGSPACES':
					if ($this->event_rsvp && get_option('dbem_rsvp_enabled')) {
					   $replace = $this->get_bookings()->get_pending_spaces();
					} else {
						$replace = "0";
					}
					break;
				case '#_SEATS': //deprecated
				case '#_SPACES':
					$replace = $this->get_spaces();
					break;
				case '#_BOOKINGSURL':
				case '#_BOOKINGSLINK':
					if( $this->can_manage('manage_bookings','manage_others_bookings') ){
						$bookings_link = esc_url($this->get_bookings_url());
						if($result == '#_BOOKINGSLINK'){
							$replace = '<a href="'.$bookings_link.'" title="'.esc_attr($this->event_name).'">'.esc_html($this->event_name).'</a>';
						}else{
							$replace = $bookings_link;	
						}
					}
					break;
				case '#_BOOKINGSCUTOFF':
				case '#_BOOKINGSCUTOFFDATE':
				case '#_BOOKINGSCUTOFFTIME':
					$replace = '';
					if ($this->event_rsvp && get_option('dbem_rsvp_enabled') && !empty($this->rsvp_end)) {
						$replace_format = get_option('dbem_date_format').' '. get_option('dbem_time_format');
						if( $result == '#_BOOKINGSCUTOFFDATE' ) $replace_format = get_option('dbem_date_format');
						if( $result == '#_BOOKINGSCUTOFFTIME' ) $replace_format = get_option('dbem_time_format');
						$replace = date($replace_format, $this->rsvp_end);
					}
					break;
				//Contact Person
				case '#_CONTACTNAME':
				case '#_CONTACTPERSON': //deprecated (your call, I think name is better)
					$replace = $this->get_contact()->display_name;
					break;
				case '#_CONTACTUSERNAME':
					$replace = $this->get_contact()->user_login;
					break;
				case '#_CONTACTEMAIL':
				case '#_CONTACTMAIL': //deprecated
					$replace = $this->get_contact()->user_email;
					break;
				case '#_CONTACTURL':
					$replace = $this->get_contact()->user_url;
					break;
				case '#_CONTACTID':
					$replace = $this->get_contact()->ID;
					break;
				case '#_CONTACTPHONE':
		      		$replace = ( $this->get_contact()->phone != '') ? $this->get_contact()->phone : __('N/A', 'events-manager');
					break;
				case '#_CONTACTAVATAR': 
					$replace = get_avatar( $this->get_contact()->ID, $size = '50' ); 
					break;
				case '#_CONTACTPROFILELINK':
				case '#_CONTACTPROFILEURL':
					if( function_exists('bp_core_get_user_domain') ){
						$replace = bp_core_get_user_domain($this->get_contact()->ID);
						if( $result == '#_CONTACTPROFILELINK' ){
							$replace = '<a href="'.esc_url($replace).'">'.__('Profile', 'events-manager').'</a>';
						}
					}
					break;
				case '#_CONTACTMETA':
					if( !empty($placeholders[3][$key]) ){
						$replace = get_user_meta($this->event_owner, $placeholders[3][$key], true);
					}
					break;
				case '#_ATTENDEES':
					ob_start();
					$template = em_locate_template('placeholders/attendees.php', true, array('EM_Event'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_ATTENDEESLIST':
					ob_start();
					$template = em_locate_template('placeholders/attendeeslist.php', true, array('EM_Event'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_ATTENDEESPENDINGLIST':
					ob_start();
					$template = em_locate_template('placeholders/attendeespendinglist.php', true, array('EM_Event'=>$this));
					$replace = ob_get_clean();
					break;
				//Categories and Tags
				case '#_EVENTCATEGORIESIMAGES':
					ob_start();
					$template = em_locate_template('placeholders/eventcategoriesimages.php', true, array('EM_Event'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_EVENTTAGS':
					ob_start();
					$template = em_locate_template('placeholders/eventtags.php', true, array('EM_Event'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_CATEGORIES': //deprecated
				case '#_EVENTCATEGORIES':
					ob_start();
					$template = em_locate_template('placeholders/categories.php', true, array('EM_Event'=>$this));
					$replace = ob_get_clean();
					break;
				//Ical Stuff
				case '#_EVENTICALURL':
				case '#_EVENTICALLINK':
					$replace = $this->get_ical_url();
					if( $result == '#_EVENTICALLINK' ){
						$replace = '<a href="'.esc_url($replace).'">iCal</a>';
					}
					break;
				case '#_EVENTGCALURL':
				case '#_EVENTGCALLINK':
					//get dates in UTC/GMT time
					if($this->event_all_day && $this->event_start_date == $this->event_end_date){
						$dateStart	= get_gmt_from_date(date('Y-m-d H:i:s', $this->start), 'Ymd');
						$dateEnd	= get_gmt_from_date(date('Y-m-d H:i:s', $this->start + 60*60*24), 'Ymd');
					}else{
						$dateStart	= get_gmt_from_date(date('Y-m-d H:i:s', $this->start), 'Ymd\THis\Z');
						$dateEnd = get_gmt_from_date(date('Y-m-d H:i:s', $this->end), 'Ymd\THis\Z');
					}
					//build url
					$gcal_url = 'http://www.google.com/calendar/event?action=TEMPLATE&text=event_name&dates=start_date/end_date&details=post_content&location=location_name&trp=false&sprop=event_url&sprop=name:blog_name';
					$gcal_url = str_replace('event_name', urlencode($this->event_name), $gcal_url);
					$gcal_url = str_replace('start_date', urlencode($dateStart), $gcal_url);
					$gcal_url = str_replace('end_date', urlencode($dateEnd), $gcal_url);
					$gcal_url = str_replace('location_name', urlencode($this->output('#_LOCATION')), $gcal_url);
					$gcal_url = str_replace('blog_name', urlencode(get_bloginfo()), $gcal_url);
					$gcal_url = str_replace('event_url', urlencode($this->get_permalink()), $gcal_url);
					//calculate URL length so we know how much we can work with to make a description.
					if( !empty($this->post_excerpt) ){
						$gcal_url_description = $this->post_excerpt;
					}else{
						$matches = explode('<!--more', $this->post_content);
						$gcal_url_description = wp_kses_data($matches[0]);
					}
					$gcal_url_length = strlen($gcal_url) - 9;
					if( strlen($gcal_url_description) + $gcal_url_length > 1350 ){
						$gcal_url_description = substr($gcal_url_description, 0, 1380 - $gcal_url_length - 3 ).'...';
					}
					$gcal_url = str_replace('post_content', urlencode($gcal_url_description), $gcal_url);
					//get the final url
					$replace = $gcal_url;
					if( $result == '#_EVENTGCALLINK' ){
						$img_url = 'www.google.com/calendar/images/ext/gc_button2.gif';
						$img_url = is_ssl() ? 'https://'.$img_url:'http://'.$img_url;
						$replace = '<a href="'.esc_url($replace).'" target="_blank"><img src="'.esc_url($img_url).'" alt="0" border="0"></a>';
					}
					break;
				default:
					$replace = $full_result;
					break;
			}
			$replaces[$full_result] = apply_filters('em_event_output_placeholder', $replace, $this, $full_result, $target);
		}
		//sort out replacements so that during replacements shorter placeholders don't overwrite longer varieties.
		krsort($replaces);
		foreach($replaces as $full_result => $replacement){
			if( !in_array($full_result, array('#_NOTES','#_EVENTNOTES')) ){
				$event_string = str_replace($full_result, $replacement , $event_string );
			}else{
			    $new_placeholder = str_replace('#_', '__#', $full_result); //this will avoid repeated filters when locations/categories are parsed
			    $event_string = str_replace($full_result, $new_placeholder , $event_string );
				$desc_replace[$new_placeholder] = $replacement;
			}
		}
		//Time placeholders
		foreach($placeholders[1] as $result) {
			// matches all PHP START date and time placeholders
			if (preg_match('/^#[dDjlNSwzWFmMntLoYyaABgGhHisueIOPTZcrU]$/', $result)) {
				$replace = date_i18n(ltrim($result, "#"), $this->start);
				$replace = apply_filters('em_event_output_placeholder', $replace, $this, $result, $target);
				$event_string = str_replace($result, $replace, $event_string );
			}
			// matches all PHP END time placeholders for endtime
			if (preg_match('/^#@[dDjlNSwzWFmMntLoYyaABgGhHisueIOPTZcrU]$/', $result)) {
				$replace = date_i18n(ltrim($result, "#@"), $this->end);
				$replace = apply_filters('em_event_output_placeholder', $replace, $this, $result, $target);
				$event_string = str_replace($result, $replace, $event_string ); 
		 	}
		}
		//Now do dependent objects
		if( !empty($this->location_id) && $this->get_location()->location_status ){
			$event_string = $this->get_location()->output($event_string, $target);
		}else{
			$EM_Location = new EM_Location();
			$event_string = $EM_Location->output($event_string, $target);
		}
		
		if( get_option('dbem_categories_enabled') ){
    		//for backwards compat and easy use, take over the individual category placeholders with the frirst cat in th elist.
    		$EM_Categories = $this->get_categories();
    		if( count($EM_Categories->categories) > 0 ){
    			$EM_Category = $EM_Categories->get_first();
    		}
    		if( empty($EM_Category) ) $EM_Category = new EM_Category();
    		$event_string = $EM_Category->output($event_string, $target);
		}
		
		//Finally, do the event notes, so that previous placeholders don't get replaced within the content, which may use shortcodes
		if( !empty($desc_replace) ){
			foreach($desc_replace as $full_result => $replacement){
				$event_string = str_replace($full_result, $replacement , $event_string );
			}
		}
		
		//do some specific formatting
		//TODO apply this sort of formatting to any output() function
		if( $target == 'ical' ){
		    //strip html and escape characters
		    $event_string = str_replace('\\','\\\\',strip_tags($event_string));
		    $event_string = str_replace(';','\;',$event_string);
		    $event_string = str_replace(',','\,',$event_string);
		    //remove and define line breaks in ical format
		    $event_string = str_replace('\\\\n','\n',$event_string);
		    $event_string = str_replace("\r\n",'\n',$event_string);
		    $event_string = str_replace("\n",'\n',$event_string);
		}
		return apply_filters('em_event_output', $event_string, $this, $format, $target);
	}	
	
	/**********************************************************
	 * RECURRENCE METHODS
	 ***********************************************************/
	
	/**
	 * Returns true if this is a recurring event.
	 * @return boolean
	 */
	function is_recurring(){
		return $this->post_type == 'event-recurring' && get_option('dbem_recurrence_enabled');
	}	
	/**
	 * Will return true if this individual event is part of a set of events that recur
	 * @return boolean
	 */
	function is_recurrence(){
		return ( $this->event_id > 0 && $this->recurrence_id > 0 && get_option('dbem_recurrence_enabled') );
	}
	/**
	 * Returns if this is an individual event and is not a recurrence
	 * @return boolean
	 */
	function is_individual(){
		return ( !$this->is_recurring() && !$this->is_recurrence() );
	}
	
	/**
	 * Gets the event recurrence template, which is an EM_Event_Recurrence object (based off an event-recurring post)
	 * @return EM_Event_Recurrence
	 */
	function get_event_recurrence(){
		if(!$this->is_recurring()){
			return new EM_Event($this->recurrence_id); //remember, recurrence_id is a post!
		}else{
			return $this;
		}
	}
	
	function get_detach_url(){
		return admin_url().'admin.php?event_id='.$this->event_id.'&amp;action=event_detach&amp;_wpnonce='.wp_create_nonce('event_detach_'.get_current_user_id().'_'.$this->event_id);
	}
	
	function get_attach_url($recurrence_id){
		return admin_url().'admin.php?undo_id='.$recurrence_id.'&amp;event_id='.$this->event_id.'&amp;action=event_attach&amp;_wpnonce='.wp_create_nonce('event_attach_'.get_current_user_id().'_'.$this->event_id);
	}
	
	/**
	 * Returns if this is an individual event and is not recurring or a recurrence
	 * @return boolean
	 */
	function detach(){
		global $wpdb;
		if( $this->is_recurrence() && !$this->is_recurring() && $this->can_manage('edit_recurring_events','edit_others_recurring_events') ){
			//remove recurrence id from post meta and index table
			$url = $this->get_attach_url($this->recurrence_id);
			$wpdb->update(EM_EVENTS_TABLE, array('recurrence_id'=>0), array('event_id' => $this->event_id));
			update_post_meta($this->post_id, '_recurrence_id', 0);
			$this->feedback_message = __('Event detached.','events-manager') . ' <a href="'.$url.'">'.__('Undo','events-manager').'</a>';
			$this->recurrence_id = 0;
			return true;
		}
		$this->add_error(__('Event could not be detached.','events-manager'));
		return false;
	}
	
	/**
	 * Returns if this is an individual event and is not recurring or a recurrence
	 * @return boolean
	 */
	function attach($recurrence_id){
		global $wpdb;
		if( !$this->is_recurrence() && !$this->is_recurring() && is_numeric($recurrence_id) && $this->can_manage('edit_recurring_events','edit_others_recurring_events') ){
			//add recurrence id to post meta and index table
			$wpdb->update(EM_EVENTS_TABLE, array('recurrence_id'=>$recurrence_id), array('event_id' => $this->event_id));
			update_post_meta($this->post_id, '_recurrence_id', $recurrence_id);
			$this->feedback_message = __('Event re-attached to recurrence.','events-manager');
			return true;
		}
		$this->add_error(__('Event could not be attached.','events-manager'));
		return false;
	}
	
	/**
	 * Saves events and replaces old ones. Returns true if sucecssful or false if not.
	 * @return boolean
	 */
	function save_events() {
		global $wpdb;
		$event_ids = $post_ids = array();
		if( $this->can_manage('edit_events','edit_others_events') && $this->is_published() ){
			do_action('em_event_save_events_pre', $this); //actions/filters only run if event is recurring
			//Make template event index, post, and meta (and we just change event dates)
			$event = $this->to_array(true); //event template - for index
			$event['event_attributes'] = serialize($event['event_attributes']);
			$post_fields = $wpdb->get_row('SELECT * FROM '.$wpdb->posts.' WHERE ID='.$this->post_id, ARRAY_A); //post to copy
			$post_name = $post_fields['post_name']; //save post slug since we'll be using this 
			$post_fields['post_type'] = 'event'; //make sure we'll save events, not recurrence templates
			$meta_fields_map = $wpdb->get_results('SELECT meta_key,meta_value FROM '.$wpdb->postmeta.' WHERE post_id='.$this->post_id, ARRAY_A);
			$meta_fields = array();
			//convert meta_fields into a cleaner array
			foreach($meta_fields_map as $meta_data){
				$meta_fields[$meta_data['meta_key']] = $meta_data['meta_value'];
			}
			//remove id and we have a event template to feed to wpdb insert
			unset($event['event_id']); 
			unset($post_fields['ID']);
			//remove recurrence meta info we won't need in events
			foreach( array_keys($this->recurrence_fields) as $recurrence_field){
				unset($event[$recurrence_field]);
				unset($meta_fields['_'.$recurrence_field]);
			}		
			$event['event_date_created'] = current_time('mysql'); //since the recurrences are recreated
			unset($event['event_date_modified']);
			//Set the recurrence ID
			$event['recurrence_id'] = $meta_fields['_recurrence_id'] = $this->event_id;
			$event['recurrence'] = $meta_fields['_recurrence'] = 0;
			//Let's start saving!
			$this->delete_events(); //Delete old events beforehand, this will change soon
			$event_saves = array();
			$matching_days = $this->get_recurrence_days(); //Get days where events recur
			if( count($matching_days) > 0 ){
				//first save event post data
				$recurring_date_format = apply_filters('em_event_save_events_format', 'Y-m-d');
				foreach( $matching_days as $day ) {
					//rewrite post fields if needed
					$post_fields['post_name'] = $event['event_slug'] = $meta_fields['_event_slug'] = apply_filters('em_event_save_events_slug', $post_name.'-'.date($recurring_date_format, $day), $post_fields, $day, $matching_days, $this);
					//adjust certain meta information
					$event['event_start_date'] = $meta_fields['_event_start_date'] = date("Y-m-d", $day);
					$meta_fields['_start_ts'] = strtotime($event['event_start_date'].' '.$event['event_start_time']);
					if( !empty($event['recurrence_rsvp_days']) && is_numeric($event['recurrence_rsvp_days']) ){
						$event_rsvp_days = $event['recurrence_rsvp_days'] >= 0 ? '+'. $event['recurrence_rsvp_days']: $event['recurrence_rsvp_days'];
			 			$event_rsvp_date = date('Y-m-d',  strtotime($event_rsvp_days.' days', $meta_fields['_start_ts']));
			 			$event['event_rsvp_date'] = $meta_fields['_event_rsvp_date'] = $event_rsvp_date;
						$event['event_rsvp_time'] = $meta_fields['_event_rsvp_time'] = $event['event_rsvp_time'];
					}else{
						$event['event_rsvp_date'] = $meta_fields['_event_rsvp_date'] = $event['event_start_date'];
						$event['event_rsvp_time'] = $meta_fields['_event_rsvp_time'] = $event['event_rsvp_time'];
					}
					if($this->recurrence_days > 0){
						$event['event_end_date'] = $meta_fields['_event_end_date'] = date("Y-m-d", $meta_fields['_start_ts'] + ($this->recurrence_days * 60*60*24));
					}else{
						$event['event_end_date'] = $meta_fields['_event_end_date'] = $event['event_start_date'];
					}	
					$meta_fields['_end_ts'] = strtotime($event['event_end_date'].' '.$event['event_end_time']);
					//create the event
					if( $wpdb->insert($wpdb->posts, $post_fields ) ){
						$event['post_id'] = $meta_fields['_post_id'] = $post_id = $post_ids[] = $wpdb->insert_id; //post id saved into event and also as a var for later user
						// Set GUID and event slug as per wp_insert_post
						$wpdb->update( $wpdb->posts, array( 'guid' => get_permalink( $post_id ) ), array('ID'=>$post_id) );
				 		//insert into events index table
						$event_saves[] = $wpdb->insert(EM_EVENTS_TABLE, $event);
						$event_ids[$post_id] = $event_id = $wpdb->insert_id;
						$event_dates[$event_id] = $meta_fields['_start_ts'];
				 		//create the meta inserts for each event
				 		$meta_fields['_event_id'] = $event_id;
				 		foreach($meta_fields as $meta_key => $meta_val){
				 			$meta_inserts[] = $wpdb->prepare("(%d, %s, %s)", array($post_id, $meta_key, $meta_val));
				 		}
					}else{
						$event_saves[] = false;
					}
					//if( EM_DEBUG ){ echo "Entering recurrence " . date("D d M Y", $day)."<br/>"; }
			 	}
			 	//insert the metas in one go, faster than one by one
			 	if( count($meta_inserts) > 0 ){
				 	$result = $wpdb->query("INSERT INTO ".$wpdb->postmeta." (post_id,meta_key,meta_value) VALUES ".implode(',',$meta_inserts));
				 	if($result === false){
				 		$this->add_error('There was a problem adding custom fields to your recurring events.','events-manager');
				 	}
			 	}
			 	//copy the event tags and categories
			 	$taxonomies = self::get_taxonomies();
			 	foreach($taxonomies as $tax_name => $tax_data){
			 		//In MS Global mode, we also save category meta information for global lookups so we use our objects
					if( EM_MS_GLOBAL && $tax_name == 'category' ){
						//use EM_Categories as this is a special taxonomy in global mode
						$EM_Categories = new EM_Categories($this);
						//we save index data for each category in in MS Global mode
						foreach($event_ids as $post_id => $event_id){
							foreach( $EM_Categories->categories as $EM_Category ){
								$EM_Categories->event_id = $event_id;
								$EM_Categories->post_id = $post_id;
								$EM_Categories->save();
							}
						}
					}else{
						//general taxonomies including event tags
					 	$terms = get_the_terms( $this->post_id, $tax_data['name']);
				 		$term_slugs = array();
				 		if( is_array($terms) ){
							foreach($terms as $term){
								if( !empty($term->slug) ) $term_slugs[] = $term->slug; //save of category will soft-fail if slug is empty
							}
				 		}
					 	foreach($post_ids as $post_id){
							wp_set_object_terms($post_id, $term_slugs, $tax_data['name']);
					 	}
					}
			 	}
			 	//featured images
			 	if( !empty($this->attributes['thumbnail_id']) ){
			 		$image_inserts = array();
			 		foreach($post_ids as $post_ids){
			 			$image_inserts[] = "({$this->post_id}, '_thumbnail_id', {$this->attributes['thumbnail_id']})";
			 		}
			 		if( count($image_inserts) > 0 ){
				 		$wpdb->query('INSERT INTO '.$wpdb->postmeta.' (post_id, meta_key, meta_value) VALUES '.implode(', ', $image_inserts));
			 		}
			 	}
			 	//MS Global Categories
				if( EM_MS_GLOBAL ){
					foreach( self::get_categories() as $EM_Category ){
						foreach($event_ids as $event_id){
							$wpdb->insert(EM_META_TABLE, array('meta_value'=>$EM_Category->term_id,'object_id'=>$event_id,'meta_key'=>'event-category'));
						}
					}
				}
			 	//now, save booking info for each event
			 	if( $this->event_rsvp ){
			 		$meta_inserts = array();
			 		foreach($this->get_tickets() as $EM_Ticket){
			 			/* @var $EM_Ticket EM_Ticket */
			 			//get array, modify event id and insert
			 			$ticket = $EM_Ticket->to_array();
			 			//empty cut-off dates of ticket, add them at per-event level
			 			unset($ticket['ticket_start']); unset($ticket['ticket_end']);
		 				if( !empty($ticket['ticket_meta']['recurrences']) ){
		 					$ticket_meta_recurrences = $ticket['ticket_meta']['recurrences'];
		 					unset($ticket['ticket_meta']['recurrences']);
		 				}
		 				//unset id
			 			unset($ticket['ticket_id']);
			 			//clean up ticket values
			 			foreach($ticket as $k => $v){
			 				if( empty($v) && $k != 'ticket_name' ){ 
			 					$ticket[$k] = 'NULL';
			 				}else{
			 					$data_type = !empty($EM_Ticket->fields[$k]['type']) ? $EM_Ticket->fields[$k]['type']:'%s';
			 					if(is_array($ticket[$k])) $v = serialize($ticket[$k]);
			 					$ticket[$k] = $wpdb->prepare($data_type,$v);
			 				}
			 			}
			 			foreach($event_ids as $event_id){
			 				$ticket['event_id'] = $event_id;
			 				$ticket['ticket_start'] = $ticket['ticket_end'] = 'NULL';
			 				//sort out cut-of dates
			 				if( !empty($ticket_meta_recurrences) ){
			 					if( array_key_exists('start_days', $ticket_meta_recurrences) ){
			 						$ticket_start_days = $ticket_meta_recurrences['start_days'] >= 0 ? '+'. $ticket_meta_recurrences['start_days']: $ticket_meta_recurrences['start_days'];
			 						$ticket_start_date = date('Y-m-d',  strtotime($ticket_start_days.' days', $event_dates[$event_id]));
			 						$ticket['ticket_start'] = "'". $ticket_start_date . ' '. $ticket_meta_recurrences['start_time'] ."'";
			 					}
			 					if( array_key_exists('end_days', $ticket_meta_recurrences) ){
			 						$ticket_end_days = $ticket_meta_recurrences['end_days'] >= 0 ? '+'. $ticket_meta_recurrences['end_days']: $ticket_meta_recurrences['end_days'];
			 						$ticket_end_date = date('Y-m-d',  strtotime($ticket_end_days.' days', $event_dates[$event_id]));
			 						$ticket['ticket_end'] = "'". $ticket_end_date . ' '. $ticket_meta_recurrences['end_time'] . "'";
			 					}
			 				}
			 				//add insert data
			 				$meta_inserts[] = "(".implode(",",$ticket).")";
			 			}
			 		}
			 		$keys = "(".implode(",",array_keys($ticket)).")";
			 		$values = implode(',',$meta_inserts);
			 		$sql = "INSERT INTO ".EM_TICKETS_TABLE." $keys VALUES $values";
			 		$result = $wpdb->query($sql);
			 	}
			}else{
		 		$this->add_error('You have not defined a date range long enough to create a recurrence.','events-manager');
		 		$result = false;
		 	}
		 	return apply_filters('em_event_save_events', !in_array(false, $event_saves) && $result !== false, $this, $event_ids, $post_ids);
		}
		return apply_filters('em_event_save_events', false, $this, $event_ids, $post_ids);
	}
	
	/**
	 * Removes all reoccurring events.
	 * @param $recurrence_id
	 * @return null
	 */
	function delete_events(){
		global $wpdb;
		do_action('em_event_delete_events_pre', $this);
		//So we don't do something we'll regret later, we could just supply the get directly into the delete, but this is safer
		$result = false;
		if( $this->can_manage('delete_events', 'delete_others_events') ){
			//delete events from em_events table
			$events_array = EM_Events::get( array('recurrence_id'=>$this->event_id, 'scope'=>'all', 'status'=>'everything' ) );
			foreach($events_array as $EM_Event){
				/* @var $EM_Event EM_Event */
				if($EM_Event->recurrence_id == $this->event_id){
					$EM_Event->delete(true);
				}
			}			
		}
		return apply_filters('delete_events', $result, $this, $events_array);
	}
	
	/**
	 * Returns the days that match the recurrance array passed (unix timestamps)
	 * @param array $recurrence
	 * @return array
	 */
	function get_recurrence_days(){			
		$start_date = strtotime($this->event_start_date);
		$end_date = strtotime($this->event_end_date);
				
		$weekdays = explode(",", $this->recurrence_byday); //what days of the week (or if monthly, one value at index 0)
		 
		$matching_days = array(); 
		$aDay = 86400;  // a day in seconds
		$aWeek = $aDay * 7;		 
			
		//TODO can this be optimized?
		switch ( $this->recurrence_freq ){
			case 'daily':
				//If daily, it's simple. Get start date, add interval timestamps to that and create matching day for each interval until end date.
				$current_date = $start_date;
				while( $current_date <= $end_date ){
					$matching_days[] = $current_date;
					$current_date = $current_date + ($aDay * $this->recurrence_interval);
				}
				break;
			case 'weekly':
				//sort out week one, get starting days and then days that match time span of event (i.e. remove past events in week 1)
				$start_of_week = get_option('start_of_week'); //Start of week depends on WordPress
				//first, get the start of this week as timestamp
				$event_start_day = date('w', $start_date);
				//then get the timestamps of weekdays during this first week, regardless if within event range
				$start_weekday_dates = array(); //Days in week 1 where there would events, regardless of event date range
				for($i = 0; $i < 7; $i++){
					$weekday_date = $start_date+($aDay*$i); //the date of the weekday we're currently checking
					$weekday_day = date('w',$weekday_date); //the day of the week we're checking, taking into account wp start of week setting

					if( in_array( $weekday_day, $weekdays) ){
						$start_weekday_dates[] = $weekday_date; //it's in our starting week day, so add it
					}
				}					
				//for each day of eventful days in week 1, add 7 days * weekly intervals
				foreach ($start_weekday_dates as $weekday_date){
					//Loop weeks by interval until we reach or surpass end date
					while($weekday_date <= $end_date){
						if( $weekday_date >= $start_date && $weekday_date <= $end_date ){
							$matching_days[] = $weekday_date;
						}
						$weekday_date = $weekday_date + ($aWeek *  $this->recurrence_interval);
					}
				}//done!
				break;  
			case 'monthly':
				//loop months starting this month by intervals
				$current_arr = getdate($start_date);
				$end_arr = getdate($end_date);
				$end_month_date = strtotime( date('Y-m-t', $end_date) ); //End date on last day of month
				$current_date = strtotime( date('Y-m-1', $start_date) ); //Start date on first day of month
				while( $current_date <= $end_month_date ){
					$last_day_of_month = date('t', $current_date);
					//Now find which day we're talking about
					$current_week_day = date('w',$current_date);
					$matching_month_days = array();
					//Loop through days of this years month and save matching days to temp array
					for($day = 1; $day <= $last_day_of_month; $day++){
						if((int) $current_week_day == $this->recurrence_byday){
							$matching_month_days[] = $day;
						}
						$current_week_day = ($current_week_day < 6) ? $current_week_day+1 : 0;							
					}
					//Now grab from the array the x day of the month
					$matching_day = ($this->recurrence_byweekno > 0) ? $matching_month_days[$this->recurrence_byweekno-1] : array_pop($matching_month_days);
					$matching_date = strtotime(date('Y-m',$current_date).'-'.$matching_day);
					if($matching_date >= $start_date && $matching_date <= $end_date){
						$matching_days[] = $matching_date;
					}
					//add the number of days in this month to make start of next month
					$current_arr['mon'] += $this->recurrence_interval;
					if($current_arr['mon'] > 12){
						//FIXME this won't work if interval is more than 12
						$current_arr['mon'] = $current_arr['mon'] - 12;
						$current_arr['year']++;
					}
					$current_date = strtotime("{$current_arr['year']}-{$current_arr['mon']}-1"); 
				}
				break;
			case 'yearly':
				//If yearly, it's simple. Get start date, add interval timestamps to that and create matching day for each interval until end date.
				$month = date('m', $this->start);
				$day = date('d',$this->start);
				$year = date('Y',$this->start);
				$end_year = date('Y',$this->end); 
				if( @mktime(0,0,0,$day,$month,$end_year) < $this->end ) $end_year--;
				while( $year <= $end_year ){
					$matching_days[] = mktime(0,0,0,$month,$day,$year);
					$year++;
				}			
				break;
		}
		sort($matching_days);
		return apply_filters('em_events_get_recurrence_days', $matching_days, $this);
	}
	
	/**
	 * If event is recurring, set recurrences to same status as template
	 * @param $status
	 */
	function set_status_events($status){
		//give sub events same status
		$events_array = EM_Events::get( array('recurrence_id'=>$this->post_id, 'scope'=>'all', 'status'=>false ) );
		foreach($events_array as $EM_Event){
			/* @var $EM_Event EM_Event */
			if($EM_Event->recurrence_id == $this->event_id){
				$EM_Event->set_status($status);
			}
		}
	}
	
	/**
	 * Returns a string representation of this recurrence. Will return false if not a recurrence
	 * @return string
	 */
	function get_recurrence_description() {
		$EM_Event_Recurring = $this->get_event_recurrence(); 
		$recurrence = $this->to_array();
		$weekdays_name = array(__('Sunday', 'events-manager'),__('Monday', 'events-manager'),__('Tuesday', 'events-manager'),__('Wednesday', 'events-manager'),__('Thursday', 'events-manager'),__('Friday', 'events-manager'),__('Saturday', 'events-manager'));
		$monthweek_name = array('1' => __('the first %s of the month', 'events-manager'),'2' => __('the second %s of the month', 'events-manager'), '3' => __('the third %s of the month', 'events-manager'), '4' => __('the fourth %s of the month', 'events-manager'), '-1' => __('the last %s of the month', 'events-manager'));
		$output = sprintf (__('From %1$s to %2$s', 'events-manager'),  $EM_Event_Recurring->event_start_date, $EM_Event_Recurring->event_end_date).", ";
		if ($EM_Event_Recurring->recurrence_freq == 'daily')  {
			$freq_desc =__('everyday', 'events-manager');
			if ($EM_Event_Recurring->recurrence_interval > 1 ) {
				$freq_desc = sprintf (__("every %s days", 'events-manager'), $EM_Event_Recurring->recurrence_interval);
			}
		}elseif ($EM_Event_Recurring->recurrence_freq == 'weekly')  {
			$weekday_array = explode(",", $EM_Event_Recurring->recurrence_byday);
			$natural_days = array();
			foreach($weekday_array as $day){
				array_push($natural_days, $weekdays_name[$day]);
			}
			$output .= implode(", ", $natural_days);
			$freq_desc = " " . __("every week", 'events-manager');
			if ($EM_Event_Recurring->recurrence_interval > 1 ) {
				$freq_desc = " ".sprintf (__("every %s weeks", 'events-manager'), $EM_Event_Recurring->recurrence_interval);
			}
			
		}elseif ($EM_Event_Recurring->recurrence_freq == 'monthly')  {
			$weekday_array = explode(",", $EM_Event_Recurring->recurrence_byday);
			$natural_days = array();
			foreach($weekday_array as $day){
				if( !empty($day) ){
					array_push($natural_days, $weekdays_name[$day]);
				}
			}
			$freq_desc = sprintf (($monthweek_name[$EM_Event_Recurring->recurrence_byweekno]), implode(" and ", $natural_days));
			if ($EM_Event_Recurring->recurrence_interval > 1 ) {
				$freq_desc .= ", ".sprintf (__("every %s months",'events-manager'), $EM_Event_Recurring->recurrence_interval);
			}
		}elseif ($EM_Event_Recurring->recurrence_freq == 'yearly')  {
			$freq_desc .= __("every year", 'events-manager');
			if ($EM_Event_Recurring->recurrence_interval > 1 ) {
				$freq_desc .= sprintf (__("every %s years",'events-manager'), $EM_Event_Recurring->recurrence_interval);
			}
		}else{
			$freq_desc = "[ERROR: corrupted database record]";
		}
		$output .= $freq_desc;
		return  $output;
	}	
	
	/**********************************************************
	 * UTILITIES
	 ***********************************************************/
	
	function __get( $var ){
	    //get the modified or created date from the DB only if requested, and save to object
	    if( $var == 'event_date_modified' || $var == 'event_date_created'){
	        global $wpdb;
	        $row = $wpdb->get_row($wpdb->prepare("SELECT event_date_created, event_date_modified FROM ".EM_EVENTS_TABLE.' WHERE event_id=%s', $this->event_id));
	        if( $row ){
	            $this->event_date_modified = $row->event_date_modified;
	            $this->event_date_created = $row->event_date_created;
	            return $this->$var;
	        }
	    }
	    return null;
	}
	
	/**
	 * Can the user manage this? 
	 */
	function can_manage( $owner_capability = false, $admin_capability = false, $user_to_check = false ){
		if( $this->event_id == '' && !is_user_logged_in() && get_option('dbem_events_anonymous_submissions') ){
			$user_to_check = get_option('dbem_events_anonymous_user');
		}
		return apply_filters('em_event_can_manage', parent::can_manage($owner_capability, $admin_capability, $user_to_check), $this, $owner_capability, $admin_capability, $user_to_check);
	}
}

//TODO placeholder targets filtering could be streamlined better
/**
 * This is a temporary filter function which mimicks the old filters in the old 2.x placeholders function
 * @param string $result
 * @param EM_Event $event
 * @param string $placeholder
 * @param string $target
 * @return mixed
 */
function em_event_output_placeholder($result,$event,$placeholder,$target='html'){
	if( $target == 'raw' ) return $result;
	if( in_array($placeholder, array("#_EXCERPT",'#_EVENTEXCERPT', "#_LOCATIONEXCERPT")) && $target == 'html' ){
		$result = apply_filters('dbem_notes_excerpt', $result);
	}elseif( $placeholder == '#_CONTACTEMAIL' && $target == 'html' ){
		$result = em_ascii_encode($event->get_contact()->user_email);
	}elseif( in_array($placeholder, array('#_EVENTNOTES','#_NOTES','#_DESCRIPTION','#_LOCATIONNOTES','#_CATEGORYNOTES','#_CATEGORYDESCRIPTION')) ){
		if($target == 'rss'){
			$result = apply_filters('dbem_notes_rss', $result);
			$result = apply_filters('the_content_rss', $result);
		}elseif($target == 'map'){
			$result = apply_filters('dbem_notes_map', $result);
		}elseif($target == 'ical'){
			$result = apply_filters('dbem_notes_ical', $result);
		}elseif ($target == "email"){    
			$result = apply_filters('dbem_notes_email', $result); 
	  	}else{ //html
			$result = apply_filters('dbem_notes', $result);
		}
	}elseif( in_array($placeholder, array("#_NAME",'#_LOCATION','#_TOWN','#_ADDRESS','#_LOCATIONNAME',"#_EVENTNAME","#_LOCATIONNAME",'#_CATEGORY')) ){
		if ($target == "rss"){
			$result = apply_filters('dbem_general_rss', $result);
	  	}elseif ($target == "ical"){    
			$result = apply_filters('dbem_general_ical', $result); 
	  	}elseif ($target == "email"){    
			$result = apply_filters('dbem_general_email', $result); 
	  	}else{ //html
			$result = apply_filters('dbem_general', $result); 
	  	}				
	}
	return $result;
}
add_filter('em_category_output_placeholder','em_event_output_placeholder',1,4);
add_filter('em_event_output_placeholder','em_event_output_placeholder',1,4);
add_filter('em_location_output_placeholder','em_event_output_placeholder',1,4);
// FILTERS
// filters for general events field (corresponding to those of  "the _title")
add_filter('dbem_general', 'wptexturize');
add_filter('dbem_general', 'convert_chars');
add_filter('dbem_general', 'trim');
// filters for the notes field in html (corresponding to those of  "the _content")
add_filter('dbem_notes', 'wptexturize');
add_filter('dbem_notes', 'convert_smilies');
add_filter('dbem_notes', 'convert_chars');
add_filter('dbem_notes', 'wpautop');
add_filter('dbem_notes', 'prepend_attachment');
add_filter('dbem_notes', 'do_shortcode');
// filters for the notes field in html (corresponding to those of  "the _content")
add_filter('dbem_notes_excerpt', 'wptexturize');
add_filter('dbem_notes_excerpt', 'convert_smilies');
add_filter('dbem_notes_excerpt', 'convert_chars');
add_filter('dbem_notes_excerpt', 'wpautop');
add_filter('dbem_notes_excerpt', 'prepend_attachment');
add_filter('dbem_notes_excerpt', 'do_shortcode');
// RSS content filter
add_filter('dbem_notes_rss', 'convert_chars', 8);
add_filter('dbem_general_rss', 'esc_html', 8);
// Notes map filters
add_filter('dbem_notes_map', 'convert_chars', 8);
add_filter('dbem_notes_map', 'js_escape');
//embeds support if using placeholders
if ( is_object($GLOBALS['wp_embed']) ){
	add_filter( 'dbem_notes', array( $GLOBALS['wp_embed'], 'run_shortcode' ), 8 );
	add_filter( 'dbem_notes', array( $GLOBALS['wp_embed'], 'autoembed' ), 8 );
}

/**
 * This function replaces the default gallery shortcode, so it can check if this is a recurring event recurrence and pass on the parent post id as the default post. 
 * @param array $attr
 */
function em_event_gallery_override( $attr = array() ){
	global $post;
	if( $post->post_type == EM_POST_TYPE_EVENT && empty($attr['id']) && empty($attr['ids']) ){
		//no id specified, so check if it's recurring and override id with recurrence template post id
		$EM_Event = em_get_event($post->ID, 'post_id');
		if( $EM_Event->is_recurrence() ){
			$attr['id'] = $EM_Event->get_event_recurrence()->post_id;
		}
	}
	return gallery_shortcode($attr);
}
function em_event_gallery_override_init(){
	remove_shortcode('gallery');
	add_shortcode('gallery', 'em_event_gallery_override');
}
add_action('init','em_event_gallery_override_init', 1000); //so that plugins like JetPack don't think we're overriding gallery, we're not i swear!
?>