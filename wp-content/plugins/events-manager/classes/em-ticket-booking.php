<?php
class EM_Ticket_Booking extends EM_Object{
	//DB Fields
	var $ticket_booking_id;
	var $booking_id;
	var $ticket_id;
	var $ticket_booking_price;
	var $ticket_booking_spaces;
	var $fields = array(
		'ticket_booking_id' => array('name'=>'id','type'=>'%d'),
		'ticket_id' => array('name'=>'ticket_id','type'=>'%d'),
		'booking_id' => array('name'=>'booking_id','type'=>'%d'),
		'ticket_booking_price' => array('name'=>'price','type'=>'%f'),
		'ticket_booking_spaces' => array('name'=>'spaces','type'=>'%d')
	);
	//Other Vars
	/**
	 * Contains ticket object
	 * @var EM_Ticket
	 */
	var $ticket;
	/**
	 * Contains the booking object of this
	 * @var EM_Booking
	 */
	var $booking;
	var $required_fields = array( 'ticket_id', 'ticket_booking_spaces');
	
	/**
	 * Creates ticket object and retreives ticket data (default is a blank ticket object). Accepts either array of ticket data (from db) or a ticket id.
	 * @param mixed $ticket_data
	 * @return null
	 */
	function __construct( $ticket_data = false ){
		if( $ticket_data !== false ){
			//Load ticket data
			$ticket = array();
			if( is_array($ticket_data) ){
				$ticket = $ticket_data;
			}elseif( is_numeric($ticket_data) ){
				//Retreiving from the database		
				global $wpdb;
				$sql = "SELECT * FROM ". EM_TICKETS_BOOKINGS_TABLE ." WHERE ticket_booking_id ='$ticket_data'";   
			  	$ticket = $wpdb->get_row($sql, ARRAY_A);
			}
			//Save into the object
			$this->to_object($ticket);
			$this->compat_keys();
		}
	}
	
	/**
	 * Saves the ticket into the database, whether a new or existing ticket
	 * @return boolean
	 */
	function save(){
		global $wpdb;
		$table = EM_TICKETS_BOOKINGS_TABLE;
		do_action('em_ticket_booking_save_pre',$this);
		//First the person
		if($this->validate()){			
			//Now we save the ticket
			$this->booking_id = $this->get_booking()->booking_id; //event wouldn't exist before save, so refresh id
			$data = $this->to_array(true); //add the true to remove the nulls
			if($this->ticket_booking_id != ''){
				if($this->get_spaces() > 0){
					$where = array( 'ticket_booking_id' => $this->ticket_booking_id );  
					$result = $wpdb->update($table, $data, $where, $this->get_types($data));
					$this->feedback_message = __('Changes saved','events-manager');
				}else{
					$this->result = $this->delete(); 
				}
			}else{
				if($this->get_spaces() > 0){
					//TODO better error handling
					$result = $wpdb->insert($table, $data, $this->get_types($data));
				    $this->ticket_booking_id = $wpdb->insert_id;  
					$this->feedback_message = __('Ticket booking created','events-manager'); 
				}else{
					//no point saving a booking with no spaces
					$result = false;
				}
			}
			if( $result === false ){
				$this->feedback_message = __('There was a problem saving the ticket booking.', 'events-manager');
				$this->errors[] = __('There was a problem saving the ticket booking.', 'events-manager');
			}
			$this->compat_keys();
			return apply_filters('em_ticket_booking_save', ( count($this->errors) == 0 ), $this);
		}else{
			$this->feedback_message = __('There was a problem saving the ticket booking.', 'events-manager');
			$this->errors[] = __('There was a problem saving the ticket booking.', 'events-manager');
			return apply_filters('em_ticket_booking_save', false, $this);
		}
		return true;
	}	
	

	/**
	 * Validates the ticket for saving. Should be run during any form submission or saving operation.
	 * @return boolean
	 */
	function validate(){
		$missing_fields = Array ();
		foreach ( $this->required_fields as $field ) {
			$true_field = $this->fields[$field]['name'];
			if ( $this->$true_field == "") {
				$missing_fields[] = $field;
			}
		}
		if ( count($missing_fields) > 0){
			// TODO Create friendly equivelant names for missing fields notice in validation 
			$this->errors[] = __ ( 'Missing fields: ' ) . implode ( ", ", $missing_fields ) . ". ";
		}
		return apply_filters('em_ticket_booking_validate', count($this->errors) == 0, $this );
	}
	
	/**
	 * Get the total number of spaces booked for this ticket within this booking.
	 * @return int
	 */
	function get_spaces(){
		return apply_filters('em_booking_get_spaces',$this->ticket_booking_spaces,$this);
	}
	
	/**
	 * Gets the total price for these tickets. If $format is set to true, the value returned is a price string with currency formatting.
	 * @param boolean $format
	 * @return double|string
	 */
	function get_price( $format = false ){
		if( $this->ticket_booking_price == 0 ){
			//get the ticket, calculate price on spaces
			$this->ticket_booking_price = $this->get_ticket()->get_price_without_tax() * $this->ticket_booking_spaces;
			$this->ticket_booking_price = apply_filters('em_ticket_booking_get_price', $this->ticket_booking_price, $this);
		}
		$price = $this->ticket_booking_price;
		//do some legacy checking here for bookings made prior to 5.4, due to how taxes are calculated
		if( $this->ticket_booking_id > 0 ){
		    $EM_Booking = $this->get_booking();
		    if( !empty($EM_Booking->legacy_tax_rate) ){
		        //check multisite nuances
		        if( EM_MS_GLOBAL && $EM_Booking->get_event()->blog_id != get_current_blog_id() ){
		            //MultiSite AND Global tables enabled - get settings for blog that published the event  
		            $tax_auto_add = get_blog_option($EM_Booking->get_event()->blog_id, 'dbem_legacy_bookings_tax_auto_add');
		        }else{
		            //get booking from current site, whether or not we're in MultiSite
		            $tax_auto_add = get_option('dbem_legacy_bookings_tax_auto_add');
		        }
		        if( $tax_auto_add && $EM_Booking->get_tax_rate() > 0 ){
				    //this booking never had a tax rate fixed to it (i.e. prior to v5.4), and according to legacy settings, taxes were applied to this price
				    //we now calculate price of ticket bookings without taxes, so remove the tax
				    $price = $this->ticket_booking_price / (1 + $EM_Booking->get_tax_rate()/100 );
		        }
		    }
		}
		//return price formatted or not
		if($format){
			return $this->format_price($price);
		}
		return $price;
	}
	
	function get_price_with_taxes( $format = false ){
		$price = $this->get_price() * (1 + $this->get_booking()->get_event()->get_tax_rate()/100);
	    if( $format ) return $this->format_price($price);
	    return $price; 
	}
	
	/**
	 * Smart booking locator, saves a database read if possible.
	 * @return EM_Booking 
	 */
	function get_booking(){
		global $EM_Booking;
		if( is_object($this->booking) && get_class($this->booking)=='EM_Booking' && ($this->booking->booking_id == $this->booking_id || (empty($this->ticket_booking_id) && empty($this->booking_id))) ){
			return $this->booking;
		}elseif( is_object($EM_Booking) && $EM_Booking->booking_id == $this->booking_id ){
			$this->booking = $EM_Booking;
		}else{
			if(is_numeric($this->booking_id)){
				$this->booking = em_get_booking($this->booking_id);
			}else{
				$this->booking = em_get_booking();
			}
		}
		return apply_filters('em_ticket_booking_get_booking', $this->booking, $this);;
	}
	
	/**
	 * Gets the ticket object this booking belongs to, saves a reference in ticket property
	 * @return EM_Ticket
	 */
	function get_ticket(){
		global $EM_Ticket;
		if( is_object($this->ticket) && get_class($this->ticket)=='EM_Ticket' && $this->ticket->ticket_id == $this->ticket_id ){
			return $this->ticket;
		}elseif( is_object($EM_Ticket) && $EM_Ticket->ticket_id == $this->ticket_id ){
			$this->ticket = $EM_Ticket;
		}else{
			$this->ticket = new EM_Ticket($this->ticket_id);
		}
		return $this->ticket;
	}
	
	/**
	 * I wonder what this does....
	 * @return boolean
	 */
	function delete(){
		global $wpdb;
		$sql = $wpdb->prepare("DELETE FROM ". EM_TICKETS_BOOKINGS_TABLE . " WHERE ticket_booking_id=%d", $this->ticket_booking_id);
		$result = $wpdb->query( $sql );
		return apply_filters('em_ticket_booking_delete', ($result !== false ), $this);
	}
	

	/**
	 * Get the html options for quantities to go within a <select> container
	 * @return string
	 */
	function get_spaces_options($zero_value = true){
		$available_spaces = $this->get_available_spaces();
		if( $available_spaces >= $this->min || ( empty($this->min) && $available_spaces > 0) ) {
			ob_start();
			?>
			<select name="em_tickets[<?php echo $this->ticket_booking_id ?>][spaces]">
				<?php 
					$min = ($this->min > 0) ? $this->min:1;
					$max = ($this->max > 0) ? $this->max:get_option('dbem_bookings_form_max');
				?>
				<?php if($zero_value) : ?><option>0</option><?php endif; ?>
				<?php for( $i=$min; $i<=$max; $i++ ): ?>
					<option><?php echo $i ?></option>
				<?php endfor; ?>
			</select>
			<?php 
			return ob_get_clean();
		}else{
			return false;
		}
			
	}
	
	/**
	 * Can the user manage this event? 
	 */
	function can_manage( $owner_capability = false, $admin_capability = false, $user_to_check = false ){
		return ( $this->get_booking()->can_manage() );
	}
}
?>