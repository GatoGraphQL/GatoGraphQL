<?php
/**
 * Model-related functions. Each ML plugin will do its own thing so must be accounted for accordingly. Below is a description of things that should happen so everything 'works'.
 * 
 * When an event or location is saved, we need to perform certain options depending whether saved on the front-end editor, 
 * or if saved/translated in the backend, since events share information across translations.
 * 
 * Event translations should assign one event to be the 'original' event, meaning bookings and event times will be managed by the 'orignal' event.
 * Since ML Plugins can change default languages and you can potentially create events in non-default languages first, the first language will be the 'orignal' event.
 * If an event is deleted and is the original event, but there are still other translations, the original event is reassigned to the default language translation, or whichever other event is found first
 */
class EM_ML_IO {
    
    public static function init(){
        //Saving/Editing
        add_filter('em_event_save_meta','EM_ML_IO::event_save_meta',1000000000000,2); //let other add-ons hook in first
        add_filter('em_event_get_post_meta','EM_ML_IO::event_get_post_meta',10,2);
        add_filter('em_location_save_meta','EM_ML_IO::location_save_meta',1000000000000,2); //let other add-ons hook in first
        add_filter('em_location_get_post_meta','EM_ML_IO::location_get_post_meta',10,2);
        //Deletion
        add_action('em_event_delete_meta_event_pre', 'EM_ML_IO::event_delete', 10, 1);
        add_action('em_location_delete_meta_pre', 'EM_ML_IO::location_delete', 10, 1);
        //Loading
        add_filter('em_event_get_location','EM_ML_IO::event_get_location',10,2);
        //Duplication link
        add_filter('em_event_duplicate_url','EM_ML_IO::event_duplicate_url',10, 2);
    }
    
    /**
     * Changes necessary event location to same language as event if different
     * @param EM_Event $EM_Event
     */
    public static function event_get_location( $EM_Location, $EM_Event ){
        if( $EM_Location->location_id ){
            $event_lang = EM_ML::get_the_language($EM_Event);
            $location_lang = EM_ML::get_the_language($EM_Location);
            if( $event_lang != $location_lang ){
                $EM_Location = EM_ML::get_translation($EM_Location, $event_lang);
            }
        }
        return $EM_Location;
    }
    
    public static function event_save_meta($result, $EM_Event){
        if( $result && EM_ML::is_original($EM_Event) ){
            //save post meta for all others as well
            foreach( EM_ML::get_langs() as $lang_code => $language ){
                $event = EM_ML::get_translation($EM_Event, $lang_code); /* @var $EM_Event EM_Event */
                if( $event->event_id != $EM_Event->event_id ){
                    self::event_merge_original_meta($event, $EM_Event);
                    $event->save_meta();
                }
            }
        }
        return $result;
    }
    
    public static function event_merge_original_meta( $EM_Event, $event ){
        $EM_Event->original_event_id = $event->event_id;
        //set values over from original event
        $EM_Event->event_start_date  = $event->event_start_date ;
		$EM_Event->event_end_date  = $event->event_end_date ;
		$EM_Event->recurrence  = $event->recurrence ;
		$EM_Event->post_type  = $event->post_type ;
		$EM_Event->location_id  = $event->location_id ;
		$EM_Event->location = false;
		$EM_Event->event_all_day  = $event->event_all_day ;
		$EM_Event->event_start_time  = $event->event_start_time ;
		$EM_Event->event_end_time  = $event->event_end_time ;
		$EM_Event->start  = $event->start ;
		$EM_Event->end  = $event->end ;
		$EM_Event->event_rsvp_date  = $event->event_rsvp_date ;
			
		$EM_Event->event_rsvp  = $event->event_rsvp ;
		$EM_Event->event_rsvp_time  = $event->event_rsvp_time ;
						
		$EM_Event->blog_id  = $event->blog_id ;
		$EM_Event->group_id  = $event->group_id ;
		$EM_Event->recurrence  = $event->recurrence ;
		$EM_Event->recurrence_freq  = $event->recurrence_freq ;
		$EM_Event->recurrence_byday  = $event->recurrence_byday ;
		$EM_Event->recurrence_interval  = $event->recurrence_interval ;
		$EM_Event->recurrence_byweekno  = $event->recurrence_byweekno ;
		$EM_Event->recurrence_days  = $event->recurrence_days ;
		self::event_merge_original_attributes($EM_Event, $event);
    }
    
    public static function event_merge_original_attributes($EM_Event, $event){
		//merge attributes
		$event->event_attributes = maybe_unserialize($event->event_attributes);
		$EM_Event->event_attributes = maybe_unserialize($EM_Event->event_attributes);
		foreach($event->event_attributes as $event_attribute_key => $event_attribute){
			if( !empty($event_attribute) && empty($EM_Event->event_attributes[$event_attribute_key]) ){
				$EM_Event->event_attributes[$event_attribute_key] = $event_attribute;
			}
		}
    }
    
    /**
     * Hooks into em_event_get_post and writes the original event translation data into the current event, to avoid validation errors and correct data saving.
     * @param boolean $result
     * @param EM_Event $EM_Event
     * @return boolean
     */
    public static function event_get_post_meta($result, $EM_Event){
        //check if this is a master event, if not then we need to get the relevant master event info and populate this object with it so it passes validation and saves correctly.
        if( !EM_ML::is_original($EM_Event) ){
            //get original event object
            $event = EM_ML::get_original_event($EM_Event);
            EM_ML_IO::event_merge_original_meta($EM_Event, $event);
			
			if( $EM_Event->location_id == 0 ) $_POST['no_location'] = 1;
			// We need to save ticket translations here as well to the ticket objects
			foreach( $EM_Event->get_tickets()->tickets as $EM_Ticket ){ /* @var $EM_Ticket EM_Ticket */
			    $ticket_translation = array();
			    if( !empty($_REQUEST['ticket_translations'][$EM_Ticket->ticket_id]['ticket_name'] ) ) $ticket_translation['ticket_name'] = wp_kses_data(stripslashes($_REQUEST['ticket_translations'][$EM_Ticket->ticket_id]['ticket_name']));
			    if( !empty($_REQUEST['ticket_translations'][$EM_Ticket->ticket_id]['ticket_description'] ) ) $ticket_translation['ticket_description'] = wp_kses_post(stripslashes($_REQUEST['ticket_translations'][$EM_Ticket->ticket_id]['ticket_description']));
			    if( !empty($ticket_translation) ) $EM_Ticket->ticket_meta['langs'][EM_ML::$current_language] = $ticket_translation;
			}
        }elseif( !empty($EM_Event->location_id) ){
            //we need to make sure the location is the original location
            $EM_Location = $EM_Event->get_location();
            if( !EM_ML::is_original($EM_Location) ){
                $EM_Event->location_id = EM_ML::get_original_location($EM_Location)->location_id;
            }
        }
        return $result;
    }
	
	/**
	 * When a master event is deleted, translations are not necessarily deleted so things like bookings must be transferred to a translation and that must now be the master event.
	 * @param EM_Event $EM_Location
	 */
	public static function event_delete($EM_Event){
		global $wpdb, $sitepress;
		if( EM_ML::is_original($EM_Event) ){
			//check to see if there's any translations of this event
		    $event = EM_ML::get_translation($EM_Event);
			//if so check if the default language still exists
			if( $EM_Event->event_id != $event->event_id && !empty($event->event_id) ){
				//make that translation the master event by changing event ids of bookings, tickets etc. to the new master event
				$wpdb->update(EM_TICKETS_TABLE, array('event_id'=>$event->event_id), array('event_id'=>$EM_Event->event_id));
				$wpdb->update(EM_BOOKINGS_TABLE, array('event_id'=>$event->event_id), array('event_id'=>$EM_Event->event_id));
				do_action('em_ml_transfer_original_event', $event, $EM_Event); //other add-ons with tables with event_id foreign keys should hook here and change
			}
		}
	}
    
	/**
	 * Changes the event id of the link for duplication so that it duplicates the original event instead of a translation. 
	 * Translation plugins should hook into em_event_duplicate, checking to make sure it is the original translation and then duplicating the translations of the original event.  
	 * @param string $url
	 * @param EM_Event $EM_Event
	 * @return string
	 */
	public static function event_duplicate_url($url, $EM_Event){
	    if( !EM_ML::is_original($EM_Event) ){
	        $EM_Event = EM_ML::get_original($EM_Event);
    	    $url = add_query_arg(array('action'=>'event_duplicate', 'event_id'=>$EM_Event->event_id, '_wpnonce'=> wp_create_nonce('event_duplicate_'.$EM_Event->event_id)));
    	    //this gets escaped later
	    }
	    return $url;
	}
    
    /**
     * Hooks into em_location_get_post_meta and assigns location info from original translation so other translations don't manage location-specific info.
     * @param boolean $result
     * @param EM_Location $EM_Location
     * @param string $validate
     * @return boolean
     */
    public static function location_get_post_meta($result, $EM_Location, $validate = true){
        //check if this is a master location, if not then we need to get the relevant master location info and populate this object with it so it passes validation and saves correctly.
        if( !EM_ML::is_original($EM_Location) ){
            //get original location object
            $location = EM_ML::get_original_location($EM_Location);
            self::location_merge_original_meta($EM_Location, $location);
    		if ($validate) $result = $EM_Location->validate();
        }
        return $result;
    }
    
    /**
     * Merge shared translation meta from original $location into $EM_Location.
     * @param EM_Location $EM_Location
     * @param EM_Location $location
     */
    public static function location_merge_original_meta( $EM_Location, $location ){
        $EM_Location->original_location_id = $location->location_id;
        //set values over from original location
		$EM_Location->location_address = $location->location_address;
		$EM_Location->location_town = $location->location_town;
		$EM_Location->location_state = $location->location_state;
		$EM_Location->location_postcode = $location->location_postcode;
		$EM_Location->location_region = $location->location_region;
		$EM_Location->location_country = $location->location_country;
		$EM_Location->location_latitude = $location->location_latitude;
		$EM_Location->location_longitude = $location->location_longitude;
		self::location_merge_original_attributes($EM_Location, $location);
    }
    
    public static function location_merge_original_attributes($EM_Location, $location){
		//merge attributes
		$location->location_attributes = maybe_unserialize($location->location_attributes);
		$EM_Location->location_attributes = maybe_unserialize($EM_Location->location_attributes);
		foreach($location->location_attributes as $attribute_key => $attribute){
			if( !empty($attribute) && empty($EM_Location->location_attributes[$attribute_key]) ){
				$EM_Location->location_attributes[$attribute_key] = $attribute;
			}
		}
    }

	/**
	 * When a master location is deleted, translations are not necessarily deleted so things like event-location linkage must be transferred to a translation and that must now be the master event.
	 * @param EM_Location $EM_Location
	 */
	public static function location_delete($EM_Location){
		global $wpdb, $sitepress;
		if( EM_ML::is_original($EM_Location) ){
			//check to see if there's any translations of this event
		    $location = EM_ML::get_translation($EM_Location);
			//if so check if the default language still exists
			if( $EM_Location->location_id != $location->location_id && !empty($location->location_id) ){
				//make that translation the master event by changing event ids of bookings, tickets etc. to the new master event
				$wpdb->update(EM_EVENTS_TABLE, array('location_id'=>$location->location_id), array('location_id'=>$EM_Location->location_id));
				//also change wp_postmeta
				$EM_Location->ms_global_switch();
				$wpdb->update($wpdb->postmeta, array('meta_value'=>$location->location_id), array('meta_key'=>'_location_id', 'meta_value'=>$EM_Location->location_id));
				$EM_Location->ms_global_switch_back();
				do_action('em_ml_transfer_original_location', $location, $EM_Location); //other add-ons with tables with location_id foreign keys should hook here and change
			}
		}
	}
    
    /**
     * When saving an original location, save shared meta to translations as well.
     * @param boolean $result
     * @param EM_Location $EM_Location
     * @return boolean
     */
    public static function location_save_meta($result, $EM_Location){
        if( $result && EM_ML::is_original($EM_Location) ){
            //save post meta for all others as well
            foreach( EM_ML::get_langs() as $lang_code => $language ){
                $location = EM_ML::get_translation($EM_Location, $lang_code); /* @var $EM_Location EM_Location */
                if( $location->location_id != $EM_Location->location_id ){
                    self::location_merge_original_meta($location, $EM_Location);
                    $location->save_meta();
                }
            }
        }
        return $result;
    }
}
EM_ML_IO::init();