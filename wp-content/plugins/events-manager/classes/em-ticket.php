<?php
class EM_Ticket extends EM_Object{
	//DB Fields
	var $ticket_id;
	var $event_id;
	var $ticket_name;
	var $ticket_description;
	var $ticket_price;
	var $ticket_start;
	var $ticket_end;
	var $ticket_min;
	var $ticket_max;
	var $ticket_spaces = 10;
	var $ticket_members = false;
	var $ticket_members_roles = array();
	var $ticket_guests = false;
	var $ticket_required = false;
	var $ticket_meta = array();
	var $fields = array(
		'ticket_id' => array('name'=>'id','type'=>'%d'),
		'event_id' => array('name'=>'event_id','type'=>'%d'),
		'ticket_name' => array('name'=>'name','type'=>'%s'),
		'ticket_description' => array('name'=>'description','type'=>'%s','null'=>1),
		'ticket_price' => array('name'=>'price','type'=>'%f','null'=>1),
		'ticket_start' => array('name'=>'start','type'=>'%s','null'=>1),
		'ticket_end' => array('name'=>'end','type'=>'%s','null'=>1),
		'ticket_min' => array('name'=>'min','type'=>'%s','null'=>1),
		'ticket_max' => array('name'=>'max','type'=>'%s','null'=>1),
		'ticket_spaces' => array('name'=>'spaces','type'=>'%s','null'=>1),
		'ticket_members' => array('name'=>'members','type'=>'%d','null'=>1),
		'ticket_members_roles' => array('name'=>'ticket_members_roles','type'=>'%s','null'=>1),
		'ticket_guests' => array('name'=>'guests','type'=>'%d','null'=>1),
		'ticket_required' => array('name'=>'required','type'=>'%d','null'=>1),
		'ticket_meta' => array('name'=>'ticket_meta','type'=>'%s','null'=>1)			
	);
	//Other Vars
	/**
	 * Contains only bookings belonging to this ticket.
	 * @var EM_Booking
	 */
	var $bookings = array();
	var $required_fields = array('ticket_name');
	var $start_timestamp;
	var $end_timestamp;
	/**
	 * is this ticket limited by spaces allotted to this ticket? false if no limit (i.e. the events general limit of seats)
	 * @var unknown_type
	 */
	var $spaces_limit = true;
	
	/**
	 * Creates ticket object and retreives ticket data (default is a blank ticket object). Accepts either array of ticket data (from db) or a ticket id.
	 * @param mixed $ticket_data
	 * @return null
	 */
	function __construct( $ticket_data = false ){
		$this->ticket_name = __('Standard Ticket','events-manager');
		$ticket = array();
		if( $ticket_data !== false ){
			//Load ticket data
			if( is_array($ticket_data) ){
				$ticket = $ticket_data;
			}elseif( is_numeric($ticket_data) ){
				//Retreiving from the database		
				global $wpdb;
				$sql = "SELECT * FROM ". EM_TICKETS_TABLE ." WHERE ticket_id ='$ticket_data'";   
			  	$ticket = $wpdb->get_row($sql, ARRAY_A);
			}
			//Save into the object
			$this->to_object($ticket);
			//serialized arrays
			$this->ticket_meta = (!empty($ticket['ticket_meta'])) ? maybe_unserialize($ticket['ticket_meta']):array();
			$this->ticket_members_roles = maybe_unserialize($this->ticket_members_roles);
			if( !is_array($this->ticket_members_roles) ) $this->ticket_members_roles = array();
			//timestamps
			$this->start_timestamp = (!empty($ticket['ticket_start'])) ? strtotime($ticket['ticket_start'], current_time('timestamp')):false;
			$this->end_timestamp = (!empty($ticket['ticket_end'])) ? strtotime($ticket['ticket_end'], current_time('timestamp')):false;
			//sort out recurrence meta to save extra empty() checks, the 'true' cut-off info is here for the ticket if part of a recurring event
			if( !empty($this->ticket_meta['recurrences']) ){
				if( !array_key_exists('start_days', $this->ticket_meta['recurrences']) ) $this->ticket_meta['recurrences']['start_days'] = false;
				if( !array_key_exists('start_time', $this->ticket_meta['recurrences']) ) $this->ticket_meta['recurrences']['start_time'] = false;
				if( !array_key_exists('end_days', $this->ticket_meta['recurrences']) ) $this->ticket_meta['recurrences']['end_days'] = false;
				if( !array_key_exists('end_time', $this->ticket_meta['recurrences']) ) $this->ticket_meta['recurrences']['end_time'] = false;
			}
		}
		$this->compat_keys();
		do_action('em_ticket',$this, $ticket_data, $ticket);
	}
	
	function get_notes(){
		global $wpdb;
		if( !is_array($this->notes) && !empty($this->ticket_id) ){
		  	$notes = $wpdb->get_results("SELECT * FROM ". EM_META_TABLE ." WHERE meta_key='ticket-note' AND object_id ='{$this->ticket_id}'", ARRAY_A);
		  	foreach($notes as $note){
		  		$this->ticket_id[] = unserialize($note['meta_value']);
		  	}
		}elseif( empty($this->booking_id) ){
			$this->notes = array();
		}
		return $this->notes;
	}
	
	/**
	 * Saves the ticket into the database, whether a new or existing ticket
	 * @return boolean
	 */
	function save(){
		global $wpdb;
		$table = EM_TICKETS_TABLE;
		do_action('em_ticket_save_pre',$this);
		//First the person
		if($this->validate() && $this->can_manage() ){			
			//Now we save the ticket
			$data = $this->to_array(true); //add the true to remove the nulls
			if( !empty($data['ticket_meta']) ) $data['ticket_meta'] = serialize($data['ticket_meta']);
			if( !empty($data['ticket_members_roles']) ) $data['ticket_members_roles'] = serialize($data['ticket_members_roles']);
			if($this->ticket_id != ''){
				//since currently wpdb calls don't accept null, let's build the sql ourselves.
				$set_array = array();
				foreach( $this->fields as $field_name => $field ){
					if( empty( $data[$field_name]) && $field['null'] ){
						$set_array[] = "{$field_name}=NULL";
					}else{
						$set_array[] = "{$field_name}='".esc_sql($data[$field_name])."'";						
					}
				}
				$sql = "UPDATE $table SET ".implode(', ', $set_array)." WHERE ticket_id={$this->ticket_id}";
				$result = $wpdb->query($sql);
				$this->feedback_message = __('Changes saved','events-manager');
			}else{
				//TODO better error handling
				$result = $wpdb->insert($table, $data, $this->get_types($data));
			    $this->ticket_id = $wpdb->insert_id;
				$this->feedback_message = __('Ticket created','events-manager'); 
			}
			if( $result === false ){
				$this->feedback_message = __('There was a problem saving the ticket.', 'events-manager');
				$this->errors[] = __('There was a problem saving the ticket.', 'events-manager');
			}
			$this->compat_keys();
			return apply_filters('em_ticket_save', ( count($this->errors) == 0 ), $this);
		}else{
			$this->feedback_message = __('There was a problem saving the ticket.', 'events-manager');
			$this->errors[] = __('There was a problem saving the ticket.', 'events-manager');
			return apply_filters('em_ticket_save', false, $this);
		}
		return true;
	}
	
	/**
	 * Get posted data and save it into the object (not db)
	 * @return boolean
	 */
	function get_post($post = array()){
		//We are getting the values via POST or GET
		global $allowedposttags;
		if( empty($post) ){
		    $post = $_REQUEST;
		}
		do_action('em_location_get_post_pre', $this, $post);
		$this->ticket_id = ( !empty($post['ticket_id']) && is_numeric($post['ticket_id']) ) ? $post['ticket_id']:'';
		$this->event_id = ( !empty($post['event_id']) && is_numeric($post['event_id']) ) ? $post['event_id']:'';
		$this->ticket_name = ( !empty($post['ticket_name']) ) ? wp_kses_data(stripslashes($post['ticket_name'])):'';
		$this->ticket_description = ( !empty($post['ticket_description']) ) ? wp_kses(stripslashes($post['ticket_description']), $allowedposttags):'';
		//spaces and limits
		$this->ticket_min = ( !empty($post['ticket_min']) && is_numeric($post['ticket_min']) ) ? $post['ticket_min']:'';
		$this->ticket_max = ( !empty($post['ticket_max']) && is_numeric($post['ticket_max']) ) ? $post['ticket_max']:'';
		$this->ticket_spaces = ( !empty($post['ticket_spaces']) && is_numeric($post['ticket_spaces']) ) ? $post['ticket_spaces']:10;
		//Sort out date/time limits
		$this->ticket_price = ( !empty($post['ticket_price']) ) ? wp_kses_data($post['ticket_price']):'';
		$this->ticket_start = ( !empty($post['ticket_start']) ) ? wp_kses_data($post['ticket_start']):'';
		$this->ticket_end = ( !empty($post['ticket_end']) ) ? wp_kses_data($post['ticket_end']):'';
		if( !empty($post['ticket_start_time']) && !empty($this->ticket_start) ) $this->ticket_start .= ' '. $this->sanitize_time($post['ticket_start_time']);
		if( !empty($post['ticket_end_time']) && !empty($this->ticket_end) ) $this->ticket_end .= ' '. $this->sanitize_time($post['ticket_end_time']);
		$this->start_timestamp = ( !empty($post['ticket_start']) ) ? strtotime($this->ticket_start):'';
		$this->end_timestamp = ( !empty($post['ticket_end']) ) ? strtotime($this->ticket_end):'';
		//sort out user availability restrictions
		$this->ticket_members = ( !empty($post['ticket_type']) && $post['ticket_type'] == 'members' ) ? 1:0;
		$this->ticket_guests = ( !empty($post['ticket_type']) && $post['ticket_type'] == 'guests' ) ? 1:0;
		$this->ticket_members_roles = array();
		if( $this->ticket_members && !empty($post['ticket_members_roles']) && is_array($post['ticket_members_roles']) ){
			$WP_Roles = new WP_Roles();
			foreach($WP_Roles->roles as $role => $role_data ){
				if( in_array($role, $post['ticket_members_roles']) ){
					$this->ticket_members_roles[] = $role;
				}
			}
		}
		$this->ticket_required = ( !empty($post['ticket_required']) ) ? 1:0;
		//if event is recurring, store start/end restrictions of this ticket, which are determined by number of days before (negative number) or after (positive number) the event start date
		if($this->get_event()->is_recurring()){
			//start of ticket cut-off
			if( array_key_exists('ticket_start_recurring_days', $post) && is_numeric($post['ticket_start_recurring_days']) ){
				if( !empty($post['ticket_start_recurring_when']) && $post['ticket_start_recurring_when'] == 'after' ){
					$this->ticket_meta['recurrences']['start_days'] = absint($post['ticket_start_recurring_days']);
					$this->ticket_start = date('Y-m-d', strtotime('+' . $this->ticket_meta['recurrences']['start_days'] . ' days', $this->get_event()->start));
				}else{ //by default the start date is the point of reference
					$this->ticket_meta['recurrences']['start_days'] = absint($post['ticket_start_recurring_days']) * -1;
					$this->ticket_start = date('Y-m-d', strtotime($this->ticket_meta['recurrences']['start_days'] . ' days', $this->get_event()->start));
				}
				$this->ticket_meta['recurrences']['start_time'] = ( !empty($post['ticket_start_time']) ) ? $this->sanitize_time($post['ticket_start_time']) : '00:00:00'; 
				$this->ticket_start .= ' '. $this->ticket_meta['recurrences']['start_time'];
				//timestamp - calculated only for purposes of not screwing up interfaces that use timestamps for outputting cut-off times such as booking settings for event
				$this->start_timestamp  = strtotime($this->ticket_start, current_time('timestamp'));
			}else{
				$this->ticket_start = $this->start_timestamp = '';
			}
			//end of ticket cut-off
			if( array_key_exists('ticket_end_recurring_days', $post) && is_numeric($post['ticket_end_recurring_days']) ){
				if( !empty($post['ticket_end_recurring_when']) && $post['ticket_end_recurring_when'] == 'after' ){
					$this->ticket_meta['recurrences']['end_days'] = absint($post['ticket_end_recurring_days']);
					$this->ticket_end = date('Y-m-d', strtotime('+' . $this->ticket_meta['recurrences']['end_days'] . ' days', $this->get_event()->end));
				}else{ //by default the end date is the point of reference
					$this->ticket_meta['recurrences']['end_days'] = absint($post['ticket_end_recurring_days']) * -1;
					$this->ticket_end = date('Y-m-d', strtotime($this->ticket_meta['recurrences']['end_days'] . ' days', $this->get_event()->end));
				}
				$this->ticket_meta['recurrences']['end_time'] = ( !empty($post['ticket_end_time']) ) ? $this->sanitize_time($post['ticket_end_time']) : '00:00:00'; 
				$this->ticket_end .= ' '. $this->ticket_meta['recurrences']['end_time'];
				//timestamp - calculated only for purposes of not screwing up interfaces that use timestamps for outputting cut-off times such as booking settings for event
				$this->end_timestamp  = strtotime($this->ticket_end, current_time('timestamp')); //we save these timestamps for quicker loading on construct
			}else{
				$this->ticket_end = $this->end_timestamp = '';
			}
		}
		$this->compat_keys();
		do_action('em_ticket_get_post', $this);
	}
	

	/**
	 * Validates the ticket for saving. Should be run during any form submission or saving operation.
	 * @return boolean
	 */
	function validate(){
		$missing_fields = Array ();
		$this->errors = array();
		foreach ( $this->required_fields as $field ) {
			$true_field = $this->fields[$field]['name'];
			if ( $this->$true_field == "") {
				$missing_fields[] = $field;
			}
		}
		if( !empty($this->ticket_price) && !is_numeric($this->ticket_price) ){
			$this->add_error(__('Please enter a valid ticket price e.g. 10.50 (no currency signs)','events-manager'));
		}
		if ( count($missing_fields) > 0){
			// TODO Create friendly equivelant names for missing fields notice in validation 
			$this->errors[] = __ ( 'Missing fields: ' ) . implode ( ", ", $missing_fields ) . ". ";
		}
		return apply_filters('em_ticket_validate', count($this->errors) == 0, $this );
	}
	
	function is_available( $include_members_only = false, $include_guests_only = false ){
		$timestamp = current_time('timestamp');
		if( isset($this->is_available) && !$include_members_only && !$include_guests_only ) return apply_filters('em_ticket_is_available',  $this->is_available, $this); //save extra queries if doing a standard check
		$is_available = false;
		$EM_Event = $this->get_event();
		$available_spaces = $this->get_available_spaces();
		$condition_1 = (empty($this->ticket_start) || $this->start_timestamp <= $timestamp);
		$condition_2 = $this->end_timestamp >= $timestamp || empty($this->ticket_end);
		$condition_3 = (empty($EM_Event->event_rsvp_date) && $EM_Event->start > $timestamp) || $EM_Event->rsvp_end > $timestamp;
		$condition_4 = !$this->ticket_members || ($this->ticket_members && is_user_logged_in()) || $include_members_only;
		$condition_5 = true;
		if( !EM_Bookings::$disable_restrictions && $this->ticket_members && !empty($this->ticket_members_roles) ){
			//check if user has the right role to use this ticket
			$condition_5 = false;
			if( is_user_logged_in() ){
				$user = wp_get_current_user();
				if( count(array_intersect($user->roles, $this->ticket_members_roles)) > 0 ){
					$condition_5 = true;
				}
			}
		}
		$condition_6 = !$this->ticket_guests || ($this->ticket_guests && !is_user_logged_in()) || $include_guests_only;
		if( $condition_1 && $condition_2 && $condition_3 && $condition_4 && $condition_5 && $condition_6 ){
			//Time Constraints met, now quantities
			if( $available_spaces > 0 && ($available_spaces >= $this->ticket_min || empty($this->ticket_min)) ){
				$is_available = true;
			}
		}
		if( !$include_members_only && !$include_guests_only ){ //$this->is_available is only stored for the viewing user
			$this->is_available = $is_available;
		}
		return apply_filters('em_ticket_is_available', $is_available, $this);
	}
	
	/**
	 * Returns whether or not this ticket should be displayed based on availability and other ticket properties and general settings
	 * @return boolean
	 */
	function is_displayable(){
		$return = false;
		if( $this->is_available() ){
			$return = true;
		}else{
			if( get_option('dbem_bookings_tickets_show_unavailable') ){
				$return =  true;
				if( $this->ticket_members && !get_option('dbem_bookings_tickets_show_member_tickets') ){
					$return = false;
				}
			}
		}
		return apply_filters('em_ticket_is_displayable', $return, $this);;
	}
	
	/**
	 * Gets the total price for this ticket, includes tax if settings dictates that tax is added to ticket price. 
	 * Use $this->ticket_price or $this->get_price_without_tax() if you definitely don't want tax included. 
	 * @param boolean $format
	 * @return float
	 */
	function get_price($format = false){
		$price = $this->ticket_price;
		if( get_option('dbem_bookings_tax_auto_add') ){
			$price = $this->get_price_with_tax();
		}
		$price = apply_filters('em_ticket_get_price',$price,$this);
		if($format){
			return $this->format_price($price);
		}
		return $price;
	}
	
	/**
	 * Calculates how much the individual ticket costs with applicable event/site taxes included.
	 * @param boolean $format
	 */
	function get_price_with_tax( $format = false ){
	    $price = $this->get_price_without_tax() * (1 + $this->get_event()->get_tax_rate()/100);
	    if( $format ) return $this->format_price($price);
	    return $price; 
	}
	
	/**
	 * Calculates how much the individual ticket costs with taxes excluded.
	 * @param boolean $format
	 */
	function get_price_without_tax( $format = false ){
	    if( $format ) return $this->format_price($this->ticket_price);
	    return $this->ticket_price; 
	}
	
	/**
	 * Shows the ticket price which can contain long decimals but will show up to 2 decimal places and remove trailing 0s
	 * For example: 10.010230 => 10.01023 and 10 => 10.00 
	 */
	function get_price_precise(){
		$price = $this->ticket_price * 1;
		if( floor($price) == (float) $price ) $price = number_format($price, 2, '.', '');
		return $price;
	}
		
	/**
	 * Get the total number of tickets (spaces) available, bearing in mind event-wide maxiumums and ticket priority settings.
	 * @return int
	 */
	function get_spaces(){
		return apply_filters('em_ticket_get_spaces',$this->ticket_spaces,$this);
	}

	/**
	 * Returns the number of available spaces left in this ticket, bearing in mind event-wide restrictions, previous bookings, approvals and other tickets.
	 * @return int
	 */
	function get_available_spaces(){
		$event_available_spaces = $this->get_event()->get_bookings()->get_available_spaces();
		$ticket_available_spaces = $this->get_spaces() - $this->get_booked_spaces();
		if( get_option('dbem_bookings_approval_reserved')){
		    $ticket_available_spaces = $ticket_available_spaces - $this->get_pending_spaces();
		}
		$return = ($ticket_available_spaces <= $event_available_spaces) ? $ticket_available_spaces:$event_available_spaces;
		return apply_filters('em_ticket_get_available_spaces', $return, $this);
	}
	
	function get_pending_spaces(){
	    $spaces = 0;
		foreach( $this->get_bookings()->get_pending_bookings()->bookings as $EM_Booking ){ //get_bookings() is used twice so we get the confirmed (or all if confirmation disabled) bookings of this ticket's total bookings.
			//foreach booking, get this ticket booking info if found
			foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){
				if( $EM_Ticket_Booking->ticket_id == $this->ticket_id ){
					$spaces += $EM_Ticket_Booking->get_spaces();
				}
			}
		}
		return apply_filters('em_ticket_get_pending_spaces', $spaces, $this);
	}

	/**
	 * Returns the number of booked spaces in this ticket.
	 * @return int
	 */
	function get_booked_spaces($force_reload=false){
		//get all bookings for this event
		$spaces = 0;
		if( is_object($this->bookings) && $force_reload ){
			//return $this->bookings;
		}
		foreach( $this->get_bookings()->get_bookings()->bookings as $EM_Booking ){ //get_bookings() is used twice so we get the confirmed (or all if confirmation disabled) bookings of this ticket's total bookings.
			//foreach booking, get this ticket booking info if found
			foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){
				if( $EM_Ticket_Booking->ticket_id == $this->ticket_id ){
					$spaces += $EM_Ticket_Booking->get_spaces();
				}
			}
		}
		return apply_filters('em_ticket_get_booked_spaces', $spaces, $this);
	}
	
	/**
	 * Smart event locator, saves a database read if possible.
	 * @return EM_Event 
	 */
	function get_event(){
		return em_get_event($this->event_id);
	}
	
	/**
	 * returns array of EM_Booking objects that have this ticket
	 * @return EM_Bookings
	 */
	function get_bookings(){
		$bookings = array();
		foreach( $this->get_event()->get_bookings()->bookings as $EM_Booking ){
			foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){
				if( $EM_Ticket_Booking->ticket_id == $this->ticket_id ){
					$bookings[$EM_Booking->booking_id] = $EM_Booking;
				}
			}
		}
		$this->bookings = new EM_Bookings($bookings);
		return $this->bookings;
	}
	
	/**
	 * I wonder what this does....
	 * @return boolean
	 */
	function delete(){
		global $wpdb;
		$result = false;
		if( $this->can_manage() ){
			if( count($this->get_bookings()->bookings) == 0 ){
				$sql = $wpdb->prepare("DELETE FROM ". EM_TICKETS_TABLE . " WHERE ticket_id=%d", $this->ticket_id);
				$result = $wpdb->query( $sql );
			}else{
				$this->feedback_message = __('You cannot delete a ticket that has a booking on it.','events-manager');
				$this->add_error($this->feedback_message);
				return false;
			}
		}
		return ( $result !== false );
	}

	/**
	 * Based on ticket minimums, whether required and if the event has more than one ticket this function will return the absolute minimum required spaces for a booking 
	 */
	function get_spaces_minimum(){
	    $ticket_count = count($this->get_event()->get_bookings()->get_tickets()->tickets);
	    //count available tickets to make sure
	    $available_tickets = 0;
	    foreach($this->get_event()->get_bookings()->get_tickets()->tickets as $EM_Ticket){
	    	if($EM_Ticket->is_available()){
	    		$available_tickets++;
	    	}
	    }
	    $min_spaces = 0;
	    if( $ticket_count > 1 ){
	        if( $this->is_required() && $this->is_available() ){
	            $min_spaces = ($this->ticket_min > 0) ? $this->ticket_min:1;
	        }elseif( $this->is_available() && $this->ticket_min > 0 ){
	            $min_spaces = $this->ticket_min;	            
	        }elseif( $this->is_available() && $available_tickets == 1 ){
	            $min_spaces = 1;
	        }
	    }else{
	    	$min_spaces = $this->ticket_min > 0 ? $this->ticket_min : 1;
	    }
	    return $min_spaces;
	}
	
	function is_required(){
	    if( $this->ticket_required || count($this->get_event()->get_tickets()->tickets) == 1 ){
	        return true;
	    }
	    return false;
	}

	/**
	 * Get the html options for quantities to go within a <select> container
	 * @return string
	 */
	function get_spaces_options($zero_value = true, $default_value = 0){
		$available_spaces = $this->get_available_spaces();		
		if( $this->is_available() ) {
		    $min_spaces = $this->get_spaces_minimum();
		    if( $default_value > 0 ){
			    $default_value = $min_spaces > $default_value ? $min_spaces:$default_value;
		    }else{
		        $default_value = $this->is_required() ? $min_spaces:0;
		    }
			ob_start();
			?>
			<select name="em_tickets[<?php echo $this->ticket_id ?>][spaces]" class="em-ticket-select" id="em-ticket-spaces-<?php echo $this->ticket_id ?>">
				<?php 
					$min = ($this->ticket_min > 0) ? $this->ticket_min:1;
					$max = ($this->ticket_max > 0) ? $this->ticket_max:get_option('dbem_bookings_form_max');
					if( $this->get_event()->event_rsvp_spaces > 0 && $this->get_event()->event_rsvp_spaces < $max ) $max = $this->get_event()->event_rsvp_spaces;
				?>
				<?php if($zero_value && !$this->is_required()) : ?><option>0</option><?php endif; ?>
				<?php for( $i=$min; $i<=$available_spaces && $i<=$max; $i++ ): ?>
					<option <?php if($i == $default_value){ echo 'selected="selected"'; $shown_default = true; } ?>><?php echo $i ?></option>
				<?php endfor; ?>
				<?php if(empty($shown_default) && $default_value > 0 ): ?><option selected="selected"><?php echo $default_value; ?></option><?php endif; ?>
			</select>
			<?php 
			return apply_filters('em_ticket_get_spaces_options', ob_get_clean(), $zero_value, $default_value, $this);
		}else{
			return false;
		}
	}
	
	/**
	 * Can the user manage this event? 
	 */
	function can_manage( $owner_capability = false, $admin_capability = false, $user_to_check = false ){
		if( $this->ticket_id == '' && !is_user_logged_in() && get_option('dbem_events_anonymous_submissions') ){
			$user_to_check = get_option('dbem_events_anonymous_user');
		}
		return $this->get_event()->can_manage('manage_bookings','manage_others_bookings', $user_to_check);
	}
	
	/**
	 * Outputs properties with formatting
	 * @param string $property
	 * @return string
	 */
	function output_property($property){
		switch($property){
			case 'start':
				$value = date_i18n( get_option('date_format'), $this->start_timestamp );
				break;
			case 'end':
				$value = date_i18n( get_option('date_format'), $this->end_timestamp );
				break;
				break;
			default:
				$value = $this->$property;
				break;
		}
		return apply_filters('em_ticket_output_property',$value,$this, $property);
	}
}
?>