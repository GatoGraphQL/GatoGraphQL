<?php
/**
 * Shows a single booking for a single person.
 */
global $EM_Booking, $EM_Notices;
$EM_Multiple_Booking = $EM_Booking;
//check that user can access this page
if( is_object($EM_Multiple_Booking) && !$EM_Multiple_Booking->can_manage() ){
	?>
	<div class="wrap"><h2><?php _e('Unauthorized Access','dbem'); ?></h2><p><?php _e('You do not have the rights to manage this event.','dbem'); ?></p></div>
	<?php
	return false;
}
?>
<div class='wrap' id="em-bookings-admin-booking">
	<div class="icon32" id="icon-bookings"><br></div>
  	<h2>
  		<?php _e('Edit Booking', 'dbem'); ?>
  	</h2>
  	<?php if( !is_admin() ) echo $EM_Notices; ?>
	<div id="poststuff" class="metabox-holder">
	<div id="post-body">
		<div id="post-body-content">		
			<div class="stuffbox">
				<h3>
					<?php _e ( 'Personal Details', 'dbem' ); ?>
				</h3>
				<div class="inside">
					<?php echo $EM_Multiple_Booking->get_person()->display_summary(); ?>
					<?php do_action('em_bookings_admin_booking_person', $EM_Multiple_Booking); ?>
				</div>
			</div> 	
			<div class="stuffbox">
				<h3>
					<?php _e ( 'Booking Details', 'dbem' ); ?>
				</h3>
				<div class="inside">
					<?php
					$EM_Event = $EM_Multiple_Booking->get_event();
					$localised_start_date = date_i18n(get_option('date_format'), $EM_Event->start);
					$localised_end_date = date_i18n(get_option('date_format'), $EM_Event->end);
					$shown_tickets = array();
					?>
					<div>
						<form action="" method="post" class="em-booking-single-status-info">
							<strong><?php _e('Status','dbem'); ?> : </strong>
							<?php echo $EM_Multiple_Booking->get_status(); ?>
							<input type="button" class="em-booking-submit-status-modify" id="em-booking-submit-status-modify" value="<?php _e('Change', 'dbem'); ?>" />
							<input type="submit" class="em-booking-resend-email" id="em-booking-resend-email" value="<?php _e('Resend Email', 'dbem'); ?>" />
						 	<input type='hidden' name='action' value='booking_resend_email'/>
						 	<input type='hidden' name='booking_id' value='<?php echo $EM_Multiple_Booking->booking_id; ?>'/>
						 	<input type='hidden' name='event_id' value='<?php echo $EM_Event->event_id; ?>'/>
						 	<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('booking_resend_email_'.$EM_Multiple_Booking->booking_id); ?>'/>
						</form>
						<form action="" method="post" class="em-booking-single-status-edit">
							<strong><?php _e('Status','dbem'); ?> : </strong>
							<select name="booking_status">
								<?php foreach($EM_Multiple_Booking->status_array as $status => $status_name): ?>
								<option value="<?php echo esc_attr($status); ?>" <?php if($status == $EM_Multiple_Booking->booking_status){ echo 'selected="selected"'; } ?>><?php echo esc_html($status_name); ?></option>
								<?php endforeach; ?>
							</select>
							<input type="checkbox" checked="checked" name="send_email" value="1" />
							<?php _e('Send Email','dbem'); ?>
							<input type="submit" class="em-booking-submit-status" id="em-booking-submit-status" value="<?php _e('Submit Changes', 'dbem'); ?>" />
							<input type="button" class="em-booking-submit-status-cancel" id="em-booking-submit-status-cancel" value="<?php _e('Cancel', 'dbem'); ?>" />
						 	<input type='hidden' name='action' value='booking_set_status'/>
						 	<input type='hidden' name='booking_id' value='<?php echo $EM_Multiple_Booking->booking_id; ?>'/>
						 	<input type='hidden' name='event_id' value='<?php echo $EM_Event->event_id; ?>'/>
						 	<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('booking_set_status_'.$EM_Multiple_Booking->booking_id); ?>'/>
							<p><em><?php _e('<strong>Warning:</strong> Status changes made here will be applied to all the bookings below.','dbem'); ?></em>
							<br /><em><?php _e('<strong>Notes:</strong> Ticket availability not taken into account when approving new bookings (i.e. you can overbook).','dbem'); ?></em>
							</p>
						</form>
					</div>
					<form action="" method="post" class="em-booking-form">
						<?php emp_locate_template('multiple-bookings/admin-cart-table.php', true, array('EM_Multiple_Booking'=>$EM_Multiple_Booking)); ?>
						<table class="em-form-fields" cellspacing="0" cellpadding="0">
							<div class="em-booking-single-edit">
								<p><em><?php _e('You are editing the information supplied no the checkout form. To edit information about a specific booking, including the data entered in that booking form, click the edit link on your bookings above.','em-pro'); ?></em></p>
							</div>
							<?php do_action('em_bookings_single_custom',$EM_Multiple_Booking); //do your own thing, e.g. pro ?>
						</table>
						<p class="em-booking-single-info">
							<input type="button" class="em-booking-submit-modify" id="em-booking-submit-modify" value="<?php _e('Modify Booking Information', 'em-pro'); ?>" />
						</p>
						<p class="em-booking-single-edit">
							<input type="submit" class="em-booking-submit" id="em-booking-submit" value="<?php _e('Submit Changes', 'dbem'); ?>" />
							<input type="button" class="em-booking-submit-cancel" id="em-booking-submit-cancel" value="<?php _e('Cancel', 'dbem'); ?>" />
						 	<input type='hidden' name='action' value='booking_save'/>
						 	<input type='hidden' name='booking_id' value='<?php echo $EM_Multiple_Booking->booking_id; ?>'/>
						 	<input type='hidden' name='event_id' value='<?php echo $EM_Event->event_id; ?>'/>
						 	<input type='hidden' name='_wpnonce' value='<?php echo wp_create_nonce('booking_save_'.$EM_Multiple_Booking->booking_id); ?>'/>
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
					<?php _e ( 'Booking Notes', 'dbem' ); ?>
				</h3>
				<div class="inside">
					<p><?php _e('You can add private notes below for internal reference that only event managers will see.','dbem'); ?></p>
					<?php foreach( $EM_Multiple_Booking->get_notes() as $note ): 
						$user = new EM_Person($note['author']);
					?>
					<div>
						<?php echo date(get_option('date_format'), $note['timestamp']) .' - '. $user->get_name(); ?> <?php _e('wrote','dbem'); ?>: 
						<p style="background:#efefef; padding:5px;"><?php echo nl2br($note['note']); ?></p> 
					</div>
					<?php endforeach; ?>
					<form method="post" action="" style="padding:5px;">
						<textarea class="widefat" rows="5" name="booking_note"></textarea>
						<input type="hidden" name="action" value="bookings_add_note" />
						<input type="submit" value="Add Note" />
					</form>
				</div>
			</div> 
			<?php do_action('em_bookings_single_metabox_footer', $EM_Multiple_Booking); ?> 
		</div>
	</div>
</div>
<br style="clear:both;" />
<?php do_action('em_bookings_single_footer', $EM_Multiple_Booking); ?>
</div>