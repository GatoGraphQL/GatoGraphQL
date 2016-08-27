<?php
//set debug mode on.
function dbem_debug_option($option){
	global $dbem_debug_options;
	return $dbem_debug_options[$option];
}

global $dbem_debug_options;
$dbem_email_template = str_replace('"', "'", file_get_contents(WP_PLUGIN_DIR.'/events-manager/includes/sample-placeholders/booking-email.html'));
$dbem_event_format = str_replace('"', "'", file_get_contents(WP_PLUGIN_DIR.'/events-manager/includes/sample-placeholders/event-single.html'));
$dbem_debug_options = array(
	//Event Formatting
	'dbem_event_list_item_format' => '<li>#j #M #Y - #H:#i<br/> #_EVENTLINK<br/>#_LOCATIONTOWN </li>',
	'dbem_single_event_format' => $dbem_event_format,
	//Location Formatting
	'dbem_location_event_list_item_format' => "<li>#_LOCATIONNAME - #j #M #Y - #H:#i</li>",
	'dbem_location_list_item_format' => '#_LOCATIONLINK<ul><li>#_LOCATIONADDRESS</li><li>#_LOCATIONTOWN</li></ul>',
	'dbem_location_no_events_message' => __('<li>No events in this location</li>', 'events-manager'),
	'dbem_single_location_format' => '<p>#_LOCATIONADDRESS</p><p>#_LOCATIONTOWN</p>',
	//General Settings
		//Emails
		'dbem_bookings_contact_email_subject' => "New booking [DEBUG MODE]",
		'dbem_bookings_contact_email_body' => $dbem_email_template,
		'dbem_contactperson_email_cancelled_subject' => "Booking Cancelled [DEBUG MODE]",
		'dbem_contactperson_email_cancelled_body' => $dbem_email_template,
		'dbem_bookings_email_pending_subject' => "Booking Pending [DEBUG MODE]",
		'dbem_bookings_email_pending_body' => $dbem_email_template,
		'dbem_bookings_email_rejected_subject' => "Booking Rejected [DEBUG MODE]",
		'dbem_bookings_email_rejected_body' => $dbem_email_template,
		'dbem_bookings_email_confirmed_subject' => 'Booking Confirmed [DEBUG MODE]',
		'dbem_bookings_email_confirmed_body' => $dbem_email_template,
		'dbem_bookings_email_cancelled_subject' => 'Booking Cancelled [DEBUG MODE]',
		'dbem_bookings_email_cancelled_body' => $dbem_email_template
);

if( get_option('dbem_debug') && !empty($_REQUEST['page']) && $_REQUEST['page'] != 'events-manager-options' ){
	foreach($dbem_debug_options as $debug_option => $value){
		if( !empty($dbem_debug_options[$debug_option]) ){
			add_filter('pre_option_'.$debug_option, create_function('','return "'.$dbem_debug_options[$debug_option].'";'));
		}
	}
}
if( is_admin() && get_option('dbem_debug')){
	function em_debug_notification(){ ?><div class="error"><p><strong><?php echo sprintf(__('You are in Events Manager debug mode. To turn debug mode off, go to the <a href="%s">settings</a> page.','events-manager'), em_add_get_params($_SERVER['REQUEST_URI'], array('dbem_debug_off'=>1))) ?></strong></p></div><?php }
	add_action ( 'admin_notices', 'em_debug_notification' );
}