<?php
if(!class_exists('EM_Gateways')) {
class EM_Gateways {
    static $customer_fields = array();
	/*
	 * --------------------------------------------------
	 * STATIC Functions - functions that don't need to be overriden
	 * --------------------------------------------------
	 */
	
	static function init(){
	    add_filter('em_wp_localize_script', array('EM_Gateways','em_wp_localize_script'),10,1);
		//add to booking interface (menu options, booking statuses)
		add_action('em_bookings_table',array('EM_Gateways','em_bookings_table'),10,1);
		// Payment return
		add_action('wp_ajax_em_payment', array('EM_Gateways', 'handle_payment_gateways'), 10 );
		add_action('wp_ajax_nopriv_em_payment', array('EM_Gateways', 'handle_payment_gateways'), 10 );
		//Booking Tables UI
		add_filter('em_bookings_table_rows_col', array('EM_Gateways','em_bookings_table_rows_col'),10,5);
		add_filter('em_bookings_table_cols_template', array('EM_Gateways','em_bookings_table_cols_template'),10,2);
		//Booking interception
		if( get_option('dbem_multiple_bookings') && !(!empty($_REQUEST['manual_booking']) && wp_verify_nonce($_REQUEST['manual_booking'], 'em_manual_booking_'.$_REQUEST['event_id'])) ){
		    //Multiple bookings mode (and not doing a manual booking)
			add_action('em_multiple_booking_add', array('EM_Gateways', 'em_booking_add'), 10, 3);
			add_filter('em_multiple_booking_get_post',array('EM_Gateways', 'em_booking_get_post'), 10, 2);
			add_filter('em_action_emp_checkout', array('EM_Gateways','em_action_booking_add'),10,2); //adds gateway var to feedback
			//Booking Form Modifications
				//buttons only way, oudated but still possible, will eventually depreciated this once an API is out, so use the latter pls
				add_filter('em_checkout_form_buttons', array('EM_Gateways','booking_form_buttons'),10,2); //Replace button with booking buttons
				//new way, with payment selector
				add_action('em_checkout_form_footer', array('EM_Gateways','booking_form_footer'),10,2);
		}else{
		    //Normal Bookings mode, or manual booking
			add_action('em_booking_add', array('EM_Gateways', 'em_booking_add'), 10, 3);
			add_filter('em_booking_get_post',array('EM_Gateways', 'em_booking_get_post'), 10, 2);
			add_filter('em_action_booking_add', array('EM_Gateways','em_action_booking_add'),10,2); //adds gateway var to feedback
			//Booking Form Modifications
				//buttons only way, oudated but still possible, will eventually depreciated this once an API is out, so use the latter pls
				add_filter('em_booking_form_buttons', array('EM_Gateways','booking_form_buttons'),10,2); //Replace button with booking buttons
				//new way, with payment selector
				add_action('em_booking_form_footer', array('EM_Gateways','event_booking_form_footer'),10,2);
		}
		add_filter('em_booking_delete', array('EM_Gateways', 'em_booking_delete'), 10, 2);
		//Gateways and user fields
		add_action('admin_init',array('EM_Gateways', 'customer_fields_admin_actions'),9); //before bookings
		add_action('emp_forms_admin_page',array('EM_Gateways', 'customer_fields_admin'),30);
		self::$customer_fields = array(
			'address' => __('Address','em-pro'),
			'address_2' => __('Address Line 2','em-pro'),
			'city' => __('City','em-pro'),
			'state' => __('State/County','em-pro'),
			'zip' => __('Zip/Post Code','em-pro'),
			'country' => __('Country','em-pro'),
			'phone' => __('Phone','em-pro'),
			'fax' => __('Fax','em-pro'),
			'company' => __('Company','em-pro')
		);
	}
	
	static function em_wp_localize_script( $vars ){
		if( is_user_logged_in() && get_option('dbem_rsvp_enabled') ){
		    $vars['booking_delete'] .= ' '.__('All transactional history associated with this booking will also be deleted.','em-pro');
		    $vars['transaction_delete'] = __('Are you sure you want to delete? This may make your transaction history out of sync with your payment gateway provider.', 'em-pro');
		}
	    return $vars;
	}
	
	static function em_bookings_table($EM_Bookings_Table){
		$EM_Bookings_Table->statuses['awaiting-online'] = array('label'=>__('Awaiting Online Payment','em-pro'), 'search'=>4);
		$EM_Bookings_Table->statuses['awaiting-payment'] = array('label'=>__('Awaiting Offline Payment','em-pro'), 'search'=>5);
		$EM_Bookings_Table->statuses['needs-attention']['search'] = array(0,4,5);
		if( !get_option('dbem_bookings_approval') ){
			$EM_Bookings_Table->statuses['needs-attention']['search'] = array(5);
		}else{
			$EM_Bookings_Table->statuses['needs-attention']['search'] = array(0,5);
		}
		$EM_Bookings_Table->status = ( !empty($_REQUEST['status']) && array_key_exists($_REQUEST['status'], $EM_Bookings_Table->statuses) ) ? $_REQUEST['status']:get_option('dbem_default_bookings_search','needs-attention');
	}

	static function register_gateway($gateway, $class) {
		global $EM_Gateways;
		if(!is_array($EM_Gateways)) {
			$EM_Gateways = array();
		}
		$EM_Gateways[$gateway] = new $class;
	}
		
	/**
	 * Returns an array of active gateway objects
	 * @return array
	 */
	static function active_gateways() {
		global $EM_Gateways;
		$gateways = array();
		foreach($EM_Gateways as $EM_Gateway){
			if($EM_Gateway->is_active()){
				$gateways[$EM_Gateway->gateway] = $EM_Gateway->title;
			}
		}
		return $gateways;
	}
	
	/**
	 * Returns an array of all registered gateway objects
	 * @return array
	 */
	static function gateways_list() {
		global $EM_Gateways;
		$gateways = array();
		foreach($EM_Gateways as $EM_Gateway){
			$gateways[$EM_Gateway->gateway] = $EM_Gateway->title;
		}
		return $gateways;
	}
	
	/**
	 * Returns the EM Gateway with supplied name
	 * @param string $gateway
	 * @return EM_Gateway
	 */
	static function get_gateway( $gateway ){
		global $EM_Gateways;
		foreach($EM_Gateways as $EM_Gateway){
			if( $EM_Gateway->gateway == $gateway ) return $EM_Gateway;
		}
		return new EM_Gateway(); //returns a blank EM_Gateway regardless to avoid fatal errors
	}

	/* 
	 * --------------------------------------------------
	 * Booking Interception - functions that modify booking object behaviour
	 * --------------------------------------------------
	 */
	/**
	 * Hooks into em_booking_get_post filter and makes sure that if there's an active gateway for new bookings, if no $_REQUEST['gateway'] is supplied (i.e. hacking, spammer, or js problem with booking button mode).
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	static function em_booking_get_post($result, $EM_Booking){
	    if( !empty($_REQUEST['manual_booking']) && wp_verify_nonce($_REQUEST['manual_booking'], 'em_manual_booking_'.$_REQUEST['event_id']) ){
	    	return $result;
	    }
	    if( get_option('dbem_multiple_bookings') && get_class($EM_Booking) == 'EM_Booking' ){ //we only deal with the EM_Multiple_Booking class if we're in multi booking mode
	        return $result;	        
	    }
	    if( empty($EM_Booking->booking_id) && (empty($_REQUEST['gateway']) || !array_key_exists($_REQUEST['gateway'], self::active_gateways())) && $EM_Booking->get_price() > 0 && count(EM_Gateways::active_gateways()) > 0 ){
	        //spammer or hacker trying to get around no gateway selection
	    	$error = __('Choice of payment method not recognized. If you are seeing this error and selecting a method of payment, we apologize for the inconvenience. Please contact us and we\'ll help you make a booking as soon as possible.','em-pro');
	    	$EM_Booking->add_error($error);
	    	$result = false;
	    	if( defined('DOING_AJAX') ){
	    		$return = array('result'=>false, 'message'=>$error, 'errors'=>$error);
	    		echo EM_Object::json_encode($return);
	    		die();
	    	}
	    }
	    return $result;
	}
	
	/**
	 * Intercepted when a booking is about to be added and saved, calls the relevant booking gateway action provided gateway is provided in submitted request variables.
	 * @param EM_Event $EM_Event the event the booking is being added to
	 * @param EM_Booking $EM_Booking the new booking to be added
	 * @param boolean $post_validation
	 */
	static function em_booking_add($EM_Event, $EM_Booking, $post_validation = false){
		global $EM_Gateways;
		if( !empty($_REQUEST['gateway']) && array_key_exists($_REQUEST['gateway'], $EM_Gateways) ){
			//we haven't been told which gateway to use, revert to offline payment, since it's closest to pending
			$EM_Booking->booking_meta['gateway'] = addslashes($_REQUEST['gateway']);
			//Individual gateways will hook into this function
			$EM_Gateways[$_REQUEST['gateway']]->booking_add($EM_Event, $EM_Booking, $post_validation);
		}
	}
	
	static function event_booking_form_footer( $EM_Event ){
		if(!$EM_Event->is_free() ){
		    self::booking_form_footer();
		}
	}
	
	/**
	 * Gets called at the bottom of the form before the submit button. 
	 * Outputs a gateway selector and allows gateways to hook in and provide their own payment information to be submitted.
	 * By default each gateway is wrapped with a div with id em-booking-gateway-x where x is the gateway for JS to work.
	 * 
	 * To prevent this from firing, call this function after the init action:
	 * remove_action('em_booking_form_footer', array('EM_Gateways','booking_form_footer'),1,2);
	 * 
	 * You'll have to ensure a gateway value is submitted in your booking form in order for paid bookings to be processed properly.
	 */
	static function booking_form_footer(){
		global $EM_Gateways;
		//Display gateway input
		add_action('em_gateway_js', array('EM_Gateways','em_gateway_js'));
		//Check if we can user quick pay buttons
		if( get_option('dbem_gateway_use_buttons', 1) ){ //backward compatability
			echo EM_Gateways::booking_form_buttons();
			return;
		}
		//Continue with payment gateway selection
		$active_gateways = self::active_gateways();
		if( is_array($active_gateways) ){
			//Add gateway selector
			if( count($active_gateways) > 1 ){
			?>
			<p class="em-booking-gateway" id="em-booking-gateway">
				<label><?php echo get_option('dbem_gateway_label'); ?></label>
				<select name="gateway">
				<?php
				foreach($active_gateways as $gateway => $active_val){
					if(array_key_exists($gateway, $EM_Gateways)) {
						$selected = (!empty($selected)) ? $selected:$gateway;
						echo '<option value="'.$gateway.'">'.get_option('em_'.$gateway.'_option_name').'</option>';
					}
				}
				?>
				</select>
			</p>
			<?php
			}elseif( count($active_gateways) == 1 ){
				foreach($active_gateways as $gateway => $val){
					$selected = (!empty($selected)) ? $selected:$gateway;
					echo '<input type="hidden" name="gateway" value="'.$gateway.'" />';
				}
			}
			foreach($active_gateways as $gateway => $active_val){
				echo '<div class="em-booking-gateway-form" id="em-booking-gateway-'.$gateway.'"';
				echo ($selected == $gateway) ? '':' style="display:none;"';
				echo '>';
				$EM_Gateways[$gateway]->booking_form();
				echo "</div>";
			}
		}
		return; //for filter compatibility
	}
	
	/**
	 * Cleans up Pro-added features in the database, such as deleting transactions for this booking.
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	static function em_booking_delete($result, $EM_Booking){
		if($result){
			//TODO decouple transaction logic from gateways
			global $wpdb;
			$wpdb->query('DELETE FROM '.EM_TRANSACTIONS_TABLE." WHERE booking_id = '".$EM_Booking->booking_id."'");
		}
		return $result;
	}
	
	static function em_action_booking_add($return){
		if( !empty($_REQUEST['gateway']) ){
			$return['gateway'] = $_REQUEST['gateway'];
		}
		return $return;
	}
	
	static function em_gateway_js(){
		include(dirname(__FILE__).'/gateways.js');
	}

	static function handle_payment_gateways() {
		if( !empty($_REQUEST['em_payment_gateway']) ) {
			do_action( 'em_handle_payment_return_' . $_REQUEST['em_payment_gateway']);
			exit();
		}
	}
	
	/*
	 * ----------------------------------------------------------
	 * Booking Table and CSV Export
	 * ----------------------------------------------------------
	 */
	
	function em_bookings_table_rows_col($value, $col, $EM_Booking, $EM_Bookings_Table, $csv){
		global $EM_Event;
		if( $col == 'gateway' ){
			//get latest transaction with an ID
			if( !empty($EM_Booking->booking_meta['gateway']) ){
				$gateway = EM_Gateways::get_gateway($EM_Booking->booking_meta['gateway']);
				$value = $gateway->title;
			}else{
				$value = __('None','em-pro');
			}
		}
		return $value;
	}
	
	function em_bookings_table_cols_template($template, $EM_Bookings_Table){
		$template['gateway'] = __('Gateway Used','em-pro');
		return $template;
	}

	/*
	 * --------------------------------------------------
	* USER FIELDS - Adds user details link for use by gateways and options to form editor
	* --------------------------------------------------
	*/
	static function get_customer_field($field_name, $EM_Booking = false, $user_or_id = false){
		//get user id
		if( is_numeric($user_or_id) ){
			$user_id = $user_or_id; 
		}elseif(is_object($user_or_id)){
			$user_id = $user_or_id->ID;
		}elseif( !empty($EM_Booking->person_id) ){
			$user_id = $EM_Booking->person_id;		
		}else{
			$user_id = get_current_user_id();
		}
		//get real field id
		if( array_key_exists($field_name, self::$customer_fields) ){
			$associated_fields = get_option('emp_gateway_customer_fields');
			$form_field_id = $associated_fields[$field_name];
		}
		//if no-user mode, discard the $user_id, we only deal with the booking object
		if( get_option('dbem_bookings_registration_disable') && $user_id == get_option('dbem_bookings_registration_user') ) $user_id = false;
		//determine field value
		if( empty($user_id) && !empty($EM_Booking) ){
			//get meta from booking if user meta isn't available
			if( !empty($EM_Booking->booking_meta['registration'][$form_field_id])){
				return $EM_Booking->booking_meta['registration'][$form_field_id];
			}
		}elseif( !empty($user_id) ){
			//get corresponding user meta field
			$value = get_user_meta($user_id, $form_field_id, true);
			if( empty($value) ){
				if( !empty($EM_Booking->booking_meta['registration'][$form_field_id]) ){
					return $EM_Booking->booking_meta['registration'][$form_field_id];
				}
			}else{
				return $value;
			}			
		}
		return '';
	}
	
	static function customer_fields_admin_actions() {
		global $EM_Notices;
		$EM_Form = EM_User_Fields::get_form();
		if( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'events-manager-forms-editor' ){
			if( !empty($_REQUEST['form_name']) && 'gateway_customer_fields' == $_REQUEST['form_name'] && wp_verify_nonce($_REQUEST['_wpnonce'], 'gateway_customer_fields_'.get_current_user_id()) ){
				//save values
				$gateway_fields = array();
				foreach( self::$customer_fields as $field_key => $field_val ){
					$gateway_fields[$field_key] = ( !empty($_REQUEST[$field_key]) ) ? $_REQUEST[$field_key]:'';
				}
				update_option('emp_gateway_customer_fields',$gateway_fields);
				$EM_Notices->add_confirm(__('Changes Saved','em-pro'));
			}
		}
		//enable dbem_bookings_tickets_single_form if enabled
	}
	
	static function customer_fields_admin() {
		//enable dbem_bookings_tickets_single_form if enabled
		$EM_Form = EM_User_Fields::get_form();
		$current_values = get_option('emp_gateway_customer_fields');
		?>
			<a name="gateway_customer_fields"></a>
			<div id="poststuff" class="metabox-holder">
				<!-- END OF SIDEBAR -->
				<div id="post-body">
					<div id="post-body-content">
						<div id="em-booking-form-editor" class="stuffbox">
							<h3>
								<?php _e ( 'Common User Fields for Gateways', 'em-pro' ); ?>
							</h3>
							<div class="inside">
								<p><?php _e('In many cases, customer address information is required by gateways for verification. This section connects your custom fields to commonly used customer information fields.', 'em-pro' ); ?></p>
								<p><?php _e('After creating user fields above, you should link them up in here so some gateways can make use of them when processing payments.', 'em-pro' ); ?></p>
								<form action="#gateway_customer_fields" method="post">
									<table class="form-table">
										<tr><td><?php _e('Name (first/last)','em-pro'); ?></td><td><em><?php _e('Generated accordingly from user first/last name or full name field. If a name field isn\'t provided in your booking form, the username will be used instead.','em-pro')?></em></td></tr>
										<tr><td><?php _e('Email','em-pro'); ?></td><td><em><?php _e('Uses the WordPress account email associated with the user.', 'em-pro')?></em></td></tr>
										<?php foreach( self::$customer_fields as $field_key => $field_val ): ?>
										<tr>
											<td><?php echo $field_val; ?></td>
											<td>
												<select name="<?php echo $field_key; ?>">
													<option value="0"><?php echo _e('none selected','em-pro'); ?></option>
													<?php foreach( $EM_Form->user_fields as $field_id => $field_name ): ?>
													<option value="<?php echo $field_id; ?>" <?php echo ($field_id == $current_values[$field_key]) ?'selected="selected"':''; ?>><?php echo $field_name; ?></option>
													<?php endforeach; ?>
												</select>
											</td>
										</tr>
										<?php endforeach; ?>
									</table>
									<p>
										<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('gateway_customer_fields_'.get_current_user_id()); ?>">
										<input type="hidden" name="form_action" value="form_fields">
										<input type="hidden" name="form_name" value="gateway_customer_fields" />
										<input type="submit" name="events_update" value="<?php _e('Save Form','em-pro'); ?>" class="button-primary">
									</p>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
		}

	/*
	 * --------------------------------------------------
	 * BUTTONS MODE Functions - i.e. booking doesn't require gateway selection, just button click
	 * --------------------------------------------------
	 */

	/**
	 * This gets called when a booking form created using the old buttons API, and calls subsequent gateways to output their buttons.
	 * @param string $button
	 * @param EM_Event $EM_Event
	 * @return string
	 */
	static function booking_form_buttons($button = ''){
		global $EM_Gateways;
		$gateway_buttons = array();
		$active_gateways = self::active_gateways();
		if( is_array($active_gateways) ){
			foreach($active_gateways as $gateway => $active_val){
				if(array_key_exists($gateway, $EM_Gateways) && $EM_Gateways[$gateway]->button_enabled) {
					$gateway_button = $EM_Gateways[$gateway]->booking_form_button();
					if(!empty($gateway_button)){
						$gateway_buttons[$gateway] = $gateway_button;
					}
				}
			}
			//$gateway_buttons = apply_filters('em_gateway_buttons', $gateway_buttons, $EM_Event);
			if( count($gateway_buttons) > 0 ){
				$button = '<div class="em-gateway-buttons"><div class="em-gateway-button first">'. implode('</div><div class="em-gateway-button">', $gateway_buttons).'</div></div>';			
			}
			if( count($gateway_buttons) > 1 ){
				$button .= '<input type="hidden" name="gateway" value="offline" />';
			}else{
				$button .= '<input type="hidden" name="gateway" value="'.$gateway.'" />';
			}
		}
		if($button != '') $button .= '<style type="text/css">input.em-booking-submit { display:none; } .em-gateway-button input.em-booking-submit { display:block; }</style>'; //hide normal button if we have buttons
		return apply_filters('em_gateway_booking_form_buttons', $button, $gateway_buttons);
	}
}
EM_Gateways::init();
//Menus
if( is_admin() ){
	include('gateways-admin.php');
}
function emp_register_gateway($gateway, $class) { EM_Gateways::register_gateway($gateway, $class); } //compatibility, use EM_Gateways directly
}

include('gateway.php');
include('gateways.transactions.php');
include('gateway.paypal.php');
include('gateway.offline.php');
include('gateway.authorize.aim.php');