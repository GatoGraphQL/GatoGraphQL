<?php
/*
* WARNING -This is a recently added template (2013-01-30), and is likly to change as we fine-tune things over the coming weeks/months, if at all possible try to use our hooks or CSS/jQuery to acheive your customizations 
* This page displays a checkout page when 'Multiple Bookings Mode' is in effect.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/multiple-bookings/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
$EM_Multiple_Booking = EM_Multiple_Bookings::get_multiple_booking();
if( empty($EM_Multiple_Booking->bookings) ){
	echo get_option('dbem_multiple_bookings_feedback_no_bookings');
	return;
}
?>
<div id="em-booking" class="em-booking">
	<form id='em-booking-form' class="em-booking-form" name='booking-form' method='post' action='<?php echo apply_filters('em_checkout_form_action_url',$_SERVER['REQUEST_URI']); ?>#em-booking'>
		<?php
			global $EM_Notices;
			echo $EM_Notices;
		?>
	 	<input type='hidden' name='action' value='emp_checkout'/>
	 	<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('emp_checkout'); ?>'/>
	 	
		<?php do_action('em_checkout_form_before_summary', $EM_Multiple_Booking); //do not delete ?>
		<?php emp_locate_template('multiple-bookings/bookings-table.php',true); ?>
		<?php do_action('em_checkout_form_after_summary', $EM_Multiple_Booking); //do not delete ?>
		
		<div class='em-booking-form-details'>
			<?php echo EM_Booking_Form::get_form(false, $EM_Multiple_Booking); ?>
			<?php do_action('em_checkout_form_footer', $EM_Multiple_Booking); //do not delete ?>
			<div class="em-booking-buttons">
				<?php if( preg_match('/https?:\/\//',get_option('dbem_multiple_bookings_submit_button')) ): //Settings have an image url (we assume). Use it here as the button.?>
				<input type="image" src="<?php echo get_option('dbem_multiple_bookings_submit_button'); ?>" class="em-booking-submit" id="em-booking-submit" />
				<?php else: //Display normal submit button ?>
				<input type="submit" class="em-booking-submit" id="em-booking-submit" value="<?php echo get_option('dbem_multiple_bookings_submit_button','Place Order'); ?>" />
				<?php endif; ?>
			</div>
			<?php do_action('em_checkout_form_footer_after_buttons', $EM_Multiple_Booking); //do not delete ?>
		</div>
	</form>
</div>