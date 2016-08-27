<?php
/**
 * Performs actions on init. This works for both ajax and normal requests, the return results depends if an em_ajax flag is passed via POST or GET.
 */
function em_init_actions() {
	global $wpdb,$EM_Notices,$EM_Event; 
	if( defined('DOING_AJAX') && DOING_AJAX ) $_REQUEST['em_ajax'] = true;
	
	//NOTE - No EM objects are globalized at this point, as we're hitting early init mode.
	//TODO Clean this up.... use a uniformed way of calling EM Ajax actions
	if( !empty($_REQUEST['em_ajax']) || !empty($_REQUEST['em_ajax_action']) ){
		if(isset($_REQUEST['em_ajax_action']) && $_REQUEST['em_ajax_action'] == 'get_location') {
			if(isset($_REQUEST['id'])){
				$EM_Location = new EM_Location($_REQUEST['id'], 'location_id');
				$location_array = $EM_Location->to_array();
				$location_array['location_balloon'] = $EM_Location->output(get_option('dbem_location_baloon_format'));
		     	echo EM_Object::json_encode($location_array);
			}
			die();
		}   
	 	if(isset($_REQUEST['em_ajax_action']) && $_REQUEST['em_ajax_action'] == 'delete_ticket') {
			if(isset($_REQUEST['id'])){
				$EM_Ticket = new EM_Ticket($_REQUEST['id']);
				$result = $EM_Ticket->delete();
				if($result){
					$result = array('result'=>true);
				}else{
					$result = array('result'=>false, 'error'=>$EM_Ticket->feedback_message);
				}
			}else{
				$result = array('result'=>false, 'error'=>__('No ticket id provided','events-manager'));	
			}			
		    echo EM_Object::json_encode($result);
			die();
		}
		if(isset($_REQUEST['query']) && $_REQUEST['query'] == 'GlobalMapData') {
			$EM_Locations = EM_Locations::get( $_REQUEST );
			$json_locations = array();
			foreach($EM_Locations as $location_key => $EM_Location) {
				$json_locations[$location_key] = $EM_Location->to_array();
				$json_locations[$location_key]['location_balloon'] = $EM_Location->output(get_option('dbem_map_text_format'));
			}
			echo EM_Object::json_encode($json_locations);
		 	die();   
	 	}
	
		if(isset($_REQUEST['ajaxCalendar']) && $_REQUEST['ajaxCalendar']) {
			//FIXME if long events enabled originally, this won't show up on ajax call
			echo EM_Calendar::output($_REQUEST, false);
			die();
		}
	}
	
	//Event Actions
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,5) == 'event' ){
		//Load the event object, with saved event if requested
		if( !empty($_REQUEST['event_id']) ){
			$EM_Event = new EM_Event($_REQUEST['event_id']);
		}else{
			$EM_Event = new EM_Event();
		}
		//Save Event, only via BP or via [event_form]
		if( $_REQUEST['action'] == 'event_save' && $EM_Event->can_manage('edit_events','edit_others_events') ){
			//Check Nonces
			if( !wp_verify_nonce($_REQUEST['_wpnonce'], 'wpnonce_event_save') ) exit('Trying to perform an illegal action.');
			//Grab and validate submitted data
			if ( $EM_Event->get_post() && $EM_Event->save() ) { //EM_Event gets the event if submitted via POST and validates it (safer than to depend on JS)
				$events_result = true;
				//Success notice
				if( is_user_logged_in() ){
					if( empty($_REQUEST['event_id']) ){
						$EM_Notices->add_confirm( $EM_Event->output(get_option('dbem_events_form_result_success')), true);
					}else{
					    $EM_Notices->add_confirm( $EM_Event->output(get_option('dbem_events_form_result_success_updated')), true);
					}
				}else{
					$EM_Notices->add_confirm( $EM_Event->output(get_option('dbem_events_anonymous_result_success')), true);
				}
				$redirect = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : wp_get_referer();
				$redirect = em_add_get_params($redirect, array('success'=>1), false, false);
				wp_redirect( $redirect );
				exit();
			}else{
				$EM_Notices->add_error( $EM_Event->get_errors() );
				$events_result = false;				
			}
		}
		if ( $_REQUEST['action'] == 'event_duplicate' && wp_verify_nonce($_REQUEST['_wpnonce'],'event_duplicate_'.$EM_Event->event_id) ) {
			$event = $EM_Event->duplicate();
			if( $event === false ){
				$EM_Notices->add_error($EM_Event->errors, true);
				wp_redirect( wp_get_referer() );
			}else{
				$EM_Notices->add_confirm($EM_Event->feedback_message, true);
				wp_redirect( $event->get_edit_url() );
			}
			exit();
		}
		if ( $_REQUEST['action'] == 'event_delete' && wp_verify_nonce($_REQUEST['_wpnonce'],'event_delete_'.$EM_Event->event_id) ) { 
			//DELETE action
			$selectedEvents = !empty($_REQUEST['events']) ? $_REQUEST['events']:'';
			if(  EM_Object::array_is_numeric($selectedEvents) ){
				$events_result = EM_Events::delete( $selectedEvents );
			}elseif( is_object($EM_Event) ){
				$events_result = $EM_Event->delete();
			}		
			$plural = (count($selectedEvents) > 1) ? __('Events','events-manager'):__('Event','events-manager');
			if($events_result){
				$message = ( !empty($EM_Event->feedback_message) ) ? $EM_Event->feedback_message : sprintf(__('%s successfully deleted.','events-manager'),$plural);
				$EM_Notices->add_confirm( $message, true );
			}else{
				$message = ( !empty($EM_Event->errors) ) ? $EM_Event->errors : sprintf(__('%s could not be deleted.','events-manager'),$plural);
				$EM_Notices->add_error( $message, true );		
			}
			wp_redirect( wp_get_referer() );
			exit();
		}elseif( $_REQUEST['action'] == 'event_detach' && wp_verify_nonce($_REQUEST['_wpnonce'],'event_detach_'.get_current_user_id().'_'.$EM_Event->event_id) ){ 
			//Detach event and move on
			if($EM_Event->detach()){
				$EM_Notices->add_confirm( $EM_Event->feedback_message, true );
			}else{
				$EM_Notices->add_error( $EM_Event->errors, true );			
			}
			wp_redirect(wp_get_referer());
			exit();
		}elseif( $_REQUEST['action'] == 'event_attach' && !empty($_REQUEST['undo_id']) && wp_verify_nonce($_REQUEST['_wpnonce'],'event_attach_'.get_current_user_id().'_'.$EM_Event->event_id) ){ 
			//Detach event and move on
			if($EM_Event->attach($_REQUEST['undo_id'])){
				$EM_Notices->add_confirm( $EM_Event->feedback_message, true );
			}else{
				$EM_Notices->add_error( $EM_Event->errors, true );
			}
			wp_redirect(wp_get_referer());
			exit();
		}
		
		//AJAX Exit
		if( isset($events_result) && !empty($_REQUEST['em_ajax']) ){
			if( $events_result ){
				$return = array('result'=>true, 'message'=>$EM_Event->feedback_message);
			}else{		
				$return = array('result'=>false, 'message'=>$EM_Event->feedback_message, 'errors'=>$EM_Event->errors);
			}
			echo EM_Object::json_encode($return);
			edit();
		}
	}
	
	//Location Actions
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,8) == 'location' ){
		global $EM_Location, $EM_Notices;
		//Load the location object, with saved event if requested
		if( !empty($_REQUEST['location_id']) ){
			$EM_Location = new EM_Location($_REQUEST['location_id']);
		}else{
			$EM_Location = new EM_Location();
		}
		if( $_REQUEST['action'] == 'location_save' && $EM_Location->can_manage('edit_locations','edit_others_locations') ){
			//Check Nonces
			em_verify_nonce('location_save');
			//Grab and validate submitted data
			if ( $EM_Location->get_post() && $EM_Location->save() ) { //EM_location gets the location if submitted via POST and validates it (safer than to depend on JS)
				$EM_Notices->add_confirm($EM_Location->feedback_message, true);
				$redirect = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : wp_get_referer();
				wp_redirect( $redirect );
				exit();
			}else{
				$EM_Notices->add_error( $EM_Location->get_errors() );
				$result = false;		
			}
		}elseif( !empty($_REQUEST['action']) && $_REQUEST['action'] == "location_delete" ){
			//delete location
			//get object or objects			
			if( !empty($_REQUEST['locations']) || !empty($_REQUEST['location_id']) ){
				$args = !empty($_REQUEST['locations']) ? $_REQUEST['locations']:$_REQUEST['location_id'];
				$locations = EM_Locations::get($args);
				foreach($locations as $location) {
					if( !$location->delete() ){
						$EM_Notices->add_error($location->get_errors());
						$errors = true;
					}			
				}
				if( empty($errors) ){
					$result = true;
					$location_term = ( count($locations) > 1 ) ?__('Locations', 'events-manager') : __('Location', 'events-manager'); 
					$EM_Notices->add_confirm( sprintf(__('%s successfully deleted', 'events-manager'), $location_term) );
				}else{
					$result = false;
				}
			}
		}elseif( !empty($_REQUEST['action']) && $_REQUEST['action'] == "locations_search" && (!empty($_REQUEST['term']) || !empty($_REQUEST['q'])) ){
			$results = array();
			if( is_user_logged_in() || ( get_option('dbem_events_anonymous_submissions') && user_can(get_option('dbem_events_anonymous_user'), 'read_others_locations') ) ){
				$location_cond = (is_user_logged_in() && !current_user_can('read_others_locations')) ? "AND location_owner=".get_current_user_id() : '';
				if( !is_user_logged_in() && get_option('dbem_events_anonymous_submissions') ){
					if( !user_can(get_option('dbem_events_anonymous_user'),'read_private_locations') ){
						$location_cond = " AND location_private=0";	
					}
				}elseif( is_user_logged_in() && !current_user_can('read_private_locations') ){
				    $location_cond = " AND location_private=0";
				}elseif( !is_user_logged_in() ){
					$location_cond = " AND location_private=0";		    
				}
				$location_cond = apply_filters('em_actions_locations_search_cond', $location_cond);
				$term = (isset($_REQUEST['term'])) ? '%'.$_REQUEST['term'].'%' : '%'.$_REQUEST['q'].'%';
				$sql = $wpdb->prepare("
					SELECT 
						location_id AS `id`,
						Concat( location_name )  AS `label`,
						location_name AS `value`,
						location_address AS `address`, 
						location_town AS `town`, 
						location_state AS `state`,
						location_region AS `region`,
						location_postcode AS `postcode`,
						location_country AS `country`
					FROM ".EM_LOCATIONS_TABLE." 
					WHERE ( `location_name` LIKE %s ) AND location_status=1 $location_cond LIMIT 10
				", $term);
				$results = $wpdb->get_results($sql);
			}
			echo EM_Object::json_encode($results);
			die();
		}
		if( isset($result) && $result && !empty($_REQUEST['em_ajax']) ){
			$return = array('result'=>true, 'message'=>$EM_Location->feedback_message);
			echo EM_Object::json_encode($return);
			die();
		}elseif( isset($result) && !$result && !empty($_REQUEST['em_ajax']) ){
			$return = array('result'=>false, 'message'=>$EM_Location->feedback_message, 'errors'=>$EM_Notices->get_errors());
			echo EM_Object::json_encode($return);
			die();
		}
	}
	
	//Booking Actions
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,7) == 'booking' && (is_user_logged_in() || ($_REQUEST['action'] == 'booking_add' && get_option('dbem_bookings_anonymous'))) ){
		global $EM_Event, $EM_Booking, $EM_Person;
		//Load the booking object, with saved booking if requested
		$EM_Booking = ( !empty($_REQUEST['booking_id']) ) ? em_get_booking($_REQUEST['booking_id']) : em_get_booking();
		if( !empty($EM_Booking->event_id) ){
			//Load the event object, with saved event if requested
			$EM_Event = $EM_Booking->get_event();
		}elseif( !empty($_REQUEST['event_id']) ){
			$EM_Event = new EM_Event($_REQUEST['event_id']);
		}
		$allowed_actions = array('bookings_approve'=>'approve','bookings_reject'=>'reject','bookings_unapprove'=>'unapprove', 'bookings_delete'=>'delete');
		$result = false;
		$feedback = '';
		if ( $_REQUEST['action'] == 'booking_add') {
			//ADD/EDIT Booking
			ob_start();
			if( !defined('WP_CACHE') || !WP_CACHE ) em_verify_nonce('booking_add');
			if( !is_user_logged_in() || get_option('dbem_bookings_double') || !$EM_Event->get_bookings()->has_booking(get_current_user_id()) ){
			    $EM_Booking->get_post();
				$post_validation = $EM_Booking->validate();
				do_action('em_booking_add', $EM_Event, $EM_Booking, $post_validation);
				if( $post_validation ){
				    //register the user - or not depending - according to the booking
				    $registration = em_booking_add_registration($EM_Booking);
					$EM_Bookings = $EM_Event->get_bookings();
					if( $registration && $EM_Bookings->add($EM_Booking) ){
					    if( is_user_logged_in() && is_multisite() && !is_user_member_of_blog(get_current_user_id(), get_current_blog_id()) ){
					        add_user_to_blog(get_current_blog_id(), get_current_user_id(), get_option('default_role'));
					    }
						$result = true;
						$EM_Notices->add_confirm( $EM_Bookings->feedback_message );		
						$feedback = $EM_Bookings->feedback_message;
					}else{
						$result = false;
						if(!$registration){
						    $EM_Notices->add_error( $EM_Booking->get_errors() );
							$feedback = $EM_Booking->feedback_message;
						}else{
						    $EM_Notices->add_error( $EM_Bookings->get_errors() );
							$feedback = $EM_Bookings->feedback_message;
						}				
					}
					global $em_temp_user_data; $em_temp_user_data = false; //delete registered user temp info (if exists)
				}else{
					$result = false;
					$EM_Notices->add_error( $EM_Booking->get_errors() );
				}
			}else{
				$result = false;
				$feedback = get_option('dbem_booking_feedback_already_booked');
				$EM_Notices->add_error( $feedback );
			}
			ob_clean();
	  	}elseif ( $_REQUEST['action'] == 'booking_add_one' && is_object($EM_Event) && is_user_logged_in() ) {
			//ADD/EDIT Booking
			em_verify_nonce('booking_add_one');
			if( !$EM_Event->get_bookings()->has_booking(get_current_user_id()) || get_option('dbem_bookings_double')){
				$EM_Booking = em_get_booking(array('person_id'=>get_current_user_id(), 'event_id'=>$EM_Event->event_id, 'booking_spaces'=>1)); //new booking
				$EM_Ticket = $EM_Event->get_bookings()->get_tickets()->get_first();	
				//get first ticket in this event and book one place there. similar to getting the form values in EM_Booking::get_post_values()
				$EM_Ticket_Booking = new EM_Ticket_Booking(array('ticket_id'=>$EM_Ticket->ticket_id, 'ticket_booking_spaces'=>1));
				$EM_Booking->tickets_bookings = new EM_Tickets_Bookings();
				$EM_Booking->tickets_bookings->booking = $EM_Ticket_Booking->booking = $EM_Booking;
				$EM_Booking->tickets_bookings->add( $EM_Ticket_Booking );
				//Now save booking
				if( $EM_Event->get_bookings()->add($EM_Booking) ){
					$result = true;
					$EM_Notices->add_confirm( $EM_Event->get_bookings()->feedback_message );		
					$feedback = $EM_Event->get_bookings()->feedback_message;	
				}else{
					$result = false;
					$EM_Notices->add_error( $EM_Event->get_bookings()->get_errors() );			
					$feedback = $EM_Event->get_bookings()->feedback_message;	
				}
			}else{
				$result = false;
				$feedback = get_option('dbem_booking_feedback_already_booked');
				$EM_Notices->add_error( $feedback );
			}
	  	}elseif ( $_REQUEST['action'] == 'booking_cancel') {
	  		//Cancel Booking
			em_verify_nonce('booking_cancel');
	  		if( $EM_Booking->can_manage() || ($EM_Booking->person->ID == get_current_user_id() && get_option('dbem_bookings_user_cancellation')) ){
				if( $EM_Booking->cancel() ){
					$result = true;
					if( !defined('DOING_AJAX') ){
						if( $EM_Booking->person->ID == get_current_user_id() ){
							$EM_Notices->add_confirm(get_option('dbem_booking_feedback_cancelled'), true );	
						}else{
							$EM_Notices->add_confirm( $EM_Booking->feedback_message, true );
						}
						wp_redirect( $_SERVER['HTTP_REFERER'] );
						exit();
					}
				}else{
					$result = false;
					$EM_Notices->add_error( $EM_Booking->get_errors() );
					$feedback = $EM_Booking->feedback_message;
				}
			}else{
				$EM_Notices->add_error( __('You must log in to cancel your booking.', 'events-manager') );
			}
		//TODO user action shouldn't check permission, booking object should.
	  	}elseif( array_key_exists($_REQUEST['action'], $allowed_actions) && $EM_Event->can_manage('manage_bookings','manage_others_bookings') ){
	  		//Event Admin only actions
			$action = $allowed_actions[$_REQUEST['action']];
			//Just do it here, since we may be deleting bookings of different events.
			if( !empty($_REQUEST['bookings']) && EM_Object::array_is_numeric($_REQUEST['bookings'])){
				$results = array();
				foreach($_REQUEST['bookings'] as $booking_id){
					$EM_Booking = em_get_booking($booking_id);
					$result = $EM_Booking->$action();
					$results[] = $result;
					if( !in_array(false, $results) && !$result ){
						$feedback = $EM_Booking->feedback_message;
					}
				}
				$result = !in_array(false,$results);
			}elseif( is_object($EM_Booking) ){
				$result = $EM_Booking->$action();
				$feedback = $EM_Booking->feedback_message;
			}
			//FIXME not adhereing to object's feedback or error message, like other bits in this file.
			//TODO multiple deletion won't work in ajax
			if( !empty($_REQUEST['em_ajax']) ){
				if( $result ){
					echo $feedback;
				}else{
					echo '<span style="color:red">'.$feedback.'</span>';
				}	
				die();
			}else{
			    if( $result ){
			        $EM_Notices->add_confirm($feedback);
			    }else{
			        $EM_Notices->add_error($feedback);
			    }
			}
		}elseif( $_REQUEST['action'] == 'booking_save' ){
			em_verify_nonce('booking_save_'.$EM_Booking->booking_id);
			do_action('em_booking_save', $EM_Event, $EM_Booking);
			if( $EM_Booking->can_manage('manage_bookings','manage_others_bookings') ){
				if ($EM_Booking->get_post(true) && $EM_Booking->validate(true) && $EM_Booking->save(false) ){
					$EM_Notices->add_confirm( $EM_Booking->feedback_message, true );
					$redirect = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : wp_get_referer();
					wp_redirect( $redirect );
					exit();
				}else{
					$result = false;
					$EM_Notices->add_error( $EM_Booking->get_errors() );			
					$feedback = $EM_Booking->feedback_message;	
				}	
			}
		}elseif( $_REQUEST['action'] == 'booking_set_status' ){
			em_verify_nonce('booking_set_status_'.$EM_Booking->booking_id);
			if( $EM_Booking->can_manage('manage_bookings','manage_others_bookings') && $_REQUEST['booking_status'] != $EM_Booking->booking_status ){
				if ( $EM_Booking->set_status($_REQUEST['booking_status'], false, true) ){
					if( !empty($_REQUEST['send_email']) ){
						if( $EM_Booking->email() ){
						    if( $EM_Booking->mails_sent > 0 ) {
						        $EM_Booking->feedback_message .= " ".__('Email Sent.','events-manager');
						    }else{
						        $EM_Booking->feedback_message .= " "._x('No emails to send for this booking.', 'bookings', 'events-manager');
						    }
						}else{
							$EM_Booking->feedback_message .= ' <span style="color:red">'.__('ERROR : Email Not Sent.','events-manager').'</span>';
						}
					}
					$EM_Notices->add_confirm( $EM_Booking->feedback_message, true );
					$redirect = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : wp_get_referer();
					wp_redirect( $redirect );
					exit();
				}else{
					$result = false;
					$EM_Notices->add_error( $EM_Booking->get_errors() );
					$feedback = $EM_Booking->feedback_message;	
				}	
			}
		}elseif( $_REQUEST['action'] == 'booking_resend_email' ){
			em_verify_nonce('booking_resend_email_'.$EM_Booking->booking_id);
			if( $EM_Booking->can_manage('manage_bookings','manage_others_bookings') ){
				if( $EM_Booking->email(false, true) ){
				    if( $EM_Booking->mails_sent > 0 ) {
				        $EM_Notices->add_confirm( __('Email Sent.','events-manager'), true );
				    }else{
				        $EM_Notices->add_confirm( _x('No emails to send for this booking.', 'bookings', 'events-manager'), true );
				    }
					$redirect = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : wp_get_referer();
					wp_redirect( $redirect );
					exit();
				}else{
					$result = false;
					$EM_Notices->add_error( __('ERROR : Email Not Sent.','events-manager') );			
					$feedback = $EM_Booking->feedback_message;
				}	
			}
		}elseif( $_REQUEST['action'] == 'booking_modify_person' ){
			em_verify_nonce('booking_modify_person_'.$EM_Booking->booking_id);
			if( $EM_Booking->can_manage('manage_bookings','manage_others_bookings') ){
			    global $wpdb;
			    $no_user = get_option('dbem_bookings_registration_disable') && $EM_Booking->get_person()->ID == get_option('dbem_bookings_registration_user');
				if( //save just the booking meta, avoid extra unneccesary hooks and things to go wrong
					$no_user && $EM_Booking->get_person_post() && 
			    	$wpdb->update(EM_BOOKINGS_TABLE, array('booking_meta'=> serialize($EM_Booking->booking_meta)), array('booking_id'=>$EM_Booking->booking_id))
				){
					$EM_Notices->add_confirm( $EM_Booking->feedback_message, true );
					$redirect = !empty($_REQUEST['redirect_to']) ? $_REQUEST['redirect_to'] : wp_get_referer();
					wp_redirect( $redirect );
					exit();
				}else{
					$result = false;
					$EM_Notices->add_error( $EM_Booking->get_errors() );			
					$feedback = $EM_Booking->feedback_message;	
				}	
			}
			do_action('em_booking_modify_person', $EM_Event, $EM_Booking);
		}
		if( $result && defined('DOING_AJAX') ){
			$return = array('result'=>true, 'message'=>$feedback);
			header( 'Content-Type: application/javascript; charset=UTF-8', true ); //add this for HTTP -> HTTPS requests which assume it's a cross-site request
			echo EM_Object::json_encode(apply_filters('em_action_'.$_REQUEST['action'], $return, $EM_Booking));
			die();
		}elseif( !$result && defined('DOING_AJAX') ){
			$return = array('result'=>false, 'message'=>$feedback, 'errors'=>$EM_Notices->get_errors());
			header( 'Content-Type: application/javascript; charset=UTF-8', true ); //add this for HTTP -> HTTPS requests which assume it's a cross-site request
			echo EM_Object::json_encode(apply_filters('em_action_'.$_REQUEST['action'], $return, $EM_Booking));
			die();
		}
	}elseif( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'booking_add' && !is_user_logged_in() && !get_option('dbem_bookings_anonymous')){
		$EM_Notices->add_error( get_option('dbem_booking_feedback_log_in') );
		if( !$result && defined('DOING_AJAX') ){
			$return = array('result'=>false, 'message'=>$EM_Booking->feedback_message, 'errors'=>$EM_Notices->get_errors());
			echo EM_Object::json_encode(apply_filters('em_action_'.$_REQUEST['action'], $return, $EM_Booking));
		}
		die();
	}
	
	//AJAX call for searches
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,6) == 'search' ){
		//default search arts
		if( $_REQUEST['action'] == 'search_states' ){
			$results = array();
			$conds = array();
			if( !empty($_REQUEST['country']) ){
				$conds[] = $wpdb->prepare("(location_country = '%s' OR location_country IS NULL )", $_REQUEST['country']);
			}
			if( !empty($_REQUEST['region']) ){
				$conds[] = $wpdb->prepare("( location_region = '%s' )", $_REQUEST['region']);
			}
			$cond = (count($conds) > 0) ? "AND ".implode(' AND ', $conds):'';
			$results = $wpdb->get_col("SELECT DISTINCT location_state FROM " . EM_LOCATIONS_TABLE ." WHERE location_state IS NOT NULL AND location_state != '' $cond ORDER BY location_state");
			if( $_REQUEST['return_html'] ) {
				//quick shortcut for quick html form manipulation
				ob_start();
				?>
				<option value=''><?php echo get_option('dbem_search_form_states_label') ?></option>
				<?php
				foreach( $results as $result ){
					echo "<option>{$result}</option>";
				}
				$return = ob_get_clean();
				echo apply_filters('em_ajax_search_states', $return);
				exit();
			}else{
				echo EM_Object::json_encode($results);
				exit();
			}
		}
		if( $_REQUEST['action'] == 'search_towns' ){
			$results = array();
			$conds = array();
			if( !empty($_REQUEST['country']) ){
				$conds[] = $wpdb->prepare("(location_country = '%s' OR location_country IS NULL )", $_REQUEST['country']);
			}
			if( !empty($_REQUEST['region']) ){
				$conds[] = $wpdb->prepare("( location_region = '%s' )", $_REQUEST['region']);
			}
			if( !empty($_REQUEST['state']) ){
				$conds[] = $wpdb->prepare("(location_state = '%s' )", $_REQUEST['state']);
			}
			$cond = (count($conds) > 0) ? "AND ".implode(' AND ', $conds):'';
			$results = $wpdb->get_col("SELECT DISTINCT location_town FROM " . EM_LOCATIONS_TABLE ." WHERE location_town IS NOT NULL AND location_town != '' $cond  ORDER BY location_town");
			if( $_REQUEST['return_html'] ) {
				//quick shortcut for quick html form manipulation
				ob_start();
				?>
				<option value=''><?php echo get_option('dbem_search_form_towns_label'); ?></option>
				<?php			
				foreach( $results as $result ){
					echo "<option>$result</option>";
				}
				$return = ob_get_clean();
				echo apply_filters('em_ajax_search_towns', $return);
				exit();
			}else{
				echo EM_Object::json_encode($results);
				exit();
			}
		}
		if( $_REQUEST['action'] == 'search_regions' ){
			$results = array();
			if( !empty($_REQUEST['country']) ){
				$conds[] = $wpdb->prepare("(location_country = '%s' )", $_REQUEST['country']);
			}
			$cond = (count($conds) > 0) ? "AND ".implode(' AND ', $conds):'';
			$results = $wpdb->get_results("SELECT DISTINCT location_region AS value FROM " . EM_LOCATIONS_TABLE ." WHERE location_region IS NOT NULL AND location_region != '' $cond  ORDER BY location_region");
			if( $_REQUEST['return_html'] ) {
				//quick shortcut for quick html form manipulation
				ob_start();
				?>
				<option value=''><?php echo get_option('dbem_search_form_regions_label'); ?></option>
				<?php	
				foreach( $results as $result ){
					echo "<option>{$result->value}</option>";
				}
				$return = ob_get_clean();
				echo apply_filters('em_ajax_search_regions', $return);
				exit();
			}else{
				echo EM_Object::json_encode($results);
				exit();
			}
		}
	}
		
	//EM Ajax requests require this flag.
	if( is_user_logged_in() ){
		//Admin operations
		//Specific Oject Ajax
		if( !empty($_REQUEST['em_obj']) ){
			switch( $_REQUEST['em_obj'] ){
				case 'em_bookings_events_table':
					include_once('admin/bookings/em-events.php');
					em_bookings_events_table();
					exit();
					break;
				case 'em_bookings_pending_table':
					include_once('admin/bookings/em-pending.php');
					em_bookings_pending_table();
					exit();
					break;
				case 'em_bookings_confirmed_table':
					//add some admin files just in case
					include_once('admin/bookings/em-confirmed.php');
					em_bookings_confirmed_table();
					exit();
					break;
			}
		}
	}
	//Export CSV - WIP
	if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'export_bookings_csv' && wp_verify_nonce($_REQUEST['_wpnonce'], 'export_bookings_csv')){
		if( !empty($_REQUEST['event_id']) ){
			$EM_Event = em_get_event($_REQUEST['event_id']);
		}
		//sort out cols
		if( !empty($_REQUEST['cols']) && is_array($_REQUEST['cols']) ){
			$cols = array();
			foreach($_REQUEST['cols'] as $col => $active){
				if( $active ){ $cols[] = $col; }
			}
			$_REQUEST['cols'] = $cols;
		}
		$_REQUEST['limit'] = 0;
		
		//generate bookings export according to search request
		$show_tickets = !empty($_REQUEST['show_tickets']);
		$EM_Bookings_Table = new EM_Bookings_Table($show_tickets);
		header("Content-Type: application/octet-stream; charset=utf-8");
		$file_name = !empty($EM_Event->event_slug) ? $EM_Event->event_slug:get_bloginfo();
		header("Content-Disposition: Attachment; filename=".sanitize_title($file_name)."-bookings-export.csv");
		do_action('em_csv_header_output');
		echo "\xEF\xBB\xBF"; // UTF-8 for MS Excel (a little hacky... but does the job)
		if( !defined('EM_CSV_DISABLE_HEADERS') || !EM_CSV_DISABLE_HEADERS ){
			if( !empty($_REQUEST['event_id']) ){
				echo __('Event','events-manager') . ' : ' . $EM_Event->event_name .  "\n";
				if( $EM_Event->location_id > 0 ) echo __('Where','events-manager') . ' - ' . $EM_Event->get_location()->location_name .  "\n";
				echo __('When','events-manager') . ' : ' . $EM_Event->output('#_EVENTDATES - #_EVENTTIMES') .  "\n";
			}
			echo sprintf(__('Exported booking on %s','events-manager'), date_i18n('D d M Y h:i', current_time('timestamp'))) .  "\n";
		}
		$delimiter = !defined('EM_CSV_DELIMITER') ? ',' : EM_CSV_DELIMITER;
		$delimiter = apply_filters('em_csv_delimiter', $delimiter);
		//Rows
		$EM_Bookings_Table->limit = 150; //if you're having server memory issues, try messing with this number
		$EM_Bookings = $EM_Bookings_Table->get_bookings();
		$handle = fopen("php://output", "w");
		fputcsv($handle, $EM_Bookings_Table->get_headers(true), $delimiter);
		while(!empty($EM_Bookings->bookings)){
			foreach( $EM_Bookings->bookings as $EM_Booking ) {
				//Display all values
				/* @var $EM_Booking EM_Booking */
				/* @var $EM_Ticket_Booking EM_Ticket_Booking */
				if( $show_tickets ){
					foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){
						$row = $EM_Bookings_Table->get_row_csv($EM_Ticket_Booking);
						fputcsv($handle, $row, $delimiter);
					}
				}else{
					$row = $EM_Bookings_Table->get_row_csv($EM_Booking);
					fputcsv($handle, $row, $delimiter);
				}
			}
			//reiterate loop
			$EM_Bookings_Table->offset += $EM_Bookings_Table->limit;
			$EM_Bookings = $EM_Bookings_Table->get_bookings();
		}
		fclose($handle);
		exit();
	}
}  
add_action('init','em_init_actions',11);

/**
 * Handles AJAX Bookings admin table filtering, view changes and pagination
 */
function em_ajax_bookings_table(){
    check_admin_referer('em_bookings_table');
	$EM_Bookings_Table = new EM_Bookings_Table();
	$EM_Bookings_Table->output_table();
	exit();
}
add_action('wp_ajax_em_bookings_table','em_ajax_bookings_table');

/**
 * Handles AJAX Searching and Pagination for events, locations, tags and categories
 */
function em_ajax_search_and_pagination(){
	$args = array( 'owner' => false, 'pagination' => 1, 'ajax' => true);
	echo '<div class="em-search-ajax">';
	ob_start();
	if( $_REQUEST['action'] == 'search_events' ){
		$args['scope'] = get_option('dbem_events_page_scope');
		$args = EM_Events::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_events_default_limit');
		em_locate_template('templates/events-list.php', true, array('args'=>$args)); //if successful, this template overrides the settings and defaults, including search
	}elseif( $_REQUEST['action'] == 'search_events_grouped' && defined('DOING_AJAX') ){
		$args['scope'] = get_option('dbem_events_page_scope');
		$args = EM_Events::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_events_default_limit');
		em_locate_template('templates/events-list-grouped.php', true, array('args'=>$args)); //if successful, this template overrides the settings and defaults, including search
	}elseif( $_REQUEST['action'] == 'search_locations' && defined('DOING_AJAX') ){
		$args = EM_Locations::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_locations_default_limit');
		em_locate_template('templates/locations-list.php', true, array('args'=>$args)); //if successful, this template overrides the settings and defaults, including search
	}elseif( $_REQUEST['action'] == 'search_tags' && defined('DOING_AJAX') ){
		$args = EM_Tags::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_tags_default_limit');
		em_locate_template('templates/tags-list.php', true, array('args'=>$args)); //if successful, this template overrides the settings and defaults, including search
	}elseif( $_REQUEST['action'] == 'search_cats' && defined('DOING_AJAX') ){
		$args = EM_Categories::get_post_search($args);
		$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_categories_default_limit');
		em_locate_template('templates/categories-list.php', true, array('args'=>$args)); //if successful, this template overrides the settings and defaults, including search
	}
	echo '</div>';
	echo apply_filters('em_ajax_'.$_REQUEST['action'], ob_get_clean(), $args);
	exit();
}
add_action('wp_ajax_nopriv_search_events','em_ajax_search_and_pagination');
add_action('wp_ajax_search_events','em_ajax_search_and_pagination');
add_action('wp_ajax_nopriv_search_events_grouped','em_ajax_search_and_pagination');
add_action('wp_ajax_search_events_grouped','em_ajax_search_and_pagination');
add_action('wp_ajax_nopriv_search_locations','em_ajax_search_and_pagination');
add_action('wp_ajax_search_locations','em_ajax_search_and_pagination');
add_action('wp_ajax_nopriv_search_tags','em_ajax_search_and_pagination');
add_action('wp_ajax_search_tags','em_ajax_search_and_pagination');
add_action('wp_ajax_nopriv_search_cats','em_ajax_search_and_pagination');
add_action('wp_ajax_search_cats','em_ajax_search_and_pagination');

/*
Added in dev 5.4.4.2 but may delete in favour of Google autocomplete service
function em_ajax_geocoding_search(){
	//GeoNames
	if( !empty($_REQUEST['q']) && get_option('dbem_geonames_username') ){
		$url = 'http://api.geonames.org/searchJSON?username='.get_option('dbem_geonames_username').'&featureClass=p&style=full&maxRows=12&q=' . rawurlencode(utf8_encode($_REQUEST['q']));
		if( !empty($_REQUEST['country']) ){
			$url .= '&countryBias=' . rawurlencode(utf8_encode($_REQUEST['country']));	
		}
		if( !empty($_REQUEST['callback']) ){
			$url .= '&callback=' . rawurlencode(utf8_encode($_REQUEST['callback']));
		}
	};
	if( !empty($url) ){
		$return = wp_remote_get($url);
		if( !is_wp_error($return) ){
			echo $return['body'];
			die();
		}
	}
	//If nothing is set up
	$default = array('geonames'=>array(array('name'=>'No Information Available','lat'=>'', 'lng'=>'', 'adminName1'=> '', 'countryName'=>'')));
	echo json_encode($default);
	die();
}
add_action('wp_ajax_nopriv_geocoding_search','em_ajax_geocoding_search');
add_action('wp_ajax_geocoding_search','em_ajax_geocoding_search');
*/
?>