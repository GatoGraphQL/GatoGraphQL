<?php
/*
* This displays the content of the #_BOOKINGTICKETS placeholder when 'Multiple Bookings Mode' is in effect.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/placeholders/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
foreach( $EM_Multiple_Booking->get_bookings() as $EM_Booking ): ?>

<?php echo $EM_Booking->get_event()->event_name; ?>
------------

<?php foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking):
/* @var $EM_Ticket_Booking EM_Ticket_Booking */
echo $EM_Ticket_Booking->get_ticket()->ticket_name; 
?>

Quantity: <?php echo $EM_Ticket_Booking->get_spaces(); ?>

Price: <?php echo em_get_currency_formatted($EM_Ticket_Booking->get_price()); ?>


<?php endforeach; ?>
<?php endforeach; ?>