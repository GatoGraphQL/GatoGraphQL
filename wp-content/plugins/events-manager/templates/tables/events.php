<?php
	//TODO Simplify panel for events, use form flags to detect certain actions (e.g. submitted, etc)
	global $wpdb, $bp, $EM_Notices;
	/* @var $args array */
	/* @var $EM_Events array */
	/* @var events_count int */
	/* @var future_count int */
	/* @var pending_count int */
	/* @var url string */
	/* @var show_add_new bool */
	//add new button will only appear if called from em_event_admin template tag, or if the $show_add_new var is set
	if(!empty($show_add_new) && current_user_can('edit_events')) echo '<a class="em-button button add-new-h2" href="'.em_add_get_params($_SERVER['REQUEST_URI'],array('action'=>'edit','scope'=>null,'status'=>null,'event_id'=>null, 'success'=>null)).'">'.__('Add New','events-manager').'</a>';
	?>
	<div class="wrap">
		<?php echo $EM_Notices; ?>
		<form id="posts-filter" action="" method="get">
			<div class="subsubsub">
				<?php $default_params = array('scope'=>null,'status'=>null,'em_search'=>null,'pno'=>null); //template for cleaning the link for each view below ?>
				<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], $default_params + array('view'=>'future')); ?>' <?php echo ( !isset($_GET['view']) ) ? 'class="current"':''; ?>><?php _e ( 'Upcoming', 'events-manager'); ?> <span class="count">(<?php echo $future_count; ?>)</span></a> &nbsp;|&nbsp; 
				<?php if( $pending_count > 0 ): ?>
				<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], $default_params + array('view'=>'pending')); ?>' <?php echo ( isset($_GET['view']) && $_GET['view'] == 'pending' ) ? 'class="current"':''; ?>><?php _e ( 'Pending', 'events-manager'); ?> <span class="count">(<?php echo $pending_count; ?>)</span></a> &nbsp;|&nbsp; 
				<?php endif; ?>
				<?php if( $draft_count > 0 ): ?>
				<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], $default_params + array('view'=>'draft')); ?>' <?php echo ( isset($_GET['view']) && $_GET['view'] == 'draft' ) ? 'class="current"':''; ?>><?php _e ( 'Draft', 'events-manager'); ?> <span class="count">(<?php echo $draft_count; ?>)</span></a> &nbsp;|&nbsp;
				<?php endif; ?>
				<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], $default_params + array('view'=>'past')); ?>' <?php echo ( isset($_GET['view']) && $_GET['view'] == 'past' ) ? 'class="current"':''; ?>><?php _e ( 'Past Events', 'events-manager'); ?> <span class="count">(<?php echo $past_count; ?>)</span></a>
			</div>
			<p class="search-box">
				<label class="screen-reader-text" for="post-search-input"><?php _e('Search Events','events-manager'); ?>:</label>
				<input type="text" id="post-search-input" name="em_search" value="<?php echo (!empty($_REQUEST['em_search'])) ? esc_attr($_REQUEST['em_search']):''; ?>" />
				<?php if( !empty($_REQUEST['view']) ): ?>
				<input type="hidden" name="view" value="<?php echo esc_attr($_REQUEST['view']); ?>" />
				<?php endif; ?>
				<input type="submit" value="<?php _e('Search Events','events-manager'); ?>" class="button" />
			</p>
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
			if ( empty($EM_Events) ) {
				echo get_option ( 'dbem_no_events_message' );
			} else {
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
						<th>&nbsp;</th>
						<th><?php _e ( 'Location', 'events-manager'); ?></th>
						<th colspan="2"><?php _e ( 'Date and time', 'events-manager'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php
					$rowno = 0;
					foreach ( $EM_Events as $EM_Event ) {
						/* @var $EM_Event EM_Event */
						$rowno++;
						$class = ($rowno % 2) ? 'alternate' : '';
						// FIXME set to american						
						$localised_start_date = date_i18n(get_option('dbem_date_format'), $EM_Event->start);
						$localised_end_date = date_i18n(get_option('dbem_date_format'), $EM_Event->end);
						$style = "";
						$today = current_time('timestamp');
						$location_summary = "<b>" . esc_html($EM_Event->get_location()->location_name) . "</b><br/>" . esc_html($EM_Event->get_location()->location_address) . " - " . esc_html($EM_Event->get_location()->location_town);
						
						if ($EM_Event->start < $today && $EM_Event->end < $today){						
							$class .= " past";
						}
						//Check pending approval events
						if ( !$EM_Event->get_status() ){
							$class .= " pending";
						}					
						?>
						<tr class="event <?php echo trim($class); ?>" <?php echo $style; ?> id="event_<?php echo $EM_Event->event_id ?>">
							<?php /*
							<td>
								<input type='checkbox' class='row-selector' value='<?php echo $EM_Event->event_id; ?>' name='events[]' />
							</td>
							*/ ?>
							<td>
								<strong>
									<a class="row-title" href="<?php echo esc_url($EM_Event->get_edit_url()); ?>"><?php echo esc_html($EM_Event->event_name); ?></a>
								</strong>
								<?php 
								if( get_option('dbem_rsvp_enabled') == 1 && $EM_Event->event_rsvp == 1 ){
									?>
									<br/>
									<a href="<?php echo $EM_Event->get_bookings_url(); ?>"><?php esc_html_e("Bookings",'events-manager'); ?></a> &ndash;
									<?php esc_html_e("Booked",'events-manager'); ?>: <?php echo $EM_Event->get_bookings()->get_booked_spaces()."/".$EM_Event->get_spaces(); ?>
									<?php if( get_option('dbem_bookings_approval') == 1 ): ?>
										| <?php _e("Pending",'events-manager') ?>: <?php echo $EM_Event->get_bookings()->get_pending_spaces(); ?>
									<?php endif;
								}
								?>
								<div class="row-actions">
									<?php if( current_user_can('delete_events')) : ?>
									<span class="trash"><a href="<?php echo esc_url(add_query_arg(array('action'=>'event_delete', 'event_id'=>$EM_Event->event_id, '_wpnonce'=> wp_create_nonce('event_delete_'.$EM_Event->event_id)))); ?>" class="em-event-delete"><?php _e('Delete','events-manager'); ?></a></span>
									<?php endif; ?>
								</div>
							</td>
							<td>
								<a href="<?php echo $EM_Event->duplicate_url(); ?>" title="<?php _e ( 'Duplicate this event', 'events-manager'); ?>">
									<strong>+</strong>
								</a>
							</td>
							<td>
								<?php echo $location_summary; ?>
							</td>
					
							<td>
								<?php echo $localised_start_date; ?>
								<?php echo ($localised_end_date != $localised_start_date) ? " - $localised_end_date":'' ?>
								<br />
								<?php
									if(!$EM_Event->event_all_day){
										echo date_i18n(get_option('time_format'), $EM_Event->start) . " - " . date_i18n(get_option('time_format'), $EM_Event->end);
									}else{
										echo get_option('dbem_event_all_day_message');
									}
								?>
							</td>
							<td>
								<?php 
								if ( $EM_Event->is_recurrence() ) {
									$recurrence_delete_confirm = __('WARNING! You will delete ALL recurrences of this event, including booking history associated with any event in this recurrence. To keep booking information, go to the relevant single event and save it to detach it from this recurrence series.','events-manager');
									?>
									<strong>
									<?php echo $EM_Event->get_recurrence_description(); ?> <br />
									<a href="<?php echo esc_url($EM_Event->get_edit_reschedule_url()); ?>"><?php _e ( 'Edit Recurring Events', 'events-manager'); ?></a>
									<?php if( current_user_can('delete_events')) : ?>
									<span class="trash"><a href="<?php echo esc_url(add_query_arg(array('action'=>'event_delete', 'event_id'=>$EM_Event->recurrence_id, '_wpnonce'=> wp_create_nonce('event_delete_'.$EM_Event->recurrence_id)))); ?>" class="em-event-rec-delete" onclick ="if( !confirm('<?php echo $recurrence_delete_confirm; ?>') ){ return false; }"><?php _e('Delete','events-manager'); ?></a></span>
									<?php endif; ?>										
									</strong>
									<?php
								}
								?>
							</td>
						</tr>
						<?php
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
		</form>
	</div>