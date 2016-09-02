<?php

/**
 * Determines whether to show event page or events page, and saves any updates to the event or events
 * @return null
 */
function em_bookings_events_table() {
	//TODO Simplify panel for events, use form flags to detect certain actions (e.g. submitted, etc)
	global $wpdb;
	global $EM_Event;

	$scope_names = array (
		'past' => __ ( 'Past events', 'events-manager'),
		'all' => __ ( 'All events', 'events-manager'),
		'future' => __ ( 'Future events', 'events-manager')
	);
	
	$action_scope = ( !empty($_REQUEST['em_obj']) && $_REQUEST['em_obj'] == 'em_bookings_events_table' );
	$action = ( $action_scope && !empty($_GET ['action']) ) ? $_GET ['action']:'';
	$order = ( $action_scope && !empty($_GET ['order']) ) ? $_GET ['order']:'ASC';
	$limit = ( $action_scope && !empty($_GET['limit']) ) ? $_GET['limit'] : 20;//Default limit
	$page = ( $action_scope && !empty($_GET['pno']) ) ? $_GET['pno']:1;
	$offset = ( $action_scope && $page > 1 ) ? ($page-1)*$limit : 0;
	$scope = ( $action_scope && !empty($_GET ['scope']) && array_key_exists($_GET ['scope'], $scope_names) ) ? $_GET ['scope']:'future';
	
	// No action, only showing the events list
	switch ($scope) {
		case "past" :
			$title = __ ( 'Past Events', 'events-manager');
			break;
		case "all" :
			$title = __ ( 'All Events', 'events-manager');
			break;
		default :
			$title = __ ( 'Future Events', 'events-manager');
			$scope = "future";
	}
	$owner = !current_user_can('manage_others_bookings') ? get_current_user_id() : false;
	// Hack PoP Plug-in: allow args to be overriden (to restrict Events to ones owned by the user + co-authored ones)
	// $events = EM_Events::get( array('scope'=>$scope, 'limit'=>$limit, 'offset' => $offset, 'order'=>$order, 'bookings'=>true, 'owner' => $owner ) );
	$args = array('scope'=>$scope, 'limit'=>$limit, 'offset' => $offset, 'order'=>$order, 'bookings'=>true, 'owner' => $owner );
	$args = apply_filters('gd_em_bookings_events_table_args', $args);
	$events = EM_Events::get( $args );
	
	$events_count = EM_Events::count( array('scope'=>$scope, 'limit'=>0, 'order'=>$order, 'bookings'=>true, 'owner' => $owner ) );
	
	$use_events_end = get_option ( 'dbem_use_event_end' );
	?>
	<div class="wrap em_bookings_events_table em_obj">
		<form id="posts-filter" action="" method="get">
			<input type="hidden" name="em_obj" value="em_bookings_events_table" />
			<?php if(!empty($_GET['page'])): ?>
			<input type='hidden' name='page' value='events-manager-bookings' />
			<?php endif; ?>		
			<div class="tablenav">			
				<div class="alignleft actions">
					<!--
					<select name="action">
						<option value="-1" selected="selected"><?php esc_html_e( 'Bulk Actions' ); ?></option>
						<option value="deleteEvents"><?php esc_html_e( 'Delete selected','events-manager'); ?></option>
					</select> 
					<input type="submit" value="<?php esc_html_e( 'Apply' ); ?>" name="doaction2" id="doaction2" class="button-secondary action" />
					 --> 
					<select name="scope">
						<?php
						foreach ( $scope_names as $key => $value ) {
							$selected = "";
							if ($key == $scope)
								$selected = "selected='selected'";
							echo "<option value='$key' $selected>$value</option>  ";
						}
						?>
					</select>
					<input id="post-query-submit" class="button-secondary" type="submit" value="<?php esc_html_e( 'Filter' )?>" />
				</div>
				<!--
				<div class="view-switch">
					<a href="/wp-admin/edit.php?mode=list"><img class="current" id="view-switch-list" src="http://wordpress.lan/wp-includes/images/blank.gif" width="20" height="20" title="List View" alt="List View" name="view-switch-list" /></a> <a href="/wp-admin/edit.php?mode=excerpt"><img id="view-switch-excerpt" src="http://wordpress.lan/wp-includes/images/blank.gif" width="20" height="20" title="Excerpt View" alt="Excerpt View" name="view-switch-excerpt" /></a>
				</div>
				-->
				<?php 
				if ( $events_count >= $limit ) {
					$events_nav = em_admin_paginate( $events_count, $limit, $page, array('em_ajax'=>0, 'em_obj'=>'em_bookings_events_table'));
					echo $events_nav;
				}
				?>
			</div>
				
			<?php
			if (empty ( $events )) {
				// TODO localize
				_e ( 'no events','events-manager');
			} else {
			?>
			<div class='table-wrap'>	
			<table class="widefat">
				<thead>
					<tr>
						<th><?php esc_html_e( 'Event', 'events-manager'); ?></th>
						<th><?php esc_html_e( 'Date and time', 'events-manager'); ?></th>
					</tr>
				</thead>
				<tbody>
					<?php 
					$rowno = 0;
					foreach ( $events as $event ) {
						/* @var $event EM_Event */
						$rowno++;
						$class = ($rowno % 2) ? ' class="alternate"' : '';
						// FIXME set to american
						$localised_start_date = date_i18n(get_option('date_format'), $event->start);
						$localised_end_date = date_i18n(get_option('date_format'), $event->end);
						$style = "";
						$today = date ( "Y-m-d" );
						
						if ($event->start_date < $today && $event->end_date < $today){
							$style = "style ='background-color: #FADDB7;'";
						}							
						?>
						<tr <?php echo "$class $style"; ?>>
							<td>
								<strong>
									<?php echo $event->output('#_BOOKINGSLINK'); ?>
								</strong>
								&ndash; 
								<?php esc_html_e("Booked Spaces",'events-manager') ?>: <?php echo $event->get_bookings()->get_booked_spaces()."/".$event->get_spaces() ?>
								<?php if( get_option('dbem_bookings_approval') == 1 ) : ?>
									| <?php esc_html_e("Pending",'events-manager') ?>: <?php echo $event->get_bookings()->get_pending_spaces(); ?>
								<?php endif; ?>
							</td>
					
							<td>
								<?php echo $localised_start_date; ?>
								<?php echo ($localised_end_date != $localised_start_date) ? " - $localised_end_date":'' ?>
								&ndash;
								<?php
									//TODO Should 00:00 - 00:00 be treated as an all day event? 
									echo substr ( $event->start_time, 0, 5 ) . " - " . substr ( $event->end_time, 0, 5 ); 
								?>
							</td>
						</tr>
						<?php
					}
					?>
				</tbody>
			</table>
			</div>
			<?php
			} // end of table
			?>
			<div class='tablenav'>
				<div class="alignleft actions">
				<br class='clear' />
				</div>
				<?php if (!empty($events_nav) &&  $events_count >= $limit ) : ?>
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
	<?php
}

?>