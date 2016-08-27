<?php
/*
* This displays the content of the #_BOOKINGATTENDEES placeholder in Multiple Bookings mode.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/placeholders/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
foreach( $EM_Multiple_Booking->get_bookings() as $EM_Booking ){ /* @var $EM_Booking EM_Booking */
	echo "\r\n". __('Event','em-pro').' - '. $EM_Booking->get_event()->event_name ."\r\n". '==============================';
	emp_locate_template('placeholders/bookingattendees.php', true, array('EM_Booking'=>$EM_Booking));
}