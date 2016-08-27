<?php
class EM_Multiple_Booking extends EM_Booking{
    
    /**
     * array of booking objects related to this group of bookings
     * @var array
     */
    public $bookings = array();
    public $booking_status = 0;
    
    function __construct( $booking_data = false ){
        global $wpdb;
        //add extra cleaning function in case we're wanting to convert a normal EM_Booking object
        if( is_object($booking_data) && get_class($booking_data) == 'EM_Booking' ){
            $booking_data = $booking_data->to_array();
        }
        //load like a normal booking
        parent::__construct( $booking_data );
    }
    
    function get_bookings( $force_refresh = false ){
        global $wpdb;
    	if( empty($this->bookings) || $force_refresh ){
	        //get bookings related to this object and load into $bookings object
	        if( !empty($this->booking_id) ){
	        	$booking_relationships = $wpdb->get_results("SELECT booking_id, event_id FROM ".$wpdb->prefix."em_bookings_relationships WHERE booking_main_id='{$this->booking_id}'", ARRAY_A);
	        	$bookings = array();
	        	foreach( $booking_relationships as $booking_data ){
	        		$EM_Booking = em_get_booking($booking_data['booking_id']);
	        		$this->bookings[$booking_data['event_id']] = $EM_Booking;
	        	}
	        }
    	}
    	return $this->bookings;
    }
	
    function add_booking( $EM_Booking ){
        if( empty($this->bookings[$EM_Booking->event_id]) ){
            //add booking to cart session
            $booking = clone($EM_Booking); //clone and strip unecessary objects to avoid large session file buildup
            $booking->tickets = null;
            $booking->event = null;
            //$booking->tickets_bookings->booking = null;
            foreach($booking->tickets_bookings->tickets_bookings as $key => $EM_Ticket_Booking){
                $booking->tickets_bookings->tickets_bookings[$key]->event = null;
                //$booking->tickets_bookings->tickets_bookings[$key]->booking = null;
                $booking->tickets_bookings->tickets_bookings[$key]->ticket = null;
            }
			$this->bookings[$EM_Booking->event_id] = $EM_Booking;
			$this->tickets_bookings = null; //so that this is done again
			$this->tickets = null; //so that this is done again
			$this->calculate_price();
            return true;            
        }else{
            //error, booking for event already exists
            $EM_Booking->add_error( get_option('dbem_multiple_bookings_feedback_already_added') );
            return false;
        }
    }
    
	function get_post( $override_availability = false ){
	    //reset booking meta to prevent previously saved data being used for empty booking fields
	    $this->booking_meta['booking'] = array();
	    $this->booking_meta['registration'] = array();
	    //let forms etc. do their thing
	    return apply_filters('em_multiple_booking_get_post', true, $this); 
	}

	function validate(){
	    //reset errors since this is always using sessions and we're about to revalidate
	    $this->errors = array(); 
	    //let forms etc. do their thing
	    return apply_filters('em_multiple_booking_validate', true, $this); 
	}

	function validate_bookings(){
	    $result = true;
	    foreach( $this->get_bookings() as $EM_Booking ){
	        $EM_Booking->errors = array();
	    	if( !$EM_Booking->validate() ){
	    		$result = false;
	    		$this->add_error($EM_Booking->get_errors());
	    	}
	    }
	    return $result;
	}
	
	function validate_bookings_spaces(){
	    $result = true;
	    foreach( $this->get_bookings() as $EM_Booking ){ /* @var $EM_Booking EM_Booking */
	        $EM_Booking->errors = array();
	        if( empty($EM_Booking->booking_id) ){ //only do this for non-saved bookings
	            $has_space = true;
		        foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking ){ /* @var $EM_Ticket_Booking EM_Ticket_Booking */
		            if( $EM_Ticket_Booking->get_spaces() > $EM_Ticket_Booking->get_ticket()->get_available_spaces() ){
		                $has_space = false;
		                break;
		            }
		        }
		        if( !$has_space ){
		            $this->add_error( sprintf(__('Unfortunately, your booking for %s was removed from your cart as there are not enough spaces anymore.'), $EM_Booking->get_event()->output('#_EVENTLINK')) );
		            unset($this->bookings[$EM_Booking->event_id]);
		            $result = false;
		        }
	        }
	    }
	    return $result;
	}
	
	/**
	 * Saves all bookings into the database, whether a new or existing booking
	 * @param boolean $mail
	 * @return boolean
	 */
	function save_bookings(){
	    /* @var $EM_Booking EM_Booking */
		//save each booking
		global $wpdb;
		$result = true;
		foreach($this->get_bookings() as $EM_Booking){
		    //assign person/registration info to this booking, overwrites any previous value
		    $EM_Booking->person_id = $this->person_id;
		    //TODO can probably add more to this, e.g. add everything EXCEPT 'booking', e.g. coupon too
		    $EM_Booking->booking_meta['registration'] = $this->booking_meta['registration'];
		    $EM_Booking->booking_meta['gateway'] = $this->booking_meta['gateway'];
		    $EM_Booking->booking_status = $this->booking_status;
		    //save the booking
		    if( !$EM_Booking->save(false) ){ //no mails please
		        $result = false;
		        $this->add_error($EM_Booking->get_errors());
		    }
		}
	    if( $result ){
	        //if all went well, save master booking
	        $saved = apply_filters('em_multiple_booking_save', $this->save( false ), $this);
	        if( $saved ){
	            //firstly delete all previous relationships if they exist
	            $wpdb->query($wpdb->prepare('DELETE FROM '.EM_BOOKINGS_RELATIONSHIPS_TABLE.' WHERE booking_main_id=%d', $this->booking_id));
	            //create new relations between bookings and master booking
	            $rel_inserts = array();
	            foreach($this->get_bookings() as $EM_Booking){
	            	$rel_inserts[] = $wpdb->insert(EM_BOOKINGS_RELATIONSHIPS_TABLE, array('booking_id'=>$EM_Booking->booking_id, 'booking_main_id'=>$this->booking_id, 'event_id'=>$EM_Booking->event_id));
	            }
	        }
	    }
	    if( $result && $saved && !in_array(false, $rel_inserts) ){
	        //successfully saved everything
	        //same concept/code to what we do with EM_Bookings::add();
		    do_action('em_bookings_added', $this);
			$email = $this->email();
			if( get_option('dbem_bookings_approval') == 1 && $this->booking_status == 0){
				$this->feedback_message = get_option('dbem_booking_feedback_pending');
			}else{
				$this->feedback_message = get_option('dbem_booking_feedback');
			}
			if( !$email ){
				$this->email_not_sent = true;
				$this->feedback_message .= ' '.get_option('dbem_booking_feedback_nomail');
				if( current_user_can('activate_plugins') ){
					if( count($this->get_errors()) > 0 ){
						$this->feedback_message .= '<br/><strong>Errors:</strong> (only admins see this message)<br/><ul><li>'. implode('</li><li>', $this->get_errors()).'</li></ul>';
					}else{
						$this->feedback_message .= '<br/><strong>No errors returned by mailer</strong> (only admins see this message)';
					}
				}
			}
	        return true;
	    }else{
	        //something went wrong - roll back and delete
	        $this->manage_override = true;
	        foreach($this->get_bookings() as $EM_Booking){
	            $EM_Booking->manage_override = true;
	            if( !empty($EM_Booking->booking_id) ){
	                $EM_Booking->delete();
	                $EM_Booking->booking_id = null;
	                $EM_Booking->get_tickets_bookings()->booking_id = null;
	                foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){ /* @var $EM_Ticket_Booking EM_Ticket_Booking */
	                    $EM_Ticket_Booking->booking_id = null;
	                    $EM_Ticket_Booking->ticket_booking_id = null;
	                }
	            }
	        }
	        if( !empty($this->booking_id) ){
	            $wpdb->query("DELETE FROM ".EM_BOOKINGS_RELATIONSHIPS_TABLE." WHERE booking_main_id='{$this->booking_id}'");
	            $this->delete();
	            $this->booking_id = null;
	        }
	        return false;
	    }
	}
	
	/**
	 * Gets the total price for this whole booking, including taxes, discounts, etc.
	 * @param boolean $format
	 * @return float
	 */
	function get_price( $format=false ){
		if( $this->booking_price === null ){
			$this->booking_price = 0;
			foreach($this->get_bookings() as $EM_Booking){
			    $this->booking_price += $EM_Booking->get_price();
			}
			$this->booking_price = apply_filters('em_multiple_booking_get_price', $this->booking_price, $this);
		}
		if($format){
			return $this->format_price($this->booking_price);
		}
		return apply_filters('em_multiple_booking_get_price', $this->booking_price, $this);
	}
	
	function get_price_base( $format=false ){
	    $base_price = 0;
	    foreach($this->get_bookings() as $EM_Booking){
    		//get post-tax price and save it to booking_price
    		$base_price += $EM_Booking->get_price_base();
		}
		//return booking_price, formatted or not
		if($format){
			return $this->format_price($base_price);
		}
		return $base_price;
	}	

	/**
	 * Gets the event this booking belongs to and saves a refernece in the event property
	 * @return EM_Event
	 */
	function get_event(){
		global $EM_Event;
		if( !is_object($this->event) || get_class($this->event) !='EM_Event' ){
			if( count($this->get_bookings()) == 1 ){
			    foreach( $this->get_bookings() as $EM_Booking ){
				    $this->event = $EM_Booking->get_event();
				    break;
			    }
			}else{
			    $this->event = new EM_Event();
			    $this->event->event_name = __('Multiple Events','em-pro');
			}
		}elseif( $this->event->event_id && count($this->get_bookings()) != 1 ){
		    $this->event = new EM_Event(); //reset event object
			$this->event->event_name = __('Multiple Events','em-pro');
		}
		return $this->event;
	}

	/**
	 * Gets the tickets object but containing all events 
	 * @return EM_Tickets
	 */
	function get_tickets(){
		if( !is_object($this->tickets) || get_class($this->tickets) !='EM_Tickets' ){
		    //Get tickets for every booking within this multiple booking
			$this->tickets = new EM_Tickets();
		    foreach($this->get_bookings() as $EM_Booking){
	        	$this->tickets->tickets = $this->tickets->tickets + $EM_Booking->get_tickets()->tickets;
		    }
		}
		return $this->tickets;
	}

	/**
	 * Gets the tickets_bookings object with ALL tickets_bookings records for every event.
	 * @return EM_Tickets_Bookings
	 */
	function get_tickets_bookings(){
		global $wpdb;
		if( !is_object($this->tickets_bookings) || get_class($this->tickets_bookings)!='EM_Tickets_Bookings'){
		    //get array of booking ids and pass this on below
			$this->tickets_bookings = new EM_Tickets_Bookings();
			foreach($this->get_bookings() as $EM_Booking){
			    $this->tickets_bookings->tickets_bookings = $this->tickets_bookings->tickets_bookings + $EM_Booking->get_tickets_bookings()->tickets_bookings; 
			}
		}
		return $this->tickets_bookings;
	}

	/**
	 * Deletes ALL bookings related to this booking and then deletes this booking group 
	 * @return boolean
	 */
	function delete(){
		return false;
	}
	
	/**
	 * Change the status of the booking group and sub-bookings.
	 * @param int $status
	 * @return boolean
	 */
	function set_status($status, $email = true, $ignore_spaces = false){
	    $result = parent::set_status($status, $email, true);
	    if( $result ){
		    //we're going to set all of the bookings to this status with one SQL statement, to prevent unecessary hooks from firing
		    $booking_ids = array();
			foreach($this->get_bookings() as $EM_Booking){
				$EM_Booking->previous_status = $this->booking_status;
				$EM_Booking->booking_status = $status;
				if( !empty($EM_Booking->booking_id) ){
				    $booking_ids[] = $EM_Booking->booking_id;
				}
			}
			if( !empty($booking_ids) && is_numeric($status) ){
			    global $wpdb;
			    $result = $wpdb->query('UPDATE '.EM_BOOKINGS_TABLE.' SET booking_status='.$status.' WHERE booking_id IN ('.implode(',',$booking_ids).')');
			}
	    }
	    return $result;
	}

	/* (non-PHPdoc)
	 * Not a booking for an actual event, so never 'reserved'
	 * @see EM_Booking::is_reserved()
	 */
	function is_reserved(){
		return false;
	}
	
	function get_admin_url(){
	    $admin_url = parent::get_admin_url();
	    $admin_url = em_add_get_params($admin_url, array('event_id'=>null, 'action'=>'multiple_booking'));
		return $admin_url;
	}
	
	function output($format, $target="html") {
	 	preg_match_all("/(#@?_?[A-Za-z0-9]+)({([^}]+)})?/", $format, $placeholders);
		$output_string = $format;
		$replaces = array();
		foreach($placeholders[1] as $key => $result) {
			$replace = '';
			$full_result = $placeholders[0][$key];		
			switch( $result ){
				case '#_BOOKINGTICKETNAME':
				case '#_BOOKINGTICKETDESCRIPTION':
				case '#_BOOKINGTICKETPRICEWITHTAX':
				case '#_BOOKINGTICKETPRICEWITHOUTTAX':
				case '#_BOOKINGTICKETTAX':
				case '#_BOOKINGTICKETPRICE':
					$replace = ''; //this booking object doesn't have 'tickets', all these become defunct
					break;
				case '#_BOOKINGTICKETS':
				    //change how this placeholder displays, for backwards compatability
					ob_start();
					emp_locate_template('placeholders/bookingtickets-multiple.php', true, array('EM_Multiple_Booking'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_BOOKINGSUMMARY':
				    //change how this placeholder displays, for backwards compatability
					ob_start();
					emp_locate_template('placeholders/bookingsummary-multiple.php', true, array('EM_Multiple_Booking'=>$this));
					$replace = ob_get_clean();
					break;
				case '#_BOOKINGATTENDEES':
				    //change how this placeholder displays, for backwards compatability
					ob_start();
					emp_locate_template('placeholders/bookingattendees-multiple.php', true, array('EM_Multiple_Booking'=>$this));
					$replace = ob_get_clean();
					break;
				default:
					$replace = $full_result;
					break;
			}
			$replaces[$full_result] = apply_filters('em_multiple_booking_output_placeholder', $replace, $this, $full_result, $target);
		}
		//sort out replacements so that during replacements shorter placeholders don't overwrite longer varieties.
		krsort($replaces);
		foreach($replaces as $full_result => $replacement){
			$output_string = str_replace($full_result, $replacement , $output_string );
		}
		$output_string = parent::output($output_string, $target); //run through original booking object for commonly used fields
		
		return apply_filters('em_multiple_booking_output', $output_string, $this, $format, $target);	
	}

	//since we're always dealing with a single email
	function email( $email_admin = true, $force_resend = false ){
		if( get_option('dbem_multiple_bookings_contact_email') ){ //we also email individual booking emails to the individual event owners
		    foreach($this->get_bookings() as $EM_Booking){
		        $EM_Booking->email($email_admin, $force_resend, false);
		    }
		}
		return parent::email( $email_admin, $force_resend );
	}

	/**
	 * Overrides the booking email content function and uses multiple booking templates
	 * @return array
	 */
	function email_messages(){
		$msg = array( 'user'=> array('subject'=>'', 'body'=>''), 'admin'=> array('subject'=>'', 'body'=>'')); //blank msg template			
		//admin messages won't change whether pending or already approved
		switch( $this->booking_status ){
			case 0:
			case 5:
				$msg['user']['subject'] = get_option('dbem_multiple_bookings_email_pending_subject');
				$msg['user']['body'] = get_option('dbem_multiple_bookings_email_pending_body');
				//admins should get something (if set to)
				$msg['admin']['subject'] = get_option('dbem_multiple_bookings_contact_email_subject');
				$msg['admin']['body'] = get_option('dbem_multiple_bookings_contact_email_body');
				break;
			case 1:
				$msg['user']['subject'] = get_option('dbem_multiple_bookings_email_confirmed_subject');
				$msg['user']['body'] = get_option('dbem_multiple_bookings_email_confirmed_body');
				//admins should get something (if set to)
				$msg['admin']['subject'] = get_option('dbem_multiple_bookings_contact_email_subject');
				$msg['admin']['body'] = get_option('dbem_multiple_bookings_contact_email_body');
				break;
			case 2:
				$msg['user']['subject'] = get_option('dbem_multiple_bookings_email_rejected_subject');
				$msg['user']['body'] = get_option('dbem_multiple_bookings_email_rejected_body');
				break;
			case 3:
				$msg['user']['subject'] = get_option('dbem_multiple_bookings_email_cancelled_subject');
				$msg['user']['body'] = get_option('dbem_multiple_bookings_email_cancelled_body');
				//admins should get something (if set to)
				$msg['admin']['subject'] = get_option('dbem_multiple_bookings_contact_email_cancelled_subject');
				$msg['admin']['body'] = get_option('dbem_multiple_bookings_contact_email_cancelled_body');
				break;
		}
	    return apply_filters('em_multiple_booking_email_messages', $msg, $this);
	}

	/**
	 * To manage a multiple booking, only event admins can see the whole set of bookings.
	 */
	function can_manage(){
		return empty($this->booking_id) || current_user_can('manage_others_bookings');
	}
}
?>