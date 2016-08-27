<?php

/**
 * Generates a "widget" table of confirmed bookings for a specific event.
 * 
 * @param int $event_id
 */
function em_bookings_confirmed_table(){
	global $EM_Event, $EM_Ticket, $wpdb, $current_user;
	
	$action_scope = ( !empty($_REQUEST['em_obj']) && $_REQUEST['em_obj'] == 'em_bookings_confirmed_table' );
	$action = ( $action_scope && !empty($_GET ['action']) ) ? $_GET ['action']:'';
	$order = ( $action_scope && !empty($_GET ['order']) ) ? $_GET ['order']:'ASC';
	$limit = ( $action_scope && !empty($_GET['limit']) ) ? $_GET['limit'] : 20;//Default limit
	$page = ( $action_scope && !empty($_GET['pno']) ) ? $_GET['pno']:1;
	$offset = ( $action_scope && $page > 1 ) ? ($page-1)*$limit : 0;
	
	if( is_object($EM_Ticket) ){
		$EM_Bookings = $EM_Ticket->get_bookings()->get_bookings();
	}else{
		if( is_object($EM_Event) ){
			$EM_Bookings = $EM_Event->get_bookings()->get_bookings();
		}else{
			return false;
		}
	}
	$bookings_count = (is_array($EM_Bookings->bookings)) ? count($EM_Bookings->bookings):0;
	?>
		<div class='wrap em_bookings_pending_table em_obj'>
			<form id='bookings-filter' method='get' action='<?php bloginfo('wpurl') ?>/wp-admin/edit.php'>
				<input type="hidden" name="em_obj" value="em_bookings_pending_table" />
				<!--
				<ul class="subsubsub">
					<li>
						<a href='edit.php?post_type=post' class="current">All <span class="count">(1)</span></a> |
					</li>
				</ul>
				<p class="search-box">
					<label class="screen-reader-text" for="post-search-input"><?php _e('Search', 'events-manager'); ?>:</label>
					<input type="text" id="post-search-input" name="em_search" value="<?php echo (!empty($_GET['em_search'])) ? esc_attr($_GET['em_search']):''; ?>" />
					<input type="submit" value="<?php _e('Search', 'events-manager'); ?>" class="button" />
				</p>
				-->
				<?php if ( $bookings_count >= $limit ) : ?>
				<div class='tablenav'>
					<!--
					<div class="alignleft actions">
						<select name="action">
							<option value="-1" selected="selected">
								<?php _e('Bulk Actions', 'events-manager'); ?>
							</option>
							<option value="approve">
								<?php _e('Approve', 'events-manager'); ?>
							</option>
							<option value="decline">
								<?php _e('Decline', 'events-manager'); ?>
							</option>
						</select> 
						<input type="submit" id="post-query-submit" value="Filter" class="button-secondary" />
					</div>
					-->
					<!--
					<div class="view-switch">
						<a href="/wp-admin/edit.php?mode=list"><img class="current" id="view-switch-list" src="http://wordpress.lan/wp-includes/images/blank.gif" width="20" height="20" title="List View" alt="List View" name="view-switch-list" /></a> <a href="/wp-admin/edit.php?mode=excerpt"><img id="view-switch-excerpt" src="http://wordpress.lan/wp-includes/images/blank.gif" width="20" height="20" title="Excerpt View" alt="Excerpt View" name="view-switch-excerpt" /></a>
					</div>
					-->
					<?php 
					if ( $bookings_count >= $limit ) {
						$bookings_nav = em_admin_paginate($bookings_count, $limit, $page, array('em_ajax'=>0, 'em_obj'=>'em_bookings_confirmed_table'));
						echo $bookings_nav;
					}
					?>
					<div class="clear"></div>
				</div>
				<?php endif; ?>
				<div class="clear"></div>
				<?php if( $bookings_count > 0 ): ?>
				<div class='table-wrap'>
				<table id='dbem-bookings-table' class='widefat post '>
					<thead>
						<tr>
							<th class='manage-column column-cb check-column' scope='col'>
								<input class='select-all' type="checkbox" value='1' />
							</th>
							<th class='manage-column' scope='col'><?php _e('Booker', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php _e('E-mail', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Phone number', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'><?php _e('Spaces', 'events-manager'); ?></th>
							<th class='manage-column' scope='col'>&nbsp;</th>
						</tr>
					</thead>
					<tbody>
						<?php 
						$rowno = 0;
						$event_count = 0;
						foreach ($EM_Bookings->bookings as $EM_Booking) {
							if( ($rowno < $limit || empty($limit)) && ($event_count >= $offset || $offset === 0) ) {
								$rowno++;
								?>
								<tr>
									<th scope="row" class="check-column" style="padding:7px 0px 7px;"><input type='checkbox' value='<?php echo $EM_Booking->booking_id ?>' name='bookings[]'/></th>
									<td><a href="<?php echo EM_ADMIN_URL; ?>&amp;page=events-manager-bookings&amp;person_id=<?php echo $EM_Booking->person_id; ?>"><?php echo $EM_Booking->person->get_name() ?></a></td>
									<td><?php echo $EM_Booking->person->user_email ?></td>
									<td><?php echo $EM_Booking->person->phone ?></td>
									<td><?php echo $EM_Booking->get_spaces() ?></td>
									<td>										
										<?php
										$unapprove_url = em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>'bookings_unapprove', 'booking_id'=>$EM_Booking->booking_id));
										$reject_url = em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>'bookings_reject', 'booking_id'=>$EM_Booking->booking_id));
										$delete_url = em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>'bookings_delete', 'booking_id'=>$EM_Booking->booking_id));
										$edit_url = em_add_get_params($_SERVER['REQUEST_URI'], array('booking_id'=>$EM_Booking->booking_id, 'em_ajax'=>null, 'em_obj'=>null));
										?>
										<?php if( current_user_can('manage_bookings') && get_option('dbem_bookings_approval') ): ?>
										<a class="em-bookings-unapprove" href="<?php echo $unapprove_url ?>"><?php _e('Unapprove','events-manager'); ?></a> |
										<?php else: ?>
										<a class="em-bookings-reject" href="<?php echo $reject_url ?>"><?php _e('Reject','events-manager'); ?></a> |
										<?php endif; ?>
										<span class="trash"><a class="em-bookings-delete" href="<?php echo $delete_url ?>"><?php _e('Delete','events-manager'); ?></a></span> |
										<a class="em-bookings-edit" href="<?php echo $edit_url; ?>"><?php _e('Edit/View','events-manager'); ?></a>
									</td>
								</tr>
								<?php
							}
							$event_count++;
						}
						?>
					</tbody>
				</table>
				</div>
				<?php else: ?>
					<?php _e('No confirmed bookings.', 'events-manager'); ?>
				<?php endif; ?>
			</form>
			<?php if( !empty($bookings_nav) && $EM_Bookings >= $limit ) : ?>
			<div class='tablenav'>
				<?php echo $bookings_nav; ?>
				<div class="clear"></div>
			</div>
			<?php endif; ?>
		</div>	
	<?php
	
}
?>