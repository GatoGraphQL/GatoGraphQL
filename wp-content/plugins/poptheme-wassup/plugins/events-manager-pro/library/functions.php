<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Events Manager Pro plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/


// Check if events manager pro plugin has been installed
include('add-ons/gateways.php');

// Add classes dynamically with jQuery
/*
add_filter('gd_jquery_addclass', 'gd_jquery_addclass_empro_impl');
function gd_jquery_addclass_empro_impl($items) { 
	$items[".em-booking-gateway-form"] = "col-md-5 alert"; 
	return $items; 
}
*/

// Remove Events Manager Pro "Further information" in the My profile page
remove_action( 'show_user_profile', array('EM_User_Fields','show_profile_fields'), 1 );
remove_action( 'edit_user_profile', array('EM_User_Fields','show_profile_fields'), 1 );


// Add Events Manager Pro Bank Transfer Gateway options
add_action('PoP:install', 'gd_emp_add_options');
function gd_emp_add_options() {

	//banktransfer
	add_option('em_banktransfer_option_name', __('Bank Transfer', 'poptheme-wassup'));
	add_option('em_banktransfer_booking_feedback', __('Booking successful.', 'dbem'));
	add_option('em_banktransfer_button', __('Bank Transfer', 'poptheme-wassup'));
	add_option('em_banktransfer_form_message', __('Make your payment directly into our bank account. Please use your Order ID as the payment reference. Once the payment has been done, please send an email to <a href="mailto:info@greendrinks.cn?subject=Bank Transfer">info@greendrinks.cn</a> with the Order ID and the Event Booking will be confirmed.', 'poptheme-wassup'));
	add_option('em_banktransfer_form_account_details', __('Account details', 'poptheme-wassup'));
	
	// Alipay
	add_option('em_alipay_option_name', __('Alipay', 'poptheme-wassup'));
	add_option('em_alipay_form', '<p>'.__("Pay via Alipay, if you don't have an Alipay account, you can also pay with your debit card or credit card", 'poptheme-wassup').'</p><img src="'.POPPROCESSORS_URI_LIB.'/plugins/events-manager-pro/includes/images/alipay/alipay.gif" width="135" height="45" />');
	add_option('em_alipay_booking_feedback', __('Please wait whilst you are redirected to Alipay to proceed with payment.','em-pro'));
	add_option('em_alipay_booking_feedback_free', __('Booking successful.', 'dbem'));
	add_option('em_alipay_button', POPPROCESSORS_URI_LIB.'/plugins/events-manager-pro/includes/images/alipay/alipaybutton.jpg');
	add_option('em_alipay_booking_feedback_thanks', __('Thank you for your payment. Your transaction has been completed, and a receipt for your purchase has been emailed to you along with a seperate email containing account details to access your booking information on this site. You may log into your account at www.paypal.com to view details of this transaction.', 'em-pro'));
	
	//offline
	add_option('em_offline_form', '<p>'.__("Simply come and pay at the Event ;)", 'poptheme-wassup').'</p>');
	
	// Add Select options
	add_option('dbem_gateway_use_please_select', 0);
	add_option('dbem_gateway_please_select_label', __('Please select','em-pro'));
} 


add_filter('em_booking_output_placeholder', 'gd_em_booking_output_placeholder', 10, 4);
function gd_em_booking_output_placeholder ($result,$EM_Booking,$placeholder,$target='html') {

	if( $placeholder == "#_GATEWAYINFORMATION" && !empty($EM_Booking->booking_meta['gateway'])) {
		
		$result = __(get_option('em_'. $EM_Booking->booking_meta['gateway'] . "_form" ), 'poptheme-wassup');
	}

	return $result;
}

/**
 * Order the Gateway Payments
 */
/* 
add_filter('em_payment_gateways', 'gd_em_payment_gateways_reorder_gateways');
function gd_em_payment_gateways_reorder_gateways($gateways) {

	$ordered_gateways = array();
	$order = array("alipay", "paypal", "banktransfer", "offline");	
	foreach ($order as $gateway) {
	
		if ($gateways[$gateway])
			$ordered_gateways[$gateway] = $gateways[$gateway];			
	}
	
	return $ordered_gateways;	
}
*/

/**
 * Order the Gateway Payments
 */

add_action('after_setup_theme', 'gd_plugins_loaded_reorder_gateways');
function gd_plugins_loaded_reorder_gateways() {

	global $EM_Gateways;

	$ordered_gateways = array();
	$order = array("alipay", "paypal", "banktransfer", "offline");	
	foreach ($order as $gateway) {
	
		if ($EM_Gateways[$gateway])
			$ordered_gateways[$gateway] = $EM_Gateways[$gateway];			
	}
	
	$EM_Gateways = $ordered_gateways;	
}


add_filter('emp_forms_output_field', 'gd_emp_forms_output_field_translate_label', 10, 3);
function gd_emp_forms_output_field_translate_label ($content, $EM_Form, $field) {

	$content = str_replace($field['label'], apply_filters("gd_translate", $field['label']), $content);

	return $content;
}

/**---------------------------------------------------------------------------------------------------------------
 * Coupons or Booking form must not be shown on the frontend. So take away if not in back-end
 * ---------------------------------------------------------------------------------------------------------------*/
if (!is_admin()) {
	// Remove Coupons from My Events
	remove_action('em_events_admin_bookings_footer',array('EM_Coupons', 'admin_meta_box'),20,1);
	remove_action('em_events_admin_bookings_footer',array('EM_Booking_Form', 'event_bookings_meta_box'),20,1);
	
	// Gateway Offline
	global $EM_Gateways;
	if ($EM_Gateways && $EM_Gateways['offline']) {
		remove_action('em_admin_event_booking_options_buttons', array($EM_Gateways['offline'], 'event_booking_options_buttons'),10);
		remove_action('em_admin_event_booking_options', array($EM_Gateways['offline'], 'event_booking_options'),10);	
		remove_action('em_bookings_single_metabox_footer', array($EM_Gateways['offline'], 'add_payment_form'),1,1); //add payment to booking
	}
	
	// Remove options to export or filter
	remove_filter('em_bookings_table_cols_template', array('EM_Booking_Form','em_bookings_table_cols_template'),10,2);
	remove_action('em_bookings_table_cols_template', array('EM_Coupons', 'em_bookings_table_cols_template'),10,1);
	
	global $EM_Gateways_Transactions;
	if ($EM_Gateways_Transactions) {
		remove_filter('em_bookings_table_cols_template', array($EM_Gateways_Transactions, 'em_bookings_table_cols_template'),10,2);
	}
}

