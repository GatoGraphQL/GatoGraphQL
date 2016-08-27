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

<?php foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking):
/* @var $EM_Ticket_Booking EM_Ticket_Booking */
echo $EM_Ticket_Booking->get_ticket()->ticket_name; 
?>

--------------------

Quantity: <?php echo $EM_Ticket_Booking->get_spaces(); ?>

Price: <?php echo $EM_Ticket_Booking->get_price(true); ?>

<?php endforeach; ?>

--------------------------------------
Spaces : <?php echo $EM_Booking->get_spaces(); ?>

Total : <?php echo $EM_Booking->get_price_base(true); ?>

<?php endforeach; ?>

=======================================

Overall Spaces : <?php echo $EM_Multiple_Booking->get_spaces(); ?>

<?php 
$price_summary = $EM_Multiple_Booking->get_price_summary_array();
//we should now have an array of information including base price, taxes and post/pre tax discounts
?>
<?php _e('Sub Total','em-pro'); ?> : <?php echo $EM_Booking->get_price_base(true); ?>

<?php if( count($price_summary['discounts_pre_tax']) > 0 ): ?>

<?php _e('Discounts Before Taxes','em-pro'); ?>

<?php foreach( $price_summary['discounts_pre_tax'] as $discount_summary ): ?>
(<?php echo $discount_summary['name']; ?>) : -<?php echo $discount_summary['amount']; ?>

<?php endforeach; ?>

<?php endif; ?>
<?php if( !empty($price_summary['taxes']['amount'])  ): ?>
<?php _e('Taxes','em-pro'); ?> ( <?php echo $price_summary['taxes']['rate']; ?> ) : <?php echo $price_summary['taxes']['amount']; ?>
<?php endif; ?>

<?php if( count($price_summary['discounts_post_tax']) > 0 ): ?>

<?php _e('Discounts (After Taxes)','em-pro'); ?>

<?php foreach( $price_summary['discounts_post_tax'] as $discount_summary ): ?>
<?php echo $discount_summary['name']; ?> : -<?php echo $discount_summary['amount']; ?>
 
<?php endforeach; ?>
<?php endif; ?>

<?php _e('Total Price','dbem'); ?> : <?php echo $price_summary['total']; ?>