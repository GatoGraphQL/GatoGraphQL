<?php
/**
 * Deals with the each ticket booked in a single booking
 * @author marcus
 *
 */
class EM_Tickets_Bookings extends EM_Object implements Iterator{
	
	/**
	 * Array of EM_Ticket_Booking objects for a specific event
	 * @var array
	 */
	var $tickets_bookings = array();
	/**
	 * This object belongs to this booking object
	 * @var EM_Booking
	 */
	var $booking;
	var $booking_id;
	/**
	 * This object belongs to this booking object
	 * @var EM_Ticket
	 */
	var $ticket;
	var $spaces;
	var $price;
	
	/**
	 * Creates an EM_Tickets instance, 
	 * @param mixed $object
	 */
	function __construct( $object = false ){
		global $wpdb;
		if($object){
			if( is_object($object) && get_class($object) == "EM_Booking"){
				$this->booking = $object;
				$sql = "SELECT * FROM ". EM_TICKETS_BOOKINGS_TABLE ." WHERE booking_id ='{$this->booking->booking_id}'";
			}elseif( is_object($object) && get_class($object) == "EM_Ticket"){
				$this->ticket = $object;
				$sql = "SELECT * FROM ". EM_TICKETS_BOOKINGS_TABLE ." WHERE ticket_id ='{$this->ticket->ticket_id}'";
			}elseif( is_numeric($object) ){
				$sql = "SELECT * FROM ". EM_TICKETS_BOOKINGS_TABLE ." WHERE booking_id ='{$object}'";
			}
			$tickets_bookings = $wpdb->get_results($sql, ARRAY_A);
			//Get tickets belonging to this tickets booking.
			foreach ($tickets_bookings as $ticket_booking){
				$EM_Ticket_Booking = new EM_Ticket_Booking($ticket_booking);
				$EM_Ticket_Booking->booking = $this->booking; //save some calls
				$this->tickets_bookings[$ticket_booking['ticket_id']] = $EM_Ticket_Booking;
			}
		}
		do_action('em_tickets_bookings',$this, $object);
	}
	
	/**
	 * Saves the ticket bookings for this booking into the database, whether a new or existing booking
	 * @return boolean
	 */
	function save(){
		global $wpdb;
		do_action('em_tickets_bookings_save_pre',$this);
		foreach( $this->tickets_bookings as $EM_Ticket_Booking ){ /* @var $EM_Ticket_Booking EM_Ticket_Booking */
			$result = $EM_Ticket_Booking->save();
			if(!$result){
				$this->errors = array_merge($this->errors, $EM_Ticket_Booking->get_errors());
			}
		}
		if( count($this->errors) > 0 ){
			$this->feedback_message = __('There was a problem saving the booking.', 'events-manager');
			$this->errors[] = __('There was a problem saving the booking.', 'events-manager');
			return apply_filters('em_tickets_bookings_save', false, $this);
		}
		return apply_filters('em_tickets_bookings_save', true, $this);
	}
	
	/**
	 * Add a booking into this event object, checking that there's enough space for the event
	 * @param EM_Ticket_Booking $EM_Ticket_Booking
	 * @return boolean
	 */
	function add( $EM_Ticket_Booking, $override = false ){ //note, $override was a quick fix, not necessarily permanent, so don't depend on it just yet
		global $wpdb,$EM_Mailer;
		//Does the ticket we want to book have enough spaeces? 
		if ( $override || $EM_Ticket_Booking->get_ticket()->get_available_spaces() >= $EM_Ticket_Booking->get_spaces() ) {  
			$ticket_booking_key = $this->has_ticket($EM_Ticket_Booking->ticket_id);
			$this->price = 0; //so price calculations are reset
			if( $ticket_booking_key !== false && is_object($this->tickets_bookings[$EM_Ticket_Booking->ticket_id]) ){
				//previously booked ticket, so let's just reset spaces/prices and replace it
				$this->tickets_bookings[$EM_Ticket_Booking->ticket_id]->ticket_booking_spaces = $EM_Ticket_Booking->get_spaces();
				$this->tickets_bookings[$EM_Ticket_Booking->ticket_id]->ticket_booking_price = $EM_Ticket_Booking->get_price();
				return apply_filters('em_tickets_bookings_add',true,$this);
			}elseif( $EM_Ticket_Booking->get_spaces() > 0 ){
				//new ticket in booking
				$this->tickets_bookings[$EM_Ticket_Booking->ticket_id] = $EM_Ticket_Booking;
				$this->get_spaces(true);
				$this->get_price();
				return apply_filters('em_tickets_bookings_add',true,$this);
			}
		} else {
			$this->add_error(get_option('dbem_booking_feedback_full'));
			return apply_filters('em_tickets_bookings_add',false,$this);
		}
		return apply_filters('em_tickets_bookings_add',false,$this);
	}
	
	/**
	 * Checks if this set has a specific ticket booked, returning the key of the ticket in the EM_Tickets_Bookings->ticket_bookings array
	 * @param int $ticket_id
	 * @return mixed
	 */
	function has_ticket( $ticket_id ){
		foreach ($this->tickets_bookings as $key => $EM_Ticket_Booking){
			if( $EM_Ticket_Booking->ticket_id == $ticket_id ){
				return apply_filters('em_tickets_has_ticket',$key,$this);
			}
		}
		return apply_filters('em_tickets_has_ticket',false,$this);
	}
	
	/**
	 * Smart event locator, saves a database read if possible. 
	 */
	function get_booking(){
		global $EM_Booking;
		$booking_id = $this->get_booking_id();
		if( is_object($this->booking) && get_class($this->booking)=='EM_Booking' && $this->booking->booking_id == $booking_id ){
			return $this->booking;
		}elseif( is_object($EM_Booking) && $EM_Booking->booking_id == $booking_id ){
			$this->booking = $EM_Booking;
		}else{
			if(is_numeric($booking_id)){
				$this->booking = em_get_booking($booking_id);
			}else{
				$this->booking = em_get_booking();
			}
		}
		return apply_filters('em_tickets_bookings_get_booking', $this->booking, $this);;
	}
	
	function get_booking_id(){
		if( empty($this->booking_id) && count($this->tickets_bookings) > 0 ){
			foreach($this->tickets_bookings as $EM_Ticket_Booking){ break; } //get first array item
			$this->booking_id = $EM_Ticket_Booking->get_booking()->booking_id;
			return apply_filters('em_tickets_bookings_get_booking_id', $this->booking_id, $this);
		}
		return apply_filters('em_tickets_bookings_get_booking_id', $this->booking_id, $this);
	}
	
	/**
	 * Delete all ticket bookings
	 * @return boolean
	 */
	function delete(){
		global $wpdb;
		if( $this->get_booking()->can_manage() ){
			$result = $wpdb->query("DELETE FROM ".EM_TICKETS_BOOKINGS_TABLE." WHERE booking_id='{$this->get_booking_id()}'");
			//echo "<pre>";print_r($this->get_booking());echo "</pre>";
		}else{
			//FIXME ticket bookings
			$ticket_ids = array();
			foreach( $this->tickets_bookings as $EM_Ticket_Booking ){
				if( $EM_Ticket_Booking->can_manage() ){
					$tickets_bookings_ids[] = $EM_Ticket_Booking->booking_id;
				}else{
					$this->errors[] = sprintf(__('You do not have the rights to manage this %s.','events-manager'),__('Booking','events-manager'));					
				}
			}
			if(count($ticket_ids) > 0){
				$result = $wpdb->query("DELETE FROM ".EM_TICKETS_BOOKINGS_TABLE." WHERE ticket_booking_id IN (".implode(',',$ticket_ids).")");
			}
		}
		return apply_filters('em_tickets_bookings_get_booking_id', ($result == true), $this);
	}
	
	/**
	 * Go through the tickets in this object and validate them 
	 */
	function validate(){
		$errors = array();
		foreach($this->tickets_bookings as $EM_Ticket_Booking){
			$errors[] = $EM_Ticket_Booking->validate();
		}
		return apply_filters('em_tickets_bookings_validate', !in_array(false, $errors), $this);
	}
	
	/**
	 * Get the total number of spaces booked in this booking. Seting $force_reset to true will recheck spaces, even if previously done so.
	 * @param unknown_type $force_refresh
	 * @return mixed
	 */
	function get_spaces( $force_refresh=false ){
		if($force_refresh || $this->spaces == 0){
			$spaces = 0;
			foreach($this->tickets_bookings as $EM_Ticket_Booking){
			    /* @var $EM_Ticket_Booking EM_Ticket_Booking */
				$spaces += $EM_Ticket_Booking->get_spaces();
			}
			$this->spaces = $spaces;
		}
		return apply_filters('em_booking_get_spaces',$this->spaces,$this);
	}
	
	/**
	 * Gets the total price for this whole booking by adding up subtotals of booked tickets. Seting $force_reset to true will recheck spaces, even if previously done so.
	 * @param boolean $format
	 * @return float
	 */
	function get_price( $format = false ){
		if( $this->price == 0 ){
			$price = 0;
			foreach($this->tickets_bookings as $EM_Ticket_Booking){
				$price += $EM_Ticket_Booking->get_price();
			}
			$this->price = apply_filters('em_tickets_bookings_get_price', $price, $this);
		}
		if($format){
			return $this->format_price($this->price);
		}
		return $this->price;
	}
	
	/**
	 * Goes through each ticket and populates it with the bookings made
	 */
	function get_ticket_bookings(){
		foreach( $this->tickets as $EM_Ticket ){
			$EM_Ticket->get_bookings();
		}
	}	
	
	/* Overrides EM_Object method to apply a filter to result
	 * @see wp-content/plugins/events-manager/classes/EM_Object#build_sql_conditions()
	 */
	public static function build_sql_conditions( $args = array() ){
		$conditions = apply_filters( 'em_tickets_build_sql_conditions', parent::build_sql_conditions($args), $args );
		if( is_numeric($args['status']) ){
			$conditions['status'] = 'ticket_status='.$args['status'];
		}
		return apply_filters('em_tickets_bookings_build_sql_conditions', $conditions, $args);
	}
	
	/* Overrides EM_Object method to apply a filter to result
	 * @see wp-content/plugins/events-manager/classes/EM_Object#build_sql_orderby()
	 */
	public static function build_sql_orderby( $args, $accepted_fields, $default_order = 'ASC' ){
		return apply_filters( 'em_tickets_bookings_build_sql_orderby', parent::build_sql_orderby($args, $accepted_fields, get_option('dbem_events_default_order')), $args, $accepted_fields, $default_order );
	}
	
	/* 
	 * Adds custom Events search defaults
	 * @param array $array_or_defaults may be the array to override defaults
	 * @param array $array
	 * @return array
	 * @uses EM_Object#get_default_search()
	 */
	public static function get_default_search( $array_or_defaults = array(), $array = array() ){
		$defaults = array(
			'status' => false,
			'person' => true //to add later, search by person's tickets...
		);	
		//sort out whether defaults were supplied or just the array of search values
		if( empty($array) ){
			$array = $array_or_defaults;
		}else{
			$defaults = array_merge($defaults, $array_or_defaults);
		}
		//specific functionality
		$defaults['owner'] = !current_user_can('manage_others_bookings') ? get_current_user_id():false;
		return apply_filters('em_tickets_bookings_get_default_search', parent::get_default_search($defaults,$array), $array, $defaults);
	}

	//Iterator Implementation
    public function rewind(){
        reset($this->tickets_bookings);
    }  
    public function current(){
        $var = current($this->tickets_bookings);
        return $var;
    }  
    public function key(){
        $var = key($this->tickets_bookings);
        return $var;
    }  
    public function next(){
        $var = next($this->tickets_bookings);
        return $var;
    }  
    public function valid(){
        $key = key($this->tickets_bookings);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }	
}
?>