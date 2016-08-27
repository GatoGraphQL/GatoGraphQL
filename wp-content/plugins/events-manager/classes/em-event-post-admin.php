<?php
/*
 * Events Edit Page
 */
class EM_Event_Post_Admin{
	public static function init(){
		global $pagenow;
		if($pagenow == 'post.php' || $pagenow == 'post-new.php' ){ //only needed if editing post
			add_action('admin_head', array('EM_Event_Post_Admin','admin_head')); //I don't think we need this anymore?
			//Meta Boxes
			add_action('add_meta_boxes', array('EM_Event_Post_Admin','meta_boxes'));
			//Notices
			add_action('admin_notices',array('EM_Event_Post_Admin','admin_notices'));
		}
		//Save/Edit actions
		add_filter('wp_insert_post_data',array('EM_Event_Post_Admin','wp_insert_post_data'),100,2); //validate post meta before saving is done
		add_action('save_post',array('EM_Event_Post_Admin','save_post'),1,1); //set to 1 so metadata gets saved ASAP
		add_action('before_delete_post',array('EM_Event_Post_Admin','before_delete_post'),10,1);
		add_action('trashed_post',array('EM_Event_Post_Admin','trashed_post'),10,1);
		add_action('untrash_post',array('EM_Event_Post_Admin','untrash_post'),10,1);
		add_action('untrashed_post',array('EM_Event_Post_Admin','untrashed_post'),10,1);
		//Notices
		add_action('post_updated_messages',array('EM_Event_Post_Admin','admin_notices_filter'),1,1);
	}

	public static function admin_head(){
		global $post, $EM_Event;
		if( empty($EM_Event) && !empty($post) && $post->post_type == EM_POST_TYPE_EVENT ){
			$EM_Event = em_get_event($post->ID, 'post_id');
		}
	}
	
	public static function admin_notices(){
		//When editing
		global $post, $EM_Event, $pagenow;
		if( $pagenow == 'post.php' && ($post->post_type == EM_POST_TYPE_EVENT || $post->post_type == 'event-recurring') ){
			if ( $EM_Event->is_recurring() ) {
				$warning = "<p><strong>".__( 'WARNING: This is a recurring event.', 'events-manager')."</strong></p>";
				$warning .= "<p>". __( 'Modifications to this event will cause all recurrences of this event to be deleted and recreated and previous bookings will be deleted! You can edit individual recurrences and disassociate them with this recurring event.', 'events-manager');
				?><div class="updated"><?php echo $warning; ?></div><?php
			} elseif ( $EM_Event->is_recurrence() ) {
				$warning = "<p><strong>".__('WARNING: This is a recurrence in a set of recurring events.', 'events-manager')."</strong></p>";
				$warning .= "<p>". sprintf(__('If you update this event data and save, it could get overwritten if you edit the recurring event template. To make it an independent, <a href="%s">detach it</a>.', 'events-manager'), $EM_Event->get_detach_url())."</p>";
				$warning .= "<p>".sprintf(__('To manage the whole set, <a href="%s">edit the recurring event template</a>.', 'events-manager'),admin_url('post.php?action=edit&amp;post='.$EM_Event->get_event_recurrence()->post_id))."</p>";
				?><div class="updated"><?php echo $warning; ?></div><?php
			}
			if( !empty($EM_Event->group_id) && function_exists('groups_get_group') ){
				$group = groups_get_group(array('group_id'=>$EM_Event->group_id));
				$warning = sprintf(__('WARNING: This is a event belonging to the group "%s". Other group admins can also modify this event.', 'events-manager'), $group->name);
				?><div class="updated"><p><?php echo $warning; ?></p></div><?php
			}
		}
	}
	
	public static function admin_notices_filter($messages){
		//When editing
		global $post, $EM_Notices;
		if( $post->post_type == EM_POST_TYPE_EVENT || $post->post_type == 'event-recurring' ){
			if ( $EM_Notices->count_errors() > 0 ) {
				unset($_GET['message']);
			}
		}
		return $messages;
	}
	
	/**
	 * Validate event once BEFORE it goes into the database, because otherwise it could get 'published' between now and save_post, 
	 * allowing other plugins hooking here to perform incorrect actions e.g. tweet a new event.
	 *  
	 * @param array $data
	 * @param array $postarr
	 * @return array
	 */
	public static function wp_insert_post_data( $data, $postarr ){
		global $wpdb, $EM_SAVING_EVENT;
		if( !empty($EM_SAVING_EVENT) ) return $data; //never proceed with this if using EM_Event::save();
		$post_type = $data['post_type'];
		$post_ID = !empty($postarr['ID']) ? $postarr['ID'] : false;
		$is_post_type = $post_type == EM_POST_TYPE_EVENT || $post_type == 'event-recurring';
		$saving_status = !in_array($data['post_status'], array('trash','auto-draft')) && !defined('DOING_AUTOSAVE');
		$untrashing = $post_ID && defined('UNTRASHING_'.$post_ID);
		if( !$untrashing && $is_post_type && $saving_status ){
			if( !empty($_REQUEST['_emnonce']) && wp_verify_nonce($_REQUEST['_emnonce'], 'edit_event') ){ 
				//this is only run if we know form data was submitted, hence the nonce
				$EM_Event = em_get_event($post_ID, 'post_id');
				$EM_Event->post_type = $post_type;
				//Handle Errors by making post draft
				$get_meta = $EM_Event->get_post_meta();
				$validate_meta = $EM_Event->validate_meta();
				if( !$get_meta || !$validate_meta ) $data['post_status'] = 'draft';
			}
		}
		return $data;
	}
	
	public static function save_post($post_id, $post = false){
		global $wpdb, $EM_Event, $EM_Location, $EM_Notices, $EM_SAVING_EVENT, $EM_EVENT_SAVE_POST;
		if( !empty($EM_SAVING_EVENT) ) return; //never proceed with this if using EM_Event::save();
		if ( isset($_GET['preview_id']) && isset($_GET['preview_nonce']) && wp_verify_nonce( $_GET['preview_nonce'], 'post_preview_' . $post_id ) ) return; //don't proceed with saving when previewing, may cause issues
		$post_type = get_post_type($post_id);
		$is_post_type = $post_type == EM_POST_TYPE_EVENT || $post_type == 'event-recurring';
		$saving_status = !in_array(get_post_status($post_id), array('trash','auto-draft')) && !defined('DOING_AUTOSAVE');
		$EM_EVENT_SAVE_POST = true; //first filter for save_post in EM for events
		if(!defined('UNTRASHING_'.$post_id) && $is_post_type && $saving_status ){
			$EM_Event = new EM_Event($post_id, 'post_id'); //grab event, via post info, reset the $EM_Event variable
			$EM_Event->post_type = $post_type;
			if( !empty($_REQUEST['_emnonce']) && wp_verify_nonce($_REQUEST['_emnonce'], 'edit_event') ){ 
				//this is only run if we know form data was submitted, hence the nonce
				$get_meta = $EM_Event->get_post_meta();
				$validate_meta = $EM_Event->validate_meta(); //Handle Errors by making post draft
				do_action('em_event_save_pre', $EM_Event); //technically, the event is saved... but the meta isn't. wp doesn't give an pre-intervention action for this (or does it?)
				//if we execute a location save here, we will screw up the current save_post $wp_filter pointer executed in do_action()
        	    //therefore, we save the current pointer position (priority) and set it back after saving the location further down
        	    global $wp_filter, $wp_current_filter;
        	    $wp_filter_priority = key($wp_filter['save_post']);
        	    $tag = end($wp_current_filter);
	           //save the event meta, whether validated or not and which includes saving a location
				$save_meta = $EM_Event->save_meta();
        		//reset save_post pointer in $wp_filter to its original position
        		reset( $wp_filter[$tag] );
        		do{
        		   if( key($wp_filter[$tag]) == $wp_filter_priority ) break; 
        		}while ( next($wp_filter[$tag]) !== false );
        		//save categories in case of default category
				$EM_Event->get_categories()->save();
				//continue whether all went well or not
				if( !$get_meta || !$validate_meta || !$save_meta ){
					//failed somewhere, set to draft, don't publish
					$EM_Event->set_status(null, true);
					if( $EM_Event->is_recurring() ){
						$EM_Notices->add_error( '<strong>'.__('Your event details are incorrect and recurrences cannot be created, please correct these errors first:','events-manager').'</strong>', true); //Always seems to redirect, so we make it static
					}else{
						$EM_Notices->add_error( '<strong>'.sprintf(__('Your %s details are incorrect and cannot be published, please correct these errors first:','events-manager'),__('event','events-manager')).'</strong>', true); //Always seems to redirect, so we make it static
					}
					$EM_Notices->add_error($EM_Event->get_errors(), true); //Always seems to redirect, so we make it static
					apply_filters('em_event_save', false, $EM_Event);
				}else{
					//if this is just published, we need to email the user about the publication, or send to pending mode again for review
					if( (!$EM_Event->is_recurring() && !current_user_can('publish_events')) || ($EM_Event->is_recurring() && !current_user_can('publish_recurring_events')) ){
						if( $EM_Event->is_published() ){ $EM_Event->set_status(0, true); } //no publishing and editing... security threat
					}
					apply_filters('em_event_save', true, $EM_Event);
				}
			}else{
				//we're updating only the quick-edit style information, which is only post info saved into the index
				if( $EM_Event->validate() ){
					do_action('em_event_save_pre', $EM_Event); //technically, the event is saved... but the meta isn't. wp doesn't give an pre-intervention action for this (or does it?)
					//first things first, we must make sure we have an index, if not, reset it to a new one:
					$event_truly_exists = $wpdb->get_var('SELECT event_id FROM '.EM_EVENTS_TABLE." WHERE event_id={$EM_Event->event_id}") == $EM_Event->event_id;
					if(empty($EM_Event->event_id) || !$event_truly_exists){ $EM_Event->save_meta(); }
					//we can save the status now
					$EM_Event->get_previous_status(); //before we save anything
					$event_status = $EM_Event->get_status(true);
					//if this is just published, we need to email the user about the publication, or send to pending mode again for review
					if( (!$EM_Event->is_recurring() && !current_user_can('publish_events')) || ($EM_Event->is_recurring() && !current_user_can('publish_recurring_events')) ){
						if( $EM_Event->is_published() ){ $EM_Event->set_status(0, true); } //no publishing and editing... security threat
					}
					//now update the db
					$where_array = array($EM_Event->event_name, $EM_Event->event_owner, $EM_Event->event_slug, $EM_Event->event_private, $EM_Event->event_id);
					$sql = $wpdb->prepare("UPDATE ".EM_EVENTS_TABLE." SET event_name=%s, event_owner=%d, event_slug=%s, event_status={$event_status}, event_private=%d WHERE event_id=%d", $where_array);
					$wpdb->query($sql);
					if( $EM_Event->is_recurring() &&  $EM_Event->is_published()){
						//recurrences are (re)saved only if event is published
						$EM_Event->save_events();
					}
					apply_filters('em_event_save', true, $EM_Event);
				}else{
					do_action('em_event_save_pre', $EM_Event); //technically, the event is saved... but the meta isn't. wp doesn't give an pre-intervention action for this (or does it?)
					//Event doesn't validate, so set status to null
					$EM_Event->set_status(null, true);
					apply_filters('em_event_save', false, $EM_Event);
				}
			}
			self::maybe_publish_location($EM_Event);
		}
	}
	
	/**
	 * Publish the location if the event has just been approved and the location is pending. We assume an editor published the event and approves the location too.
	 * @param EM_Event $EM_Event
	 */
	public static function maybe_publish_location($EM_Event){
		//do a dirty update for location too if it's not published
		if( $EM_Event->is_published() && !empty($EM_Event->location_id) ){
			$EM_Location = $EM_Event->get_location();
			if( $EM_Location->location_status !== 1 ){
				//let's also publish the location
				$EM_Location->set_status(1, true);
			}
		}
	}

	public static function before_delete_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_EVENT){
			$EM_Event = em_get_event($post_id,'post_id');
			do_action('em_event_delete_pre ',$EM_Event);
			$EM_Event->delete_meta();
		}
	}
	
	public static function trashed_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_EVENT){
			global $EM_Notices;
			$EM_Event = em_get_event($post_id,'post_id');
			$EM_Event->set_status(-1);
			$EM_Notices->remove_all(); //no validation/notices needed
		}
	}
	
	public static function untrash_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_EVENT){
			//set a constant so we know this event doesn't need 'saving'
			if(!defined('UNTRASHING_'.$post_id)) define('UNTRASHING_'.$post_id, true);
		}
	}
	
	public static function untrashed_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_EVENT){
			global $EM_Notices, $EM_Event;
			$EM_Event = new EM_Event($post_id, 'post_id'); //get a refreshed $EM_Event because otherwise statuses don't get updated by WP
			$EM_Event->set_status( $EM_Event->get_status() );
			$EM_Notices->remove_all(); //no validation/notices needed
		}
	}
	
	public static function meta_boxes(){
		global $EM_Event, $post;
		//no need to proceed if we're not dealing with an event
		if( $post->post_type != EM_POST_TYPE_EVENT ) return;
		//since this is the first point when the admin area loads event stuff, we load our EM_Event here
		if( empty($EM_Event) && !empty($post) ){
			$EM_Event = em_get_event($post->ID, 'post_id');
		}
		if( !empty($EM_Event->event_owner_anonymous) ){
			add_meta_box('em-event-anonymous', __('Anonymous Submitter Info','events-manager'), array('EM_Event_Post_Admin','meta_box_anonymous'),EM_POST_TYPE_EVENT, 'side','high');
		}
		add_meta_box('em-event-when', __('When','events-manager'), array('EM_Event_Post_Admin','meta_box_date'),EM_POST_TYPE_EVENT, 'side','high');
		if(get_option('dbem_locations_enabled', true)){
			add_meta_box('em-event-where', __('Where','events-manager'), array('EM_Event_Post_Admin','meta_box_location'),EM_POST_TYPE_EVENT, 'normal','high');
		}
		if( defined('WP_DEBUG') && WP_DEBUG ){
			add_meta_box('em-event-meta', 'Event Meta (debugging only)', array('EM_Event_Post_Admin','meta_box_metadump'),EM_POST_TYPE_EVENT, 'normal','high');
		}
		if( get_option('dbem_rsvp_enabled', true) && $EM_Event->can_manage('manage_bookings','manage_others_bookings') ){
			add_meta_box('em-event-bookings', __('Bookings/Registration','events-manager'), array('EM_Event_Post_Admin','meta_box_bookings'),EM_POST_TYPE_EVENT, 'normal','high');
			if( !empty($EM_Event->event_id) && $EM_Event->event_rsvp ){
				add_meta_box('em-event-bookings-stats', __('Bookings Stats','events-manager'), array('EM_Event_Post_Admin','meta_box_bookings_stats'),EM_POST_TYPE_EVENT, 'side','core');
			}
		}
		if( get_option('dbem_attributes_enabled', true) ){
			add_meta_box('em-event-attributes', __('Attributes','events-manager'), array('EM_Event_Post_Admin','meta_box_attributes'),EM_POST_TYPE_EVENT, 'normal','default');
		}
		if( EM_MS_GLOBAL && !is_main_site() && get_option('dbem_categories_enabled') ){
			add_meta_box('em-event-categories', __('Site Categories','events-manager'), array('EM_Event_Post_Admin','meta_box_ms_categories'),EM_POST_TYPE_EVENT, 'side','low');
		}
	}
	
	public static function meta_box_metadump(){
		global $post,$EM_Event;
		echo "<pre>"; print_r($EM_Event); echo "</pre>";
	}
	
	public static function meta_box_anonymous(){
		global $EM_Event;
		?>
		<div class='updated'><p><?php _e('This event was submitted by a guest. You will find their details in the <em>Anonymous Submitter Info</em> box','events-manager')?></p></div>
		<p><strong><?php _e('Name','events-manager'); ?> :</strong> <?php echo $EM_Event->event_owner_name; ?></p> 
		<p><strong><?php _e('Email','events-manager'); ?> :</strong> <?php echo $EM_Event->event_owner_email; ?></p> 
		<?php
	}
	
	public static function meta_box_date(){
		//create meta box check of date nonce
		?><input type="hidden" name="_emnonce" value="<?php echo wp_create_nonce('edit_event'); ?>" /><?php
		em_locate_template('forms/event/when.php', true);
	}
	
	public static function meta_box_bookings_stats(){
		em_locate_template('forms/event/booking-stats.php',true);
	}

	public static function meta_box_bookings(){
		em_locate_template('forms/event/bookings.php', true);
		add_action('admin_footer',array('EM_Event_Post_Admin','meta_box_bookings_overlay'));
	}
	
	public static function meta_box_bookings_overlay(){
		em_locate_template('forms/tickets-form.php', true); //put here as it can't be in the add event form
	}
	
	public static function meta_box_attributes(){
		em_locate_template('forms/event/attributes.php',true);
	}
	
	public static function meta_box_location(){
		em_locate_template('forms/event/location.php',true);
	}
	
	public static function meta_box_ms_categories(){
		global $EM_Event;
		EM_Object::ms_global_switch();
		$categories = EM_Categories::get(array('hide_empty'=>false));
		?>
		<?php if( count($categories) > 0 ): ?>
			<p class="ms-global-categories">
			 <?php $selected = $EM_Event->get_categories()->get_ids(); ?>
			 <?php $walker = new EM_Walker_Category(); ?>
			 <?php $args_em = array( 'hide_empty' => 0, 'name' => 'event_categories[]', 'hierarchical' => true, 'id' => EM_TAXONOMY_CATEGORY, 'taxonomy' => EM_TAXONOMY_CATEGORY, 'selected' => $selected, 'walker'=> $walker); ?>
			 <?php echo walk_category_dropdown_tree($categories, 0, $args_em); ?>
			</p>
		<?php else: ?>
			<p><?php sprintf(__('No categories available, <a href="%s">create one here first</a>','events-manager'), get_bloginfo('wpurl').'/wp-admin/admin.php?page=events-manager-categories'); ?></p>
		<?php endif; ?>
		<!-- END Categories -->
		<?php
		EM_Object::ms_global_switch_back();
	}
}
add_action('admin_init',array('EM_Event_Post_Admin','init'));

/*
 * Recurring Events
 */
class EM_Event_Recurring_Post_Admin{
	public static function init(){
		global $pagenow;
		if($pagenow == 'post.php' || $pagenow == 'post-new.php' ){ //only needed if editing post
			add_action('admin_head', array('EM_Event_Recurring_Post_Admin','admin_head'));
			//Meta Boxes
			add_action('add_meta_boxes', array('EM_Event_Recurring_Post_Admin','meta_boxes'));
			//Notices
			add_action('admin_notices',array('EM_Event_Post_Admin','admin_notices')); //shared with posts
		}
		//Save/Edit actions
		add_action('save_post',array('EM_Event_Recurring_Post_Admin','save_post'),10000,1); //late priority for checking non-EM meta data added later
		add_action('before_delete_post',array('EM_Event_Recurring_Post_Admin','before_delete_post'),10,1);
		add_action('trashed_post',array('EM_Event_Recurring_Post_Admin','trashed_post'),10,1);
		add_action('untrash_post',array('EM_Event_Recurring_Post_Admin','untrash_post'),10,1);
		add_action('untrashed_post',array('EM_Event_Recurring_Post_Admin','untrashed_post'),10,1);
		//Notices
		add_action('post_updated_messages',array('EM_Event_Post_Admin','admin_notices_filter'),1,1); //shared with posts
	}
	
	public static function admin_head(){
		global $post, $EM_Event;
		if( !empty($post) && $post->post_type == 'event-recurring' ){
			$EM_Event = em_get_event($post->ID, 'post_id');
			//quick hacks to make event admin table make more sense for events
			?>
			<script type="text/javascript">
				jQuery(document).ready( function($){
					if(!EM.recurrences_menu){
						$('#menu-posts-'+EM.event_post_type+', #menu-posts-'+EM.event_post_type+' > a').addClass('wp-has-current-submenu');
					}
				});
			</script>
			<?php
		}
	}
	
	/**
	 * Beacuse in wp admin recurrences get saved early on during save_post, meta added by  other plugins to the recurring event template don't get copied over to recurrences
	 * This re-saves meta late in save_post to correct this issue, in the future when recurrences refer to one post, this shouldn't be an issue 
	 * @param int $post_id
	 */
	public static function save_post($post_id){
		global $wpdb, $EM_Notices, $EM_SAVING_EVENT, $EM_EVENT_SAVE_POST;
		if( !empty($EM_SAVING_EVENT) ) return; //never proceed with this if using EM_Event::save(); which only gets executed outside wp admin
		$post_type = get_post_type($post_id);
		$saving_status = !in_array(get_post_status($post_id), array('trash','auto-draft')) && !defined('DOING_AUTOSAVE');
		if(!defined('UNTRASHING_'.$post_id) && $post_type == 'event-recurring' && $saving_status && !empty($EM_EVENT_SAVE_POST) ){
			$EM_Event = em_get_event($post_id, 'post_id');
			$EM_Event->post_type = $post_type;
			//get the list post IDs for recurrences this recurrence
		 	if( !$EM_Event->save_events() && $EM_Event->is_published() ){
				$EM_Event->set_status(null, true);
				$EM_Notices->add_error(__ ( 'Something went wrong with the recurrence update...', 'events-manager'). __ ( 'There was a problem saving the recurring events.', 'events-manager'));
		 	}
		}
		$EM_EVENT_SAVE_POST = false; //last filter of save_post in EM for events
	}

	public static function before_delete_post($post_id){
		if(get_post_type($post_id) == 'event-recurring'){
			$EM_Event = em_get_event($post_id,'post_id');
			do_action('em_event_delete_pre ',$EM_Event);
			//now delete recurrences
			//only delete other events if this isn't a draft-never-published event
			if( !empty($EM_Event->event_id) ){
    			$events_array = EM_Events::get( array('recurrence'=>$EM_Event->event_id, 'scope'=>'all', 'status'=>'everything' ) );
    			foreach($events_array as $event){
    				/* @var $event EM_Event */
    				if($EM_Event->event_id == $event->recurrence_id && !empty($event->recurrence_id) ){ //double check the event is a recurrence of this event
    					wp_delete_post($event->post_id, true);
    				}
    			}
			}
			$EM_Event->post_type = EM_POST_TYPE_EVENT; //trick it into thinking it's one event.
			$EM_Event->delete_meta();
		}
	}
	
	public static function trashed_post($post_id){
		if(get_post_type($post_id) == 'event-recurring'){
			global $EM_Notices, $wpdb;
			$EM_Event = em_get_event($post_id,'post_id');
			$EM_Event->set_status(null);
			//only trash other events if this isn't a draft-never-published event
			if( !empty($EM_Event->event_id) ){
    			//now trash recurrences
    			$events_array = EM_Events::get( array('recurrence_id'=>$EM_Event->event_id, 'scope'=>'all', 'status'=>'everything' ) );
    			foreach($events_array as $event){
    				/* @var $event EM_Event */
    				if($EM_Event->event_id == $event->recurrence_id ){ //double check the event is a recurrence of this event
    					wp_trash_post($event->post_id);
    				}
    			}
			}
			$EM_Notices->remove_all(); //no validation/notices needed
		}
	}
	
	public static function untrash_post($post_id){
		if(get_post_type($post_id) == 'event-recurring'){
			global $wpdb;
			//set a constant so we know this event doesn't need 'saving'
			if(!defined('UNTRASHING_'.$post_id)) define('UNTRASHING_'.$post_id, true);
			$EM_Event = em_get_event($post_id,'post_id');
			//only untrash other events if this isn't a draft-never-published event, because if so it never had other events to untrash
			if( !empty($EM_Event->event_id) ){
    			$events_array = EM_Events::get( array('recurrence_id'=>$EM_Event->event_id, 'scope'=>'all', 'status'=>'everything' ) );
    			foreach($events_array as $event){
    				/* @var $event EM_Event */
    				if($EM_Event->event_id == $event->recurrence_id){
    					wp_untrash_post($event->post_id);
    				}
    			}
			}
		}
	}
	
	public static function untrashed_post($post_id){
		if(get_post_type($post_id) == 'event-recurring'){
			global $EM_Notices,$EM_Event;
			$EM_Event->set_status(1);
			$EM_Notices->remove_all(); //no validation/notices needed
		}
	}
	
	public static function meta_boxes(){
		global $EM_Event, $post;
		//no need to proceed if we're not dealing with a recurring event
		if( $post->post_type != 'event-recurring' ) return;
		//since this is the first point when the admin area loads event stuff, we load our EM_Event here
		if( empty($EM_Event) && !empty($post) ){
			$EM_Event = em_get_event($post->ID, 'post_id');
		}
		add_meta_box('em-event-recurring', __('Recurrences','events-manager'), array('EM_Event_Recurring_Post_Admin','meta_box_recurrence'),'event-recurring', 'normal','high');
		//add_meta_box('em-event-meta', 'Event Meta (debugging only)', array('EM_Event_Post_Admin','meta_box_metadump'),'event-recurring', 'normal','high');
		add_meta_box('em-event-where', __('Where','events-manager'), array('EM_Event_Post_Admin','meta_box_location'),'event-recurring', 'normal','high');
		if( get_option('dbem_rsvp_enabled') && $EM_Event->can_manage('manage_bookings','manage_others_bookings') ){
			add_meta_box('em-event-bookings', __('Bookings/Registration','events-manager'), array('EM_Event_Post_Admin','meta_box_bookings'),'event-recurring', 'normal','high');
		}
		if( get_option('dbem_attributes_enabled') ){
			add_meta_box('em-event-attributes', __('Attributes','events-manager'), array('EM_Event_Post_Admin','meta_box_attributes'),'event-recurring', 'normal','default');
		}
		if( EM_MS_GLOBAL && !is_main_site() && get_option('dbem_categories_enabled') ){
			add_meta_box('em-event-categories', __('Site Categories','events-manager'), array('EM_Event_Post_Admin','meta_box_ms_categories'),'event-recurring', 'side','low');
		}
		if( defined('WP_DEBUG') && WP_DEBUG ){
		    add_meta_box('em-event-meta', 'Event Meta (debugging only)', array('EM_Event_Post_Admin','meta_box_metadump'),'event-recurring', 'normal','high');
		}
	}
	
	public static function meta_box_recurrence(){
		em_locate_template('forms/event/recurring-when.php', true);
	}
}
add_action('admin_init',array('EM_Event_Recurring_Post_Admin','init'));