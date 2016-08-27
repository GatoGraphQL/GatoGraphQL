<?php
/**
 * Adds a note to the event post type in the admin area, so it's obvious EM is interfering.
 */
function wpfc_admin_options_post_type_event(){
	echo " - <i>powered by Events Manager</i>";
}
add_action('wpfc_admin_options_post_type_event','wpfc_admin_options_post_type_event');

function wpfc_em_admin_notice(){
    if( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'wp-fullcalendar'){
    ?>
    <div class="updated"><p><?php echo sprintf(__('If you choose the Event post type whilst Events Manager is activated, you can also visit the <a href="%s">Events Manager settings page</a> for a few more options when displaying event information on your calendar.','events-manager'), admin_url('edit.php?post_type='.EM_POST_TYPE_EVENT.'&page=events-manager-options')); ?></p></div>
    <?php
    }
}
add_action('admin_notices', 'wpfc_em_admin_notice');

function wpfc_em_admin_options(){
	?>
	<div  class="postbox " >
		<div class="handlediv" title="<?php __('Click to toggle', 'events-manager'); ?>"><br /></div><h3 class='hndle'><span><?php _e ( 'Full Calendar Options', 'events-manager'); ?> </span></h3>
		<div class="inside">
			<p  class="em-boxheader"><?php echo sprintf(__('Looking for the rest of the FullCalendar Options? They\'ve moved <a href="%s">here</a>, the options below are for overriding specific bits relevant to Events Manager.','events-manager'), admin_url('options-general.php?page=wp-fullcalendar')); ?></p>
			<table class='form-table'>
				<?php
				global $events_placeholder_tip, $save_button;
				em_options_radio_binary ( __( 'Override calendar on events page?', 'events-manager'), 'dbem_emfc_override_calendar', __( 'If set to yes, the FullCalendar will be used instead of the standard calendar on the events page.', 'events-manager') );
				em_options_radio_binary ( __( 'Override calendar shortcode?', 'events-manager'), 'dbem_emfc_override_shortcode', __( 'Overrides the default calendar shortcode. You can also use [events_fullcalendar] instead.','events-manager') );
				em_options_input_text ( __( 'Event title format', 'events-manager'), 'dbem_emfc_full_calendar_event_format', __('HTML is not accepted.','events-manager').' '.$events_placeholder_tip, '#_EVENTNAME' );
				em_options_textarea( __( 'Event tooltips format', 'events-manager'), 'dbem_emfc_qtips_format', __('If you enable tips, this information will be shown, which can include HTML.','events-manager').' '.$events_placeholder_tip, '#_EVENTNAME' );$positions_options = array();
				?>
			</table>
			<?php echo $save_button; ?>
		</div> <!-- . inside -->
		</div> <!-- .postbox -->
	<?php
}
add_action('em_options_page_footer', 'wpfc_em_admin_options');

function wpfc_em_install(){
	//check for updates - try adding one option, if it works then it's a first time install so add more
	if( current_user_can('manage_options') && get_option('dbem_emfc_full_calendar_event_format', false) ){
	    add_option('dbem_emfc_full_calendar_event_format','#_EVENTTIMES - #_EVENTNAME');
		add_option('dbem_emfc_qtips_format', '{has_image}<div style="float:left; margin:0px 5px 5px 0px;">#_EVENTIMAGE{75,75}</div>{/has_image}#_EVENTEXCERPT');
	}
}
add_action('init', 'wpfc_em_install');