<?php
/*
* WARNING -This is a recently added template (2013-01-30), and is likly to change as we fine-tune things over the coming weeks/months, if at all possible try to use our hooks or CSS/jQuery to acheive your customizations
* This page displays a 'cart' of bookings a user is in the process of making, provided 'Multiple Bookings Mode' is in effect.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/multiple-bookings/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
$EM_Multiple_Booking = EM_Multiple_Bookings::get_multiple_booking();
if( empty($EM_Multiple_Booking->bookings) ){
	echo get_option('dbem_multiple_bookings_feedback_no_bookings');
	return;
}
//TODO make bookings editable
global $EM_Notices;
echo $EM_Notices;
emp_locate_template('multiple-bookings/cart-table.php',true);
?>
<div class="em-cart-actions <?php if( get_option('dbem_css_rsvp') ) echo 'css-booking'; ?>">
	<form action="<?php echo get_permalink(get_option('dbem_multiple_bookings_checkout_page')); ?>" method="post">
		<button type="submit" class="em-cart-actions-empty"><?php _e('Empty Cart','em-pro'); ?></button>
		<button type="submit"><?php _e('Proceed to Checkout','em-pro'); ?></button>
	</form>
</div>