<?php 
/**
 * Check if there's any admin-related actions to take for bookings. All actions are caught here.
 * @return null
 */
function em_admin_actions_bookings() {
  	global $dbem_form_add_message;   
	global $dbem_form_delete_message; 
	global $wpdb, $EM_Booking, $EM_Event, $EM_Notices;
	
	if( is_object($EM_Booking) && !empty($_REQUEST['action']) && $EM_Booking->can_manage('manage_bookings','manage_others_bookings') ) {
		if( $_REQUEST['action'] == 'bookings_add_note' && wp_verify_nonce($_REQUEST['_wpnonce'],'bookings_add_note') ){
			$EM_Booking->add_note($_REQUEST['booking_note']);
			function em_booking_save_notification(){ global $EM_Booking; ?><div class="updated"><p><strong><?php echo $EM_Booking->feedback_message; ?></strong></p></div><?php }
			add_action ( 'admin_notices', 'em_booking_save_notification' );
		}
	}
	if( is_object($EM_Event) && !empty($_REQUEST['action']) ){
		if( $_REQUEST['action'] == 'bookings_export_csv' && wp_verify_nonce($_REQUEST['_wpnonce'],'bookings_export_csv') ){
			$EM_Event->get_bookings()->export_csv();
			exit();
		}
	}
}
add_action('admin_init','em_admin_actions_bookings',100);

/**
 * Decide what content to show in the bookings section. 
 */
function em_bookings_page(){
	//First any actions take priority
	do_action('em_bookings_admin_page');
	if( !empty($_REQUEST['_wpnonce']) ){ $_REQUEST['_wpnonce'] = $_GET['_wpnonce'] = $_POST['_wpnonce'] = esc_attr($_REQUEST['_wpnonce']); } //XSS fix just in case here too
	if( !empty($_REQUEST['action']) && substr($_REQUEST['action'],0,7) != 'booking' ){ //actions not starting with booking_
		do_action('em_bookings_'.$_REQUEST['action']);
	}elseif( !empty($_REQUEST['booking_id']) ){
		em_bookings_single();
	}elseif( !empty($_REQUEST['person_id']) ){
		em_bookings_person();
	}elseif( !empty($_REQUEST['event_id']) ){
		em_bookings_event();
	}elseif( !empty($_REQUEST['ticket_id']) ){
		em_bookings_ticket();
	}else{
		em_bookings_dashboard();
	}
}

/**
 * Generates the bookings dashboard, showing information on all events 
 */
function em_bookings_dashboard(){
	global $EM_Notices;
	?>
	<div class='wrap em-bookings-dashboard'>
		<?php if( is_admin() ): ?>
		<div id='icon-users' class='icon32'>
			<br/>
		</div>
  		<h2>
  			<?php esc_html_e('Event Bookings Dashboard', 'events-manager'); ?>
  		</h2>
  		<?php else: echo $EM_Notices; ?>
  		<?php endif; ?>
  		<div class="em-bookings-recent">
			<h2><?php esc_html_e('Recent Bookings','events-manager'); ?></h2>	
	  		<?php
			$EM_Bookings_Table = new EM_Bookings_Table();
			$EM_Bookings_Table->status = get_option('dbem_bookings_approval') ? 'needs-attention':'confirmed';
			$EM_Bookings_Table->output();
	  		?>
  		</div>
  		<br class="clear" />
  		<div class="em-bookings-events">
			<h2><?php esc_html_e('Events With Bookings Enabled','events-manager'); ?></h2>		
			<?php em_bookings_events_table(); ?>
			<?php do_action('em_bookings_dashboard'); ?>
		</div>
	</div>
	<?php		
}

/**
 * Shows all booking data for a single event 
 */
function em_bookings_event(){
	global $EM_Event,$EM_Person,$EM_Notices;
	//check that user can access this page
	if( is_object($EM_Event) && !$EM_Event->can_manage('manage_bookings','manage_others_bookings') ){
		?>
		<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this event.','events-manager'); ?></p></div>
		<?php
		return false;
	}
	$localised_start_date = date_i18n('D d M Y', $EM_Event->start);
	$localised_end_date = date_i18n('D d M Y', $EM_Event->end);
	?>
	<div class='wrap'>
		<div id='icon-users' class='icon32'>
			<br/>
		</div>
  		<h2>
  			<?php echo sprintf(__('Manage %s Bookings', 'events-manager'), "'{$EM_Event->event_name}'"); ?>
  			<a href="<?php echo $EM_Event->get_permalink(); ?>" class="button add-new-h2"><?php echo sprintf(__('View %s','events-manager'), __('Event', 'events-manager')) ?></a>
  			<a href="<?php echo $EM_Event->get_edit_url(); ?>" class="button add-new-h2"><?php echo sprintf(__('Edit %s','events-manager'), __('Event', 'events-manager')) ?></a>
  			<?php if( locate_template('plugins/events-manager/templates/csv-event-bookings.php', false) ): //support for legacy template ?>
  			<a href='<?php echo EM_ADMIN_URL ."&amp;page=events-manager-bookings&amp;action=bookings_export_csv&amp;_wpnonce=".wp_create_nonce('bookings_export_csv')."&amp;event_id=".$EM_Event->event_id ?>' class="button add-new-h2"><?php esc_html_e('Export CSV','events-manager')?></a>
  			<?php endif; ?>
  			<?php do_action('em_admin_event_booking_options_buttons'); ?>
  		</h2>
  		<?php if( !is_admin() ) echo $EM_Notices; ?>  
		<div>
			<p><strong><?php esc_html_e('Event Name','events-manager'); ?></strong> : <?php echo esc_html($EM_Event->event_name); ?></p>
			<p>
				<strong><?php esc_html_e('Availability','events-manager'); ?></strong> : 
				<?php echo $EM_Event->get_bookings()->get_booked_spaces() . '/'. $EM_Event->get_spaces() ." ". __('Spaces confirmed','events-manager'); ?>
				<?php if( get_option('dbem_bookings_approval_reserved') ): ?>
				, <?php echo $EM_Event->get_bookings()->get_available_spaces() . '/'. $EM_Event->get_spaces() ." ". __('Available spaces','events-manager'); ?>
				<?php endif; ?>
			</p>
			<p>
				<strong><?php esc_html_e('Date','events-manager'); ?></strong> : 
				<?php echo $localised_start_date; ?>
				<?php echo ($localised_end_date != $localised_start_date) ? " - $localised_end_date":'' ?>
				<?php echo substr ( $EM_Event->event_start_time, 0, 5 ) . " - " . substr ( $EM_Event->event_end_time, 0, 5 ); ?>							
			</p>
			<p>
				<strong><?php esc_html_e('Location','events-manager'); ?></strong> :
				<a class="row-title" href="<?php echo admin_url(); ?>post.php?action=edit&amp;post=<?php echo $EM_Event->get_location()->post_id ?>"><?php echo ($EM_Event->get_location()->location_name); ?></a> 
			</p>
		</div>
		<h2><?php esc_html_e('Bookings','events-manager'); ?></h2>
		<?php
		$EM_Bookings_Table = new EM_Bookings_Table();
		$EM_Bookings_Table->status = 'all';
		$EM_Bookings_Table->output();
  		?>
		<?php do_action('em_bookings_event_footer', $EM_Event); ?>
	</div>
	<?php
}

/**
 * Shows a ticket view
 */
function em_bookings_ticket(){
	global $EM_Ticket,$EM_Notices;
	$EM_Event = $EM_Ticket->get_event();
	//check that user can access this page
	if( is_object($EM_Ticket) && !$EM_Ticket->can_manage() ){
		?>
		<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this ticket.','events-manager'); ?></p></div>
		<?php
		return false;
	}
	?>
	<div class='wrap'>
		<div id='icon-users' class='icon32'>
			<br/>
		</div>
  		<h2>
  			<?php echo sprintf(__('Ticket for %s', 'events-manager'), "'{$EM_Event->name}'"); ?>
  			<a href="<?php echo $EM_Event->get_edit_url(); ?>" class="button add-new-h2"><?php esc_html_e('View/Edit Event','events-manager') ?></a>
  			<a href="<?php echo $EM_Event->get_bookings_url(); ?>" class="button add-new-h2"><?php esc_html_e('View Event Bookings','events-manager') ?></a>
  		</h2> 
  		<?php if( !is_admin() ) echo $EM_Notices; ?>
		<div>
			<table>
				<tr><td><?php echo __('Name','events-manager'); ?></td><td></td><td><?php echo $EM_Ticket->ticket_name; ?></td></tr>
				<tr><td><?php echo __('Description','events-manager'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td></td><td><?php echo ($EM_Ticket->ticket_description) ? $EM_Ticket->ticket_description : '-'; ?></td></tr>
				<tr><td><?php echo __('Price','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_price) ? $EM_Ticket->ticket_price : '-'; ?></td></tr>
				<tr><td><?php echo __('Spaces','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_spaces) ? $EM_Ticket->ticket_spaces : '-'; ?></td></tr>
				<tr><td><?php echo __('Min','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_min) ? $EM_Ticket->ticket_min : '-'; ?></td></tr>
				<tr><td><?php echo __('Max','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_max) ? $EM_Ticket->ticket_max : '-'; ?></td></tr>
				<tr><td><?php echo __('Start','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_start) ? $EM_Ticket->ticket_start : '-'; ?></td></tr>
				<tr><td><?php echo __('End','events-manager'); ?></td><td></td><td><?php echo ($EM_Ticket->ticket_end) ? $EM_Ticket->ticket_end : '-'; ?></td></tr>
				<?php do_action('em_booking_admin_ticket_row', $EM_Ticket); ?>
			</table>
		</div>
		<div class="icon32" id="icon-bookings"><br></div>
		<h2><?php esc_html_e('Bookings','events-manager'); ?></h2>
		<?php
		$EM_Bookings_Table = new EM_Bookings_Table();
		$EM_Bookings_Table->status = get_option('dbem_bookings_approval') ? 'needs-attention':'confirmed';
		$EM_Bookings_Table->output();
  		?>
		<?php do_action('em_bookings_ticket_footer', $EM_Ticket); ?>
	</div>
	<?php	
}

/**
 * Shows a single booking for a single person. 
 */
function em_bookings_single(){
	global $EM_Booking, $EM_Notices; /* @var $EM_Booking EM_Booking */
	//check that user can access this page
	if( is_object($EM_Booking) && !$EM_Booking->can_manage() ){
		?>
		<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this event.','events-manager'); ?></p></div>
		<?php
		return false;
	}
	?>
	<div class='wrap' id="em-bookings-admin-booking">
		<div class="icon32" id="icon-bookings"><br></div>
  		<h2>
  			<?php esc_html_e('Edit Booking', 'events-manager'); ?>
  		</h2>
  		<div id="poststuff" class="metabox-holder">
	  		<div id="post-body">
				<div id="post-body-content">
					<div class="stuffbox">
						<h3>
							<?php esc_html_e( 'Event Details', 'events-manager'); ?>
						</h3>
						<div class="inside">
							<?php
							$EM_Event = $EM_Booking->get_event();
							$localised_start_date = date_i18n(get_option('dbem_date_format'), $EM_Event->start);
							$localised_end_date = date_i18n(get_option('dbem_date_format'), $EM_Event->end);
							?>
							<table>
								<tr><td><strong><?php esc_html_e('Name','events-manager'); ?></strong></td><td><a class="row-title" href="<?php echo $EM_Event->get_bookings_url(); ?>"><?php echo ($EM_Event->event_name); ?></a></td></tr>
								<tr>
									<td><strong><?php esc_html_e('Date/Time','events-manager'); ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</strong></td>
									<td>
										<?php echo $localised_start_date; ?>
										<?php echo ($localised_end_date != $localised_start_date) ? " - $localised_end_date":'' ?>
										<?php echo substr ( $EM_Event->event_start_time, 0, 5 ) . " - " . substr ( $EM_Event->event_end_time, 0, 5 ); ?>
									</td>
								</tr>
							</table>
							<?php do_action('em_bookings_admin_booking_event', $EM_Event); ?>
						</div>
					</div> 		
					<div class="stuffbox">
						<h3>
							<?php esc_html_e( 'Personal Details', 'events-manager'); ?>
						</h3>
						<div class="inside">
							<?php $no_user = get_option('dbem_bookings_registration_disable') && $EM_Booking->get_person()->ID == get_option('dbem_bookings_registration_user'); ?>
							<div class="em-booking-person-details">
								<?php echo $EM_Booking->get_person()->display_summary(); ?>
								<?php if( $no_user ): ?>
								<input type="button" id="em-booking-person-modify" value="<?php esc_attr_e('Edit Details','events-manager'); ?>" />
								<?php endif; ?>
							</div>
							<?php if( $no_user ): ?>
							<form action="" method="post" class="em-booking-person-form">
								<div class="em-booking-person-editor" style="display:none;">
									<?php echo $EM_Booking->get_person_editor(); ?>
								 	<input type='hidden' name='action' value='booking_modify_person'/>
								 	<input type='hidden' name='booking_id' value='<?php echo $EM_Booking->booking_id; ?>'/>
								 	<input type='hidden' name='event_id' value='<?php echo $EM_Event->event_id; ?>'/>
								 	<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('booking_modify_person_'.$EM_Booking->booking_id); ?>'/>
									<input type="submit" class="em-booking-person-modify-submit" id="em-booking-person-modify-submit" value="<?php esc_attr_e('Submit Changes', 'events-manager'); ?>" />
									<input type="button" id="em-booking-person-modify-cancel" value="<?php esc_attr_e('Cancel','events-manager'); ?>" />
								</div>
							</form>	
							<script type="text/javascript">
								jQuery(document).ready( function($){
									$('#em-booking-person-modify').click(function(){
										$('.em-booking-person-details').hide();
										$('.em-booking-person-editor').show();
									});
									$('#em-booking-person-modify-cancel').click(function(){
										$('.em-booking-person-details').show();
										$('.em-booking-person-editor').hide();
									});
								});
							</script>				
							<?php endif; ?>
							<?php do_action('em_bookings_admin_booking_person', $EM_Booking); ?>
						</div>
					</div> 	
					<div class="stuffbox">
						<h3>
							<?php esc_html_e( 'Booking Details', 'events-manager'); ?>
						</h3>
						<div class="inside">
							<?php
							$EM_Event = $EM_Booking->get_event();
							$localised_start_date = date_i18n(get_option('date_format'), $EM_Event->start);
							$localised_end_date = date_i18n(get_option('date_format'), $EM_Event->end);
							$shown_tickets = array();
							?>
							<div>
								<form action="" method="post" class="em-booking-single-status-info">
									<strong><?php esc_html_e('Status','events-manager'); ?> : </strong>
									<?php echo $EM_Booking->get_status(); ?>
									<input type="button" class="em-booking-submit-status-modify" id="em-booking-submit-status-modify" value="<?php esc_attr_e('Change', 'events-manager'); ?>" />
									<input type="submit" class="em-booking-resend-email" id="em-booking-resend-email" value="<?php esc_attr_e('Resend Email', 'events-manager'); ?>" />
								 	<input type='hidden' name='action' value='booking_resend_email'/>
								 	<input type='hidden' name='booking_id' value='<?php echo $EM_Booking->booking_id; ?>'/>
								 	<input type='hidden' name='event_id' value='<?php echo $EM_Event->event_id; ?>'/>
								 	<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('booking_resend_email_'.$EM_Booking->booking_id); ?>'/>
								</form>
								<form action="" method="post" class="em-booking-single-status-edit">
									<strong><?php esc_html_e('Status','events-manager'); ?> : </strong>
									<select name="booking_status">
										<?php foreach($EM_Booking->status_array as $status => $status_name): ?>
										<option value="<?php echo esc_attr($status); ?>" <?php if($status == $EM_Booking->booking_status){ echo 'selected="selected"'; } ?>><?php echo esc_html($status_name); ?></option>
										<?php endforeach; ?>
									</select>
									<input type="checkbox" checked="checked" name="send_email" value="1" />
									<?php esc_html_e('Send Email','events-manager'); ?>
									<input type="submit" class="em-booking-submit-status" id="em-booking-submit-status" value="<?php esc_attr_e('Submit Changes', 'events-manager'); ?>" />
									<input type="button" class="em-booking-submit-status-cancel" id="em-booking-submit-status-cancel" value="<?php esc_attr_e('Cancel', 'events-manager'); ?>" />
								 	<input type='hidden' name='action' value='booking_set_status'/>
								 	<input type='hidden' name='booking_id' value='<?php echo $EM_Booking->booking_id; ?>'/>
								 	<input type='hidden' name='event_id' value='<?php echo $EM_Event->event_id; ?>'/>
								 	<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('booking_set_status_'.$EM_Booking->booking_id); ?>'/>
									<br /><em><?php echo wp_kses_data(__('<strong>Notes:</strong> Ticket availability not taken into account when approving new bookings (i.e. you can overbook).','events-manager')); ?></em>
								</form>
							</div>
							<form action="" method="post" class="em-booking-form">
								<table class="em-tickets-bookings-table" cellpadding="0" cellspacing="0">
									<thead>
									<tr>
										<th><?php esc_html_e('Ticket Type','events-manager'); ?></th>
										<th><?php esc_html_e('Spaces','events-manager'); ?></th>			
										<th><?php esc_html_e('Price','events-manager'); ?></th>
									</tr>
									</thead>
									<tbody>
										<?php foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking): /* @var $EM_Ticket_Booking EM_Ticket_Booking */ ?>
										<tr>
											<td class="ticket-type"><a class="row-title" href="<?php echo em_add_get_params($EM_Event->get_bookings_url(), array('ticket_id'=>$EM_Ticket_Booking->ticket_id)); ?>"><?php echo $EM_Ticket_Booking->get_ticket()->ticket_name ?></a></td>
											<td>
												<span class="em-booking-single-info"><?php echo $EM_Ticket_Booking->get_spaces(); ?></span>
												<div class="em-booking-single-edit"><input name="em_tickets[<?php echo $EM_Ticket_Booking->ticket_id; ?>][spaces]" class="em-ticket-select" id="em-ticket-spaces-<?php echo $EM_Ticket_Booking->ticket_id; ?>" value="<?php echo $EM_Ticket_Booking->get_spaces(); ?>" /></div>
											</td>
											<td><?php echo $EM_Ticket_Booking->get_price(true,true); ?></td>
										</tr>
										<?php 
											$shown_tickets[] = $EM_Ticket_Booking->ticket_id;
											do_action('em_bookings_admin_ticket_row', $EM_Ticket_Booking->get_ticket(), $EM_Booking); 
										?>
										<?php endforeach; ?>
										<?php if( count($shown_tickets) < count($EM_Event->get_bookings()->get_tickets()->tickets)): ?><tr>
											<?php foreach($EM_Event->get_bookings()->get_tickets()->tickets as $EM_Ticket): /* @var $EM_Ticket EM_Ticket */ ?>
												<?php if( !in_array($EM_Ticket->ticket_id, $shown_tickets) ): ?>
												<tr>
													<td class="ticket-type"><a class="row-title" href="<?php echo em_add_get_params($EM_Event->get_bookings_url(), array('ticket_id'=>$EM_Ticket->ticket_id)); ?>"><?php echo $EM_Ticket->ticket_name ?></a></td>
													<td>
														<span class="em-booking-single-info">0</span>
														<div class="em-booking-single-edit"><input name="em_tickets[<?php echo $EM_Ticket->ticket_id; ?>][spaces]" class="em-ticket-select" id="em-ticket-spaces-<?php echo $EM_Ticket_Booking->ticket_id; ?>" value="0" /></div>
													</td>
													<td><?php echo em_get_currency_symbol() ?>0.00</td>
												</tr>
												<?php do_action('em_bookings_admin_ticket_row', $EM_Ticket, $EM_Booking); ?>
												<?php endif; ?>
											<?php endforeach; ?>
										<?php endif; ?>
									</tbody>
									<tfoot>
										<?php 
											do_action('em_bookings_admin_ticket_totals_header');
											$price_summary = $EM_Booking->get_price_summary_array();
											//we should now have an array of information including base price, taxes and post/pre tax discounts
										?>
										<tr>
											<th><?php esc_html_e('Price','events-manager'); ?></th>
											<th><?php echo sprintf(__('%d Spaces','events-manager'), $EM_Booking->get_spaces()); ?></th>
											<th><?php echo $EM_Booking->get_price_base(true); ?></th>
										</tr>
										<?php if( count($price_summary['discounts_pre_tax']) > 0 ): ?>
											<?php foreach( $price_summary['discounts_pre_tax'] as $discount_summary ): ?>
											<tr>
												<th><?php echo $discount_summary['name']; ?></th>
												<th><?php echo $discount_summary['discount']; ?></th>
												<th>- <?php echo $discount_summary['amount']; ?></th>
											</tr>
											<?php endforeach; ?>
										<?php endif; ?>
										<?php if( !empty($price_summary['taxes']['amount'])  ): ?>
										<tr>
											<th><?php esc_html_e('Tax','events-manager'); ?></th>
											<th>
												<span class="em-booking-single-info"><?php echo $price_summary['taxes']['rate'] ?></span>
												<div class="em-booking-single-edit"><input name="booking_tax_rate" value="<?php echo esc_attr($EM_Booking->get_tax_rate()); ?>">%</div>
											</th>
											<th><?php echo $price_summary['taxes']['amount']; ?></th>
										</tr>
										<?php endif; ?>
										<?php if( count($price_summary['discounts_post_tax']) > 0 ): ?>
											<?php foreach( $price_summary['discounts_post_tax'] as $discount_summary ): ?>
											<tr>
												<th><?php echo $discount_summary['name']; ?></th>
												<th><?php echo $discount_summary['discount']; ?></th>
												<th>- <?php echo $discount_summary['amount']; ?></th>
											</tr>
											<?php endforeach; ?>
										<?php endif; ?>
										<tr>
											<th><?php esc_html_e('Total Price','events-manager'); ?></th>
											<th>&nbsp;</th>
											<th><?php echo $price_summary['total']; ?></th>
										</tr>
										<?php do_action('em_bookings_admin_ticket_totals_footer'); ?>
									</tfoot>
								</table>
								<table class="em-form-fields" cellspacing="0" cellpadding="0">
									<?php if( !has_action('em_bookings_single_custom') ): //default behavior ?>
									<tr>
										<th><?php esc_html_e('Comment','events-manager'); ?></th>
										<td>
											<span class="em-booking-single-info"><?php echo esc_html($EM_Booking->booking_comment); ?></span>
											<div class="em-booking-single-edit"><textarea name="booking_comment"><?php echo esc_html($EM_Booking->booking_comment); ?></textarea></div>
										</td>
									</tr>
									<?php else: do_action('em_bookings_single_custom',$EM_Booking); //do your own thing, e.g. pro ?>
									<?php endif; ?>
								</table>
								<p class="em-booking-single-info">
									<input type="button" class="em-booking-submit-modify" id="em-booking-submit-modify" value="<?php esc_attr_e('Modify Booking', 'events-manager'); ?>" />
								</p>
								<p class="em-booking-single-edit">
									<em><?php _e('<strong>Notes:</strong> Ticket availability not taken into account (i.e. you can overbook). Emails are not resent automatically.','events-manager'); ?></em>
									<br /><br />
									<input type="submit" class="em-booking-submit" id="em-booking-submit" value="<?php esc_attr_e('Submit Changes', 'events-manager'); ?>" />
									<input type="button" class="em-booking-submit-cancel" id="em-booking-submit-cancel" value="<?php esc_attr_e('Cancel', 'events-manager'); ?>" />
								 	<input type='hidden' name='action' value='booking_save'/>
								 	<input type='hidden' name='booking_id' value='<?php echo $EM_Booking->booking_id; ?>'/>
								 	<input type='hidden' name='event_id' value='<?php echo $EM_Event->event_id; ?>'/>
								 	<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('booking_save_'.$EM_Booking->booking_id); ?>'/>
								</p>
							</form>
							<script type="text/javascript">
								jQuery(document).ready( function($){
									$('#em-booking-submit-modify').click(function(){
										$('.em-booking-single-info').hide();
										$('.em-booking-single-edit').show();
									});
									$('#em-booking-submit-cancel').click(function(){
										$('.em-booking-single-info').show();
										$('.em-booking-single-edit').hide();
									});	
									$('.em-booking-single-info').show();
									$('.em-booking-single-edit').hide();

									$('#em-booking-submit-status-modify').click(function(){
										$('.em-booking-single-status-info').hide();
										$('.em-booking-single-status-edit').show();
									});
									$('#em-booking-submit-status-cancel').click(function(){
										$('.em-booking-single-status-info').show();
										$('.em-booking-single-status-edit').hide();
									});	
									$('.em-booking-single-status-info').show();
									$('.em-booking-single-status-edit').hide();
								});
							</script>
						</div>
					</div>
					<div id="em-booking-notes" class="stuffbox">
						<h3>
							<?php esc_html_e( 'Booking Notes', 'events-manager'); ?>
						</h3>
						<div class="inside">
							<p><?php esc_html_e('You can add private notes below for internal reference that only event managers will see.','events-manager'); ?></p>
							<?php foreach( $EM_Booking->get_notes() as $note ): 
								$user = new EM_Person($note['author']);
							?>
							<div>
								<?php echo sprintf(esc_html_x('%1$s - %2$s wrote','[Date] - [Name] wrote','events-manager'), date(get_option('date_format'), $note['timestamp']), $user->get_name()); ?>: 
								<p style="background:#efefef; padding:5px;"><?php echo nl2br($note['note']); ?></p> 
							</div>
							<?php endforeach; ?>
							<form method="post" action="" style="padding:5px;">
								<textarea class="widefat" rows="5" name="booking_note"></textarea>
								<input type="hidden" name="action" value="bookings_add_note" />
								<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('bookings_add_note'); ?>" />
								<input type="submit" value="Add Note" />
							</form>
						</div>
					</div> 
					<?php do_action('em_bookings_single_metabox_footer', $EM_Booking); ?> 
				</div>
			</div>
		</div>
		<br style="clear:both;" />
		<?php do_action('em_bookings_single_footer', $EM_Booking); ?>
	</div>
	<?php
	
}

/**
 * Shows all bookings made by one person.
 */
function em_bookings_person(){	
	global $EM_Person, $EM_Notices;
	$EM_Person->get_bookings();
	$has_booking = false;
	foreach($EM_Person->get_bookings() as $EM_Booking){
		if($EM_Booking->can_manage('manage_bookings','manage_others_bookings')){
			$has_booking = true;
		}
	}
	if( !$has_booking && !current_user_can('manage_others_bookings') ){
		?>
		<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this event.','events-manager'); ?></p></div>
		<?php
		return false;
	}
	?>
	<div class='wrap'>
		<div id='icon-users' class='icon32'>
			<br/>
		</div>
  		<h2>
  			<?php esc_html_e('Manage Person\'s Booking', 'events-manager'); ?>
  			<?php if( current_user_can('edit_users') ) : ?>
  			<a href="<?php echo admin_url('user-edit.php?user_id='.$EM_Person->ID); ?>" class="button add-new-h2"><?php esc_html_e('Edit User','events-manager') ?></a>
  			<?php endif; ?>
  			<?php if( current_user_can('delete_users') ) : ?>
  			<a href="<?php echo wp_nonce_url( admin_url("users.php?action=delete&amp;user=$EM_Person->ID"), 'bulk-users' ); ?>" class="button add-new-h2"><?php esc_html_e('Delete User','events-manager') ?></a>
  			<?php endif; ?>
  		</h2>
  		<?php if( !is_admin() ) echo $EM_Notices; ?>
		<?php do_action('em_bookings_person_header'); ?>
  		<div id="poststuff" class="metabox-holder has-right-sidebar">
	  		<div id="post-body">
				<div id="post-body-content">
					<div id="event_name" class="stuffbox">
						<h3>
							<?php esc_html_e( 'Personal Details', 'events-manager'); ?>
						</h3>
						<div class="inside">
							<?php echo $EM_Person->display_summary(); ?>
						</div>
					</div> 
				</div>
			</div>
		</div>
		<br style="clear:both;" />
		<?php do_action('em_bookings_person_body_1'); ?>
		<div class="icon32" id="icon-bookings"><br></div>
		<h2><?php esc_html_e('Past And Present Bookings','events-manager'); ?></h2>
		<?php
		$EM_Bookings_Table = new EM_Bookings_Table();
		$EM_Bookings_Table->status = 'all';
		$EM_Bookings_Table->scope = 'all';
		$EM_Bookings_Table->output();
  		?>
		<?php do_action('em_bookings_person_footer', $EM_Person); ?>
	</div>
	<?php
}

function em_printable_booking_report() {
	global $EM_Event;
	//check that user can access this page
	if( isset($_GET['page']) && $_GET['page']=='events-manager-bookings' && isset($_GET['action']) && $_GET['action'] == 'bookings_report' && is_object($EM_Event)){
		if( is_object($EM_Event) && !$EM_Event->can_manage('edit_events','edit_others_events') ){
			?>
			<div class="wrap"><h2><?php esc_html_e('Unauthorized Access','events-manager'); ?></h2><p><?php esc_html_e('You do not have the rights to manage this event.','events-manager'); ?></p></div>
			<?php
			return false;
		}
		em_locate_template('templates/bookings-event-printable.php', true);
		die();
	}
} 
add_action('admin_init', 'em_printable_booking_report');
?>