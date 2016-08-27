<?php
/*
* WARNING -This is a recently added template (2013-01-30), and is likly to change as we fine-tune things over the coming weeks/months, if at all possible try to use our hooks or CSS/jQuery to acheive your customizations
* This displays the booking cart showing the persons bookings, attendees and a breakdown of pricing.
* You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager-pro/multiple-bookings/ and modifying it however you need.
* For more information, see http://wp-events-plugin.com/documentation/using-template-files/
*/
if(empty($EM_Multiple_Booking)) $EM_Multiple_Booking = EM_Multiple_Bookings::get_multiple_booking();
//TODO add delete booking from cart
//TODO make bookings editable
?>
<table class="em-checkout-table">
	<tbody class="em-checkout-summary">
		<?php do_action('em_checkout_form_before_events', $EM_Multiple_Booking); //do not delete ?>
		<tr class="em-checkout-title">
			<th class="em-checkout-title-event">Event</th>
			<th class="em-checkout-title-spaces">Spaces</th>
			<th class="em-checkout-title-price">Price</th>
		</tr>
		<?php foreach($EM_Multiple_Booking->get_bookings() as $EM_Booking): /* @var $EM_Booking EM_Booking */ ?>
			<tr class="em-checkout-table-event-summary" id="em-checkout-table-event-summary-<?php echo $EM_Booking->get_event()->event_id; ?>">
				<td>
					<?php ob_start(); ?>
					<span class="em-checkout-table-event-title">#_EVENTLINK</span><br/>
					<?php _e('When','em-pro'); ?> : #_EVENTDATES @ #_EVENTTIMES<br />
					<?php _e('Where','em-pro'); ?> : #_LOCATIONFULLLINE
					<?php echo $EM_Booking->output(ob_get_clean()); ?><br />
					<div class="em-checkout-table-details-actions">
						<!-- <a href="#" class="em-checkout-table-actions-edit" rel="<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-checkout-table-actions-edit-<?php echo $EM_Booking->get_event()->event_id; ?>">
							<?php _e('edit','em-pro'); ?>
						</a>&nbsp; --->
						<a href="#" class="em-checkout-table-actions-remove" rel="<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-checkout-table-actions-remove-<?php echo $EM_Booking->get_event()->event_id; ?>">
							<?php _e('remove','em-pro'); ?>
						</a>
					</div>
					<div class="em-checkout-table-details-triggers">
						<a href="#" class="em-checkout-table-details-show" rel="<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-checkout-table-details-show-<?php echo $EM_Booking->get_event()->event_id; ?>">
							+ <?php _e('details','em-pro'); ?>
						</a>
						<a href="#" class="em-checkout-table-details-hide" rel="<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-checkout-table-details-hide-<?php echo $EM_Booking->get_event()->event_id; ?>">
							- <?php _e('details','em-pro'); ?>
						</a>
					</div>
				</td>
				<td class="em-checkout-table-spaces"><span><?php echo $EM_Booking->get_spaces(); ?></span></td>
				<td class="em-checkout-table-price"><span><?php echo $EM_Booking->get_price(false, true); ?></span></td>
			</tr>
			<!-- BEGIN Subtotal Tickets for Event -->
			<?php $attendee_datas = EM_Attendees_Form::get_booking_attendees($EM_Booking); ?>
			<?php foreach( $EM_Booking->get_tickets_bookings() as $EM_Ticket_Booking ): ?>
			<tr class="em-checkout-table-event-details em-checkout-table-event-details-<?php echo $EM_Booking->get_event()->event_id; ?>" id="em-checkout-row-<?php echo $EM_Booking->get_event()->event_id; ?>-<?php echo $EM_Ticket_Booking->get_ticket()->ticket_id; ?>">
				<td>
					<div class="em-checkout-table-ticket"><?php echo $EM_Ticket_Booking->get_ticket()->ticket_name; ?></div>
					<?php //BEGIN Attendee Info (if applicable) ?>
					<?php foreach( EM_Attendees_Form::get_ticket_attendees($EM_Ticket_Booking) as $attendee_title => $attendee_data): ?>
					<div class="em-checkout-attendee-info">
						<span class="em-checkout-attendee-info-title"><?php echo $attendee_title; ?></span>
						<div class="em-checkout-attendee-info-values">
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
				<td class="em-checkout-table-spaces"><?php echo $EM_Ticket_Booking->get_spaces(); ?></td>
				<td class="em-checkout-table-price"><?php echo $EM_Ticket_Booking->get_price(false, true); ?></td>
			</tr>
			<?php endforeach; ?>
			<!-- END Subtotal Tickets for Event -->
		<?php endforeach; ?>
		<?php do_action('em_checkout_form_after_events', $EM_Multiple_Booking); //do not delete ?>
	</tbody>
	<tbody class="em-checkout-totals">
		<?php do_action('em_checkout_form_before_totals', $EM_Multiple_Booking); //do not delete ?>
		<?php 
			$cols = 2;
			$has_taxes = $EM_Multiple_Booking->has_taxes();
			$has_discounts = !empty($EM_Multiple_Booking->booking_meta['discounts']) || !empty($EM_Multiple_Booking->booking_meta['coupon']); 
		?>
		<?php if( $has_discounts || $has_taxes ): ?>
		<tr class="em-checkout-totals-sub">
			<th colspan="<?php echo $cols; ?>"><?php _e('Sub Total','em-pro'); ?></th>
			<td><?php echo $EM_Multiple_Booking->get_price(false,true,false); ?></td>
		</tr>
		<?php endif; ?>
		<?php if( $has_taxes ): ?>
		<tr class="em-checkout-totals-tax">
			<th colspan="<?php echo $cols; ?>"><?php _e('Taxes','em-pro'); ?> ( <?php echo $EM_Multiple_Booking->get_tax_rate(); ?>% )</th>
			<td><?php echo em_get_currency_formatted($EM_Multiple_Booking->get_price(false,false,false) * (get_option('dbem_bookings_tax')/100)); ?></td>
		</tr>
		<?php endif; ?>
		<?php if( $has_discounts ): ?>
		<tr class="em-checkout-totals-discount">
			<th colspan="<?php echo $cols; ?>"><?php _e('Discounts','em-pro'); ?></th>
			<td></td>
		</tr>
		<?php endif; ?>
		<tr class="em-checkout-totals-total">
			<th colspan="<?php echo $cols; ?>"><?php _e('Total','em-pro'); ?></th>
			<td><?php echo $EM_Multiple_Booking->get_price(false, true, true); ?></td>
		</tr>
		<?php do_action('em_checkout_form_after_totals', $EM_Multiple_Booking); //do not delete ?>
	</tbody>
</table>