<?php do_action('em_template_my_bookings_header'); ?>
<?php
	global $wpdb, $current_user, $EM_Notices, $EM_Person;
	if( is_user_logged_in() ):
		$EM_Person = new EM_Person( get_current_user_id() );
		$EM_Bookings = $EM_Person->get_bookings();
		$bookings_count = count($EM_Bookings->bookings);
		if($bookings_count > 0){
			//Get events here in one query to speed things up
			$event_ids = array();
			foreach($EM_Bookings as $EM_Booking){
				$event_ids[] = $EM_Booking->event_id;
			}
		}
		$limit = ( !empty($_GET['limit']) ) ? $_GET['limit'] : 20;//Default limit
		$page = ( !empty($_GET['pno']) ) ? $_GET['pno']:1;
		$offset = ( $page > 1 ) ? ($page-1)*$limit : 0;
		echo $EM_Notices;
		?>
		<div class='em-my-bookings'>
				<?php if ( $bookings_count >= $limit ) : ?>
				<div class='tablenav'>
					<?php 
					if ( $bookings_count >= $limit ) {
						$link = em_add_get_params($_SERVER['REQUEST_URI'], array('pno'=>'%PAGE%'), false); //don't html encode, so em_paginate does its thing
						$bookings_nav = em_paginate( $link, $bookings_count, $limit, $page);
						echo $bookings_nav;
					}
					?>
					<div class="clear"></div>
				</div>
				<?php endif; ?>
				<div class="clear"></div>
				<?php if( $bookings_count > 0 ): ?>
				<div class='table-wrap'>
				<table id='dbem-bookings-table' class='widefat post fixed'>
					<thead>
						<tr>
							<th class='manage-column' scope='col'><?php _e('Event', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Date', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Spaces', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Status', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$rowno = 0;
						$event_count = 0;
						$nonce = wp_create_nonce('booking_cancel');
						foreach ($EM_Bookings as $EM_Booking) {
							/* @var $EM_Booking EM_Booking */
							$EM_Event = $EM_Booking->get_event();						
							if( ($rowno < $limit || empty($limit)) && ($event_count >= $offset || $offset === 0) ) {
								$rowno++;
								?>
								<tr>
									<td><?php echo $EM_Event->output("#_EVENTLINK"); ?></td>
									<td><?php echo date_i18n( get_option('dbem_date_format'), $EM_Event->start ); ?></td>
									<td><?php echo $EM_Booking->get_spaces() ?></td>
									<td>
										<?php echo $EM_Booking->get_status(); ?>
									</td>
									<td>
										<?php
										$cancel_link = '';
										if( !in_array($EM_Booking->booking_status, array(2,3)) && get_option('dbem_bookings_user_cancellation') && $EM_Event->get_bookings()->has_open_time() ){
											$cancel_url = em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>'booking_cancel', 'booking_id'=>$EM_Booking->booking_id, '_wpnonce'=>$nonce));
											$cancel_link = '<a class="em-bookings-cancel" href="'.$cancel_url.'" onclick="if( !confirm(EM.booking_warning_cancel) ){ return false; }">'.__('Cancel','events-manager').'</a>';
										}
										echo apply_filters('em_my_bookings_booking_actions', $cancel_link, $EM_Booking);
										?>
									</td>
								</tr>								
								<?php
							}
							do_action('em_my_bookings_booking_loop',$EM_Booking);
							$event_count++;
						}
						?>
					</tbody>
				</table>
				</div>
				<?php else: ?>
					<?php _e('You do not have any bookings.', 'events-manager'); ?>
				<?php endif; ?>
			<?php if( !empty($bookings_nav) && $bookings_count >= $limit ) : ?>
			<div class='tablenav'>
				<?php echo $bookings_nav; ?>
				<div class="clear"></div>
			</div>
			<?php endif; ?>
		</div>
		<?php do_action('em_template_my_bookings_footer', $EM_Bookings); ?>
<?php else: ?>
	<p><?php echo sprintf(__('Please <a href="%s">Log In</a> to view your bookings.','events-manager'),site_url('wp-login.php?redirect_to=' . urlencode(get_permalink()), 'login'))?></p>
<?php endif; ?>