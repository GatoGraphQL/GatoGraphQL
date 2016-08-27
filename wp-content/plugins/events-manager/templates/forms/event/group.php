<?php
global $EM_Event;
if( !function_exists('bp_is_active') || !bp_is_active('groups') ) return false;
$user_groups = array();
$group_data = groups_get_user_groups(get_current_user_id());
if( !is_super_admin() ){
	foreach( $group_data['groups'] as $group_id ){
		if( groups_is_user_admin(get_current_user_id(), $group_id) ){
			$user_groups[] = groups_get_group( array('group_id'=>$group_id)); 
		}
	}
	$group_count = count($user_groups);
}else{
    $groups = groups_get_groups(array('show_hidden'=>true, 'per_page'=>0));
    $user_groups = $groups['groups'];
	$group_count = $groups['total'];
}
if( count($user_groups) > 0 ){ 
	?>
	<p>
	<select name="group_id">
		<option value=""><?php _e('Not a Group Event', 'events-manager'); ?></option>
		<?php
		//in case user isn't a group mod, but can edit other users' events
		if( !empty($EM_Event->group_id) && !in_array($EM_Event->group_id, $group_data['groups']) ){
			$other_group = groups_get_group( array('group_id'=>$EM_Event->group_id));
			?>
			<option value="<?php echo $other_group->id; ?>" selected="selected"><?php echo $other_group->name; ?></option>
			<?php
		}
		//show user groups
		foreach($user_groups as $BP_Group){
			?>
			<option value="<?php echo $BP_Group->id; ?>" <?php echo ($BP_Group->id == $EM_Event->group_id) ? 'selected="selected"':''; ?>><?php echo $BP_Group->name; ?></option>
			<?php
		} 
		?>
	</select>
	<br />
	<em><?php _e ( 'Select a group you admin to attach this event to it. Note that all other admins of that group can modify the booking.', 'events-manager')?></em>
	</p>
	<?php if( is_super_admin() ): ?>
	<p><em><?php _e ( 'As a site admin, you see all group events, users will only be able to choose groups they are admins of.', 'events-manager')?></em></p>
	<?php endif; 
	
}else{
	?><p><em><?php _e('No groups defined yet.','events-manager'); ?></em></p><?php
}