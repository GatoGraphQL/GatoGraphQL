<?php
	global $wpdb, $EM_Notices;
	//add new button will only appear if called from em_location_admin template tag, or if the $show_add_new var is set	
	if(!empty($show_add_new) && current_user_can('edit_locations')) echo '<a class="em-button button add-new-h2" href="'.em_add_get_params($_SERVER['REQUEST_URI'],array('action'=>'edit','scope'=>null,'status'=>null,'location_id'=>null)).'">'.__('Add New','events-manager').'</a>';
?>
<?php if(!is_admin()) echo $EM_Notices; ?>			  
<form id='locations-filter' method='post' action=''>
	<input type='hidden' name='pno' value='<?php echo esc_attr($page) ?>' />
	<div class="subsubsub">
		<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], array('view'=>null, 'pno'=>null)); ?>' <?php echo ( empty($_REQUEST['view']) ) ? 'class="current"':''; ?>><?php echo sprintf( __( 'My %s', 'events-manager'), __('Locations','events-manager')); ?> <span class="count">(<?php echo $locations_mine_count; ?>)</span></a>
		<?php if( current_user_can('read_others_locations') ): ?>
		&nbsp;|&nbsp;
		<a href='<?php echo em_add_get_params($_SERVER['REQUEST_URI'], array('view'=>'others', 'pno'=>null)); ?>' <?php echo ( !empty($_REQUEST['view']) && $_REQUEST['view'] == 'others' ) ? 'class="current"':''; ?>><?php echo sprintf( __( 'All %s', 'events-manager'), __('Locations','events-manager')); ?><span class="count">(<?php echo $locations_all_count; ?>)</span></a>
		<?php endif; ?>
	</div>						
	<?php if ( $locations_count > 0 ) : ?>
	<div class='tablenav'>					
		<?php if( (empty($_REQUEST['view']) && current_user_can('delete_events')) || (!empty($_REQUEST['view']) && $_REQUEST['view'] == 'others' && current_user_can('delete_others_events')) ): ?>
		<div class="alignleft actions">
			<select name="action">
				<option value="" selected="selected"><?php _e ( 'Bulk Actions', 'events-manager'); ?></option>
				<?php if( empty($_REQUEST['view']) && current_user_can('delete_events') ) : ?>
				<option value="location_delete"><?php _e ( 'Delete selected','events-manager'); ?></option>
				<?php endif; ?>
			</select> 
			<input type="submit" value="<?php _e ( 'Apply' ); ?>" id="doaction2" class="button-secondary action" /> 
		</div>
		<?php else: $hide_checkboxes = true; /* @todo this and the first condition of this if statement will need to change when other bulk actions are added */ ?>
		<?php endif; ?>
		<?php
		if ( $locations_count >= $limit ) {
			$locations_nav = em_admin_paginate( $locations_count, $limit, $page );
			echo $locations_nav;
		}
		?>
	</div>
	<table class='widefat'>
		<thead>
			<tr>
				<?php if(empty($hide_checkboxes)): ?>
				<th class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='1'/></th>
				<?php endif; ?>
				<th><?php _e('Name', 'events-manager') ?></th>
				<th><?php _e('Address', 'events-manager') ?></th>
				<th><?php _e('State', 'events-manager') ?></th>  
				<th><?php _e('Country', 'events-manager') ?></th>                
			</tr> 
		</thead>
		<tfoot>
			<tr>
				<?php if(empty($hide_checkboxes)): ?>
				<th class='manage-column column-cb check-column' scope='col'><input type='checkbox' class='select-all' value='1'/></th>
				<?php endif; ?>
				<th><?php _e('Name', 'events-manager') ?></th>
				<th><?php _e('Address', 'events-manager') ?></th>
				<th><?php _e('State', 'events-manager') ?></th> 
				<th><?php _e('Country', 'events-manager') ?></th>      
			</tr>             
		</tfoot>
		<tbody>
			<?php $rowno = 0; ?>
			<?php foreach ($locations as $EM_Location) : ?>
				<?php
					$class = ($rowno % 2) ? 'alternate' : '';
					$rowno++;
				?>
				<tr class="<?php echo $class; ?>">
				    <?php if(empty($hide_checkboxes)): ?>
					<td><input type='checkbox' class ='row-selector' value='<?php echo $EM_Location->location_id ?>' name='locations[]'/></td>
					<?php endif; ?>
					<td>
						<?php if( $EM_Location->can_manage('edit_events','edit_others_events') ): ?>
						<a href='<?php echo esc_url($EM_Location->get_edit_url()); ?>'><?php echo esc_html($EM_Location->location_name); ?></a>
						<?php else: ?>
						<strong><?php echo esc_html($EM_Location->location_name) ?></strong> - 
						<a href='<?php echo $EM_Location->output('#_LOCATIONURL'); ?>'><?php esc_html_e('View') ?></a>
						<?php endif; ?>
					</td>
					<td><?php echo esc_html(implode(', ', array($EM_Location->location_address,$EM_Location->location_town,$EM_Location->location_postcode))); ?></td>
					<td><?php echo esc_html($EM_Location->location_state) ?></td>  
					<td><?php echo $EM_Location->get_country() ?></td>                             
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
	<?php else: ?>
	<br class="clear" />
	<p><?php esc_html_e('No locations have been inserted yet!', 'events-manager') ?></p>
	<?php endif; ?>
	
	<?php if ( !empty($locations_nav) ) echo $locations_nav; ?>
</form>