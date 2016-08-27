<?php
/*
* WARNING -This is a recently added template (2013-01-30), and is likly to change as we fine-tune things over the coming weeks/months, if at all possible try to use our hooks or CSS/jQuery to acheive your customizations
* This page displays the widget content of the bookings cart when 'Multiple Bookings Mode' is in effect.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/multiple-bookings/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
* 
* Available arguments:
* $instance - arguments for displaying the widget
*/
$instance = !empty($instance) ? $instance : $_REQUEST;
$EM_Multiple_Booking = EM_Multiple_Bookings::get_multiple_booking();
if( empty($EM_Multiple_Booking->bookings) ){
	echo $instance['no_bookings_text'];
	return;
}
?>
<ul>
	<?php if(!empty($instance['format'])): foreach($EM_Multiple_Booking->bookings as $EM_Booking): /* @var $EM_Booking EM_Booking */ ?>
		<li><?php echo $EM_Booking->output($instance['format']); ?></li>
	<?php endforeach; endif; ?>
	<li class="em-cart-widget-total"><strong><?php _e('Total','em-pro'); ?> : </strong><?php echo sprintf(__('%d Spaces','em-pro'), $EM_Multiple_Booking->get_spaces(true)); ?> - <?php echo $EM_Multiple_Booking->get_price(true); ?></li>
	<li class="em-cart-widget-cart-link"><a href="<?php echo get_permalink(get_option('dbem_multiple_bookings_cart_page')); ?>"><?php echo $instance['cart_text']; ?></a></li>
	<li class="em-cart-widget-checkout-link"><a href="<?php echo get_permalink(get_option('dbem_multiple_bookings_checkout_page')); ?>"><?php echo $instance['checkout_text']; ?></a></li>
</ul>