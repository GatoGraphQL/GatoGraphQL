<?php
/**
 * Object that holds coupon info and related functions
 * @author marcus
 */
class EM_Coupon extends EM_Object {
	//DB Fields
	var $coupon_id = '';
	var $coupon_owner = '';
	var $blog_id = '';
	var $coupon_code = '';
	var $coupon_name = '';
	var $coupon_description = '';
	var $coupon_start;
	var $coupon_end;
	var $coupon_max;
	var $coupon_type = '';
	var $coupon_discount = 0;
	var $coupon_eventwide = false;
	var $coupon_sitewide = false;
	var $coupon_private = false;
	//Other Vars
	var $fields = array( 
		'coupon_id' => array('name'=>'id','type'=>'%d'),
		'coupon_owner' => array('name'=>'owner','type'=>'%d'),
		'blog_id' => array('name'=>'blog_id','type'=>'%d', 'null'=>true),
		'coupon_code' => array('name'=>'name','type'=>'%s'), 
		'coupon_name' => array('name'=>'name','type'=>'%s'), 
		'coupon_description' => array('name'=>'description','type'=>'%s','null'=>true),
		'coupon_start' =>  array('name'=>'start','type'=>'%s','null'=>true),
		'coupon_end' => array('name'=>'end','type'=>'%s','null'=>true),
		'coupon_max' => array('name'=>'max','type'=>'%d', 'null'=>true),
		'coupon_type' => array('name'=>'type','type'=>'%s'),
		'coupon_discount' => array('name'=>'discount','type'=>'%f'),
		'coupon_private' => array('name'=>'private','type'=>'%d', 'null'=>true),
		'coupon_eventwide' => array('name'=>'eventwide','type'=>'%d', 'null'=>true),
		'coupon_sitewide' => array('name'=>'sitewide','type'=>'%d', 'null'=>true),
	);
	var $required_fields = array();
	var $feedback_message = ""; 
	var $errors = array();
	
	
	/**
	 * Gets data from POST (default), supplied array, or from the database if an ID is supplied
	 * @param $coupon_data
	 * @param $search_by can be set to post_id or a number for a blog id if in ms mode with global tables, default is coupon_id
	 * @return null
	 */
	function __construct($id = false, $search_by = 'id') {
		global $wpdb;
		//Initialize
		$this->required_fields = array("coupon_name" => __('Name', 'em-pro'), "coupon_discount" => __('Discount', 'em-pro'), "coupon_code" => __('Code', 'em-pro'));
		//Get the array/coupon_id
		if( is_numeric($id) && $search_by == 'id' ){
			//search by coupon_id, get post_id and blog_id (if in ms mode) and load the post
			$coupon = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".EM_COUPONS_TABLE." WHERE coupon_id=%d",$id), ARRAY_A);
		}elseif( is_numeric($id) && $search_by == 'code' ){
			//search by coupon_id, get post_id and blog_id (if in ms mode) and load the post
			$coupon = $wpdb->get_row($wpdb->prepare("SELECT * FROM ".EM_COUPONS_TABLE." WHERE coupon_code='%s'",$id), ARRAY_A);
		}elseif( is_array($id) ){
			$coupon = $id;
		}
		if( !empty($coupon) && is_array($coupon) && !empty($coupon['coupon_code']) ){
			foreach( $coupon as $key => $value ){ //merge the post data into coupon object
				$this->$key = $value;
			}
		}
		$this->id = $this->coupon_id;
		$this->owner = $this->coupon_owner;
		do_action('em_coupon', $this, $id);
	}
	
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
		$this->coupon_discount = ( !empty($_POST['coupon_discount']) ) ? $_POST['coupon_discount']:'';
		$this->coupon_eventwide = ( !empty($_POST['coupon_eventwide']) ) ? 1:0;
		$this->coupon_sitewide = ( !empty($_POST['coupon_sitewide']) ) ? 1:0;
		$this->coupon_private = ( !empty($_POST['coupon_private']) ) ? 1:0;
		$result = $validate ? $this->validate():true; //validate both post and meta, otherwise return true
		return apply_filters('em_coupon_get_post', $result, $this);		
	}
	
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
	
	function save(){
		global $wpdb;
		if( !$this->can_manage('manage_bookings', 'manage_others_bookings') ){
			return apply_filters('em_coupon_save', false, $this);
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
	
	function delete($force_delete = true){ //atm wp seems to force cp deletions anyway
		global $wpdb;
		$result = false;
		if( $this->can_manage('manage_bookings','manage_others_bookings') ){
			do_action('em_coupon_delete_pre', $this);
			$result = $wpdb->query("DELETE FROM ".EM_COUPONS_TABLE." WHERE coupon_id=".$this->coupon_id);
			$this->feedback_message = sprintf(__('Successfully deleted %s','em-pro'),__('Coupon','em-pro'));
		}
		return apply_filters('em_coupon_delete', $result !== false, $this);
	}	
	
	function apply_discount($price){
		switch($this->coupon_type){
			case '%':
				//discount by percent
				$price -= $price * ($this->coupon_discount / 100);
				break;
			case '#' :
				//discount by price
				$price -= $this->coupon_discount;
				if( $price < 0 ) $price = 0; //no negative values
				break;
		}
		return $price;
	}
	
	function get_discount($price){
		return $price - $this->apply_discount($price);
	}
	
	function get_person(){
		global $EM_Person;
		if( is_object($this->person) && get_class($this->person)=='EM_Person' && ($this->person->ID == $this->coupon_owner || empty($this->coupon_owner) ) ){
			//This person is already included, so don't do anything
		}elseif( is_object($EM_Person) && ($EM_Person->ID === $this->coupon_owner || $this->coupon_id == '') ){
			$this->person = $EM_Person;
		}elseif( is_numeric($this->coupon_owner) ){
			$this->person = new EM_Person($this->coupon_owner);
		}else{
			$this->person = new EM_Person(0);
		}
		return apply_filters('em_booking_get_person', $this->person, $this);
	}
	
	/**
	 * Returns boolean depending whether this coupon is valid right now (i.e. meets time/capacity requirements)
	 * @return boolean
	 */
	function is_valid(){
		if( !empty($this->coupon_end) && current_time('timestamp') > strtotime($this->coupon_end) ){
			return false;
		}elseif( !empty($this->coupon_start) && current_time('timestamp') < strtotime($this->coupon_start) ){
			return false;
		}
		if( !empty($this->coupon_max) && $this->get_count() >= $this->coupon_max ){
			return false;			
		}
		if( $this->coupon_private && !is_user_logged_in() ){
			return false;
		}
		//check min/max values
		return true;
	}
	
	function get_count(){
		global $wpdb;
		$total = $wpdb->get_var("SELECT meta_value FROM ".EM_META_TABLE." WHERE meta_key='coupon-count' AND object_id={$this->coupon_id} LIMIT 1");
		if( !$total ) $total = 0;
		return $total;		
	}
	
	/**
	 * Puts the coupon into a text representation in terms of discount
	 */
	function get_discount_text(){
		$text = "";
		switch($this->coupon_type){
			case '%':
				//discount by percent
				$text = sprintf(__('%s Off','em-pro'), '%'.number_format($this->coupon_discount, 2));
				break;
			case '#' :
				//discount by price
				$text = sprintf(__('%s Off','em-pro'), em_get_currency_formatted($this->coupon_discount));
				break;
		}
		return $text;
	}
	
	function has_events(){
		global $wpdb;
		$sql = "SELECT count(object_id) as events_no FROM ".EM_META_TABLE." WHERE meta_value = {$this->coupon_id}";   
	 	$affected_events = $wpdb->get_row($sql);
		return apply_filters('em_coupon_has_events', (count($affected_events) > 0), $this);
	}
	
	/**
	 * Can the user manage this coupon? 
	 */
	function can_manage( $owner_capability = false, $admin_capability = false, $user_to_check = false ){
		return apply_filters('em_coupon_can_manage', parent::can_manage($owner_capability, $admin_capability, $user_to_check), $this, $owner_capability, $admin_capability, $user_to_check);
	}
}