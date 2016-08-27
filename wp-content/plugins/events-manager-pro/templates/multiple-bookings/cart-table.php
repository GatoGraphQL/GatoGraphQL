<?php
/*
* WARNING -This is a recently added template (2013-01-30), and is likly to change as we fine-tune things over the coming weeks/months, if at all possible try to use our hooks or CSS/jQuery to acheive your customizations
* This displays the booking cart showing the persons bookings, attendees and a breakdown of pricing.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/multiple-bookings/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
if(empty($EM_Multiple_Booking)) $EM_Multiple_Booking = EM_Multiple_Bookings::get_multiple_booking();
if( empty($EM_Multiple_Booking->bookings) ){
	echo get_option('dbem_multiple_bookings_feedback_no_bookings');
	return;
}
//TODO make bookings editable
//echo "<pre>"; print_r($EM_Multiple_Booking->booking_meta); echo "</pre>";
?>
<div class="em-cart-table">
	<table>
		<tbody class="em-cart-summary">
			<?php do_action('em_cart_form_before_events', $EM_Multiple_Booking); //do not delete ?>
			<tr class="em-cart-title">
				<th class="em-cart-title-event">Event</th>
				<th class="em-cart-title-spaces">Spaces</th>
				<th class="em-cart-title-price">Price</th>
			</tr>
			<?php foreach($EM_Multiple_Booking->get_bookings() as $EM_Booking): /* @var $EM_Booking EM_Booking */ ?>
				<tr class="em-cart-table-event-summary" id="em-cart-table-event-summary-<?php echo $EM_Booking->get_event()->event_id; ?>">
					<td>
						<?php ob_start(); ?>
						<span class="em-cart-table-event-title">#_EVENTLINK</span><br/>
						<?php _e('When','em-pro'); ?> : #_EVENTDATES @ #_EVENTTIMES<br />
						<?php _e('Where','em-pro'); ?> : #_LOCATIONFULLLINE
						<?php echo $EM_Booking->output(ob_get_clean()); ?><br />
						<div class="em-cart-table-details-actions">
							<!-- <a href="#" class="em-cart-table-actions-edit" rel="<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-cart-table-actions-edit-<?php echo $EM_Booking->get_event()->event_id; ?>">
								<?php _e('edit','em-pro'); ?>
							</a>&nbsp; --->
							<a href="#" class="em-cart-table-actions-remove" rel="<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-cart-table-actions-remove-<?php echo $EM_Booking->get_event()->event_id; ?>">
								<?php _e('remove','em-pro'); ?>
							</a>
						</div>
						<div class="em-cart-table-details-triggers">
							<a href="#" class="em-cart-table-details-show" rel="<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-cart-table-details-show-<?php echo $EM_Booking->get_event()->event_id; ?>">
								+ <?php _e('details','em-pro'); ?>
							</a>
							<a href="#" class="em-cart-table-details-hide" rel="<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-cart-table-details-hide-<?php echo $EM_Booking->get_event()->event_id; ?>">
								- <?php _e('details','em-pro'); ?>
							</a>
						</div>
						<?php
							//get booking form information (aside from attendee info 
							$booking_data = EM_Booking_Form::get_booking_data($EM_Booking);
							if( count($booking_data['booking']) ){ 
							?>
							<div class="em-cart-table-event-details em-cart-table-event-details-<?php echo $EM_Booking->get_event()->event_id; ?> em-cart-info">
								<div class="em-cart-info-booking-title"><?php echo __('Booking Information','em-pro') ?></div>
								<div class="em-cart-info-values">
									<?php foreach( $booking_data['booking'] as $booking_label => $booking_value ): ?>
									<label><?php echo $booking_label; ?> :</label>
									<span><?php echo $booking_value; ?></span><br />
									<?php endforeach; ?>
								</div>
							</div>
						<?php } ?>
					</td>
					<td class="em-cart-table-spaces"><span><?php echo $EM_Booking->get_spaces(); ?></span></td>
					<td class="em-cart-table-price"><span><?php echo $EM_Booking->get_price_base(true); ?></span></td>
				</tr>
				<!-- BEGIN Subtotal Tickets for Event -->
				<?php $attendee_datas = EM_Attendees_Form::get_booking_attendees($EM_Booking); ?>
				<?php foreach( $EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking ): ?>
				<tr class="em-cart-table-event-details em-cart-table-event-details-<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-cart-row-<?php echo $EM_Booking->get_event()->event_id; ?>-<?php echo $EM_Ticket_Booking->get_ticket()->ticket_id; ?>">
					<td>
						<div class="em-cart-table-ticket"><?php echo $EM_Ticket_Booking->get_ticket()->ticket_name; ?></div>
						<?php //BEGIN Attendee Info (if applicable) ?>
						<?php foreach( EM_Attendees_Form::get_ticket_attendees($EM_Ticket_Booking) as $attendee_title => $attendee_data): ?>
						<div class="em-cart-info">
							<span class="em-cart-info-title"><?php echo $attendee_title; ?></span>
							<div class="em-cart-info-values">
							<?php
							foreach( $attendee_data as $attendee_label => $attendee_value ){
								?>
								<label><?php echo $attendee_label; ?> :</label>
								<span><?php echo $attendee_value; ?></span><br />
								<?php
							}
							?>
							</div>
						</div>
						<?php endforeach; ?>
						<?php //END Attendee Info ?>
					</td>
					<td class="em-cart-table-spaces"><?php echo $EM_Ticket_Booking->get_spaces(); ?></td>
					<td class="em-cart-table-price"><?php echo $EM_Ticket_Booking->get_price(true); ?></td>
				</tr>
				<?php endforeach; ?>
				<!-- END Subtotal Tickets for Event -->
			<?php endforeach; ?>
			<?php do_action('em_cart_form_after_events', $EM_Multiple_Booking); //do not delete ?>
		</tbody>
		<tbody class="em-cart-totals">
			<?php 
				do_action('em_cart_form_before_totals', $EM_Multiple_Booking); //do not delete 
				$price_summary = $EM_Multiple_Booking->get_price_summary_array();
				//we should now have an array of information including base price, taxes and post/pre tax discounts
			?>
			<tr>
				<th colspan="2"><?php _e('Sub Total','em-pro'); ?></th>
				<td><?php echo $EM_Multiple_Booking->get_price_base(true); ?></td>
			</tr>
			<?php if( count($price_summary['discounts_pre_tax']) > 0 ): ?>
				<?php foreach( $price_summary['discounts_pre_tax'] as $discount_summary ): ?>
				<tr>
					<th colspan="2"><?php echo $discount_summary['name']; ?></th>
					<td>- <?php echo $discount_summary['amount']; ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			<?php if( !empty($price_summary['taxes']['amount'])  ): ?>
			<tr>
				<th colspan="2"><?php _e('Taxes','em-pro'); ?> ( <?php echo $price_summary['taxes']['rate']; ?> )</th>
				<td><?php echo $price_summary['taxes']['amount']; ?></td>
			</tr>
			<?php endif; ?>
			<?php if( count($price_summary['discounts_post_tax']) > 0 ): ?>
				<?php foreach( $price_summary['discounts_post_tax'] as $discount_summary ): ?>
				<tr>
					<th colspan="2"><?php echo $discount_summary['name']; ?></th>
					<td>- <?php echo $discount_summary['amount']; ?></td>
				</tr>
				<?php endforeach; ?>
			<?php endif; ?>
			<tr>
				<th colspan="2"><?php _e('Total Price','dbem'); ?></th>
				<td><?php echo $price_summary['total']; ?></td>
			</tr>
			<?php do_action('em_cart_form_after_totals', $EM_Multiple_Booking); //do not delete ?>
		</tbody>
	</table>
</div>
<?php do_action('em_cart_footer', $EM_Multiple_Booking); //do not delete ?>