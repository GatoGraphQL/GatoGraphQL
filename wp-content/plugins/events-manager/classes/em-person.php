<?php
// TODO make person details more secure and integrate with WP user data 
class EM_Person extends WP_User{
	
	function __construct( $person_id = 0, $username = '', $blog_id='' ){
		if( is_array($person_id) ){
			if( array_key_exists('person_id',$person_id) ){
				$person_id = $person_id['person_id'];
			}elseif ( array_key_exists('user_id',$person_id) ){
				$person_id = $person_id['user_id'];
			}else{
				$person_id = $person_id['ID'];
			}
		}elseif( is_object($person_id) && get_class($person_id) == 'WP_User'){
			$person_id = $person_id->ID; //create new object if passed a wp_user
		}
		if($username){
			parent::__construct($person_id, $username);
		}elseif( is_numeric($person_id) && ($person_id <= 0) ){
			$this->data = new stdClass();
			$this->ID = 0;
			$this->display_name = 'Non-Registered User';
			$this->user_email = '';
		}else{
			parent::__construct($person_id);
		}
		$this->phone = wp_kses_data(get_metadata('user', $this->ID, 'dbem_phone', true)); //extra field for EM
		do_action('em_person',$this, $person_id, $username);
	}
	
	function get_bookings($ids_only = false, $status= false){
		global $wpdb;
		$status_condition = $blog_condition = '';
		if( is_multisite() ){
			if( !is_main_site() ){
				//not the main blog, force single blog search
				$blog_condition = "AND e.blog_id=".get_current_blog_id();
			}elseif(is_main_site() && !get_option('dbem_ms_global_events')){
				$blog_condition = "AND (e.blog_id=".get_current_blog_id().' OR e.blog_id IS NULL)';
			}
		}
		if( is_numeric($status) ){
			$status_condition = " AND booking_status=$status";
		}elseif( EM_Object::array_is_numeric($status) ){
			$status_condition = " AND booking_status IN (".implode(',', $status).")";
		}
		$EM_Booking = em_get_booking(); //empty booking for fields
		$results = $wpdb->get_results("SELECT b.".implode(', b.', array_keys($EM_Booking->fields))." FROM ".EM_BOOKINGS_TABLE." b, ".EM_EVENTS_TABLE." e WHERE e.event_id=b.event_id AND person_id={$this->ID} {$blog_condition} {$status_condition} ORDER BY ".get_option('dbem_bookings_default_orderby','event_start_date')." ".get_option('dbem_bookings_default_order','ASC'),ARRAY_A);
		$bookings = array();
		if($ids_only){
			foreach($results as $booking_data){
				$bookings[] = $booking_data['booking_id'];
			}
			return apply_filters('em_person_get_bookings', $bookings, $this);
		}else{
			foreach($results as $booking_data){
				$bookings[] = em_get_booking($booking_data);
			}
			return apply_filters('em_person_get_bookings', new EM_Bookings($bookings), $this);
		}
	}

	/**
	 * @return EM_Events
	 */
	function get_events(){
		global $wpdb;
		$events = array();
		foreach( $this->get_bookings()->get_bookings() as $EM_Booking ){
			$events[$EM_Booking->event_id] = $EM_Booking->get_event();
		}
		return apply_filters('em_person_get_events', $events);
	}
	
	function get_bookings_url(){
		if( get_option('dbem_edit_bookings_page') && (!is_admin() || !empty($_REQUEST['is_public'])) ){
			$my_bookings_page = get_permalink(get_option('dbem_edit_bookings_page'));
			$bookings_link = em_add_get_params($my_bookings_page, array('person_id'=>$this->ID, 'event_id'=>null, 'ticket_id'=>null, 'booking_id'=>null), false);
		}else{
			$bookings_link = EM_ADMIN_URL. "&page=events-manager-bookings&person_id=".$this->ID;
		}
		return apply_filters('em_person_get_bookings_url', $bookings_link, $this);
	}
	
	function display_summary(){
		ob_start();
		$no_user = get_option('dbem_bookings_registration_disable') && $this->ID == get_option('dbem_bookings_registration_user');
		?>
		<table class="em-form-fields">
			<tr>
				<td><?php echo get_avatar($this->ID); ?></td>
				<td style="padding-left:10px; vertical-align: top;">
					<table>
						<?php if( $no_user ): ?>
						<tr><th><?php _e('Name','events-manager'); ?> : </th><th><?php echo $this->get_name(); ?></th></tr>
						<?php else: ?>
						<tr><th><?php _e('Name','events-manager'); ?> : </th><th><a href="<?php echo $this->get_bookings_url(); ?>"><?php echo $this->get_name(); ?></a></th></tr>
						<?php endif; ?>
						<tr><th><?php _e('Email','events-manager'); ?> : </th><td><?php echo $this->user_email; ?></td></tr>
						<tr><th><?php _e('Phone','events-manager'); ?> : </th><td><?php echo esc_html($this->phone); ?></td></tr>
					</table>
				</td>
			</tr>
		</table>
		<?php
		return apply_filters('em_person_display_summary', ob_get_clean(), $this);
	}
	
	function get_name(){
		$full_name = $this->first_name  . " " . $this->last_name ;
		$full_name = wp_kses_data(trim($full_name));
		$name = !empty($full_name) ? $full_name : $this->display_name;
		return apply_filters('em_person_get_name', $name, $this);
	}
}
?>