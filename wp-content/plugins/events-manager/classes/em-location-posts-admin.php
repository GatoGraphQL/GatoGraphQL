<?php
class EM_Location_Posts_Admin{
	public static function init(){
		global $pagenow;
		if($pagenow == 'edit.php' && !empty($_REQUEST['post_type']) && $_REQUEST['post_type'] == EM_POST_TYPE_LOCATION ){ //only needed if editing post
			//hide some cols by default:
			$screen = 'edit-'.EM_POST_TYPE_LOCATION;
			$hidden = get_user_option( 'manage' . $screen . 'columnshidden' );
			if( !$hidden ){
				$hidden = array('location-id');
				update_user_option(get_current_user_id(), "manage{$screen}columnshidden", $hidden, true);
			}
			add_action('admin_head', array('EM_Location_Posts_Admin','admin_head'));
		}
		add_filter('manage_edit-'.EM_POST_TYPE_LOCATION.'_columns' , array('EM_Location_Posts_Admin','columns_add'));
		add_filter('manage_'.EM_POST_TYPE_LOCATION.'_posts_custom_column' , array('EM_Location_Posts_Admin','columns_output'),10,2 );
	}
	
	public static function admin_head(){
		//quick hacks to make event admin table make more sense for events
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($){
				$('.inline-edit-date').prev().css('display','none').next().css('display','none').next().css('display','none');
			});
		</script>
		<style>
			table.fixed{ table-layout:auto !important; }
			.tablenav select[name="m"] { display:none; }
		</style>
		<?php
	}
	
	public static function admin_menu(){
		global $menu, $submenu;
	  	// Add a submenu to the custom top-level menu:
   		$plugin_pages = array(); 
		$plugin_pages[] = add_submenu_page('edit.php?post_type='.EM_POST_TYPE_EVENT, __('Locations', 'events-manager'), __('Locations', 'events-manager'), 'edit_locations', 'events-manager-locations', "edit.php?post_type=event");
		$plugin_pages = apply_filters('em_create_locationss_submenu',$plugin_pages);
	}
	
	public static function columns_add($columns) {
		//prepend ID after checkbox
		if( array_key_exists('cb', $columns) ){
			$cb = $columns['cb'];
	    	unset($columns['cb']);
	    	$id_array = array('cb'=>$cb, 'location-id' => sprintf(__('%s ID','events-manager'),__('Location','events-manager')));
		}else{
	    	$id_array = array('location-id' => sprintf(__('%s ID','events-manager'),__('Location','events-manager')));
		}
	    unset($columns['author']);
	    unset($columns['date']);
	    unset($columns['comments']);
	    return array_merge($id_array, $columns, array(
	    	'address' => __('Address','events-manager'), 
	    	'town' => __('Town','events-manager'),
	    	'state' => __('State','events-manager'),
	    	'country' => __('Country','events-manager') 
	    ));
	}
	
	public static function columns_output( $column ) {
		global $post;
		$post = em_get_location($post); 
		switch ( $column ) {
			case 'location-id':
				echo $post->location_id;
				break;
			case 'address':
				echo $post->location_address;
				break;
			case 'town':
				echo $post->location_town;
				break;
			case 'state':
				echo $post->location_state;
				break;
			case 'country':
				echo $post->location_country;
				break;
		}
	}
}
add_action('admin_init', array('EM_Location_Posts_Admin','init'));