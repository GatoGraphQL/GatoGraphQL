<?php
/**
 * Deals with the ticket info for an event
 * @author marcus
 *
 */
class EM_Tickets extends EM_Object implements Iterator{
	
	/**
	 * Array of EM_Ticket objects for a specific event
	 * @var array
	 */
	var $tickets = array();
	/**
	 * @var int
	 */
	var $event_id;
	/**
	 * @var EM_Booking
	 */
	var $booking;
	var $spaces;
	
	
	/**
	 * Creates an EM_Tickets instance
	 * @param mixed $event
	 */
	function __construct( $object = false ){
		global $wpdb;
		if( is_numeric($object) || (is_object($object) && in_array(get_class($object), array("EM_Event","EM_Booking"))) ){
			$this->event_id = (is_object($object)) ? $object->event_id:$object;
		    if( is_object($object) && get_class($object) == 'EM_Booking' ){
				$sql = "SELECT * FROM ". EM_TICKETS_TABLE ." WHERE ticket_id IN (SELECT ticket_id FROM ".EM_TICKETS_BOOKINGS_TABLE." WHERE booking_id='{$object->booking_id}') ORDER BY ".get_option('dbem_bookings_tickets_orderby');
		    }else{
		        $sql = "SELECT * FROM ". EM_TICKETS_TABLE ." WHERE event_id ='{$this->event_id}' ORDER BY ".get_option('dbem_bookings_tickets_orderby');
		    }
			$tickets = $wpdb->get_results($sql, ARRAY_A);
			foreach ($tickets as $ticket){
				$EM_Ticket = new EM_Ticket($ticket);
				$EM_Ticket->event_id = $this->event_id;
				$this->tickets[$EM_Ticket->ticket_id] = $EM_Ticket;
			}
		}elseif( is_array($object) ){ //expecting an array of EM_Ticket objects or ticket db array
			if( is_object(current($object)) && get_class(current($object)) == 'EM_Ticket' ){
			    foreach($object as $EM_Ticket){
					$this->tickets[$EM_Ticket->ticket_id] = $EM_Ticket;
			    }
			}else{
				foreach($object as $ticket){
					$EM_Ticket = new EM_Ticket($ticket);
					$EM_Ticket->event_id = $this->event_id;
					$this->tickets[$EM_Ticket->ticket_id] = $EM_Ticket;				
				}
			}
		}
		do_action('em_tickets', $this, $object);
	}
	
	/**
	 * @return EM_Event
	 */
	function get_event(){
		global $EM_Event;
		if( is_object($EM_Event) && $EM_Event->event_id == $this->event_id ){
			return $EM_Event;
		}else{
			return new EM_Event($this->event_id);
		}
	}

	/**
	 * does this ticket exist?
	 * @return bool 
	 */
	function has_ticket($ticket_id){
		foreach( $this->tickets as $EM_Ticket){
			if($EM_Ticket->ticket_id == $ticket_id){
				return apply_filters('em_tickets_has_ticket',true, $EM_Ticket, $this);
			}
		}
		return apply_filters('em_tickets_has_ticket',false, false,$this);
	}
	
	/**
	 * Get the first EM_Ticket object in this instance. Returns false if no tickets available.
	 * @return EM_Ticket
	 */
	function get_first(){
		if( count($this->tickets) > 0 ){
			foreach($this->tickets as $EM_Ticket){
				return $EM_Ticket;
			}
		}else{
			return false;
		}
	}
	
	/**
	 * Delete tickets in thie object
	 * @return boolean
	 */
	function delete(){
		global $wpdb;
		//get all the ticket ids
		$result = false;
		$ticket_ids = array();
		foreach( $this->tickets as $EM_Ticket ){
			$ticket_ids[] = $EM_Ticket->ticket_id;
		}
		//check that tickets don't have bookings
		if(count($ticket_ids) > 0){
			$bookings = $wpdb->get_var("SELECT COUNT(*) FROM ". EM_TICKETS_BOOKINGS_TABLE." WHERE ticket_id IN (".implode(',',$ticket_ids).")");
			if( $bookings > 0 ){
				$result = false;
				$this->add_error(__('You cannot delete tickets if there are any bookings associated with them. Please delete these bookings first.','events-manager'));
			}else{
				$result = $wpdb->query("DELETE FROM ".EM_TICKETS_TABLE." WHERE event_id IN (".implode(',',$ticket_ids).")");
			}
		}
		return ($result !== false);
	}
	
	/**
	 * Retrieve multiple ticket info via POST
	 * @return boolean
	 */
	function get_post(){
		//Build Event Array
		do_action('em_tickets_get_post_pre', $this);
		$this->tickets = array(); //clean current tickets out
		if( !empty($_POST['em_tickets']) && is_array($_POST['em_tickets']) ){
			//get all ticket data and create objects
			global $allowedposttags;
			foreach($_POST['em_tickets'] as $row => $ticket_data){
			    if( $row > 0 ){
					$EM_Ticket = new EM_Ticket();
					$ticket_data['event_id'] = $this->event_id;
					$EM_Ticket->get_post($ticket_data);
					$this->tickets[] = $EM_Ticket;
			    }
			}
		}else{
			//we create a blank standard ticket
			$EM_Ticket = new EM_Ticket(array(
				'event_id' => $this->event_id,
				'ticket_name' => __('Standard','events-manager')
			));
			$this->tickets[] = $EM_Ticket;
		}
		return apply_filters('em_tickets_get_post', count($this->errors) == 0, $this);
	}
	
	/**
	 * Go through the tickets in this object and validate them 
	 */
	function validate(){
		$this->errors = array();
		foreach($this->tickets as $EM_Ticket){
			if( !$EM_Ticket->validate() ){
				$this->add_error($EM_Ticket->get_errors());
			} 
		}
		return apply_filters('em_tickets_validate', count($this->errors) == 0, $this);
	}
	
	/**
	 * Save tickets into DB 
	 */
	function save(){
		$result = true;
		foreach( $this->tickets as $EM_Ticket ){
			/* @var $EM_Ticket EM_Ticket */
			$EM_Ticket->event_id = $this->event_id; //pass on saved event_data
			if( !$EM_Ticket->save() ){
				$result = false;
				$this->add_error($EM_Ticket->get_errors());
			}
		}
		return apply_filters('em_tickets_save', $result, $this);
	}
	
	/**
	 * Goes through each ticket and populates it with the bookings made
	 */
	function get_ticket_bookings(){
		foreach( $this->tickets as $EM_Ticket ){
			$EM_Ticket->get_bookings();
		}
	}
	
	/**
	 * Get the total number of spaces this event has. This will show the lower value of event global spaces limit or total ticket spaces. Setting $force_refresh to true will recheck spaces, even if previously done so.
	 * @param boolean $force_refresh
	 * @return int
	 */
	function get_spaces( $force_refresh=false ){
		$spaces = 0;
		if($force_refresh || $this->spaces == 0){
			foreach( $this->tickets as $EM_Ticket ){
				/* @var $EM_Ticket EM_Ticket */
				$spaces += $EM_Ticket->get_spaces();
			}
			$this->spaces = $spaces;
		}
		return apply_filters('em_booking_get_spaces',$this->spaces,$this);
	}
	
	/**
	 * Returns the collumns used in ticket public pricing tables/forms
	 * @param unknown_type $EM_Event
	 */
	function get_ticket_collumns($EM_Event = false){
		if( !$EM_Event ) $EM_Event = $this->get_event();
		$collumns = array( 'type' => __('Ticket Type','events-manager'), 'price' => __('Price','events-manager'), 'spaces' => __('Spaces','events-manager'));
		if( $EM_Event->is_free() ) unset($collumns['price']); //add event price
		return apply_filters('em_booking_form_tickets_cols', $collumns, $EM_Event );
	}
	
	//Iterator Implementation
    public function rewind(){
        reset($this->tickets);
    }  
    public function current(){
        $var = current($this->tickets);
        return $var;
    }  
    public function key(){
        $var = key($this->tickets);
        return $var;
    }  
    public function next(){
        $var = next($this->tickets);
        return $var;
    }  
    public function valid(){
        $key = key($this->tickets);
        $var = ($key !== NULL && $key !== FALSE);
        return $var;
    }
}
?>