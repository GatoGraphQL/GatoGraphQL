<?php
/**
 * gets a booking in a more db-friendly manner, allows hooking into booking object right after instantiation
 * @param mixed $id
 * @param mixed $search_by
 * @return EM_Booking
 */
function em_get_booking($id = false) {
	global $EM_Booking;
	//check if it's not already global so we don't instantiate again
	if( is_object($EM_Booking) && get_class($EM_Booking) == 'EM_Booking' ){
		if( is_object($id) && $EM_Booking->booking_id == $id->booking_id ){
			return apply_filters('em_get_booking', $EM_Booking);
		}else{
			if( is_numeric($id) && $EM_Booking->booking_id == $id ){
				return apply_filters('em_get_booking', $EM_Booking);
			}elseif( is_array($id) && !empty($id['booking_id']) && $EM_Booking->booking_id == $id['booking_id'] ){
				return apply_filters('em_get_booking', $EM_Booking);
			}
		}
	}
	if( is_object($id) && get_class($id) == 'EM_Booking' ){
		return apply_filters('em_get_booking', $id);
	}else{
		return apply_filters('em_get_booking', new EM_Booking($id));
	}
}
/**
 * Contains all information and relevant functions surrounding a single booking made with Events Manager
 */
class EM_Booking extends EM_Object{
	//DB Fields
	var $booking_id;
	var $event_id;
	var $person_id;
	var $booking_price = null;
	var $booking_spaces;
	var $booking_comment;
	var $booking_status = false;
	var $booking_tax_rate = null;
	var $booking_taxes = null;
	var $booking_meta = array();
	var $fields = array(
		'booking_id' => array('name'=>'id','type'=>'%d'),
		'event_id' => array('name'=>'event_id','type'=>'%d'),
		'person_id' => array('name'=>'person_id','type'=>'%d'),
		'booking_price' => array('name'=>'price','type'=>'%f'),
		'booking_spaces' => array('name'=>'spaces','type'=>'%d'),
		'booking_comment' => array('name'=>'comment','type'=>'%s'),
		'booking_status' => array('name'=>'status','type'=>'%d'),
		'booking_tax_rate' => array('name'=>'tax_rate','type'=>'%f','null'=>1),
		'booking_taxes' => array('name'=>'taxes','type'=>'%f','null'=>1),
		'booking_meta' => array('name'=>'meta','type'=>'%s')
	);
	//Other Vars
	/**
	 * array of notes by admins on this booking. loaded from em_meta table in construct
	 * @var array
	 */
	var $notes;
	var $timestamp;
	var $person;
	var $required_fields = array('booking_id', 'event_id', 'person_id', 'booking_spaces');
	var $feedback_message = "";
	var $errors = array();
	/**
	 * when using EM_Booking::email_send(), this number is updated with sent emails
	 * @var int
	 */
	var $mails_sent = 0;
	/**
	 * Contains an array of custom fields for a booking. This is loaded from em_meta, where the booking_custom name contains arrays of data.
	 * @var array
	 */
	var $custom = array();
	/**
	 * If saved in this instance, you can see what previous approval status was.
	 * @var int
	 */
	var $previous_status = false;
	/**
	 * The booking approval status number corresponds to a state in this array.
	 * @var unknown_type
	 */
	var $status_array = array();
	/**
	 * @var EM_Tickets
	 */
	var $tickets;
	/**
	 * @var EM_Event
	 */
	var $event;
	/**
	 * @var EM_Tickets_Bookings
	 */
	var $tickets_bookings;
	/**
	 * If set to true, this booking can be managed by any logged in user.
	 * @var EM_Tickets_Bookings
	 */
	var $manage_override;
	
	/**
	 * Creates booking object and retreives booking data (default is a blank booking object). Accepts either array of booking data (from db) or a booking id.
	 * @param mixed $booking_data
	 * @return null
	 */
	function __construct( $booking_data = false ){
		//Get the person for this booking
		global $wpdb;
	  	if( $booking_data !== false ){
			//Load booking data
			$booking = array();
			if( is_array($booking_data) ){
				$booking = $booking_data;
			}elseif( is_numeric($booking_data) ){
				//Retreiving from the database
				$sql = "SELECT * FROM ". EM_BOOKINGS_TABLE ." WHERE booking_id ='$booking_data'";
				$booking = $wpdb->get_row($sql, ARRAY_A);
			}
			//booking meta
			$booking['booking_meta'] = (!empty($booking['booking_meta'])) ? maybe_unserialize($booking['booking_meta']):array();
			//Save into the object
			$this->to_object($booking);
			$this->previous_status = $this->booking_status;
			$this->get_person();
			$this->timestamp = !empty($booking['booking_date']) ? strtotime($booking['booking_date'], current_time('timestamp')):false;
		}
		//Do it here so things appear in the po file.
		$this->status_array = array(
			0 => __('Pending','events-manager'),
			1 => __('Approved','events-manager'),
			2 => __('Rejected','events-manager'),
			3 => __('Cancelled','events-manager'),
			4 => __('Awaiting Online Payment','events-manager'),
			5 => __('Awaiting Payment','events-manager')
		);
		$this->compat_keys(); //depricating in 6.0
		//do some legacy checking here for bookings made prior to 5.4, due to how taxes are calculated
		$this->get_tax_rate();
		if( !empty($this->legacy_tax_rate) ){
			//reset booking_price, it'll be recalculated later (if you're using this property directly, don't, use $this->get_price())
	    	$this->booking_price = $this->booking_taxes = null;
		}
		do_action('em_booking', $this, $booking_data);
	}
	
	function get_notes(){
		global $wpdb;
		if( !is_array($this->notes) && !empty($this->booking_id) ){
		  	$notes = $wpdb->get_results("SELECT * FROM ". EM_META_TABLE ." WHERE meta_key='booking-note' AND object_id ='{$this->booking_id}'", ARRAY_A);
		  	$this->notes = array();
		  	foreach($notes as $note){
		  		$this->notes[] = unserialize($note['meta_value']);
		  	}
		}elseif( empty($this->booking_id) ){
			$this->notes = array();
		}
		return $this->notes;
	}
	
	/**
	 * Saves the booking into the database, whether a new or existing booking
	 * @param $mail whether or not to email the user and contact people
	 * @return boolean
	 */
	function save($mail = true){
		global $wpdb;
		$table = EM_BOOKINGS_TABLE;
		do_action('em_booking_save_pre',$this);
		if( $this->can_manage() ){
			//update prices, spaces, person_id
			$this->get_spaces(true);
			$this->calculate_price();
			$this->person_id = (empty($this->person_id)) ? $this->get_person()->ID : $this->person_id;			
			//Step 1. Save the booking
			$data = $this->to_array();
			$data['booking_meta'] = serialize($data['booking_meta']);
			//update or save
			if($this->booking_id != ''){
				$update = true;
				$where = array( 'booking_id' => $this->booking_id );  
				$result = $wpdb->update($table, $data, $where, $this->get_types($data));
				$result = ($result !== false);
				$this->feedback_message = __('Changes saved','events-manager');
			}else{
				$update = false;
				$data_types = $this->get_types($data);
				$data['booking_date'] = current_time('mysql');
				$data_types[] = '%s';
				$result = $wpdb->insert($table, $data, $data_types);
			    $this->booking_id = $wpdb->insert_id;  
				$this->feedback_message = __('Your booking has been recorded','events-manager'); 
			}
			//Step 2. Insert ticket bookings for this booking id if no errors so far
			if( $result === false ){
				$this->feedback_message = __('There was a problem saving the booking.', 'events-manager');
				$this->errors[] = __('There was a problem saving the booking.', 'events-manager');
			}else{
				$tickets_bookings_result = $this->get_tickets_bookings()->save();
				if( !$tickets_bookings_result ){
					if( !$update ){
						//delete the booking and tickets, instead of a transaction
						$this->delete();
					}
					$this->errors[] = __('There was a problem saving the booking.', 'events-manager');
					$this->add_error( $this->get_tickets_bookings()->get_errors() );
				}
			}
			//Step 3. email if necessary
			if ( count($this->errors) == 0  && $mail ) {
				$this->email();
			}
			$this->compat_keys();
			return apply_filters('em_booking_save', ( count($this->errors) == 0 ), $this);
		}else{
			$this->feedback_message = __('There was a problem saving the booking.', 'events-manager');
			if( !$this->can_manage() ){
				$this->add_error(sprintf(__('You cannot manage this %s.', 'events-manager'),__('Booking','events-manager')));
			}
		}
		return apply_filters('em_booking_save', false, $this);
	}
	
	/**
	 * Load an record into this object by passing an associative array of table criterie to search for. 
	 * Returns boolean depending on whether a record is found or not. 
	 * @param $search
	 * @return boolean
	 */
	function get($search) {
		global $wpdb;
		$conds = array(); 
		foreach($search as $key => $value) {
			if( array_key_exists($key, $this->fields) ){
				$value = esc_sql($value);
				$conds[] = "`$key`='$value'";
			} 
		}
		$sql = "SELECT * FROM ". $wpdb->EM_BOOKINGS_TABLE ." WHERE " . implode(' AND ', $conds) ;
		$result = $wpdb->get_row($sql, ARRAY_A);
		if($result){
			$this->to_object($result);
			$this->person = new EM_Person($this->person_id);
			return true;	
		}else{
			return false;
		}
	}
	
	/**
	 * Get posted data and save it into the object (not db)
	 * @return boolean
	 */
	function get_post( $override_availability = false ){
		$this->tickets_bookings = new EM_Tickets_Bookings($this->booking_id);
		do_action('em_booking_get_post_pre',$this);
		$result = array();
		$this->event_id = $_REQUEST['event_id'];
		if( isset($_REQUEST['em_tickets']) && is_array($_REQUEST['em_tickets']) && ($_REQUEST['em_tickets'] || $override_availability) ){
			foreach( $_REQUEST['em_tickets'] as $ticket_id => $values){
				//make sure ticket exists
				if( !empty($values['spaces']) || $override_availability ){
					$args = array('ticket_id'=>$ticket_id, 'ticket_booking_spaces'=>$values['spaces'], 'booking_id'=>$this->booking_id);
					if($this->get_event()->get_bookings()->ticket_exists($ticket_id)){
							$EM_Ticket_Booking = new EM_Ticket_Booking($args);
							$EM_Ticket_Booking->booking = $this;
							if( !$this->tickets_bookings->add( $EM_Ticket_Booking, $override_availability ) ){
							    $this->add_error($this->tickets_bookings->get_errors());
							}
					}else{
						$this->errors[]=__('You are trying to book a non-existent ticket for this event.','events-manager');
					}
				}
			}
			$this->booking_comment = (!empty($_REQUEST['booking_comment'])) ? wp_kses_data(stripslashes($_REQUEST['booking_comment'])):'';
			//allow editing of tax rate
			if( !empty($this->booking_id) && $this->can_manage() ){ 
			    $this->booking_tax_rate = (!empty($_REQUEST['booking_tax_rate']) && is_numeric($_REQUEST['booking_tax_rate'])) ? $_REQUEST['booking_tax_rate']:$this->booking_tax_rate; 
			}
			//recalculate spaces/price
			$this->get_spaces(true);
			$this->calculate_price();
			//get person
			$this->get_person();
			//re-run compatiblity keys function
			$this->compat_keys(); //depricating in 6.0
		}
		return apply_filters('em_booking_get_post',count($this->errors) == 0,$this);
	}
	
	function validate( $override_availability = false ){
		//step 1, basic info
		$basic = ( 
			(empty($this->event_id) || is_numeric($this->event_id)) && 
			(empty($this->person_id) || is_numeric($this->person_id)) &&
			is_numeric($this->booking_spaces) && $this->booking_spaces > 0
		);
		//give some errors in step 1
		if( $this->booking_spaces == 0 ){
			$this->add_error(get_option('dbem_booking_feedback_min_space'));
		}
		//step 2, tickets bookings info
		if( count($this->get_tickets_bookings()) > 0 ){
			$ticket_validation = array();
			foreach($this->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){ /* @var $EM_Ticket_Booking EM_Ticket_Booking */
				if ( !$EM_Ticket_Booking->validate() ){
					$ticket_validation[] = false;
					$result = $basic && !in_array(false,$ticket_validation);
				}
				$this->errors = array_merge($this->errors, $EM_Ticket_Booking->get_errors());
			}
			$result = $basic && !in_array(false,$ticket_validation);
		}else{
			$result = false;
		}
		//is there enough space overall?
		if( !$override_availability && $this->get_event()->get_bookings()->get_available_spaces() < $this->get_spaces() ){
		    $result = false;
		    $this->add_error(get_option('dbem_booking_feedback_full'));
		}
		//can we book this amount of spaces at once?
		if( $this->get_event()->event_rsvp_spaces > 0 && $this->get_spaces() > $this->get_event()->event_rsvp_spaces ){
		    $result = false;
		    $this->add_error( sprintf(get_option('dbem_booking_feedback_spaces_limit'), $this->get_event()->event_rsvp_spaces));			
		}
		return apply_filters('em_booking_validate',$result,$this);
	}
	
	/**
	 * Get the total number of spaces booked in THIS booking. Seting $force_refresh to true will recheck spaces, even if previously done so.
	 * @param unknown_type $force_refresh
	 * @return mixed
	 */
	function get_spaces( $force_refresh=false ){
		if($this->booking_spaces == 0 || $force_refresh == true ){
			$this->booking_spaces = $this->get_tickets_bookings()->get_spaces($force_refresh);
		}
		return apply_filters('em_booking_get_spaces',$this->booking_spaces,$this);
	}
	
	/* Price Calculations */
	
	/**
	 * Gets the total price for this whole booking, including any discounts, taxes, and any other additional items. In other words, what the person has to pay or has supposedly paid.
	 * This price shouldn't change once established, unless there's any alteration to the booking itself that'd affect the price, such as a change in ticket numbers, discount, etc.
	 * @param boolean $format
	 * @return double|string
	 */
	function get_price( $format = false, $format_depricated = null ){
	    if( $format_depricated !== null ) $format = $format_depricated; //support for old parameters, will be depricated soon
	    //recalculate price here only if price is not actually set
		if( $this->booking_price === null ){
		    $this->calculate_price();
			$this->booking_price = apply_filters('em_booking_get_price', $this->booking_price, $this);
		}
		//return booking_price, formatted or not
		if($format){
			return $this->format_price($this->booking_price);
		}
		return round($this->booking_price,2);
	}
	
	/**
	 * Total of tickets without taxes, discounts or any other modification. No filter given here for that very reason!
	 * @param boolean $format
	 * @return double|string
	 */
	function get_price_base( $format = false ){
	    $price = $this->get_tickets_bookings()->get_price();
		if($format){
			return $this->format_price($price);
		}
	    return $price;
	}
	
	function get_price_pre_taxes( $format = false ){
	    $price = $base_price = $this->get_price_base();
	    //apply pre-tax discounts
	    $price -= $this->get_price_discounts_amount('pre', $price);
	    if( $price < 0 ){ $price = 0; } //no negative prices
	    //return amount of taxes applied, formatted or not
	    if( $format ) return $this->format_price($price);
	    return $price;
	}
	
	/**
	 * Gets price AFTER taxes and post-tax discounts have also been added
	 * @param boolean $format
	 * @return double|string
	 */
	function get_price_post_taxes( $format = false ){
	    //get price before taxes
	    $price = $this->get_price_pre_taxes();
	    //add taxes to price
	    if( $this->get_tax_rate() > 0 ){
	        $this->booking_taxes = $price * ($this->get_tax_rate()/100); //calculate and save tax amount
		    $price += $this->booking_taxes; //add taxes
		    $this->taxes_applied = true;
	    }
	    //apply post-tax discounts
	    $price -= $this->get_price_discounts_amount('post', $price);
	    if( $price < 0 ){ $price = 0; } //no negative prices
	    //return amount of taxes applied, formatted or not
	    if( $format ) return $this->format_price($price);
	    return $price;
	}
	
	/**
	 * Get amount of taxes applied to this booking price.
	 * @param boolean $format
	 * @return double|string
	 */
	function get_price_taxes( $format=false ){
	    if( $this->booking_taxes !== null ){
	        $this->booking_taxes; //taxes already calculated
	    }else{
	        $this->calculate_price(); //recalculate price and taxes
	    }
		//return amount of taxes applied, formatted or not
	    if( $format ){
	        return $this->format_price($this->booking_taxes);
	    }
	    return $this->booking_taxes;
	}
	
	function get_price_discount(){
	    
	}
	
	/**
	 * Calculates (or recalculates) the price of this booking including taxes, discounts etc., saves it to the booking_price property and writes to relevant properties booking_meta variables
	 * @return double
	 */
	function calculate_price(){
	    //reset price and taxes calculations
	    $this->booking_price = $this->booking_taxes = null;
	    //get post-tax price and save it to booking_price
	    $this->booking_price = apply_filters('em_booking_calculate_price', $this->get_price_post_taxes(), $this);
	    return $this->booking_price; 
	}
	
	/* 
	 * Gets tax rate of booking
	 * @see EM_Object::get_tax_rate()
	 * @return double
	 */
	function get_tax_rate(){
	    if( $this->booking_tax_rate === null ){
	        //booking not saved or tax never defined
	        if( !empty($this->booking_id) && get_option('dbem_legacy_bookings_tax', 'x') !== 'x'){ //even if 0 if defined as tax rate we still use it, delete the option entirely to stop
	            //no tax applied yet to an existing booking, or tax possibly applied (but handled separately in EM_Tickets_Bookings but in legacy < v5.4
	            //sort out MultiSite nuances
	            if( EM_MS_GLOBAL && $this->get_event()->blog_id != get_current_blog_id() ){
	            	//MultiSite AND Global tables enabled AND this event belongs to another blog - get settings for blog that published the event
					$this->booking_tax_rate = get_blog_option($this->get_event()->blog_id, 'dbem_legacy_bookings_tax');
	            }else{
	            	//get booking from current site, whether or not we're in MultiSite
	            	$this->booking_tax_rate = get_option('dbem_legacy_bookings_tax');
	            }
	            $this->legacy_tax_rate = true;
	        }else{
	            //first time we're applying tax rate
	            $this->booking_tax_rate = $this->get_event()->get_tax_rate();
	        }
	    }
	    return $this->booking_tax_rate;
	}
	
	/**
	 * Returns an array of discounts to be applied to a booking. Here is an example of an array item that is expected:
	 * array('name' => 'Name of Discount', 'type'=>'% or #', 'amount'=> 0.00, 'desc' => 'Comments about discount', 'tax'=>'pre/post', 'data' => 'any info for hooks to use' );
	 * About the array keys:
	 * type - $ means a fixed amount of discount, % means a percentage off the base price
	 * amount - if type is a percentage, it is written as a number from 0-100, e.g. 10 = 10%
	 * tax - 'pre' means discount is applied before tax, 'post' means after tax
	 * data - any data to be stored that can be used by actions/filters
	 * @return array
	 */
	function get_price_discounts(){
	    $discounts = array();
	    if( !empty($this->booking_meta['discounts']) && is_array($this->booking_meta['discounts']) ){
	        $discounts = $this->booking_meta['discounts'];
	    }
		return apply_filters('em_booking_get_price_discounts', $discounts, $this);
	}
	
	function get_price_discounts_amount( $pre_or_post = 'pre', $price = false ){
	    $discounts = $this->get_price_discounts_summary($pre_or_post, $price);
	    $discount_amount = 0;
	    foreach($discounts as $discount){
	        $discount_amount += $discount['amount_discounted'];
	    }
	    return $discount_amount;
	}

	function get_price_discounts_summary( $pre_or_post = 'pre', $price = false ){
		$discounts=  $this->get_price_discounts();
		$discount_summary = array();
		if( $price === false ){
			$price = $this->get_price_base();
			if( $pre_or_post == 'post' ) $price = $this->get_price_pre_taxes() + $this->get_price_taxes();
		}
		foreach($discounts as $discount){
			$discount_amount = 0;
			if( !empty($discount['amount']) ){
				if( !empty($discount['tax']) && $discount['tax'] == $pre_or_post ){
					if( !empty($discount['type']) ){
						$discount_summary_item = array('name' => $discount['name'], 'desc' => $discount['desc'], 'discount'=>'0', 'amount_discounted'=>0);
						if( $discount['type'] == '%' ){ //discount by percentage
						    $discount_summary_item['amount_discounted'] = round($price * ($discount['amount']/100),2);
						    $discount_summary_item['amount'] = $this->format_price($discount_summary_item['amount_discounted']);
						    $discount_summary_item['discount'] = number_format($discount['amount'],2).'%';
							$discount_summary[] = $discount_summary_item;
						}elseif( $discount['type'] == '#' ){ //discount by amount
						    $discount_summary_item['amount_discounted'] = round($discount['amount'],2);
						    $discount_summary_item['amount'] = $this->format_price($discount_summary_item['amount_discounted']);
						    $discount_summary_item['discount'] = $this->format_price($discount['amount']);
							$discount_summary[] = $discount_summary_item;
						}
					}
				}
			}
		}
		return $discount_summary;
	}
	
	/**
	 * When generating totals at the bottom of a booking, this creates a useful array for displaying the summary in a meaningful way. 
	 */
	function get_price_summary_array(){
	    $summary = array();
	    //get base price of bookings
	    $summary['total_base'] = $this->get_price_base();
	    //get discounts
	    //apply pre-tax discounts
	    $summary['discounts_pre_tax'] = $this->get_price_discounts_summary('pre');
	    //add taxes to price
		$summary['taxes'] = array('rate'=> 0, 'amount'=> 0);
	    if( $this->get_price_taxes() > 0 ){
		    $summary['taxes'] = array('rate'=> number_format($this->get_tax_rate(),2, get_option('dbem_bookings_currency_decimal_point'), get_option('dbem_bookings_currency_thousands_sep')).'%', 'amount'=> $this->get_price_taxes(true));
	    }
	    //apply post-tax discounts
	    $summary['discounts_post_tax'] = $this->get_price_discounts_summary('post');
	    //final price
	    $summary['total'] =  $this->get_price(true);
	    return $summary;
	}
	
	/* Get Objects linked to booking */
	
	/**
	 * Gets the event this booking belongs to and saves a refernece in the event property
	 * @return EM_Event
	 */
	function get_event(){
		global $EM_Event;
		if( is_object($this->event) && get_class($this->event)=='EM_Event' && $this->event->event_id == $this->event_id ){
			return $this->event;
		}elseif( is_object($EM_Event) && ( (is_object($this->event) && $this->event->event_id == $this->event_id) || empty($this->booking_id)) ){
			$this->event = $EM_Event;
		}else{
			$this->event = new EM_Event($this->event_id, 'event_id');
		}
		return apply_filters('em_booking_get_event',$this->event, $this);
	}
	
	/**
	 * Gets the ticket object this booking belongs to, saves a reference in ticket property
	 * @return EM_Tickets
	 */
	function get_tickets(){
		if( is_object($this->tickets) && get_class($this->tickets)=='EM_Tickets' ){
			return apply_filters('em_booking_get_tickets', $this->tickets, $this);
		}else{
			$this->tickets = new EM_Tickets($this);
		}
		return apply_filters('em_booking_get_tickets', $this->tickets, $this);
	}
	
	/**
	 * Gets the ticket object this booking belongs to, saves a reference in ticket property
	 * @return EM_Tickets_Bookings
	 */
	function get_tickets_bookings(){
		global $wpdb;
		if( !is_object($this->tickets_bookings) || get_class($this->tickets_bookings)!='EM_Tickets_Bookings'){
			$this->tickets_bookings = new EM_Tickets_Bookings($this);
		}
		return apply_filters('em_booking_get_tickets_bookings', $this->tickets_bookings, $this);
	}
	
	/**
	 * @return EM_Person
	 */
	function get_person(){
		global $EM_Person;
		if( is_object($this->person) && get_class($this->person)=='EM_Person' && ($this->person->ID == $this->person_id || empty($this->person_id) ) ){
			//This person is already included, so don't do anything
		}elseif( is_object($EM_Person) && ($EM_Person->ID === $this->person_id || $this->booking_id == '') ){
			$this->person = $EM_Person;
			$this->person_id = $this->person->ID;
		}elseif( is_numeric($this->person_id) ){
			$this->person = new EM_Person($this->person_id);
		}else{
			$this->person = new EM_Person(0);
			$this->person_id = $this->person->ID;
		}
		//if this user is the parent user of disabled registrations, replace user details here:
		if( get_option('dbem_bookings_registration_disable') && $this->person->ID == get_option('dbem_bookings_registration_user') && (empty($this->person->loaded_no_user) || $this->person->loaded_no_user != $this->booking_id) ){
			//override any registration data into the person objet
			if( !empty($this->booking_meta['registration']) ){
				foreach($this->booking_meta['registration'] as $key => $value){
					$this->person->$key = $value;
				}
			}
			$this->person->user_email = ( !empty($this->booking_meta['registration']['user_email']) ) ? $this->booking_meta['registration']['user_email']:$this->person->user_email;
			if( !empty($this->booking_meta['registration']['user_name']) ){
				$name_string = explode(' ',$this->booking_meta['registration']['user_name']); 
				$this->booking_meta['registration']['first_name'] = array_shift($name_string);
				$this->booking_meta['registration']['last_name'] = implode(' ', $name_string);
			}
			$this->person->user_firstname = ( !empty($this->booking_meta['registration']['first_name']) ) ? $this->booking_meta['registration']['first_name']:__('Guest User','events-manager');
			$this->person->first_name = $this->person->user_firstname;
			$this->person->user_lastname = ( !empty($this->booking_meta['registration']['last_name']) ) ? $this->booking_meta['registration']['last_name']:'';
			$this->person->last_name = $this->person->user_lastname;
			$this->person->phone = ( !empty($this->booking_meta['registration']['dbem_phone']) ) ? $this->booking_meta['registration']['dbem_phone']:__('Not Supplied','events-manager');
			//build display name
			$full_name = $this->person->user_firstname  . " " . $this->person->user_lastname ;
			$full_name = trim($full_name);
			$display_name = ( empty($full_name) ) ? __('Guest User','events-manager'):$full_name;
			$this->person->display_name = $display_name;
			$this->person->loaded_no_user = $this->booking_id;
		}
		return apply_filters('em_booking_get_person', $this->person, $this);
	}
	
	/**
	 * Gets personal information from the $_REQUEST array and saves it to the $EM_Booking->booking_meta['registration'] array
	 * @return boolean
	 */
	function get_person_post(){
	    $user_data = array();
	    $registration = true;
	    if( empty($this->booking_meta['registration']) ) $this->booking_meta['registration'] = array();
	    // Check the e-mail address
	    if ( $_REQUEST['user_email'] == '' ) {
	    	$registration = false;
	    	$this->add_error(__( '<strong>ERROR</strong>: Please type your e-mail address.', 'events-manager') );
	    } elseif ( !is_email( $_REQUEST['user_email'] ) ) {
	    	$registration = false;
	    	$this->add_error( __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'events-manager') );
	    }elseif(email_exists( $_REQUEST['user_email'] ) && !get_option('dbem_bookings_registration_disable_user_emails') ){
	    	$registration = false;
	    	$this->add_error( get_option('dbem_booking_feedback_email_exists') );
	    }else{
	    	$user_data['user_email'] = $_REQUEST['user_email'];
	    }
	    //Check the user name
	    if( !empty($_REQUEST['user_name']) ){
	    	//split full name up and save full, first and last names
	    	$user_data['user_name'] = wp_kses($_REQUEST['user_name'], array());
	    	$name_string = explode(' ',$user_data['user_name']);
	    	$user_data['first_name'] = array_shift($name_string);
	    	$user_data['last_name'] = implode(' ', $name_string);
	    }else{
		    //Check the first/last name
		    $name_string = array();
		    if( !empty($_REQUEST['first_name']) ){
		    	$user_data['first_name'] = $name_string[] = wp_kses($_REQUEST['first_name'], array()); 
		    }
		    if( !empty($_REQUEST['last_name']) ){
		    	$user_data['last_name'] = $name_string[] = wp_kses($_REQUEST['last_name'], array());
		    }
		    if( !empty($name_string) ) $user_data['user_name'] = implode(' ', $name_string);
	    }
	    //Save full name
	    if( !empty($user_data['first_name']) || !empty($user_data['last_name']) )
	    //Check the phone
	    if( !empty($_REQUEST['dbem_phone']) ){
	    	$user_data['dbem_phone'] = wp_kses($_REQUEST['dbem_phone'], array());
	    }
	    //Add booking meta
	    if( $registration ){
		    $this->booking_meta['registration'] = array_merge($this->booking_meta['registration'], $user_data);	//in case someone else added stuff
	    }
	    $registration = apply_filters('em_booking_get_person_post', $registration, $this);
	    if( $registration ){
	        $this->feedback_message = __('Personal details have successfully been modified.', 'events-manager');
	    }
	    return $registration;
	}
	
	/**
	 * Displays a form containing user fields, used in no-user booking mode for editing guest users within a booking
	 * @return string
	 */
	function get_person_editor(){
		ob_start();
		$name = $this->get_person()->get_name();
		$email = $this->get_person()->user_email;
		$phone = ($this->get_person()->phone != __('Not Supplied','events-manager')) ? $this->get_person()->phone:'';
		if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'booking_modify_person' ){
		    $name = !empty($_REQUEST['user_name']) ? $_REQUEST['user_name']:$name;
		    $email = !empty($_REQUEST['user_email']) ? $_REQUEST['user_email']:$email;
		    $phone = !empty($_REQUEST['dbem_phone']) ? $_REQUEST['dbem_phone']:$phone;
		}
		?>
		<table class="em-form-fields">
			<tr><th><?php _e('Name','events-manager'); ?> : </th><td><input type="text" name="user_name" value="<?php echo esc_attr($name); ?>" /></td></tr>
			<tr><th><?php _e('Email','events-manager'); ?> : </th><td><input type="text" name="user_email" value="<?php echo esc_attr($email); ?>" /></td></tr>
			<tr><th><?php _e('Phone','events-manager'); ?> : </th><td><input type="text" name="dbem_phone" value="<?php echo esc_attr($phone); ?>" /></td></tr>
		</table>
		<?php
		return apply_filters('em_booking_get_person_editor', ob_get_clean(), $this);
	}

	/**
	 * Returns a string representation of the booking's status
	 * @return string
	 */
	function get_status(){
		$status = ($this->booking_status == 0 && !get_option('dbem_bookings_approval') ) ? 1:$this->booking_status;
		return apply_filters('em_booking_get_status', $this->status_array[$status], $this);
	}
	
	/**
	 * I wonder what this does....
	 * @return boolean
	 */
	function delete(){
		global $wpdb;
		$result = false;
		if( $this->can_manage('manage_bookings','manage_others_bookings') ){
			$sql = $wpdb->prepare("DELETE FROM ". EM_BOOKINGS_TABLE . " WHERE booking_id=%d", $this->booking_id);
			$result = $wpdb->query( $sql );
			if( $result !== false ){
				//delete the tickets too
				$this->get_tickets_bookings()->delete();
				$this->previous_status = $this->booking_status;
				$this->booking_status = false;
				$this->feedback_message = sprintf(__('%s deleted', 'events-manager'), __('Booking','events-manager'));
				$wpdb->delete( EM_META_TABLE, array('meta_key'=>'booking-note', 'object_id' => $this->booking_id), array('%s','%d'));
			}else{
				$this->add_error(sprintf(__('%s could not be deleted', 'events-manager'), __('Booking','events-manager')));
			}
		}
		return apply_filters('em_booking_delete',( $result !== false ), $this);
	}
	
	function cancel($email = true){
		if( $this->person->ID == get_current_user_id() ){
			$this->manage_override = true; //normally, users can't manage a bookiing, only event owners, so we allow them to mod their booking status in this case only.
		}
		return $this->set_status(3, $email);
	}
	
	/**
	 * Approve a booking.
	 * @return bool
	 */
	function approve($email = true, $ignore_spaces = false){
		return $this->set_status(1, $email, $ignore_spaces);
	}	
	/**
	 * Reject a booking and save
	 * @return bool
	 */
	function reject($email = true){
		return $this->set_status(2, $email);
	}	
	/**
	 * Unapprove a booking.
	 * @return bool
	 */
	function unapprove($email = true){
		return $this->set_status(0, $email);
	}
	
	/**
	 * Change the status of the booking. This will save to the Database too. 
	 * @param int $status
	 * @return boolean
	 */
	function set_status($status, $email = true, $ignore_spaces = false){
		global $wpdb;
		$action_string = strtolower($this->status_array[$status]); 
		//if we're approving we can't approve a booking if spaces are full, so check before it's approved.
		if(!$ignore_spaces && $status == 1){
			if( !$this->is_reserved() && $this->get_event()->get_bookings()->get_available_spaces() < $this->get_spaces() && !get_option('dbem_bookings_approval_overbooking') ){
				$this->feedback_message = sprintf(__('Not approved, spaces full.','events-manager'), $action_string);
				$this->add_error($this->feedback_message);
				return apply_filters('em_booking_set_status', false, $this);
			}
		}
		$this->previous_status = $this->booking_status;
		$this->booking_status = $status;
		$result = $wpdb->query($wpdb->prepare('UPDATE '.EM_BOOKINGS_TABLE.' SET booking_status=%d WHERE booking_id=%d', array($status, $this->booking_id)));
		if($result !== false){
			$this->feedback_message = sprintf(__('Booking %s.','events-manager'), $action_string);
			if( $email ){
				if( $this->email() ){
				    if( $this->mails_sent > 0 ){
				        $this->feedback_message .= " ".__('Email Sent.','events-manager');
				    }
				}else{
					//extra errors may be logged by email() in EM_Object
					$this->feedback_message .= ' <span style="color:red">'.__('ERROR : Email Not Sent.','events-manager').'</span>';
					$this->add_error(__('ERROR : Email Not Sent.','events-manager'));
				}
			}
		}else{
			//errors should be logged by save()
			$this->feedback_message = sprintf(__('Booking could not be %s.','events-manager'), $action_string);
			$this->add_error(sprintf(__('Booking could not be %s.','events-manager'), $action_string));
			$result =  false;
		}
		return apply_filters('em_booking_set_status', $result, $this);
	}
	
	/**
	 * Returns true if booking is reserving a space at this event, whether confirmed or not 
	 */
	function is_reserved(){
	    $result = false;
	    if( $this->booking_status == 0 && get_option('dbem_bookings_approval_reserved') ){
	        $result = true;
	    }elseif( $this->booking_status == 0 && !get_option('dbem_bookings_approval') ){
	        $result = true;
	    }elseif( $this->booking_status == 1 ){
	        $result = true;
	    }
	    return apply_filters('em_booking_is_reserved', $result, $this);
	}
	
	/**
	 * Returns true if booking is either pending or reserved but not confirmed (which is assumed pending) 
	 */
	function is_pending(){
		$result = ($this->is_reserved() || $this->booking_status == 0) && $this->booking_status != 1;
	    return apply_filters('em_booking_is_pending', $result, $this);
	}
	
	/**
	 * Add a booking note to this booking. returns wpdb result or false if use can't manage this event.
	 * @param string $note
	 * @return mixed
	 */
	function add_note( $note_text ){
		global $wpdb;
		if( $this->can_manage() ){
			$this->get_notes();
			$note = array('author'=>get_current_user_id(),'note'=>$note_text,'timestamp'=>current_time('timestamp'));
			$this->notes[] = wp_kses_data($note);
			$this->feedback_message = __('Booking note successfully added.','events-manager');
			return $wpdb->insert(EM_META_TABLE, array('object_id'=>$this->booking_id, 'meta_key'=>'booking-note', 'meta_value'=> serialize($note)),array('%d','%s','%s'));
		}
		return false;
	}

	function get_admin_url(){
		if( get_option('dbem_edit_bookings_page') && (!is_admin() || !empty($_REQUEST['is_public'])) ){
			$my_bookings_page = get_permalink(get_option('dbem_edit_bookings_page'));
			$bookings_link = em_add_get_params($my_bookings_page, array('event_id'=>$this->event_id, 'booking_id'=>$this->booking_id), false);
		}else{
			if( $this->get_event()->blog_id != get_current_blog_id() ){
				$bookings_link = get_admin_url($this->get_event()->blog_id, 'edit.php?post_type='.EM_POST_TYPE_EVENT."&page=events-manager-bookings&event_id=".$this->event_id."&booking_id=".$this->booking_id);
			}else{
				$bookings_link = EM_ADMIN_URL. "&page=events-manager-bookings&event_id=".$this->event_id."&booking_id=".$this->booking_id;
			}
		}
		return apply_filters('em_booking_get_bookings_url', $bookings_link, $this);
	}
	
	function output($format, $target="html") {
	 	preg_match_all("/(#@?_?[A-Za-z0-9]+)({([^}]+)})?/", $format, $placeholders);
		foreach( $this->get_tickets() as $EM_Ticket){ /* @var $EM_Ticket EM_Ticket */ break; } //Get first ticket for single ticket placeholders
		$output_string = $format;
		$replaces = array();
		foreach($placeholders[1] as $key => $result) {
			$replace = '';
			$full_result = $placeholders[0][$key];		
			switch( $result ){
				case '#_BOOKINGID':
					$replace = $this->booking_id;
					break;
				case '#_RESPNAME' : //deprecated
				case '#_BOOKINGNAME':
					$replace = $this->get_person()->get_name();
					break;
				case '#_RESPEMAIL' : //deprecated
				case '#_BOOKINGEMAIL':
					$replace = $this->get_person()->user_email;
					break;
				case '#_RESPPHONE' : //deprecated
				case '#_BOOKINGPHONE':
					$replace = $this->get_person()->phone;
					break;
				case '#_BOOKINGSPACES':
					$replace = $this->get_spaces();
					break;
				case '#_BOOKINGDATE':
					$replace = ( $this->timestamp ) ? date_i18n(get_option('dbem_date_format'), $this->timestamp):'n/a';
					break;
				case '#_BOOKINGTIME':
					$replace = ( $this->timestamp ) ? date_i18n(get_option('dbem_time_format'), $this->timestamp):'n/a';
					break;
				case '#_BOOKINGDATETIME':
					$replace = ( $this->timestamp ) ? date_i18n(get_option('dbem_date_format').' '.get_option('dbem_time_format'), $this->timestamp):'n/a';
					break;
				case '#_BOOKINGLISTURL':
					$replace = em_get_my_bookings_url();
					break;
				case '#_COMMENT' : //deprecated
				case '#_BOOKINGCOMMENT':
					$replace = $this->booking_comment;
					break;
					$replace = $this->get_price(true); //if there's tax, it'll be added here
					break;
				case '#_BOOKINGPRICEWITHOUTTAX':
					$replace = $this->format_price($this->get_price() - $this->get_price_taxes());
					break;
				case '#_BOOKINGPRICETAX':
					$replace = $this->get_price_taxes(true);
					break;
				case '#_BOOKINGPRICEWITHTAX':
				case '#_BOOKINGPRICE':
					$replace = $this->get_price(true);
					break;
				case '#_BOOKINGTICKETNAME':
					$replace = $EM_Ticket->name;
					break;
				case '#_BOOKINGTICKETDESCRIPTION':
					$replace = $EM_Ticket->description;
					break;
				case '#_BOOKINGTICKETPRICEWITHTAX':
					$replace = $this->format_price( $EM_Ticket->get_price_without_tax() * (1+$this->get_tax_rate()/100) );
					break;
				case '#_BOOKINGTICKETPRICEWITHOUTTAX':
					$replace = $EM_Ticket->get_price_without_tax(true);
					break;
				case '#_BOOKINGTICKETTAX':
					$replace = $this->format_price( $EM_Ticket->get_price_without_tax() * ($this->get_tax_rate()/100) );
					break;
				case '#_BOOKINGTICKETPRICE':
					$replace = $EM_Ticket->get_price(true);
					break;
				case '#_BOOKINGTICKETS':
					ob_start();
					em_locate_template('emails/bookingtickets.php', true, array('EM_Booking'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_BOOKINGSUMMARY':
					ob_start();
					em_locate_template('emails/bookingsummary.php', true, array('EM_Booking'=>$this));
					$replace = ob_get_clean();
					break;
				default:
					$replace = $full_result;
					break;
			}
			$replaces[$full_result] = apply_filters('em_booking_output_placeholder', $replace, $this, $full_result, $target);
		}
		//sort out replacements so that during replacements shorter placeholders don't overwrite longer varieties.
		krsort($replaces);
		foreach($replaces as $full_result => $replacement){
			$output_string = str_replace($full_result, $replacement , $output_string );
		}
		//run event output too, since this is never run from within events and will not infinitely loop
		$EM_Event = apply_filters('em_booking_output_event', $this->get_event(), $this); //allows us to override the booking event info if it belongs to a parent or translation
		$output_string = $EM_Event->output($output_string, $target);
		return apply_filters('em_booking_output', $output_string, $this, $format, $target);	
	}
	
	/**
	 * @param EM_Booking $EM_Booking
	 * @param EM_Event $event
	 * @return boolean
	 */
	function email( $email_admin = true, $force_resend = false, $email_attendee = true ){
		global $EM_Mailer;
		$result = true;
		$this->mails_sent = 0;
		
		//FIXME ticket logic needed
		$EM_Event = $this->get_event(); //We NEED event details here.
		$EM_Event->get_bookings(true); //refresh all bookings
		
		//Make sure event matches booking, and that booking used to be approved.
		if( $this->booking_status !== $this->previous_status || $force_resend ){
			//messages can be overridden just before being sent
			$msg = $this->email_messages();
			$output_type = get_option('dbem_smtp_html') ? 'html':'email';

			//Send user (booker) emails
			if( !empty($msg['user']['subject']) && $email_attendee ){
				$msg['user']['subject'] = $this->output($msg['user']['subject'], 'raw');
				$msg['user']['body'] = $this->output($msg['user']['body'], $output_type);
				//Send to the person booking
				if( !$this->email_send( $msg['user']['subject'], $msg['user']['body'], $this->get_person()->user_email) ){
					$result = false;
				}else{
					$this->mails_sent++;
				}
			}
			
			//Send admin/contact emails if this isn't the event owner or an events admin
			if( $email_admin && !empty($msg['admin']['subject']) ){ //emails won't be sent if admin is logged in unless they book themselves
				//get admin emails that need to be notified, hook here to add extra admin emails
				$admin_emails = str_replace(' ','',get_option('dbem_bookings_notify_admin'));
				$admin_emails = apply_filters('em_booking_admin_emails', explode(',', $admin_emails), $this); //supply emails as array
				if( get_option('dbem_bookings_contact_email') == 1 && !empty($EM_Event->get_contact()->user_email) ){
				    //add event owner contact email to list of admin emails
				    $admin_emails[] = $EM_Event->get_contact()->user_email;
				}
				foreach($admin_emails as $key => $email){ if( !is_email($email) ) unset($admin_emails[$key]); } //remove bad emails
				//proceed to email admins if need be
				if( !empty($admin_emails) ){
					//Only gets sent if this is a pending booking, unless approvals are disabled.
					$msg['admin']['subject'] = $this->output($msg['admin']['subject'],'raw');
					$msg['admin']['body'] = $this->output($msg['admin']['body'], $output_type);
					//email admins
						if( !$this->email_send( $msg['admin']['subject'], $msg['admin']['body'], $admin_emails) && current_user_can('manage_options') ){
							$this->errors[] = __('Confirmation email could not be sent to admin. Registrant should have gotten their email (only admin see this warning).','events-manager');
							$result = false;
						}else{
							$this->mails_sent++;
						}
				}
			}
		}
		return $result;
		//TODO need error checking for booking mail send
	}	
	
	function email_messages(){
		$msg = array( 'user'=> array('subject'=>'', 'body'=>''), 'admin'=> array('subject'=>'', 'body'=>'')); //blank msg template			
		//admin messages won't change whether pending or already approved
	    switch( $this->booking_status ){
	    	case 0:
	    	case 5: //TODO remove offline status from here and move to pro
	    		$msg['user']['subject'] = get_option('dbem_bookings_email_pending_subject');
	    		$msg['user']['body'] = get_option('dbem_bookings_email_pending_body');
	    		//admins should get something (if set to)
	    		$msg['admin']['subject'] = get_option('dbem_bookings_contact_email_pending_subject');
	    		$msg['admin']['body'] = get_option('dbem_bookings_contact_email_pending_body');
	    		break;
	    	case 1:
	    		$msg['user']['subject'] = get_option('dbem_bookings_email_confirmed_subject');
	    		$msg['user']['body'] = get_option('dbem_bookings_email_confirmed_body');
	    		//admins should get something (if set to)
	    		$msg['admin']['subject'] = get_option('dbem_bookings_contact_email_confirmed_subject');
	    		$msg['admin']['body'] = get_option('dbem_bookings_contact_email_confirmed_body');
	    		break;
	    	case 2:
	    		$msg['user']['subject'] = get_option('dbem_bookings_email_rejected_subject');
	    		$msg['user']['body'] = get_option('dbem_bookings_email_rejected_body');
	    		//admins should get something (if set to)
	    		$msg['admin']['subject'] = get_option('dbem_bookings_contact_email_rejected_subject');
	    		$msg['admin']['body'] = get_option('dbem_bookings_contact_email_rejected_body');
	    		break;
	    	case 3:
	    		$msg['user']['subject'] = get_option('dbem_bookings_email_cancelled_subject');
	    		$msg['user']['body'] = get_option('dbem_bookings_email_cancelled_body');
	    		//admins should get something (if set to)
	    		$msg['admin']['subject'] = get_option('dbem_bookings_contact_email_cancelled_subject');
	    		$msg['admin']['body'] = get_option('dbem_bookings_contact_email_cancelled_body');
	    		break;
	    }
	    return apply_filters('em_booking_email_messages', $msg, $this);
	}
	
	/**
	 * Can the user manage this event? 
	 */
	function can_manage( $owner_capability = false, $admin_capability = false, $user_to_check = false ){
		return $this->get_event()->can_manage('manage_bookings','manage_others_bookings') || empty($this->booking_id) || !empty($this->manage_override);
	}
	
	/**
	 * Returns this object in the form of an array
	 * @return array
	 */
	function to_array($person = false){
		$booking = array();
		//Core Data
		$booking = parent::to_array();
		//Person Data
		if($person && is_object($this->person)){
			$person = $this->person->to_array();
			$booking = array_merge($booking, $person);
		}
		return $booking;
	}
}
?>