<?php
class EM_Location_Post_Admin{
	public static function init(){
		global $pagenow;
		if($pagenow == 'post.php' || $pagenow == 'post-new.php' ){ //only needed if editing post
			add_action('admin_head', array('EM_Location_Post_Admin','admin_head'));
		}
		//Meta Boxes
		add_action('add_meta_boxes', array('EM_Location_Post_Admin','meta_boxes'));
		//Save/Edit actions
		add_filter('wp_insert_post_data',array('EM_Location_Post_Admin','wp_insert_post_data'),100,2); //validate post meta before saving is done
		add_action('save_post',array('EM_Location_Post_Admin','save_post'),1,1); //set to 1 so metadata gets saved ASAP
		add_action('before_delete_post',array('EM_Location_Post_Admin','before_delete_post'),10,1);
		add_action('trashed_post',array('EM_Location_Post_Admin','trashed_post'),10,1);
		add_action('untrash_post',array('EM_Location_Post_Admin','untrash_post'),10,1);
		add_action('untrashed_post',array('EM_Location_Post_Admin','untrashed_post'),10,1);
		//Notices
		add_action('admin_notices',array('EM_Location_Post_Admin','admin_notices'));
		add_action('post_updated_messages',array('EM_Location_Post_Admin','admin_notices_filter'),1,1);
	}
	
	public static function admin_head(){
		global $post, $EM_Location;
		if( !empty($post) && $post->post_type == EM_POST_TYPE_LOCATION ){
			$EM_Location = em_get_location($post);
		}
	}
	
	public static function admin_notices(){
		//When editing
		global $post, $EM_Notices;
		if( !empty($post) && $post->post_type == EM_POST_TYPE_LOCATION){
		}
	}
	
	public static function admin_notices_filter($messages){
		//When editing
		global $post, $EM_Notices;
		if( $post->post_type == EM_POST_TYPE_LOCATION ){
			if ( $EM_Notices->count_errors() > 0 ) {
				unset($_GET['message']);
			}
		}
		return $messages;
	}
	
	/**
	 * Hooks in just before a post is saves and does a quick post meta validation. 
	 * This prevents the location from being temporarily published and firing hooks that indicate this before we come in on save_post and properly save data.
	 * @param array $data
	 * @param array $postarr
	 * @return array
	 */
	public static function wp_insert_post_data( $data, $postarr ){
		global $wpdb, $EM_Event, $EM_Location, $EM_Notices, $EM_SAVING_LOCATION;
		if( !empty($EM_SAVING_LOCATION) ) return $data; //If we're saving a location via EM_Location::save() we should never run the below
		$post_type = $data['post_type'];
		$post_ID = !empty($postarr['ID']) ? $postarr['ID'] : false;
		$is_post_type = $post_type == EM_POST_TYPE_LOCATION;
		$saving_status = !in_array($data['post_status'], array('trash','auto-draft')) && !defined('DOING_AUTOSAVE');
		$untrashing = $post_ID && defined('UNTRASHING_'.$post_ID);
		if( !$untrashing && $is_post_type && $saving_status ){
			if( !empty($_REQUEST['_emnonce']) && wp_verify_nonce($_REQUEST['_emnonce'], 'edit_location') ){ 
				//this is only run if we know form data was submitted, hence the nonce
				$EM_Location = em_get_location($post_ID, 'post_id');
				$EM_Location->post_type = $post_type;
				//Handle Errors by making post draft
				$get_meta = $EM_Location->get_post_meta(false);
				$validate_meta = $EM_Location->validate_meta();
				if( !$get_meta || !$validate_meta ) $data['post_status'] = 'draft';
			}
		}
		return $data;
	}
	
	/**
	 * Once the post is saved, saves EM meta data
	 * @param int $post_id
	 */
	public static function save_post($post_id){
		global $wpdb, $EM_Location, $EM_Notices, $EM_SAVING_LOCATION;
		if( !empty($EM_SAVING_LOCATION) ) return; //If we're saving a location via EM_Location::save() we should never run the below
		$saving_status = !in_array(get_post_status($post_id), array('trash','auto-draft')) && !defined('DOING_AUTOSAVE');
		$is_post_type = get_post_type($post_id) == EM_POST_TYPE_LOCATION;
		if(!defined('UNTRASHING_'.$post_id) && $is_post_type && $saving_status){
			if( !empty($_REQUEST['_emnonce']) && wp_verify_nonce($_REQUEST['_emnonce'], 'edit_location')){
				$EM_Location = em_get_location($post_id, 'post_id');
				$get_meta = $EM_Location->get_post_meta(false);
				$validate_meta = $EM_Location->validate_meta();
				do_action('em_location_save_pre', $EM_Location);
				$save_meta = $EM_Location->save_meta();
				//Handle Errors by making post draft
				if( !$get_meta || !$validate_meta || !$save_meta ){
					$EM_Location->set_status(null, true);
					$EM_Notices->add_error( '<strong>'.sprintf(__('Your %s details are incorrect and cannot be published, please correct these errors first:','events-manager'),__('location','events-manager')).'</strong>', true); //Always seems to redirect, so we make it static
					$EM_Notices->add_error($EM_Location->get_errors(), true); //Always seems to redirect, so we make it static
					apply_filters('em_location_save', false , $EM_Location);
				}else{
					apply_filters('em_location_save', true , $EM_Location);
				}
			}else{
				//do a quick and dirty update
				$EM_Location = new EM_Location($post_id, 'post_id');
				do_action('em_location_save_pre', $EM_Location);
				//check for existence of index
				$loc_truly_exists = $EM_Location->location_id > 0 && $wpdb->get_var('SELECT location_id FROM '.EM_LOCATIONS_TABLE." WHERE location_id={$EM_Location->location_id}") == $EM_Location->location_id;
				if(empty($EM_Location->location_id) || !$loc_truly_exists){ $EM_Location->save_meta(); }
				//continue
				$EM_Location->get_previous_status(); //before we save anything
				$location_status = $EM_Location->get_status(true);
				$where_array = array($EM_Location->location_name, $EM_Location->location_owner, $EM_Location->location_slug, $EM_Location->location_private, $EM_Location->location_id);
				$sql = $wpdb->prepare("UPDATE ".EM_LOCATIONS_TABLE." SET location_name=%s, location_owner=%s, location_slug=%s, location_private=%d, location_status={$location_status} WHERE location_id=%d", $where_array);
				$wpdb->query($sql);
				apply_filters('em_location_save', true , $EM_Location);
			}
		}
	}

	public static function before_delete_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_LOCATION){
			$EM_Location = em_get_location($post_id,'post_id');
			$EM_Location->delete_meta();
		}
	}
	
	public static function trashed_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_LOCATION){
			global $EM_Notices;
			$EM_Location = em_get_location($post_id,'post_id');
			$EM_Location->set_status(-1);
			$EM_Notices->remove_all(); //no validation/notices needed
		}
	}
	
	public static function untrash_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_LOCATION){
			//set a constant so we know this event doesn't need 'saving'
			if(!defined('UNTRASHING_'.$post_id)) define('UNTRASHING_'.$post_id, true);
		}
	}
	
	public static function untrashed_post($post_id){
		if(get_post_type($post_id) == EM_POST_TYPE_LOCATION){
			global $EM_Notices;
			$EM_Location = new EM_Location($post_id,'post_id');
			$EM_Location->set_status($EM_Location->get_status());
			$EM_Notices->remove_all(); //no validation/notices needed
		}
	}
	
	public static function meta_boxes(){
		global $EM_Location, $post;
		//no need to proceed if we're not dealing with a location
		if( $post->post_type != EM_POST_TYPE_LOCATION ) return;
		//since this is the first point when the admin area loads location stuff, we load our EM_Event here
		if( empty($EM_Location) && !empty($post) ){
			$EM_Location = em_get_location($post->ID, 'post_id');
		}
		add_meta_box('em-location-where', __('Where','events-manager'), array('EM_Location_Post_Admin','meta_box_where'),EM_POST_TYPE_LOCATION, 'normal','high');
		//add_meta_box('em-location-metadump', __('EM_Location Meta Dump','events-manager'), array('EM_Location_Post_Admin','meta_box_metadump'),EM_POST_TYPE_LOCATION, 'normal','high');
		if( get_option('dbem_location_attributes_enabled') ){
			add_meta_box('em-location-attributes', __('Attributes','events-manager'), array('EM_Location_Post_Admin','meta_box_attributes'),EM_POST_TYPE_LOCATION, 'normal','default');
		}
	}
	
	public static function meta_box_metadump(){
		global $post,$EM_Location;
		echo "<pre>"; print_r(get_post_custom($post->ID)); echo "</pre>";
		echo "<pre>"; print_r($EM_Location); echo "</pre>";
	}
	public static function meta_box_where(){
		?><input type="hidden" name="_emnonce" value="<?php echo wp_create_nonce('edit_location'); ?>" /><?php
		em_locate_template('forms/location/where.php',true);		
	}
	
	public static function meta_box_attributes(){
		em_locate_template('forms/location/attributes.php',true);
	}
}
add_action('admin_init',array('EM_Location_Post_Admin','init'));