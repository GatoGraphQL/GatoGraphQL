<?php
/*
* This displays the content of the #_BOOKINGATTENDEES placeholder.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/placeholders/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
$EM_Tickets_Bookings = $EM_Booking->get_tickets_bookings();
foreach( $EM_Booking->get_tickets()->tickets as $EM_Ticket ){
	if( !empty($EM_Tickets_Bookings->tickets_bookings[$EM_Ticket->ticket_id]) ){
		$EM_Ticket_Booking = $EM_Tickets_Bookings->tickets_bookings[$EM_Ticket->ticket_id];
		//Display ticket info
		echo "\r\n". __('Ticket','em-pro').' - '. $EM_Ticket->ticket_name ."\r\n". '-----------------------------';
		//display a row for each space booked on this ticket
		for( $i = 0; $i < $EM_Ticket_Booking->ticket_booking_spaces; $i++ ){
			if( isset($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i]) ){ //unlike post values each attendee has an array within the array of a ticket attendee info
			    $EM_Form = EM_Attendees_Form::get_form($EM_Booking->event_id); //can be repeated since object is stored temporarily
				$EM_Form->field_values = $EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i];
				$EM_Form->errors = array();
				//backward compatibility for old booking forms and saved comments
				if( empty($EM_Form->field_values['booking_comment']) && !empty($EM_Booking->booking_comment) ){ $EM_Form->field_values['booking_comment'] = $EM_Booking->booking_comment; }
				//output the field values
				echo "\r\n". sprintf(__('Attendee %s','em-pro'), $i+1) ."\r\n". '------------';
				foreach( $EM_Form->form_fields as $fieldid => $field){
					if( !array_key_exists($fieldid, $EM_Form->user_fields) && $field['type'] != 'html' ){
						echo  "\r\n". $field['label'] .': ';
						$field_value = (isset($EM_Form->field_values[$fieldid])) ? $EM_Form->field_values[$fieldid]:'n/a';
						echo  $EM_Form->get_formatted_value($field, $field_value);
					}
				}
				echo  "\r\n";
			}
		}
	}
}