<?php
/* @var $EM_Event EM_Event */
$people = array();
$EM_Bookings = $EM_Event->get_bookings();
if( count($EM_Bookings->bookings) > 0 ){
	?>
	<ul class="event-attendees">
	<?php
	$guest_bookings = get_option('dbem_bookings_registration_disable');
	$guest_booking_user = get_option('dbem_bookings_registration_user');
	foreach( $EM_Bookings as $EM_Booking){
		if($EM_Booking->booking_status == 1 && !in_array($EM_Booking->get_person()->ID, $people) ){
			$people[] = $EM_Booking->get_person()->ID;
			echo '<li>'. $EM_Booking->get_person()->get_name() .'</li>';
		}elseif($EM_Booking->booking_status == 1 && $guest_bookings && $EM_Booking->get_person()->ID == $guest_booking_user ){
			echo '<li>'. $EM_Booking->get_person()->get_name() .'</li>';
		}
	}
	?>
	</ul>
	<?php
}