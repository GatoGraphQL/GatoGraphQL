<?php
/* WARNING! This file may change in the near future as we intend to add features to BuddyPress - 2012-02-14 */
	global $bp, $EM_Notices;
	$url = $bp->events->link . 'my-events/'; //url to this page
	$order = ( !empty($_REQUEST ['order']) ) ? $_REQUEST ['order']:'ASC';
	$limit = ( !empty($_REQUEST['limit']) ) ? $_REQUEST['limit'] : 20;//Default limit
	$page = ( !empty($_REQUEST['pno']) ) ? $_REQUEST['pno']:1;
	$offset = ( $page > 1 ) ? ($page-1)*$limit : 0;
	$EM_Events = EM_Events::get( array('group'=>'this','scope'=>'future', 'limit' => 0, 'order' => $order) );
	$events_count = count ( $EM_Events );
	$future_count = EM_Events::count( array('status'=>1, 'owner' =>get_current_user_id(), 'scope' => 'future'));
	$pending_count = EM_Events::count( array('status'=>0, 'owner' =>get_current_user_id(), 'scope' => 'all') );
	$use_events_end = get_option('dbem_use_event_end');
	echo $EM_Notices;
	?>
	<div class="tablenav">
		<?php
		if ( $events_count >= $limit ) {
			$events_nav = em_admin_paginate( $events_count, $limit, $page);
			echo $events_nav;
		}
		?>
		<br class="clear" />
	</div>
		
	<?php
	if (empty ( $EM_Events )) {
		// TODO localize
		echo "<p>". __( 'No Events','events-manager') ."</p>";
	} else {
	    foreach( $EM_Events as $EM_Event ) break;
	    $can_edit_events = $EM_Event->can_manage('edit_events','edit_others_events');
	?>
	<table class="widefat events-table">
		<thead>
			<tr>
				<?php /* 
				<th class='manage-column column-cb check-column' scope='col'>
					<input class='select-all' type="checkbox" value='1' />
				</th>
				*/ ?>
				<th><?php _e ( 'Name', 'events-manager'); ?></th>
				<th><?php _e ( 'Location', 'events-manager'); ?></th>
				<th><?php _e ( 'Date and time', 'events-manager'); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php 
			$rowno = 0;
			$event_count = 0;
			foreach ( $EM_Events as $EM_Event ) {
				/* @var $event EM_Event */
			    $can_edit_events = $EM_Event->can_manage('edit_events','edit_others_events');
				if( ($rowno < $limit || empty($limit)) && ($event_count >= $offset || $offset === 0) ) {
					$rowno++;
					$class = ($rowno % 2) ? 'alternate' : '';
					// FIXME set to american
					$localised_start_date = date_i18n(get_option('dbem_date_format'), $EM_Event->start);
					$localised_end_date = date_i18n(get_option('dbem_date_format'), $EM_Event->end);
					$style = "";
					$today = date ( "Y-m-d" );
					$location_summary = "<b>" . $EM_Event->get_location()->name . "</b><br/>" . $EM_Event->get_location()->address . " - " . $EM_Event->get_location()->town;
					
					if ($EM_Event->start_date < $today && $EM_Event->end_date < $today){
						$class .= " past";
					}
					//Check pending approval events
					if ( !$EM_Event->status ){
						$class .= " pending";
					}					
					?>
					<tr class="event <?php echo trim($class); ?>" <?php echo $style; ?> id="event_<?php echo $EM_Event->event_id ?>">
						<?php /*
						<td>
							<input type='checkbox' class='row-selector' value='<?php echo $event->event_id; ?>' name='events[]' />
						</td>
						*/ ?>
						<td>
							<strong>
								<?php 
								if( $can_edit_events ){ 
									echo $EM_Event->output('<a href="#_EDITEVENTURL">#_NAME</a>');
								}else{
									echo $EM_Event->output('#_EVENTLINK');
								}
								?>
							</strong>
							<?php 
							if( $EM_Event->can_manage('manage_bookings','manage_others_bookings') && get_option('dbem_rsvp_enabled') == 1 && $EM_Event->rsvp == 1 ){
								?>
								<br/>
								<a href="<?php echo $url ?>bookings/?event_id=<?php echo $EM_Event->event_id ?>"><?php echo __("Bookings",'events-manager'); ?></a> &ndash;
								<?php _e("Booked",'events-manager'); ?>: <?php echo $EM_Event->get_bookings()->get_booked_spaces()."/".$EM_Event->get_spaces(); ?>
								<?php if( get_option('dbem_bookings_approval') == 1 ): ?>
									| <?php _e("Pending",'events-manager') ?>: <?php echo $EM_Event->get_bookings()->get_pending_spaces(); ?>
								<?php endif;
							}
							?>
							<div class="row-actions">
								<?php if( $EM_Event->can_manage('delete_events', 'delete_others_events')) : $can_delete_events = true; ?>
								<span class="trash"><a href="<?php echo $url ?>?action=event_delete&amp;event_id=<?php echo $EM_Event->event_id . '&amp;_wpnonce=' . wp_create_nonce('event_delete_'.$EM_Event->event_id); ?>" class="em-event-delete"><?php _e('Delete','events-manager'); ?></a></span>
								<?php endif; ?>
								<?php if( $can_edit_events ): ?>
								    <?php if( $can_delete_events ) echo " | "; ?>
        							<a href="<?php echo $url ?>edit/?action=event_duplicate&amp;event_id=<?php echo $EM_Event->event_id . '&amp;_wpnonce=' . wp_create_nonce('event_duplicate_'.$EM_Event->event_id); ?>" title="<?php echo esc_attr ( sprintf(__('Duplicate %s','events-manager'), __('Event','events-manager')) ); ?>">
        								<?php esc_html_e('Duplicate','events-manager'); ?>
        							</a>
    							<?php endif; ?>
							</div>
						</td>
						
						<td>
							<?php echo $location_summary; ?>
						</td>
				
						<td>
							<?php echo $localised_start_date; ?>
							<?php echo ($localised_end_date != $localised_start_date) ? " - $localised_end_date":'' ?>
							<br />
							<?php
								//TODO Should 00:00 - 00:00 be treated as an all day event? 
								echo substr ( $EM_Event->start_time, 0, 5 ) . " - " . substr ( $EM_Event->end_time, 0, 5 ); 
							?>
							<br />
							<?php 
							if ( $EM_Event->is_recurrence() && $EM_Event->can_manage('edit_events','edit_others_events') ) {
								$recurrence_delete_confirm = __('WARNING! You will delete ALL recurrences of this event, including booking history associated with any event in this recurrence. To keep booking information, go to the relevant single event and save it to detach it from this recurrence series.','events-manager');
								?>
								<strong>
								<?php echo $EM_Event->get_recurrence_description(); ?> <br />
								<a href="<?php echo $url ?>edit/?event_id=<?php echo $EM_Event->recurrence_id ?>"><?php _e ( 'Edit Recurring Events', 'events-manager'); ?></a>
								</strong>
								<?php
							}else{ echo "&nbsp;"; }
							?>
						</td>
					</tr>
					<?php
				}
				$event_count++;
			}
			?>
		</tbody>
	</table>  
	<?php
	} // end of table
	?>
	<div class='tablenav'>
		<div class="alignleft actions">
		<br class='clear' />
		</div>
		<?php if ( $events_count >= $limit ) : ?>
		<div class="tablenav-pages">
			<?php
			echo $events_nav;
			?>
		</div>
		<?php endif; ?>
		<br class='clear' />
	</div>