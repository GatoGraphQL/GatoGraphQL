<?php
/*
* This displays the content of the #_BOOKINGATTENDEES placeholder.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/placeholders/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
$EM_Tickets_Bookings = $EM_Booking->get_tickets_bookings();
$attendee_datas = EM_Attendees_Form::get_booking_attendees($EM_Booking);
foreach( $EM_Tickets_Bookings->tickets_bookings as $EM_Ticket_Booking ){
	//Display ticket info
	if( !empty($attendee_datas[$EM_Ticket_Booking->ticket_id]) ){
		echo "\r\n". __('Ticket','em-pro').' - '. $EM_Ticket_Booking->get_ticket()->ticket_name ."\r\n". '-----------------------------';
		//display a row for each space booked on this ticket
		foreach( $attendee_datas[$EM_Ticket_Booking->ticket_id] as $attendee_title => $attendee_data ){
		    echo "\r\n". $attendee_title ."\r\n". '------------';
			foreach( $attendee_data as $field_label => $field_value){
				echo  "\r\n". $field_label .': ';
				echo  $field_value;
				echo  "\r\n";
			}
		}
	}
}