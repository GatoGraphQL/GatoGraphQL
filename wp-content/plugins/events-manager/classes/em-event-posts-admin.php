<?php
class EM_Event_Posts_Admin{
	public static function init(){
		global $pagenow;
		if( $pagenow == 'edit.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] == EM_POST_TYPE_EVENT ){ //only needed for events list
			if( !empty($_REQUEST['category_id']) && is_numeric($_REQUEST['category_id']) ){
				$term = get_term_by('id', $_REQUEST['category_id'], EM_TAXONOMY_CATEGORY);
				if( !empty($term->slug) ){
					$_REQUEST['category_id'] = $term->slug;
				}
			}
			//hide some cols by default:
			$screen = 'edit-'.EM_POST_TYPE_EVENT;
			$hidden = get_user_option( 'manage' . $screen . 'columnshidden' );
			if( !$hidden ){
				$hidden = array('event-id');
				update_user_option(get_current_user_id(), "manage{$screen}columnshidden", $hidden, true);
			}
			//deal with actions
			$row_action_type = is_post_type_hierarchical( EM_POST_TYPE_EVENT ) ? 'page_row_actions' : 'post_row_actions';
			add_filter($row_action_type, array('EM_Event_Posts_Admin','row_actions'),10,2);
			add_action('admin_head', array('EM_Event_Posts_Admin','admin_head'));
			//collumns
			add_filter('manage_edit-'.EM_POST_TYPE_EVENT.'_columns' , array('EM_Event_Posts_Admin','columns_add'));
			add_filter('manage_'.EM_POST_TYPE_EVENT.'_posts_custom_column' , array('EM_Event_Posts_Admin','columns_output'),10,2 );
		}
		//clean up the views in the admin selection area - WIP
		//add_filter('views_edit-'.EM_POST_TYPE_EVENT, array('EM_Event_Posts_Admin','restrict_views'),10,2);
		//add_filter('views_edit-event-recurring', array('EM_Event_Posts_Admin','restrict_views'),10,2);
		//add filters to event post list tables
		add_action('restrict_manage_posts', array('EM_Event_Posts_Admin','restrict_manage_posts'));
	}
	
	public static function admin_head(){
		//quick hacks to make event admin table make more sense for events
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($){
				$('.inline-edit-date').prev().css('display','none').next().css('display','none').next().css('display','none');
				$('.em-detach-link').click(function( event ){
					if( !confirm(EM.event_detach_warning) ){
						event.preventDefault();
						return false;
					}
				});
				$('.em-delete-recurrence-link').click(function( event ){
					if( !confirm(EM.delete_recurrence_warning) ){
						event.preventDefault();
						return false;
					}
				});
			});
		</script>
		<style>
			table.fixed{ table-layout:auto !important; }
			.tablenav select[name="m"] { display:none; }
		</style>
		<?php
	}
	
	/**
	 * Handles WP_Query filter option in the admin area, which gets executed before EM_Event_Post::parse_query
	 * Not yet in use 
	 */
	public static function parse_query(){
		global $wp_query;
		//Search Query Filtering
	    if( !empty($wp_query->query_vars[EM_TAXONOMY_CATEGORY]) && is_numeric($wp_query->query_vars[EM_TAXONOMY_CATEGORY]) ){
	        //sorts out filtering admin-side as it searches by id
	        $term = get_term_by('id', $wp_query->query_vars[EM_TAXONOMY_CATEGORY], EM_TAXONOMY_CATEGORY);
	        $wp_query->query_vars[EM_TAXONOMY_CATEGORY] = ( $term !== false && !is_wp_error($term) )? $term->slug:0;
	    }
		if( !empty($wp_query->query_vars['post_type']) && ($wp_query->query_vars['post_type'] == EM_POST_TYPE_EVENT || $wp_query->query_vars['post_type'] == 'event-recurring') && (empty($wp_query->query_vars['post_status']) || !in_array($wp_query->query_vars['post_status'],array('trash','pending','draft'))) ) {
		    //Set up Scope for EM_Event_Post
			$scope = $wp_query->query_vars['scope'] = (!empty($_REQUEST['scope'])) ? $_REQUEST['scope']:'future';
		}
	}
	
	/**
	 * Adds Future view to make things simpler, and also changes counts if user doesn't have edit_others_events permission
	 * @param array $views
	 * @return array
	 */
	public static function restrict_views( $views ){
		global $wp_query;
		//TODO alter views of locations, events and recurrences, specifically find a good way to alter the wp_count_posts method to force user owned posts only
		$post_type = get_current_screen()->post_type;
		if( in_array($post_type, array(EM_POST_TYPE_EVENT, 'event-recurring')) ){
			//get counts for future events
			$num_posts = wp_count_posts( $post_type, 'readable' );
			//prepare to alter cache if neccessary
			if( !isset($num_posts->em_future) ){
				$cache_key = $post_type;
				$user = wp_get_current_user();
				if ( is_user_logged_in() && !current_user_can('read_private_events') ) {
					$cache_key .= '_readable_' . $user->ID; //as seen on wp_count_posts
				}
				$args = array('scope'=>'future', 'status'=>'all');
				if( $post_type == 'event-recurring' ) $args['recurring'] = 1;
				$num_posts->em_future = EM_Events::count($args);
				wp_cache_set($cache_key, $num_posts, 'counts');
			}
			$class = '';
			//highlight the 'Future' status if necessary
			if( empty($_REQUEST['post_status']) && !empty($wp_query->query_vars['scope']) && $wp_query->query_vars['scope'] == 'future'){
				$class = ' class="current"';
				foreach($views as $key => $view){
					$views[$key] = str_replace(' class="current"','', $view);
				}
			}
			//change the 'All' status to have scope=all
			$views['all'] = str_replace('edit.php?', 'edit.php?scope=all&', $views['all'] );
			//merge new custom status into views
			$old_views = $views;
			$views = array('em_future' => "<a href='edit.php?post_type=$post_type'$class>" . sprintf( _nx( 'Future <span class="count">(%s)</span>', 'Future <span class="count">(%s)</span>', $num_posts->em_future, 'events', 'events-manager'), number_format_i18n( $num_posts->em_future ) ) . '</a>');
			$views = array_merge($views, $old_views);
		}
		
		return $views;
	}
	
	public static function restrict_manage_posts(){
		global $wp_query;
		if( $wp_query->query_vars['post_type'] == EM_POST_TYPE_EVENT || $wp_query->query_vars['post_type'] == 'event-recurring' ){
			?>
			<select name="scope">
				<?php
				$scope = (!empty($wp_query->query_vars['scope'])) ? $wp_query->query_vars['scope']:'future';
				foreach ( em_get_scopes() as $key => $value ) {
					$selected = "";
					if ($key == $scope)
						$selected = "selected='selected'";
					echo "<option value='$key' $selected>$value</option>  ";
				}
				?>
			</select>
			<?php
			if( get_option('dbem_categories_enabled') ){
				//Categories
	            $selected = !empty($_GET['event-categories']) ? $_GET['event-categories'] : 0;
				wp_dropdown_categories(array( 'hide_empty' => 1, 'name' => EM_TAXONOMY_CATEGORY,
                              'hierarchical' => true, 'orderby'=>'name', 'id' => EM_TAXONOMY_CATEGORY,
                              'taxonomy' => EM_TAXONOMY_CATEGORY, 'selected' => $selected,
                              'show_option_all' => __('View all categories')));
			}
            if( !empty($_REQUEST['author']) ){
            	?>
            	<input type="hidden" name="author" value="<?php echo esc_attr($_REQUEST['author']); ?>" />
            	<?php            	
            }
		}
	}
	
	public static function views($views){
		if( !current_user_can('edit_others_events') ){
			//alter the views to reflect correct numbering
			 
		}
		return $views;
	}
	
	public static function columns_add($columns) {
		if( array_key_exists('cb', $columns) ){
			$cb = $columns['cb'];
	    	unset($columns['cb']);
	    	$id_array = array('cb'=>$cb, 'event-id' => sprintf(__('%s ID','events-manager'),__('Event','events-manager')));
		}else{
	    	$id_array = array('event-id' => sprintf(__('%s ID','events-manager'),__('Event','events-manager')));
		}
	    unset($columns['comments']);
	    unset($columns['date']);
	    unset($columns['author']);
	    $columns = array_merge($id_array, $columns, array(
	    	'location' => __('Location','events-manager'),
	    	'date-time' => __('Date and Time','events-manager'),
	    	'author' => __('Owner','events-manager'),
	    	'extra' => ''
	    ));
	    if( !get_option('dbem_locations_enabled') ){
	    	unset($columns['location']);
	    }
	    return $columns;
	}
	
	public static function columns_output( $column ) {
		global $post, $EM_Event;
		$EM_Event = em_get_event($post, 'post_id');
		/* @var $post EM_Event */
		switch ( $column ) {
			case 'event-id':
				echo $EM_Event->event_id;
				break;
			case 'location':
				//get meta value to see if post has location, otherwise
				$EM_Location = $EM_Event->get_location();
				if( !empty($EM_Location->location_id) ){
					echo "<strong>" . $EM_Location->location_name . "</strong><br/>" . $EM_Location->location_address . " - " . $EM_Location->location_town;
				}else{
					echo __('None','events-manager');
				}
				break;
			case 'date-time':
				//get meta value to see if post has location, otherwise
				$localised_start_date = date_i18n(get_option('date_format'), $EM_Event->start);
				$localised_end_date = date_i18n(get_option('date_format'), $EM_Event->end);
				echo $localised_start_date;
				echo ($localised_end_date != $localised_start_date) ? " - $localised_end_date":'';
				echo "<br />";
				if(!$EM_Event->event_all_day){
					echo date_i18n(get_option('time_format'), $EM_Event->start) . " - " . date_i18n(get_option('time_format'), $EM_Event->end);
				}else{
					echo get_option('dbem_event_all_day_message');
				}
				break;
			case 'extra':
				if( get_option('dbem_rsvp_enabled') == 1 && !empty($EM_Event->event_rsvp) && $EM_Event->can_manage('manage_bookings','manage_others_bookings')){
					?>
					<a href="<?php echo $EM_Event->get_bookings_url(); ?>"><?php echo __("Bookings",'events-manager'); ?></a> &ndash;
					<?php _e("Booked",'events-manager'); ?>: <?php echo $EM_Event->get_bookings()->get_booked_spaces()."/".$EM_Event->get_spaces(); ?>
					<?php if( get_option('dbem_bookings_approval') == 1 ): ?>
						| <?php _e("Pending",'events-manager') ?>: <?php echo $EM_Event->get_bookings()->get_pending_spaces(); ?>
					<?php endif;
					echo ($EM_Event->is_recurrence()) ? '<br />':'';
				}
				if ( $EM_Event->is_recurrence() && $EM_Event->can_manage('edit_recurring_events','edit_others_recurring_events') ) {
					$recurrence_delete_confirm = __('WARNING! You will delete ALL recurrences of this event, including booking history associated with any event in this recurrence. To keep booking information, go to the relevant single event and save it to detach it from this recurrence series.','events-manager');
					?>
					<strong>
					<?php echo $EM_Event->get_recurrence_description(); ?> <br />
					</strong>
					<div class="row-actions">
						<a href="<?php echo admin_url(); ?>post.php?action=edit&amp;post=<?php echo $EM_Event->get_event_recurrence()->post_id ?>"><?php _e ( 'Edit Recurring Events', 'events-manager'); ?></a> | <span class="trash"><a class="em-delete-recurrence-link" href="<?php echo get_delete_post_link($EM_Event->get_event_recurrence()->post_id); ?>"><?php _e('Delete','events-manager'); ?></a></span> | <a class="em-detach-link" href="<?php echo $EM_Event->get_detach_url(); ?>"><?php _e('Detach', 'events-manager'); ?></a>
					</div>
					<?php
				}
				
				break;
		}
	}
	
	public static function row_actions($actions, $post){
		if($post->post_type == EM_POST_TYPE_EVENT){
			global $post, $EM_Event;
			$EM_Event = em_get_event($post, 'post_id');
			$actions['duplicate'] = '<a href="'.$EM_Event->duplicate_url().'" title="'.sprintf(__('Duplicate %s','events-manager'), __('Event','events-manager')).'">'.__('Duplicate','events-manager').'</a>';
		}
		return $actions;
	}
}
add_action('admin_init', array('EM_Event_Posts_Admin','init'));

/*
 * Recurring Events
 */
class EM_Event_Recurring_Posts_Admin{
	public static function init(){
		global $pagenow;
		if( $pagenow == 'edit.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] == 'event-recurring' ){
			//hide some cols by default:
			$screen = 'edit-'.EM_POST_TYPE_EVENT;
			$hidden = get_user_option( 'manage' . $screen . 'columnshidden' );
			if( !$hidden ){
				$hidden = array('event-id');
				update_user_option(get_current_user_id(), "manage{$screen}columnshidden", $hidden, true);
			}
			//notices			
			add_action('admin_notices',array('EM_Event_Recurring_Posts_Admin','admin_notices'));
			add_action('admin_head', array('EM_Event_Recurring_Posts_Admin','admin_head'));
			//collumns
			add_filter('manage_edit-event-recurring_columns' , array('EM_Event_Recurring_Posts_Admin','columns_add'));
			add_filter('manage_posts_custom_column' , array('EM_Event_Recurring_Posts_Admin','columns_output'),10,1 );
			add_action('restrict_manage_posts', array('EM_Event_Posts_Admin','restrict_manage_posts'));
		}
	}
	
	public static function admin_notices(){
		$warning = sprintf(__( 'Modifications to these events will cause all recurrences of each event to be deleted and recreated and previous bookings will be deleted! You can edit individual recurrences and detach them from recurring events by visiting the <a href="%s">events page</a>.', 'events-manager'), admin_url().'edit.php?post_type='.EM_POST_TYPE_EVENT);
		?><div class="updated"><p><?php echo $warning; ?></p></div><?php
	}
	
	public static function admin_head(){
		//quick hacks to make event admin table make more sense for events
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($){
				$('.inline-edit-date').prev().css('display','none').next().css('display','none').next().css('display','none');
				if(!EM.recurrences_menu){
					$('#menu-posts-'+EM.event_post_type+', #menu-posts-'+EM.event_post_type+' > a').addClass('wp-has-current-submenu');
				}
			});
		</script>
		<style>
			table.fixed{ table-layout:auto !important; }
			.tablenav select[name="m"] { display:none; }
		</style>
		<?php
	}
	
	public static function columns_add($columns) {
		if( array_key_exists('cb', $columns) ){
			$cb = $columns['cb'];
	    	unset($columns['cb']);
	    	$id_array = array('cb'=>$cb, 'event-id' => sprintf(__('%s ID','events-manager'),__('Event','events-manager')));
		}else{
	    	$id_array = array('event-id' => sprintf(__('%s ID','events-manager'),__('Event','events-manager')));
		}
	    unset($columns['comments']);
	    unset($columns['date']);
	    unset($columns['author']);
	    return array_merge($id_array, $columns, array(
	    	'location' => __('Location','events-manager'),
	    	'date-time' => __('Date and Time','events-manager'),
	    	'author' => __('Owner','events-manager'),
	    ));
	}

	
	public static function columns_output( $column ) {
		global $post, $EM_Event;
		if( $post->post_type == 'event-recurring' ){
			$post = $EM_Event = em_get_event($post);
			/* @var $post EM_Event */
			switch ( $column ) {
				case 'event-id':
					echo $EM_Event->event_id;
					break;
				case 'location':
					//get meta value to see if post has location, otherwise
					$EM_Location = $EM_Event->get_location();
					if( !empty($EM_Location->location_id) ){
						echo "<strong>" . $EM_Location->location_name . "</strong><br/>" . $EM_Location->location_address . " - " . $EM_Location->location_town;
					}else{
						echo __('None','events-manager');
					}
					break;
				case 'date-time':
					echo $EM_Event->get_recurrence_description();
					break;
			}
		}
	}	
}
add_action('admin_init', array('EM_Event_Recurring_Posts_Admin','init'));