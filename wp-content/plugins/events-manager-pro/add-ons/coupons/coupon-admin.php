<?php
/**
 * Extended EM_Coupon object with administrative functions which can be used in admin situations to add/edit/delete coupons themselves.
 */
class EM_Coupon_Admin extends EM_Coupon {
	
	/**
	 * Retrieve event information via POST (used in situations where posts aren't submitted via WP)
	 * @param boolean $validate whether or not to run validation, default is true
	 * @return boolean
	 */
	function get_post($validate = true){
		do_action('em_coupon_get_post_pre', $this);
		$this->coupon_code = ( !empty($_POST['coupon_code']) ) ? wp_kses($_POST['coupon_code'], array()):'';
		$this->coupon_name = ( !empty($_POST['coupon_name']) ) ? wp_kses($_POST['coupon_name'], array()):'';
		$this->coupon_description = ( !empty($_POST['coupon_description']) ) ? wp_kses($_POST['coupon_description'], array()):'';
		$this->coupon_start = ( !empty($_POST['coupon_start']) ) ? $_POST['coupon_start']:'';
		$this->coupon_end = ( !empty($_POST['coupon_end']) ) ? $_POST['coupon_end']:'';
		$this->coupon_max = ( !empty($_POST['coupon_max']) && is_numeric($_POST['coupon_max']) ) ? $_POST['coupon_max']:'';
		$this->coupon_type = ( !empty($_POST['coupon_type']) ) ? $_POST['coupon_type']:'';
		$this->coupon_tax = ( !empty($_POST['coupon_tax']) && in_array($_POST['coupon_tax'], array('pre','post')) ) ? $_POST['coupon_tax']:'';
		$this->coupon_discount = ( !empty($_POST['coupon_discount']) ) ? $_POST['coupon_discount']:'';
		if( !empty($this->coupon_discount) && $this->coupon_discount < 0 ){ $this->coupon_discount = $this->coupon_discount * -1 ; } //no negatives
		$this->coupon_eventwide = ( !empty($_POST['coupon_availability']) && $_POST['coupon_availability'] == 'eventwide') ? 1:0;
		$this->coupon_sitewide = ( !empty($_POST['coupon_availability']) && $_POST['coupon_availability'] == 'sitewide' && (current_user_can('manage_others_bookings') || is_super_admin()) ) ? 1:0;
		$this->coupon_private = ( !empty($_POST['coupon_private']) ) ? 1:0;
		$result = $validate ? $this->validate():true; //validate both post and meta, otherwise return true
		return apply_filters('em_coupon_get_post', $result, $this);		
	}
	
	/**
	 * Validates a coupon to make sure the submitted fields include required ones
	 * @return boolean
	 */
	function validate(){
		$validate = true;
		foreach( $this->required_fields as $field => $msg){
			if( empty($this->$field) ){
				$validate = false;
				$this->add_error( sprintf(__("%s is required.", "dbem"),$msg) );
			}
		}
		return apply_filters('em_coupon_validate', $validate, $this );		
	}
	
	/**
	 * Saves the coupon to the db
	 * @return boolean
	 */
	function save(){
		global $wpdb;
		if( !$this->can_manage('manage_bookings', 'manage_others_bookings') ){
			return apply_filters('em_coupon_save', false, $this);
		}
		//if in MB mode, always save it as site/event-wide
		if( get_option('dbem_multiple_bookings') ){
		    $this->coupon_sitewide = $this->coupon_eventwide = 1;
		}
		//Set Blog ID
		if( is_multisite() && empty($this->blog_id) ){
			$this->blog_id = get_current_blog_id();
		}
		if( empty($this->coupon_owner) ){ $this->coupon_owner = get_current_user_id(); }
		do_action('em_coupon_save_pre', $this);
		$coupon_array = $this->to_array(true);
		$just_added_coupon = false;
		if( empty($this->coupon_id) ){
			unset($coupon_array['coupon_id']);
			if ( !$wpdb->insert(EM_COUPONS_TABLE, $coupon_array) ){
				$this->add_error( sprintf(__('Something went wrong saving your %s.','em-pro'),__('Coupon','em-pro')));
			}else{
				//success, so link the coupon with the post via an coupon id meta value for easy retrieval
				$this->coupon_id = $wpdb->insert_id;
				$this->feedback_message = sprintf(__('Successfully saved %s','em-pro'),__('Coupon','em-pro'));
				do_action('em_coupon_save_new', $this);
			}
		}else{
			if ( $wpdb->update(EM_COUPONS_TABLE, $coupon_array, array('coupon_id'=>$this->coupon_id) ) === false ){
				$this->add_error( sprintf(__('Something went wrong updating your %s.','em-pro'),__('Coupon','em-pro')));			
			}else{
				$this->feedback_message = sprintf(__('Successfully saved %s','em-pro'),__('Coupon','em-pro'));
			}		
		}
		$this->id = $this->coupon_id;
		$this->owner = $this->coupon_owner;
		return apply_filters('em_coupon_save', count($this->errors) == 0, $this, $just_added_coupon);
	}
	
	/**
	 * Deletes the coupon
	 * @param boolean $force_delete
	 * @return boolean
	 */
	function delete($force_delete = true){ //atm wp seems to force cp deletions anyway
		global $wpdb;
		$result = false;
		if( $this->can_manage('manage_bookings','manage_others_bookings') ){
			do_action('em_coupon_delete_pre', $this);
			$result = $wpdb->query("DELETE FROM ".EM_COUPONS_TABLE." WHERE coupon_id=".$this->coupon_id);
			$total = $wpdb->query("DELETE FROM ".EM_META_TABLE." WHERE meta_key='coupon-count' AND object_id={$this->coupon_id} LIMIT 1");
			$this->feedback_message = sprintf(__('Successfully deleted %s','em-pro'),__('Coupon','em-pro'));
		}
		return apply_filters('em_coupon_delete', $result !== false, $this);
	}	
}