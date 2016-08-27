<?php

function emp_install() {
	$old_version = get_option('em_pro_version');
	if( EMP_VERSION > $old_version || $old_version == ''|| (is_multisite() && !EM_MS_GLOBAL && get_option('emp_ms_global_install')) ){
	 	// Creates the tables + options if necessary
		if( !EM_MS_GLOBAL || (EM_MS_GLOBAL && is_main_blog()) ){
		    //hm....
		 	emp_create_transactions_table();
			emp_create_coupons_table(); 
			emp_create_reminders_table();
			emp_create_bookings_relationships_table();
	 		delete_option('emp_ms_global_install'); //in case for some reason the user changed global settings
	 	}else{
	 		update_option('emp_ms_global_install',1); //in case for some reason the user changes global settings in the future
	 	}
		emp_add_options();
		
		//Upate Version	
	  	update_option('em_pro_version', EMP_VERSION);
	}
}

/**
 * Magic function that takes a table name and cleans all non-unique keys not present in the $clean_keys array. if no array is supplied, all but the primary key is removed.
 * @param string $table_name
 * @param array $clean_keys
 */
function emp_sort_out_table_nu_keys($table_name, $clean_keys = array()){
	global $wpdb;
	//sort out the keys
	$new_keys = $clean_keys;
	$table_key_changes = array();
	$table_keys = $wpdb->get_results("SHOW KEYS FROM $table_name WHERE Key_name != 'PRIMARY'", ARRAY_A);
	foreach($table_keys as $table_key_row){
		if( !in_array($table_key_row['Key_name'], $clean_keys) ){
			$table_key_changes[] = "ALTER TABLE $table_name DROP INDEX ".$table_key_row['Key_name'];
		}elseif( in_array($table_key_row['Key_name'], $clean_keys) ){
			foreach($clean_keys as $key => $clean_key){
				if($table_key_row['Key_name'] == $clean_key){
					unset($new_keys[$key]);
				}
			}
		}
	}
	//delete duplicates
	foreach($table_key_changes as $sql){
		$wpdb->query($sql);
	}
	//add new keys
	foreach($new_keys as $key){
		$wpdb->query("ALTER TABLE $table_name ADD INDEX ($key)");
	}
}

function emp_create_transactions_table() {
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	$table_name = $wpdb->prefix.'em_transactions'; 
	$sql = "CREATE TABLE ".$table_name." (
		  transaction_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  booking_id bigint(20) unsigned NOT NULL DEFAULT '0',
		  transaction_gateway_id varchar(30) DEFAULT NULL,
		  transaction_payment_type varchar(20) DEFAULT NULL,
		  transaction_timestamp datetime NOT NULL,
		  transaction_total_amount decimal(8,2) DEFAULT NULL,
		  transaction_currency varchar(35) DEFAULT NULL,
		  transaction_status varchar(35) DEFAULT NULL,
		  transaction_duedate date DEFAULT NULL,
		  transaction_gateway varchar(50) DEFAULT NULL,
		  transaction_note text,
		  transaction_expires datetime DEFAULT NULL,
		  PRIMARY KEY  (transaction_id)
		) DEFAULT CHARSET=utf8 ;";
	
	dbDelta($sql);
	emp_sort_out_table_nu_keys($table_name,array('transaction_gateway','booking_id'));
}

function emp_create_coupons_table() {
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
	$table_name = $wpdb->prefix.'em_coupons'; 
	$sql = "CREATE TABLE ".$table_name." (
		  coupon_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  coupon_owner bigint(20) unsigned NOT NULL,
		  blog_id bigint(20) unsigned DEFAULT NULL,
		  coupon_code varchar(20) NOT NULL,
		  coupon_name text NOT NULL,
		  coupon_description text NULL,
		  coupon_max int(10) NULL,
		  coupon_start datetime DEFAULT NULL,
		  coupon_end datetime DEFAULT NULL,
		  coupon_type varchar(20) DEFAULT NULL,
		  coupon_tax varchar(4) DEFAULT NULL,
		  coupon_discount decimal(8,2) NOT NULL,
		  coupon_eventwide bool NOT NULL DEFAULT 0,
		  coupon_sitewide bool NOT NULL DEFAULT 0,
		  coupon_private bool NOT NULL DEFAULT 0,
		  PRIMARY KEY  (coupon_id)
		) DEFAULT CHARSET=utf8 ;";
	dbDelta($sql);
	$array = array('coupon_owner','coupon_code');
	if( is_multisite() ) $array[] = 'blog_id'; //only add index if needed
	emp_sort_out_table_nu_keys($table_name,$array);
}

function emp_create_reminders_table(){
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
    $table_name = $wpdb->prefix.'em_email_queue';
	$sql = "CREATE TABLE ".$table_name." (
		  queue_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  event_id bigint(20) unsigned DEFAULT NULL,
		  booking_id bigint(20) unsigned DEFAULT NULL,
		  email text NOT NULL,
		  subject text NOT NULL,
		  body text NOT NULL,
		  attachment text NOT NULL,
		  PRIMARY KEY  (queue_id)
		) DEFAULT CHARSET=utf8 ;";
	dbDelta($sql);
	emp_sort_out_table_nu_keys($table_name,array('event_id','booking_id'));
}

function emp_create_bookings_relationships_table(){
	global  $wpdb;
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php'); 
    $table_name = $wpdb->prefix.'em_bookings_relationships';
	$sql = "CREATE TABLE ".$table_name." (
		  booking_relationship_id bigint(20) unsigned NOT NULL AUTO_INCREMENT,
		  event_id bigint(20) unsigned DEFAULT NULL,
		  booking_id bigint(20) unsigned DEFAULT NULL,
		  booking_main_id bigint(20) unsigned DEFAULT NULL,
		  PRIMARY KEY  (booking_relationship_id)
		) DEFAULT CHARSET=utf8 ;";
	dbDelta($sql);
	emp_sort_out_table_nu_keys($table_name,array('event_id','booking_id','booking_main_id'));
}

function emp_add_options() {
	global $wpdb;
	add_option('em_pro_data', array());
	//Form Stuff
	$booking_form_data = array( 'name'=> __('Default','em-pro'), 'form'=> array (
	  'name' => array ( 'label' => __('Name','dbem'), 'type' => 'name', 'fieldid'=>'user_name', 'required'=>1 ),
	  'user_email' => array ( 'label' => __('Email','dbem'), 'type' => 'user_email', 'fieldid'=>'user_email', 'required'=>1 ),
    	'dbem_address' => array ( 'label' => __('Address','dbem'), 'type' => 'dbem_address', 'fieldid'=>'dbem_address', 'required'=>1 ),
    	'dbem_city' => array ( 'label' => __('City','dbem'), 'type' => 'dbem_city', 'fieldid'=>'dbem_city', 'required'=>1 ),
    	'dbem_state' => array ( 'label' => __('State/County','dbem'), 'type' => 'dbem_state', 'fieldid'=>'dbem_state', 'required'=>1 ),
    	'dbem_zip' => array ( 'label' => __('Zip/Post Code','dbem'), 'type' => 'dbem_zip', 'fieldid'=>'dbem_zip', 'required'=>1 ),
    	'dbem_country' => array ( 'label' => __('Country','dbem'), 'type' => 'dbem_country', 'fieldid'=>'dbem_country', 'required'=>1 ),
    	'dbem_phone' => array ( 'label' => __('Phone','dbem'), 'type' => 'dbem_phone', 'fieldid'=>'dbem_phone' ),
    	'dbem_fax' => array ( 'label' => __('Fax','dbem'), 'type' => 'dbem_fax', 'fieldid'=>'dbem_fax' ),
	  	'booking_comment' => array ( 'label' => __('Comment','dbem'), 'type' => 'textarea', 'fieldid'=>'booking_comment' ),
	));
	add_option('em_booking_form_error_required', __('Please fill in the field: %s','em-pro'));
    $new_fields = array(
    	'dbem_address' => array ( 'label' => __('Address','dbem'), 'type' => 'text', 'fieldid'=>'dbem_address', 'required'=>1 ),
    	'dbem_address_2' => array ( 'label' => __('Address Line 2','dbem'), 'type' => 'text', 'fieldid'=>'dbem_address_2' ),
    	'dbem_city' => array ( 'label' => __('City','dbem'), 'type' => 'text', 'fieldid'=>'dbem_city', 'required'=>1 ),
    	'dbem_state' => array ( 'label' => __('State/County','dbem'), 'type' => 'text', 'fieldid'=>'dbem_state', 'required'=>1 ),
    	'dbem_zip' => array ( 'label' => __('Zip/Post Code','dbem'), 'type' => 'text', 'fieldid'=>'dbem_zip', 'required'=>1 ),
    	'dbem_country' => array ( 'label' => __('Country','dbem'), 'type' => 'country', 'fieldid'=>'dbem_country', 'required'=>1 ),
    	'dbem_phone' => array ( 'label' => __('Phone','dbem'), 'type' => 'text', 'fieldid'=>'dbem_phone' ),
    	'dbem_fax' => array ( 'label' => __('Fax','dbem'), 'type' => 'text', 'fieldid'=>'dbem_fax' ),
    	'dbem_company' => array ( 'label' => __('Company','dbem'), 'type' => 'text', 'fieldid'=>'dbem_company' ),
    );
	add_option('em_user_fields', $new_fields);
	$customer_fields = array('address' => 'dbem_address','address_2' => 'dbem_address_2','city' => 'dbem_city','state' => 'dbem_state','zip' => 'dbem_zip','country' => 'dbem_country','phone' => 'dbem_phone','fax' => 'dbem_fax','company' => 'dbem_company');
    add_option('emp_gateway_customer_fields', $customer_fields);
    add_option('em_attendee_fields_enabled', defined('EM_ATTENDEES') && EM_ATTENDEES );
	//Gateway Stuff
    add_option('dbem_emp_booking_form_reg_input', 1);
    add_option('dbem_emp_booking_form_reg_show', 1);
	add_option('dbem_gateway_use_buttons', 0);
	add_option('dbem_gateway_label', __('Pay With','em-pro'));
	//paypal
	add_option('em_paypal_option_name', __('PayPal', 'em-pro'));
	add_option('em_paypal_form', '<img src="'.plugins_url('events-manager-pro/includes/images/paypal/paypal_info.png','events-manager').'" width="228" height="61" />');
	add_option('em_paypal_booking_feedback', __('Please wait whilst you are redirected to PayPal to proceed with payment.','em-pro'));
	add_option('em_paypal_booking_feedback_free', __('Booking successful.', 'dbem'));
	add_option('em_paypal_button', 'http://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif');
	add_option('em_paypal_booking_feedback_thanks', __('Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you along with a seperate email containing account details to access your booking information on this site. You may log into your account at www.paypal.com to view details of this transaction.', 'em-pro'));
	//offline
	add_option('em_offline_option_name', __('Pay Offline', 'em-pro'));
	add_option('em_offline_booking_feedback', __('Booking successful.', 'dbem'));
	add_option('em_offline_button', __('Pay Offline', 'em-pro'));
	//authorize.net
	add_option('em_authorize_aim_option_name', __('Credit Card', 'em-pro'));
	add_option('em_authorize_aim_booking_feedback', __('Booking successful.', 'dbem'));
	add_option('em_authorize_aim_booking_feedback_free', __('Booking successful. You have not been charged for this booking.', 'em-pro'));
	//email reminders
	add_option('dbem_cron_emails', 0);
	add_option('dbem_emp_emails_reminder_subject', 'Reminder - #_EVENTNAME');
	$email_footer = '<br /><br />-------------------------------<br />Powered by Events Manager - http://wp-events-plugin.com';
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />This is a reminder about your #_BOOKINGSPACES space/spaces reserved for #_EVENTNAME.<br />When : #_EVENTDATES @ #_EVENTTIMES<br />Where : #_LOCATIONNAME - #_LOCATIONFULLLINE<br />We look forward to seeing you there!<br />Yours faithfully,<br />#_CONTACTNAME",'dbem').$email_footer;
	add_option('dbem_emp_emails_reminder_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	add_option('dbem_emp_emails_reminder_time', '12:00 AM');
	add_option('dbem_emp_emails_reminder_days', 1);	
	add_option('dbem_emp_emails_reminder_ical', 1);
	//custom emails
	add_option('dbem_custom_emails', 0);
	add_option('dbem_custom_emails_events', 1);	
	add_option('dbem_custom_emails_events_admins', 1);
	add_option('dbem_custom_emails_gateways', 1);
	add_option('dbem_custom_emails_gateways_admins', 1);	
	//multiple bookings
	add_option('dbem_multiple_bookings_feedback_added', __('Your booking was added to your shopping cart.','em-pro'));
	add_option('dbem_multiple_bookings_feedback_already_added', __('You have already booked a spot at this eventin your cart, please modify or delete your current booking.','em-pro'));
	add_option('dbem_multiple_bookings_feedback_no_bookings', __('You have not booked any events yet. Your cart is empty.','em-pro'));
	add_option('dbem_multiple_bookings_feedback_loading_cart', __('Loading Cart Contents...','em-pro'));
	add_option('dbem_multiple_bookings_feedback_empty_cart', __('Are you sure you want to empty your cart?','em-pro'));
	add_option('dbem_multiple_bookings_submit_button', __('Place Order','em_pro'));
	//multiple bookings - emails
	add_option('dbem_multiple_bookings_contact_email_subject', __('New Booking','em-pro'));
	$respondent_email_body_localizable = __("#_BOOKINGNAME (#_BOOKINGEMAIL) has made a booking: <br />#_BOOKINGSUMMARY",'dbem').$email_footer;
	add_option('dbem_multiple_bookings_contact_email_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	add_option('dbem_multiple_bookings_contact_email_cancelled_subject', __('Booking Cancelled','em-pro'));
	$respondent_email_body_localizable = __("#_BOOKINGNAME (#_BOOKINGEMAIL) has cancelled a booking: <br />#_BOOKINGSUMMARY",'dbem').$email_footer;
	add_option('dbem_multiple_bookings_contact_email_cancelled_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	add_option('dbem_multiple_bookings_email_confirmed_subject', __('Booking Confirmed','em-pro'));
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />Your booking has been confirmed. <br />Below is a summary of your booking: <br />#_BOOKINGSUMMARY <br />We look forward to seeing you there!",'dbem').$email_footer;
	add_option('dbem_multiple_bookings_email_confirmed_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	add_option('dbem_multiple_bookings_email_pending_subject', __('Booking Pending','em-pro'));
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />Your booking is currently pending approval by our administrators. Once approved you will receive another confirmation email. <br />Below is a summary of your booking: <br />#_BOOKINGSUMMARY",'dbem').$email_footer;
	add_option('dbem_multiple_bookings_email_pending_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	add_option('dbem_multiple_bookings_email_rejected_subject', __('Booking Rejected','em-pro'));
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />Your requested booking has been rejected. <br />Below is a summary of your booking: <br />#_BOOKINGSUMMARY",'dbem').$email_footer;
	add_option('dbem_multiple_bookings_email_rejected_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	add_option('dbem_multiple_bookings_email_cancelled_subject', __('Booking Cancelled','em-pro'));
	$respondent_email_body_localizable = __("Dear #_BOOKINGNAME, <br />Your requested booking has been cancelled. <br />Below is a summary of your booking: <br />#_BOOKINGSUMMARY",'dbem').$email_footer;
	add_option('dbem_multiple_bookings_email_cancelled_body', str_replace("<br />", "\n\r", $respondent_email_body_localizable));
	
	//Version updates
	if( get_option('em_pro_version') ){ //upgrade, so do any specific version updates
		if( get_option('em_pro_version') < 2.16 ){ //add new customer information fields
		    $user_fields = get_option('em_user_fields', array () );
		    update_option('em_user_fields', array_merge($new_fields, $user_fields));
		}
		if( get_option('em_pro_version') < 2.061 ){ //new booking form data structure
			global $wpdb;
			//backward compatability, check first field to see if indexes start with 'booking_form_...' and change this.
			$form_fields = get_option('em_booking_form_fields', $booking_form_data['form']);
			if( is_array($form_fields) ){
				$booking_form_fields = array();
				foreach( $form_fields as $form_field_id => $form_field_data){
					foreach( $form_field_data as $field_key => $value ){
						$field_key = str_replace('booking_form_', '', $field_key);
						$booking_form_fields[$form_field_id][$field_key] = $value;
					}
				}
				//move booking form to meta table and update wp option with booking form id too
				$booking_form = serialize(array('name'=>__('Default','em-pro'), 'form'=>$booking_form_fields));
				if ($wpdb->insert(EM_META_TABLE, array('meta_key'=>'booking-form','meta_value'=>$booking_form,'object_id'=>0))){
					update_option('em_booking_form_fields',$wpdb->insert_id);
				}
			}
		}
		if( get_option('em_pro_version') < 1.6 ){ //make buttons the default option
			update_option('dbem_gateway_use_buttons', 1);
			if( get_option('em_offline_button_text') && !get_option('em_offline_button') ){
				update_option('em_offline_button',get_option('em_offline_button_text')); //merge offline quick pay button option into one
			}
			if( get_option('em_paypal_button_text') && !get_option('em_paypal_button') ){
				update_option('em_paypal_button',get_option('em_paypal_button_text')); //merge offline quick pay button option into one
			}
		}
		if( get_option('em_pro_version') < 2.243 ){ //fix badly stored user dates and times
			$EM_User_Form = EM_User_Fields::get_form();
			foreach($EM_User_Form->form_fields as $field_id => $field){
			    if( in_array($field['type'], array('date','time')) ){
			        //search the user meta table and modify all occorunces of this value if the format isn't correct
			        $meta_results = $wpdb->get_results("SELECT umeta_id, meta_value FROM {$wpdb->usermeta} WHERE meta_key='".$field_id."'", ARRAY_A);
			        foreach($meta_results as $meta_result){
			            if( is_serialized($meta_result['meta_value']) ){
			                $meta_value = unserialize($meta_result['meta_value']);
				            if( is_array($meta_value) && !empty($meta_value['start']) ){
				                $new_value = $meta_value['start'];
				                if( !empty($meta_value['end']) ){
				                	$new_value .= ','.$meta_value['end'];
				                }
				                //update this user meta with the new value
				                $wpdb->query("UPDATE {$wpdb->usermeta} SET meta_value='$new_value' WHERE umeta_id='{$meta_result['umeta_id']}'");
				            }
			            } 
			        }
			    }
			}
		}
		if( get_option('em_pro_version') < 2.36 ){ //disable custom emails for upgrades, prevent unecessary features
			add_option('dbem_custom_emails', 0);	
		}
		if( get_option('dbem_muliple_bookings_form') ){ //fix badly stored user dates and times
			update_option('dbem_multiple_bookings_form', get_option('dbem_muliple_bookings_form'));
			delete_option('dbem_muliple_bookings_form');
		}
	}else{
		//Booking form stuff only run on install
		$insert_result = $wpdb->insert(EM_META_TABLE, array('meta_value'=>serialize($booking_form_data), 'meta_key'=>'booking-form','object_id'=>0));
		add_option('em_booking_form_fields', $wpdb->insert_id);
	}
}     
?>