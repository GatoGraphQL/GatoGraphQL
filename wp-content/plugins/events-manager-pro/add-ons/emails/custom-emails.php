<?php
class EM_Custom_Emails{
	
	public static function init(){
		//custom email bookings
		add_filter('em_booking_email_messages', 'EM_Custom_Emails::em_booking_email_messages',100,2);
		if( get_option('dbem_custom_emails_events_admins')){
			add_filter('em_booking_admin_emails','EM_Custom_Emails::event_admin_emails', 100, 2);
		}
		if( get_option('dbem_custom_emails_gateways') ){
			add_filter('em_multiple_booking_email_messages', 'EM_Custom_Emails::em_multiple_booking_email_messages',100,2);
			if( get_option('dbem_custom_emails_gateways_admins')){
				add_filter('em_booking_admin_emails','EM_Custom_Emails::gateway_admin_emails', 100, 2);
			}
		}
		//admin area
		if( is_admin() ){
		    include('custom-emails-admin.php');
		}
	}
	
    /*
     * --------------------------------------------
     * Custom Event Booking Emails
     * --------------------------------------------
     */
	
	public static function get_event_emails( $EM_Event ){
		global $wpdb;
		$custom_email_values = array();
		if( !empty($EM_Event->event_id) ){
			$sql = $wpdb->prepare('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id = %d AND meta_key = %s LIMIT 1", $EM_Event->event_id, 'event-emails');
			$possible_email_values = maybe_unserialize($wpdb->get_var($sql));
			if( is_array($possible_email_values) ){
				$custom_email_values = $possible_email_values;
			}
		}
		return $custom_email_values;
	}
	
	public static function get_event_admin_emails( $EM_Event ){
		global $wpdb;
		$custom_admin_emails = array();
		if( !empty($EM_Event->event_id) && get_option('dbem_custom_emails_events_admins') ){
			$sql = $wpdb->prepare('SELECT meta_value FROM '.EM_META_TABLE." WHERE object_id = %d AND meta_key = %s LIMIT 1", $EM_Event->event_id, 'event-admin-emails');
			$possible_email_values = maybe_unserialize($wpdb->get_var($sql));
			if( is_array($possible_email_values) ){
				$custom_admin_emails = $possible_email_values;
			}
		}
		return $custom_admin_emails;
	}
	
	/**
	 * @param array $msg
	 * @param EM_Booking $EM_Booking
	 */
	public static function em_booking_email_messages( $msg, $EM_Booking ){
		//get the event object and custom emails array
		$EM_Event = $EM_Booking->get_event();
		$custom_emails = self::get_event_emails($EM_Event);
		$users_to_check = array();
		if( get_option('dbem_custom_emails_events') ){
			$users_to_check = array('admin'=>'admin','user'=>'user');
		}
		//firstly, check if we're using a gateway, and if there's an email to send for that gateway
		if( !empty($EM_Booking->booking_meta['gateway']) &&  get_option('dbem_custom_emails_gateways') && $EM_Booking->get_price() > 0 ){
			$gateway = $EM_Booking->booking_meta['gateway'];
			$gateway_users = array($gateway.'-admin' => 'admin', $gateway.'-user' => 'user');
			$gateway_emails = maybe_unserialize(get_option('em_'.$EM_Booking->booking_meta['gateway'] . "_emails"));
			$users_to_check = array_merge($users_to_check, $gateway_users);
		}
		//set both admin and user email messages according to settings in custom emails
		foreach( $users_to_check as $user => $email_type ){
			if( !empty($custom_emails[$user][$EM_Booking->booking_status]) ){
				if( $custom_emails[$user][$EM_Booking->booking_status]['status'] == 1 ){
					//override default email with custom email
			    	$msg[$email_type]['subject'] = $custom_emails[$user][$EM_Booking->booking_status]['subject'];
			    	$msg[$email_type]['body'] = $custom_emails[$user][$EM_Booking->booking_status]['message'];
				}elseif( $custom_emails[$user][$EM_Booking->booking_status]['status'] == 2 ){
					//disable the email entirely
	    			$msg[$email_type]['subject'] = $msg[$user]['body'] = '';		
				}elseif( !empty($EM_Booking->booking_meta['gateway']) && array_key_exists($user, $gateway_users) ){
					//we requested the default for this gateway, so check if there's a overriden default for this gateway
					if( $gateway_emails[$user][$EM_Booking->booking_status]['status'] == 1 ){
						//override default gateway email with custom email
				    	$msg[$email_type]['subject'] = $gateway_emails[$user][$EM_Booking->booking_status]['subject'];
				    	$msg[$email_type]['body'] = $gateway_emails[$user][$EM_Booking->booking_status]['message'];
					}elseif( $gateway_emails[$user][$EM_Booking->booking_status]['status'] == 2 ){
						//disable the gateway email entirely
		    			$msg[$email_type]['subject'] = $msg[$email_type]['body'] = '';
					}
				}
		    }
		}
		return $msg;
	}
	
	public static function event_admin_emails( $emails, $EM_Booking ){
		$admin_emails = array();
		if( get_class($EM_Booking) == 'EM_Booking' ){ //prevent MB bookings from possibly sending individual event emails
			$admin_emails_array = self::get_event_admin_emails($EM_Booking->get_event());
			$group = empty($EM_Booking->booking_meta['gateway']) || $EM_Booking->get_price() == 0 ? 'default':$EM_Booking->booking_meta['gateway'];
			if( !empty($admin_emails_array[$group]) ){
				$admin_emails = explode(',', $admin_emails_array[$group]);
			}
		}
		return array_merge($emails, $admin_emails);
	}
	
    /*
     * --------------------------------------------
     * Custom Gateway Booking Emails
     * --------------------------------------------
     */
	
	public static function get_gateway_admin_emails($EM_Gateway){
		$custom_admin_emails = array();
		if( get_option('dbem_custom_emails_gateways_admins') ){
			$possible_email_values = maybe_unserialize($EM_Gateway->get_option('emails_admins'));
			if( is_array($possible_email_values) ){
				$custom_admin_emails = $possible_email_values;
			}
		}
		return $custom_admin_emails;
	}
	
	public static function em_multiple_booking_email_messages( $msg, $EM_Booking ){
		//firstly, check if we're using a gateway, and get any email settings to send for that gateway
		if( empty($EM_Booking->booking_meta['gateway']) || $EM_Booking->get_price() == 0 ) return $msg;
		$gateway = $EM_Booking->booking_meta['gateway'];
		$EM_Gateway = EM_Gateways::get_gateway($gateway);
		$users_to_check = array($gateway.'-mb-admin' => 'admin', $gateway.'-mb-user' => 'user');
		$custom_emails = maybe_unserialize($EM_Gateway->get_option("emails"));
		//set both admin and user email messages according to settings in custom emails
		foreach( $users_to_check as $user => $email_type ){
			if( !empty($custom_emails[$user][$EM_Booking->booking_status]) ){
				if( $custom_emails[$user][$EM_Booking->booking_status]['status'] == 1 ){
					//override default email with custom email
			    	$msg[$email_type]['subject'] = $custom_emails[$user][$EM_Booking->booking_status]['subject'];
			    	$msg[$email_type]['body'] = $custom_emails[$user][$EM_Booking->booking_status]['message'];
				}elseif( $custom_emails[$user][$EM_Booking->booking_status]['status'] == 2 ){
					//disable the email entirely
	    			$msg[$email_type]['subject'] = $msg[$user]['body'] = '';		
				}
		    }
		}
		return $msg;
	}
	
	public static function gateway_admin_emails( $emails, $EM_Booking ){
		if( empty($EM_Booking->booking_meta['gateway']) || $EM_Booking->get_price() == 0 ) return $emails;
		$gateway = $EM_Booking->booking_meta['gateway'];
		$EM_Gateway = EM_Gateways::get_gateway($gateway);
		$admin_emails_array = self::get_gateway_admin_emails($EM_Gateway);
		$admin_emails = array();
		if( get_class($EM_Booking) == 'EM_Booking' ){
			if( !empty($admin_emails_array[$gateway]) ){
				$admin_emails = explode(',', $admin_emails_array[$gateway]);
			}
		}elseif( get_class($EM_Booking) == 'EM_Multiple_Booking' ){
			//if MB mode is on, we check the mb email templates instead
			if( !empty($admin_emails_array[$gateway.'-mb']) ){
				$admin_emails = explode(',', $admin_emails_array[$gateway.'-mb']);
			}
		}
		return array_merge($emails, $admin_emails);
	}
}
EM_Custom_Emails::init();