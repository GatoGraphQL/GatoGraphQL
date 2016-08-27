<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Events Manager plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// To integrate qtranslate in the Event Manager's Ajax calls
add_filter('em_wp_localize_script', 'gd_em_wp_localize_script');
function gd_em_wp_localize_script($em_localized_js){

	$em_localized_js['ajaxurl'] = admin_url('admin-ajax.php?lang='.qtranxf_getLanguage());
	$em_localized_js['bookingajaxurl'] = admin_url('admin-ajax.php?lang='.qtranxf_getLanguage());
	$em_localized_js['locationajaxurl'] = admin_url('admin-ajax.php?action=locations_search&lang='.qtranxf_getLanguage());

	return $em_localized_js;
}


// To translate the email from Event Manager
add_filter('em_booking_email_messages', 'gd_em_booking_email_messages', 10, 2);
function gd_em_booking_email_messages($msg, $booking) {

	if ($msg['user']) {
	
		$msg['user']['subject'] = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($msg['user']['subject']);
		$msg['user']['body'] = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($msg['user']['body']);
	}
	
	return $msg;
}




// Translate all the Messages before being sent by email
add_filter('em_booking_email_messages', 'gd_em_booking_email_messages_translate', 10, 2);
function gd_em_booking_email_messages_translate($msg, $EM_Booking = null) {

	// User Message: translate to his language
	$msg['user'] = qtranxf_useCurrentLanguageIfNotFoundUseDefaultLanguage($msg['user']);
	
	// Admin Message: translate always to the default language
	$msg['admin'] = qtranxf_useDefaultLanguage($msg['admin']);
	
	return $msg;
}
