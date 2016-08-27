<?php
/*
* This displays the content of the #_BOOKINGSUMMARY placeholder
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manage/placeholders/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
/* @var $EM_Booking EM_Booking */ ?>
<?php foreach($EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking): /* @var $EM_Ticket_Booking EM_Ticket_Booking */ ?>

<?php echo $EM_Ticket_Booking->get_ticket()->ticket_name; ?>

--------------------------------------
<?php _e('Quantity','events-manager'); ?>: <?php echo $EM_Ticket_Booking->get_spaces(); ?>

<?php _e('Price','events-manager'); ?>: <?php echo $EM_Ticket_Booking->get_price(true); ?>

<?php endforeach; ?>

=======================================

<?php 
$price_summary = $EM_Booking->get_price_summary_array();
//we should now have an array of information including base price, taxes and post/pre tax discounts
?>
<?php _e('Sub Total','events-manager'); ?> : <?php echo $EM_Booking->get_price_base(true); ?>

<?php if( count($price_summary['discounts_pre_tax']) > 0 ): ?>

<?php _e('Discounts Before Taxes','events-manager'); ?>

<?php foreach( $price_summary['discounts_pre_tax'] as $discount_summary ): ?>
(<?php echo $discount_summary['name']; ?>) : -<?php echo $discount_summary['amount']; ?>

<?php endforeach; ?>

<?php endif; ?>
<?php if( !empty($price_summary['taxes']['amount'])  ): ?>
<?php _e('Taxes','events-manager'); ?> ( <?php echo $price_summary['taxes']['rate']; ?> ) : <?php echo $price_summary['taxes']['amount']; ?>
<?php endif; ?>

<?php if( count($price_summary['discounts_post_tax']) > 0 ): ?>

<?php _e('Discounts (After Taxes)','events-manager'); ?>

<?php foreach( $price_summary['discounts_post_tax'] as $discount_summary ): ?>
<?php echo $discount_summary['name']; ?> : -<?php echo $discount_summary['amount']; ?>
 
<?php endforeach; ?>
<?php endif; ?>

<?php _e('Total Price','events-manager'); ?> : <?php echo $price_summary['total']; ?>