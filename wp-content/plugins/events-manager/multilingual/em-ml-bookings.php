<?php
class EM_ML_Bookings {
    
    public static function init(){
        add_action('em_booking_add','EM_ML_Bookings::em_booking_add', 1, 3);
		add_filter('em_event_get_bookings', 'EM_ML_Bookings::override_bookings',100,2);
		add_action('em_booking_form_footer','EM_ML_Bookings::em_booking_form_footer',10,1);
		add_action('em_booking_output_event', 'EM_ML_Bookings::em_booking_output_event',10,2);
		add_filter('em_booking_email_messages', 'EM_ML_Bookings::em_booking_email_messages',10,2);		
    }
    
    /**
     * @param EM_Booking $EM_Booking
     */
    public static function em_booking_add( $EM_Event, $EM_Booking ){
        if( empty($EM_Booking->booking_id) && EM_ML::$current_language != EM_ML::$wplang ){
            $EM_Booking->booking_meta['lang'] = EM_ML::$current_language;
        }
    }
	
	public static function override_bookings($EM_Bookings, $EM_Event){
		if( !EM_ML::is_original($EM_Event) ){
		    $event = EM_ML::get_original_event($EM_Event);
		    if( !empty($EM_Bookings->translated) ){
		        //we've already done this before, so we just need to make sure the event id isn't being reset to the translated event id
		        $EM_Bookings->event_id = $event->event_id;
		    }else{
		        //bookings hasn't been 'translated' yet, so we get the original event, get the EM_Bookings object and replace the current event with it. 
    			$EM_Bookings = new EM_Bookings($event);
    			$EM_Bookings->event_id = $event->event_id;
    			$EM_Bookings->translated = true;
    			//go through tickets and translate to appropriate language
    			$event_lang = EM_ML::get_the_language($EM_Event);
    			foreach($EM_Bookings->get_tickets()->tickets as $EM_Ticket){ /* @var $EM_Ticket EM_Ticket */
    			    if( !empty($EM_Ticket->ticket_meta['langs'][$event_lang]['ticket_name']) ){
    			        $EM_Ticket->ticket_name = $EM_Ticket->ticket_meta['langs'][$event_lang]['ticket_name'];
    			    }
    			    if( !empty($EM_Ticket->ticket_meta['langs'][$event_lang]['ticket_description']) ){
    			        $EM_Ticket->ticket_description = $EM_Ticket->ticket_meta['langs'][$event_lang]['ticket_description'];
    			    }
    			}
		    }
		}
		return $EM_Bookings;
	}
	
	public static function em_booking_form_footer($EM_Event){
	    if( EM_ML::$current_language != EM_ML::$wplang || EM_ML::$current_language != EM_ML::get_the_language($EM_Event) ){
	        echo '<input type="hidden" name="em_lang" value="'.EM_ML::$current_language.'" />';
	    }
	}
	
	/**
	 * Switches the event related to this booking if a translation was booked, so that when outputting information like emails, event info shows in appropriate language
	 * @param EM_Event $EM_Event
	 * @param EM_Booking $EM_Booking
	 */
	public static function em_booking_output_event($EM_Event, $EM_Booking){
	    if( !empty($EM_Booking->booking_meta['lang']) && EM_ML::get_the_language($EM_Event) != $EM_Booking->booking_meta['lang'] ){
		    $event = EM_ML::get_translation($EM_Event, $EM_Booking->booking_meta['lang']);
		    $EM_Booking->event = $event;
		    return $event;
		}
	    return $EM_Event;
	}
	
	public static function em_booking_email_messages($msg, $EM_Booking){
	    //only proceed if booking was in another language AND we're not in the current language given the option is translated automatically
	    if( !empty($EM_Booking->booking_meta['lang']) && EM_ML::$current_language != $EM_Booking->booking_meta['lang'] ){
	        $lang = $EM_Booking->booking_meta['lang'];
		    //get the translated event
	        $EM_Event = EM_ML::get_translation($EM_Booking->get_event(), $lang);
	        //check that we're not already dealing with the translated event
	        if( $EM_Event->post_id != $EM_Booking->get_event()->post_id ){
	            //below is copied script from EM_Booking::email_messages() replacing get_option with EM_ML_Options::get_option() supplying the booking language 
        	    switch( $EM_Booking->booking_status ){
        	    	case 0:
        	    	case 5: //TODO remove offline status from here and move to pro
        	    		$msg['user']['subject'] = EM_ML_Options::get_option('dbem_bookings_email_pending_subject', $lang);
        	    		$msg['user']['body'] = EM_ML_Options::get_option('dbem_bookings_email_pending_body', $lang);
        	    		//admins should get something (if set to)
        	    		$msg['admin']['subject'] = EM_ML_Options::get_option('dbem_bookings_contact_email_pending_subject', $lang);
        	    		$msg['admin']['body'] = EM_ML_Options::get_option('dbem_bookings_contact_email_pending_body', $lang);
        	    		break;
        	    	case 1:
        	    		$msg['user']['subject'] = EM_ML_Options::get_option('dbem_bookings_email_confirmed_subject', $lang);
        	    		$msg['user']['body'] = EM_ML_Options::get_option('dbem_bookings_email_confirmed_body', $lang);
        	    		//admins should get something (if set to)
        	    		$msg['admin']['subject'] = EM_ML_Options::get_option('dbem_bookings_contact_email_confirmed_subject', $lang);
        	    		$msg['admin']['body'] = EM_ML_Options::get_option('dbem_bookings_contact_email_confirmed_body', $lang);
        	    		break;
        	    	case 2:
        	    		$msg['user']['subject'] = EM_ML_Options::get_option('dbem_bookings_email_rejected_subject', $lang);
        	    		$msg['user']['body'] = EM_ML_Options::get_option('dbem_bookings_email_rejected_body', $lang);
        	    		//admins should get something (if set to)
        	    		$msg['admin']['subject'] = EM_ML_Options::get_option('dbem_bookings_contact_email_rejected_subject', $lang);
        	    		$msg['admin']['body'] = EM_ML_Options::get_option('dbem_bookings_contact_email_rejected_body', $lang);
        	    		break;
        	    	case 3:
        	    		$msg['user']['subject'] = EM_ML_Options::get_option('dbem_bookings_email_cancelled_subject', $lang);
        	    		$msg['user']['body'] = EM_ML_Options::get_option('dbem_bookings_email_cancelled_body', $lang);
        	    		//admins should get something (if set to)
        	    		$msg['admin']['subject'] = EM_ML_Options::get_option('dbem_bookings_contact_email_cancelled_subject', $lang);
        	    		$msg['admin']['body'] = EM_ML_Options::get_option('dbem_bookings_contact_email_cancelled_body', $lang);
        	    		break;
        	    }  
        	}
	    }
	    return $msg;
	}
}
EM_ML_Bookings::init();