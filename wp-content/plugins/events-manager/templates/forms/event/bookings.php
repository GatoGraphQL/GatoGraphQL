<?php
global $EM_Event, $post, $allowedposttags, $EM_Ticket, $col_count;
?>
<div id="event-rsvp-box">
	<input id="event-rsvp" name='event_rsvp' value='1' type='checkbox' <?php echo ($EM_Event->event_rsvp) ? 'checked="checked"' : ''; ?> />
	&nbsp;&nbsp;
	<?php _e ( 'Enable registration for this event', 'events-manager')?>
</div>
<div id="event-rsvp-options" style="<?php echo ($EM_Event->event_rsvp) ? '':'display:none;' ?>">
	<?php do_action('em_events_admin_bookings_header', $EM_Event); ?>
	<div id="em-tickets-form">
	<?php
	//get tickets here and if there are none, create a blank ticket
	$EM_Tickets = $EM_Event->get_tickets();
	if( count($EM_Tickets->tickets) == 0 ){
		$EM_Tickets->tickets[] = new EM_Ticket();
		$delete_temp_ticket = true;
	}
	if( get_option('dbem_bookings_tickets_single') && count($EM_Tickets->tickets) == 1 ){
		?>
		<h4><?php esc_html_e('Ticket Options','events-manager'); ?></h4>
		<?php
		$col_count = 1;	
		$EM_Ticket = $EM_Tickets->get_first();				
		include( em_locate_template('forms/ticket-form.php') ); //in future we'll be accessing forms/event/bookings-ticket-form.php directly
	}else{
		?>
		<h4><?php esc_html_e('Tickets','events-manager'); ?></h4>
		<p><em><?php esc_html_e('You can have single or multiple tickets, where certain tickets become available under certain conditions, e.g. early bookings, group discounts, maximum bookings per ticket, etc.', 'events-manager'); ?> <?php esc_html_e('Basic HTML is allowed in ticket labels and descriptions.','events-manager'); ?></em></p>					
		<table class="form-table">
			<thead>
				<tr valign="top">
					<th colspan="2"><?php esc_html_e('Ticket Name','events-manager'); ?></th>
					<th><?php esc_html_e('Price','events-manager'); ?></th>
					<th><?php esc_html_e('Min/Max','events-manager'); ?></th>
					<th><?php esc_html_e('Start/End','events-manager'); ?></th>
					<th><?php esc_html_e('Avail. Spaces','events-manager'); ?></th>
					<th><?php esc_html_e('Booked Spaces','events-manager'); ?></th>
					<th>&nbsp;</th>
				</tr>
			</thead>    
			<tfoot>
				<tr valign="top">
					<td colspan="8">
						<a href="#" id="em-tickets-add"><?php esc_html_e('Add new ticket','events-manager'); ?></a>
					</td>
				</tr>
			</tfoot>
			<?php
				$EM_Ticket = new EM_Ticket();
				$EM_Ticket->event_id = $EM_Event->event_id;
				array_unshift($EM_Tickets->tickets, $EM_Ticket); //prepend template ticket for JS
				$col_count = 0;
				foreach( $EM_Tickets->tickets as $EM_Ticket){
					/* @var $EM_Ticket EM_Ticket */
					?>
					<tbody id="em-ticket-<?php echo $col_count ?>" <?php if( $col_count == 0 ) echo 'style="display:none;"' ?>>
						<tr class="em-tickets-row">
							<td class="ticket-status"><span class="<?php if($EM_Ticket->ticket_id && $EM_Ticket->is_available()){ echo 'ticket_on'; }elseif($EM_Ticket->ticket_id > 0){ echo 'ticket_off'; }else{ echo 'ticket_new'; } ?>"></span></td>													
							<td class="ticket-name">
								<span class="ticket_name"><?php if($EM_Ticket->ticket_members) echo '* ';?><?php echo wp_kses_data($EM_Ticket->ticket_name); ?></span>
								<div class="ticket_description"><?php echo wp_kses($EM_Ticket->ticket_description,$allowedposttags); ?></div>
								<div class="ticket-actions">
									<a href="#" class="ticket-actions-edit"><?php esc_html_e('Edit','events-manager'); ?></a> 
									<?php if( count($EM_Ticket->get_bookings()->bookings) == 0 ): ?>
									| <a href="<?php bloginfo('wpurl'); ?>/wp-load.php" class="ticket-actions-delete"><?php esc_html_e('Delete','events-manager'); ?></a>
									<?php else: ?>
									| <a href="<?php echo EM_ADMIN_URL; ?>&amp;page=events-manager-bookings&ticket_id=<?php echo $EM_Ticket->ticket_id ?>"><?php esc_html_e('View Bookings','events-manager'); ?></a>
									<?php endif; ?>
								</div>
							</td>
							<td class="ticket-price">
								<span class="ticket_price"><?php echo ($EM_Ticket->ticket_price) ? esc_html($EM_Ticket->get_price_precise()) : esc_html__('Free','events-manager'); ?></span>
							</td>
							<td class="ticket-limit">
								<span class="ticket_min">
									<?php  echo ( !empty($EM_Ticket->ticket_min) ) ? esc_html($EM_Ticket->ticket_min):'-'; ?>
								</span> / 
								<span class="ticket_max"><?php echo ( !empty($EM_Ticket->ticket_max) ) ? esc_html($EM_Ticket->ticket_max):'-'; ?></span>
							</td>
							<td class="ticket-time">
								<span class="ticket_start ticket-dates-from-normal"><?php echo ( !empty($EM_Ticket->ticket_start) ) ? date(get_option('dbem_date_format'), $EM_Ticket->start_timestamp):''; ?></span>
								<span class="ticket_start_recurring_days ticket-dates-from-recurring"><?php if( !empty($EM_Ticket->ticket_meta['recurrences']) ) echo $EM_Ticket->ticket_meta['recurrences']['start_days']; ?></span>
								<span class="ticket_start_recurring_days_text ticket-dates-from-recurring <?php if( !empty($EM_Ticket->ticket_meta['recurrences']) && !is_numeric($EM_Ticket->ticket_meta['recurrences']['start_days']) ) echo 'hidden'; ?>"><?php _e('day(s)','events-manager'); ?></span>
								<span class="ticket_start_time"><?php echo ( !empty($EM_Ticket->ticket_start) ) ? date( em_get_hour_format(), $EM_Ticket->start_timestamp):''; ?></span>
								<br />
								<span class="ticket_end ticket-dates-from-normal"><?php echo ( !empty($EM_Ticket->ticket_end) ) ? date(get_option('dbem_date_format'), $EM_Ticket->end_timestamp):''; ?></span>
								<span class="ticket_end_recurring_days ticket-dates-from-recurring"><?php if( !empty($EM_Ticket->ticket_meta['recurrences']) ) echo $EM_Ticket->ticket_meta['recurrences']['end_days']; ?></span>
								<span class="ticket_end_recurring_days_text ticket-dates-from-recurring <?php if( !empty($EM_Ticket->ticket_meta['recurrences']) && !is_numeric($EM_Ticket->ticket_meta['recurrences']['end_days']) ) echo 'hidden'; ?>"><?php _e('day(s)','events-manager'); ?></span>
								<span class="ticket_end_time"><?php echo ( !empty($EM_Ticket->ticket_end) ) ? date( em_get_hour_format(), $EM_Ticket->end_timestamp):''; ?></span>
							</td>
							<td class="ticket-qty">
								<span class="ticket_available_spaces"><?php echo $EM_Ticket->get_available_spaces(); ?></span>/
								<span class="ticket_spaces"><?php echo $EM_Ticket->get_spaces() ? $EM_Ticket->get_spaces() : '-'; ?></span>
							</td>
							<td class="ticket-booked-spaces">
								<span class="ticket_booked_spaces"><?php echo $EM_Ticket->get_booked_spaces(); ?></span>
							</td>
							<?php do_action('em_event_edit_ticket_td', $EM_Ticket); ?>
						</tr>
						<tr class="em-tickets-row-form" style="display:none;">
							<td colspan="<?php echo apply_filters('em_event_edit_ticket_td_colspan', 7); ?>">
								<?php include( em_locate_template('forms/event/bookings-ticket-form.php')); ?>
								<div class="em-ticket-form-actions">
								<button type="button" class="ticket-actions-edited"><?php esc_html_e('Close Ticket Editor','events-manager')?></button>
								</div>
							</td>
						</tr>
					</tbody>
					<?php
					$col_count++;
				}
				array_shift($EM_Tickets->tickets);
			?>
		</table>
	<?php 
	}
	?>
	</div>
	<div id="em-booking-options">
	<?php if( !get_option('dbem_bookings_tickets_single') || count($EM_Ticket->get_event()->get_tickets()->tickets) > 1 ): ?>
	<h4><?php esc_html_e('Event Options','events-manager'); ?></h4>
	<p>
		<label><?php esc_html_e('Total Spaces','events-manager'); ?></label>
		<input type="text" name="event_spaces" value="<?php if( $EM_Event->event_spaces > 0 ){ echo $EM_Event->event_spaces; } ?>" /><br />
		<em><?php esc_html_e('Individual tickets with remaining spaces will not be available if total booking spaces reach this limit. Leave blank for no limit.','events-manager'); ?></em>
	</p>
	<p>
		<label><?php esc_html_e('Maximum Spaces Per Booking','events-manager'); ?></label>
		<input type="text" name="event_rsvp_spaces" value="<?php if( $EM_Event->event_rsvp_spaces > 0 ){ echo $EM_Event->event_rsvp_spaces; } ?>" /><br />
		<em><?php esc_html_e('If set, the total number of spaces for a single booking to this event cannot exceed this amount.','events-manager'); ?><?php esc_html_e('Leave blank for no limit.','events-manager'); ?></em>
	</p>
	<p>
		<label><?php esc_html_e('Booking Cut-Off Date','events-manager'); ?></label>
		<span class="em-booking-date-normal">
			<span class="em-date-single">
				<input id="em-bookings-date-loc" class="em-date-input-loc" type="text" />
				<input id="em-bookings-date" class="em-date-input" type="hidden" name="event_rsvp_date" value="<?php echo $EM_Event->event_rsvp_date; ?>" />
			</span>
		</span>
		<span class="em-booking-date-recurring">
			<input type="text" name="recurrence_rsvp_days" size="3" value="<?php echo absint($EM_Event->recurrence_rsvp_days); ?>" />
			<?php _e('day(s)','events-manager'); ?>
			<select name="recurrence_rsvp_days_when">
				<option value="before" <?php if( !empty($EM_Event->recurrence_rsvp_days) && $EM_Event->recurrence_rsvp_days <= 0) echo 'selected="selected"'; ?>><?php echo sprintf(_x('%s the event starts','before or after','events-manager'),__('Before','events-manager')); ?></option>
				<option value="after" <?php if( !empty($EM_Event->recurrence_rsvp_days) && $EM_Event->recurrence_rsvp_days > 0) echo 'selected="selected"'; ?>><?php echo sprintf(_x('%s the event starts','before or after','events-manager'),__('After','events-manager')); ?></option>
			</select>
			<?php _e('at','events-manager'); ?>
		</span>
		<input type="text" name="event_rsvp_time" class="em-time-input" maxlength="8" size="8" value="<?php echo date( em_get_hour_format(), $EM_Event->rsvp_end ); ?>" />
		<br />
		<em><?php esc_html_e('This is the definite date after which bookings will be closed for this event, regardless of individual ticket settings above. Default value will be the event start date.','events-manager'); ?></em>
	</p>
	<?php endif; ?>
	</div>
	<?php
		if( !empty($delete_temp_ticket) ){
			array_pop($EM_Tickets->tickets);
		}
		do_action('em_events_admin_bookings_footer', $EM_Event); 
	?>
</div>