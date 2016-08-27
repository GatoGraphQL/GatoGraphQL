<?php
/*
* This displays the content of the #_BOOKINGSUMMARY placeholder ONLY when Multiple Bookings Mode is being used.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/placeholders/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
foreach( $EM_Multiple_Booking->get_bookings() as $EM_Booking ): /* @var $EM_Booking EM_Booking */ ?>

======================================
<?php echo $EM_Booking->get_event()->output("#_EVENTNAME - #_EVENTDATES - #_EVENTTIMES 
#_LOCATIONFULLLINE"); ?>

======================================

<?php foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking):
/* @var $EM_Ticket_Booking EM_Ticket_Booking */
echo $EM_Ticket_Booking->get_ticket()->ticket_name; 
?>

Quantity: <?php echo $EM_Ticket_Booking->get_spaces(); ?>

Price: <?php echo em_get_currency_formatted($EM_Ticket_Booking->get_price()); ?>

<?php endforeach; ?>

--------------------------------------
Spaces : <?php echo $EM_Booking->get_spaces(); ?>

Total : <?php echo $EM_Booking->get_price(true); ?>

<?php endforeach; ?>

=======================================

Overall Spaces : <?php echo $EM_Multiple_Booking->get_spaces(); ?>
<?php if( !get_option('dbem_bookings_tax_auto_add') && is_numeric(get_option('dbem_bookings_tax')) && get_option('dbem_bookings_tax') > 0 ): ?>

Sub Total : <?php echo $EM_Multiple_Booking->get_price_pre_taxes(true); ?>

Taxes : <?php echo $EM_Multiple_Booking->get_price_taxes(true); ?>

Total with Taxes : <?php echo $EM_Multiple_Booking->get_price(true); ?>
<?php else: ?>

Total : <?php echo $EM_Multiple_Booking->get_price(true); ?>
<?php endif; ?>