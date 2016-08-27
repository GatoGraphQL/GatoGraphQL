<?php
global $EM_Event, $post;
?>
<div id='rsvp-data'>
	<?php
		$available_spaces = $EM_Event->get_bookings()->get_available_spaces();
		$booked_spaces = $EM_Event->get_bookings()->get_booked_spaces();
			
		if ( count($EM_Event->get_bookings()->bookings) > 0 ) {
			?>
			<div class='wrap'>
				<p><strong><?php echo __('Available Spaces','events-manager').': '.$EM_Event->get_bookings()->get_available_spaces(); ?></strong></p>
				<p><strong><?php echo __('Confirmed Spaces','events-manager').': '.$EM_Event->get_bookings()->get_booked_spaces(); ?></strong></p>
				<p><strong><?php echo __('Pending Spaces','events-manager').': '.$EM_Event->get_bookings()->get_pending_spaces(); ?></strong></p>
		 	</div>
			<br class='clear'/>
			<?php                                                     
		} else {
			?>
			<p><em><?php _e('No responses yet!', 'events-manager')?></em></p>
			<?php
		} 
	?>
 	    
 	<div id='major-publishing-actions'>  
		<div id='publishing-action'> 
			<a id='printable' href='<?php echo $EM_Event->get_bookings_url(); ?>'><?php _e('manage bookings','events-manager')?></a><br />
			<a target='_blank' href='<?php echo EM_ADMIN_URL ."&amp;page=events-manager-bookings&action=bookings_report&event_id=".$EM_Event->event_id ?>'><?php _e('printable view','events-manager')?></a>
			<?php if( locate_template('plugins/events-manager/templates/csv-event-bookings.php', false) ): ?>
			<br /><a href='<?php echo EM_ADMIN_URL ."&amp;page=events-manager-bookings&action=export_csv&event_id=".$EM_Event->event_id ?>'><?php _e('export csv','events-manager')?></a>
			<?php endif; ?>
			<?php do_action('em_admin_event_booking_options'); ?>
			<br class='clear'/>             
        </div>
		<br class='clear'/>    
	</div>
</div>