<?php
/* WARNING! This file may change in the near future as we intend to add features to BuddyPress - 2012-02-14 */
global $bp, $EM_Notices;
echo $EM_Notices;
if( user_can($bp->displayed_user->id,'edit_events') ){
	?>
	<h4><?php _e('My Events', 'events-manager'); ?></h4>
	<?php
	$args = array(
		'owner'=>$bp->displayed_user->id, 
		'format_header' => get_option('dbem_bp_events_list_format_header'),
		'format' => get_option('dbem_bp_events_list_format'),
		'format_footer' => get_option('dbem_bp_events_list_format_footer'),
		'owner' => $bp->displayed_user->id,
		'pagination'=>1
	);
	$args['limit'] = !empty($args['limit']) ? $args['limit'] : get_option('dbem_events_default_limit');
	if( EM_Events::count($args) > 0 ){
		echo EM_Events::output($args);
	}else{
		?>
		<p><?php _e('No Events', 'events-manager'); ?>.
		<?php if( get_current_user_id() == $bp->displayed_user->id ): ?> 
		<a href="<?php echo $bp->events->link . 'my-events/edit/'; ?>"><?php _e('Add Event','events-manager'); ?></a>
		<?php endif; ?>
		</p>
		<?php
	}
}
?>
<h4><?php _e("Events I'm Attending", 'events-manager'); ?></h4>
<?php
$EM_Person = new EM_Person( $bp->displayed_user->id );
$EM_Bookings = $EM_Person->get_bookings( false, apply_filters('em_bp_attending_status',1) );
if(count($EM_Bookings->bookings) > 0){
	//Get events here in one query to speed things up
	$event_ids = array();
	foreach($EM_Bookings as $EM_Booking){
		$event_ids[] = $EM_Booking->event_id;
	}
	echo EM_Events::output(array('event'=>$event_ids));
}else{
	?>
	<p><?php _e('Not attending any events yet.','events-manager'); ?></p>
	<?php
}