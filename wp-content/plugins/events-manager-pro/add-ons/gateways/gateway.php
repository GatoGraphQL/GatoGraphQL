<?php
/**
 * This class is a parent class which gateways should extend. There are various variables and functions that are automatically taken care of by
 * EM_Gateway, which will reduce redundant code and unecessary errors across all gateways. You can override any function you want on your gateway,
 * but it's advised you read through before doing so.
 *
 */
class EM_Gateway {
	/**
	 * Gateway reference, which is used in various places for referencing gateway info. Use lowercase characters/numbers and underscores.
	 * @var string
	 */
	var $gateway = 'unknown';
	/**
	 * This will be what admins see as the gatweway name (e.g. Offline, PayPal, Authorize.net ...)
	 * @var string
	 */
	var $title = 'Unknown';
	/**
	 * The default status value your gateway assigns this booking. Default is 0, i.e. pending 'something'.
	 * @var int
	 */
	var $status = 0;
	/**
	 * Set this to any true value and this will trigger the em_my_bookings_booked_message function to override the status name of this booking when in progress.
	 * @var string
	 */
	var $status_txt = '';
	/**
	 * If your gateway supports the ability to pay without requiring further fields (e.g. credit card info), then you can set this to true.
	 * 
	 * You will automatically have the ability to show buttons within your gateway. It's up to you to change what happens after by 
	 * overriding functions from EM_Gateway such as modifying booking_add or booking_form_feedback.
	 *  
	 * @var boolean
	 */
	var $button_enabled = false;
	/**
	 * If your gateway is compatible with our Multiple Bookings Mode, then you can set this to true, otherwise your gateway won't be available for booking in this mode.
	 *  
	 * @var boolean
	 */
	var $supports_multiple_bookings = false;	
	/**
	 * Some external gateways (e.g. PayPal IPNs) return information back to your site about payments, which allow you to automatically track refunds made outside Events Manager.
	 * If you enable this to true, be sure to add an overriding handle_payment_return function to deal with the information sent by your gateway.
	 * @var unknown_type
	 */
	var $payment_return = false;
	/**
	 * Counts bookings with pending spaces for availability 
	 * @var boolean
	 */
	var $count_pending_spaces = false;

	/**
	 * Adds some basic actions and filters to hook into the EM_Gateways class and Events Manager bookings interface. 
	 */
	function __construct() {
		// Actions and Filters, only if gateway is active
		if( $this->is_active() ){
			add_filter('em_booking_output_placeholder',array(&$this, 'em_booking_output_placeholder'),1,4); //add booking placeholders
			if( $this->payment_return ){
				add_action('em_handle_payment_return_' . $this->gateway, array(&$this, 'handle_payment_return')); //handle return payment notifications
			}
			if(!empty($this->status_txt)){
				//Booking UI
				add_filter('em_my_bookings_booked_message',array(&$this,'em_my_bookings_booked_message'),10,2);
				add_filter('em_booking_get_status',array(&$this,'em_booking_get_status'),10,2);
			}
		}
		if( $this->count_pending_spaces ){
			//Modify spaces calculations, required even if inactive, due to previously made bookings whilst this may have been active
			add_filter('em_bookings_get_pending_spaces', array(&$this, 'em_bookings_get_pending_spaces'),1,2);
			add_filter('em_ticket_get_pending_spaces', array(&$this, 'em_ticket_get_pending_spaces'),1,2);
			add_filter('em_booking_is_reserved', array(&$this, 'em_booking_is_reserved'),1,2);
		}
	}

	/*
	 * --------------------------------------------------
	 * OVERRIDABLE FUNCTIONS - should be overriden by the extending class
	 * --------------------------------------------------
	 */

	/**
	 * Triggered by the em_booking_add_yourgateway action, modifies the booking status if the event isn't free and also adds a filter to modify user feedback returned.
	 * @param EM_Event $EM_Event
	 * @param EM_Booking $EM_Booking
	 * @param boolean $post_validation
	 */
	function booking_add($EM_Event,$EM_Booking, $post_validation = false){
		global $wpdb, $wp_rewrite, $EM_Notices;
		add_filter('em_action_booking_add',array(&$this, 'booking_form_feedback'),1,2);//modify the payment return
		add_filter('em_action_emp_checkout',array(&$this, 'booking_form_feedback'),1,2);//modify the payment return
		if( $EM_Booking->get_price() > 0 ){
			$EM_Booking->booking_status = $this->status; //status 4 = awaiting online payment
		}
	}

	/**
	 * Intercepts return JSON and adjust feedback messages when booking with this gateway. This filter is added only when the em_booking_add function is triggered by the em_booking_add filter.
	 * @param array $return
	 * @param EM_Booking $EM_Booking
	 * @return array
	 */
	function booking_form_feedback( $return, $EM_Booking ){
		return $return; //remember this, it's a filter!	
	}

	/**
	 * Outputs extra custom content e.g. information about this gateway or extra form fields to be requested if this gateway is selected (not applicable with Quick Pay Buttons).
	 */
	function booking_form(){}

	/**
	 * Called by $this->settings(), override this to output your own gateway options on this gateway settings page  
	 */
	function mysettings(){}
	
	/**
	 * Run by EM_Gateways::handle_gateways_panel_updates() if this gateway has been updated. You should capture the values of your new fields above and save them as options here.
	 * return boolean 
	 */
	function update() {
		// default action is to return true
		if($this->button_enabled){ 
			//either use parent::update() or just save this along with your other options in your function 
			update_option('em_'.$this->gateway . "_button", $_REQUEST[ $this->gateway.'_button' ]);
		}
		update_option('em_'.$this->gateway . "_option_name", $_REQUEST[ $this->gateway.'_option_name' ]);
		update_option('em_'.$this->gateway . "_form", $_REQUEST[ $this->gateway.'_form' ]);
		do_action('em_gateway_update', $this);
		return true;
	}

	/**
	 * Adds extra placeholders to the booking email. Called by em_booking_output_placeholder filter, added in this object __construct() function.
	 * 
	 * You can override this function and just use this within your function:
	 * $result = parent::em_booking_output_placeholder($result);
	 * 
	 * @param string $result
	 * @param EM_Booking $EM_Booking
	 * @param string $placeholder
	 * @param string $target
	 * @return string
	 */
	function em_booking_output_placeholder($result,$EM_Booking,$placeholder,$target='html'){	
		global $wpdb;
		if( ($placeholder == "#_BOOKINGTXNID" && !empty($EM_Booking->booking_meta['gateway'])) && $EM_Booking->booking_meta['gateway'] == $this->gateway ){
			if(empty($this->BOOKINGTXNID)){
				$sql = $wpdb->prepare( "SELECT transaction_gateway_id FROM ".EM_TRANSACTIONS_TABLE." WHERE booking_id=%d AND transaction_gateway = %s", $EM_Booking->booking_id, $this->gateway );
				$txn_id = $wpdb->get_var($sql);
				if(!empty($txn_id)){
					$result = $this->BOOKINGTXNID = $txn_id;
				}else{
				    $result = '';
				}
			}else{
				$result = $this->BOOKINGTXNID;
			}
		}
		return $result;
	}
	
	/**
	 * If you set your gateway class $payment_return property to true, this function will be called when your external gateway sends a notification of payment.
	 * 
	 * Override this in your function to catch payment returns and do something with this information, such as handling refunds.
	 */
	function handle_payment_return(){}
	
	/**
	 * If you would like to modify the default status message for this payment whilst in progress.
	 * 
	 * This function is triggered if set $this->status_text to something and this will be called automatically. You can also override this function.
	 * 
	 * @param string $message
	 * @param EM_Booking $EM_Booking
	 * @return string
	 */
	function em_booking_get_status($message, $EM_Booking){
		if( !empty($this->status_txt) && $EM_Booking->booking_status == $this->status && $this->uses_gateway($EM_Booking) ){ 
			return $this->status_txt; 
		}
		return $message;
	}
	
	/*
	 * --------------------------------------------------
	 * PENDING SPACE COUNTING - if $this->count_pending_spaces is true, depending on the gateway, bookings with this gateway status are considered pending and reserved
	 * --------------------------------------------------
	 */
	
	/**
	 * Modifies pending spaces calculations to include paypal bookings, but only if PayPal bookings are set to time-out (i.e. they'll get deleted after x minutes), therefore can be considered as 'pending' and can be reserved temporarily.
	 * @param integer $count
	 * @param EM_Bookings $EM_Bookings
	 * @return integer
	 */
	function em_bookings_get_pending_spaces($count, $EM_Bookings){
		foreach($EM_Bookings->bookings as $EM_Booking){
			if($EM_Booking->booking_status == $this->status && $this->uses_gateway($EM_Booking)){
				$count += $EM_Booking->get_spaces();
			}
		}
		return $count;
	}
	
	/**
	 * Changes EM_Booking::is_reserved() return value to true. Only called if $this->count_pending_spaces is set to true.
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	function em_booking_is_reserved( $result, $EM_Booking ){
		if($EM_Booking->booking_status == $this->status && $this->uses_gateway($EM_Booking) && get_option('dbem_bookings_approval_reserved')){
			return true;
		}
		return $result;
	}
	
	/**
	 * Modifies pending spaces calculations for individual tickets to include paypal bookings, but only if PayPal bookings are set to time-out (i.e. they'll get deleted after x minutes), therefore can be considered as 'pending' and can be reserved temporarily.
	 * @param integer $count
	 * @param EM_Ticket $EM_Ticket
	 * @return integer
	 */
	function em_ticket_get_pending_spaces($count, $EM_Ticket){
		foreach( $EM_Ticket->get_bookings()->bookings as $EM_Booking ){ //get_bookings() is used twice so we get the confirmed (or all if confirmation disabled) bookings of this ticket's total bookings.
			if($EM_Booking->booking_status == $this->status && $this->uses_gateway($EM_Booking)){
				foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){
					if( $EM_Ticket_Booking->ticket_id == $EM_Ticket->ticket_id ){
						$count += $EM_Ticket_Booking->get_spaces();
					}
				}
			}
		}
		return $count;
	}
	
		
	/*
	 * --------------------------------------------------
	 * BUTTONS MODE Functions - i.e. booking doesn't require gateway selection, just button click, EMP adds gateway choice via JS to submission
	 * --------------------------------------------------
	 */
	
	/**
	 * Shows button, not needed if using the new form display
	 * @param string $button
	 * @return string
	 */
	function booking_form_button(){
		ob_start();
		if( preg_match('/https?:\/\//',get_option('em_'. $this->gateway . "_button")) ): ?>
			<input type="image" class="em-booking-submit em-gateway-button" id="em-gateway-button-<?php echo $this->gateway; ?>" src="<?php echo get_option('em_'. $this->gateway . "_button"); ?>" alt="<?php echo $this->title; ?>" />
		<?php else: ?>
			<input type="submit" class="em-booking-submit em-gateway-button" id="em-gateway-button-<?php echo $this->gateway; ?>" value="<?php echo get_option('em_'. $this->gateway . "_button",$this->title); ?>" />
		<?php endif;
		return ob_get_clean();
	}
	
	/*
	 * --------------------------------------------------
	 * PARENT FUNCTIONS - overriding not required, but could be done
	 * --------------------------------------------------
	 */
	
	/**
	 * Gets the gateway option from the correct place. Does not require prefixing of em_gatewayname_
	 * Will be particularly useful when restricting possible gateway settings in MultiSite mode and sharing accross networks, use this and you're future-proof.
	 * @param string $name
	 * @param mixed $value
	 * @return mixed
	 */
	function get_option( $name ){
		return get_option('em_'.$this->gateway.'_'.$name);
	}
	
	/**
	 * Updates the gateway option to the correct place. Does not require prefixing of em_gatewayname_
	 * Will be particularly useful when restricting possible gateway settings in MultiSite mode and sharing accross networks, use this and you're future-proof.
	 * @param string $name
	 * @param mixed $value
	 * @return boolean
	 */
	function update_option( $name, $value ){
		return update_option('em_'.$this->gateway.'_'.$name, $value);
	}
	
	/**
	 * Checks an EM_Booking object and returns whether or not this gateway is/was used in the booking.
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	function uses_gateway($EM_Booking){
		return (!empty($EM_Booking->booking_meta['gateway']) && $EM_Booking->booking_meta['gateway'] == $this->gateway);
	}


	/**
	 * Returns the notification URL which gateways sends return messages to, e.g. notifying of payment status. 
	 * 
	 * Your URL would correspond to http://yoursite.com/admin-ajax.php?action=em_payment&em_payment_gateway=yourgateway
	 * @return string
	 */
	function get_payment_return_url(){
		return admin_url('admin-ajax.php?action=em_payment&em_payment_gateway='.$this->gateway);
	}

	/**
	 * Records a transaction according to this booking and gateway type.
	 * @param EM_Booking $EM_Booking
	 * @param float $amount
	 * @param string $currency
	 * @param int $timestamp
	 * @param string $txn_id
	 * @param int $status
	 * @param string $note
	 */
	function record_transaction($EM_Booking, $amount, $currency, $timestamp, $txn_id, $status, $note) {
		global $wpdb;
		$data = array();
		$data['booking_id'] = $EM_Booking->booking_id;
		$data['transaction_gateway_id'] = $txn_id;
		$data['transaction_timestamp'] = $timestamp;
		$data['transaction_currency'] = $currency;
		$data['transaction_status'] = $status;
		$data['transaction_total_amount'] = $amount;
		$data['transaction_note'] = $note;
		$data['transaction_gateway'] = $this->gateway;

		if( !empty($txn_id) ){
			$existing = $wpdb->get_row( $wpdb->prepare( "SELECT transaction_id, transaction_status, transaction_gateway_id, transaction_total_amount FROM ".EM_TRANSACTIONS_TABLE." WHERE transaction_gateway = %s AND transaction_gateway_id = %s", $this->gateway, $txn_id ) );
		}
		$table = EM_TRANSACTIONS_TABLE;
		if( is_multisite() && !EM_MS_GLOBAL && !empty($EM_Event->blog_id) && !is_main_site($EM_Event->blog_id) ){ 
			//we must get the prefix of the transaction table for this event's blog if it is not the root blog
			$table = $wpdb->get_blog_prefix($EM_Event->blog_id).'em_transactions';
		}
		if( !empty($existing->transaction_gateway_id) && $amount == $existing->transaction_total_amount && $status != $existing->transaction_status ) {
			// Update only if txn id and amounts are the same (e.g. pending payments changing status)
			$wpdb->update( $table, $data, array('transaction_id' => $existing->transaction_id) );
		} else {
			// Insert
			$wpdb->insert( $table, $data );
		}
	}

	function toggleactivation() {
		global $EM_Pro;
		$active = get_option('em_payment_gateways');

		if(array_key_exists($this->gateway, $active)) {
			unset($active[$this->gateway]);
			update_option('em_payment_gateways',$active);
			return true;
		} else {
			$active[$this->gateway] = true;
			update_option('em_payment_gateways',$active);
			return true;
		}
	}

	function activate() {
		global $EM_Pro;
		$active = get_option('em_payment_gateways', array());
		if(array_key_exists($this->gateway, $active)) {
			return true;
		} else {
			$active[$this->gateway] = true;
			update_option('em_payment_gateways', $active);
			return true;
		}
	}

	function deactivate() {
		global $EM_Pro;
		$active = get_option('em_payment_gateways');
		if(array_key_exists($this->gateway, $active)) {
			unset($active[$this->gateway]);
			update_option('em_payment_gateways', $active);
			return true;
		} else {
			return true;
		}
	}

	function is_active() {
		global $EM_Pro;
		$active = get_option('em_payment_gateways', array());
		$is_active = array_key_exists($this->gateway, $active);
		if( get_option('dbem_multiple_bookings') ){
			return $is_active && $this->supports_multiple_bookings;
		}else{
			return $is_active;			
		}
	}

	/**
	 * Generates a settings pages.
	 * @uses EM_Gateway::mysettings()
	 */
	function settings() {
		global $page, $action, $EM_Notices;
		$gateway_link = admin_url('edit.php?post_type='.EM_POST_TYPE_EVENT.'&page=events-manager-options#bookings');
		$messages['updated'] = esc_html__('Gateway updated.', 'em-pro');
		$messages['error'] = esc_html__('Gateway not updated.', 'em-pro');
		?>
		<div class='wrap nosubsub'>
			<div class="icon32" id="icon-plugins"><br></div>
			<h2><?php echo sprintf(__('Edit &quot;%s&quot; settings','em-pro'), esc_html($this->title) ); ?></h2>
			<?php
			if ( isset($_GET['msg']) && !empty($messages[$_GET['msg']]) ){ 
				echo '<div id="message" class="'.$_GET['msg'].' fade"><p>' . $messages[$_GET['msg']] . 
				' <a href="'.em_add_get_params($_SERVER['REQUEST_URI'], array('action'=>null,'gateway'=>null, 'msg' => null)).'">'.esc_html__('Back to gateways','em-pro').'</a>'.
				'</p></div>';
			}
			?>
			<form action='' method='post' name='gatewaysettingsform'>
				<input type='hidden' name='action' id='action' value='updated' />
				<input type='hidden' name='gateway' id='gateway' value='<?php echo $this->gateway; ?>' />
				<?php wp_nonce_field('updated-' . $this->gateway); ?>
				<h3><?php echo sprintf(__( '%s Options', 'dbem' ),__('Booking Form','dbem')); ?></h3>
				<table class="form-table">
				<tbody>
				  <tr valign="top">
					  <th scope="row"><?php _e('Gateway Title', 'em-pro') ?></th>
					  <td>
					  	<input type="text" name="<?php echo $this->gateway; ?>_option_name" value="<?php esc_html_e(get_option('em_'. $this->gateway . "_option_name" )); ?>"/><br />
					  	<em><?php 
					  		echo sprintf(__('Only if you have not enabled quick pay buttons in your <a href="%s">gateway settings</a>.', 'em-pro'),$gateway_link).' '.
					  		__('The user will see this as the text option when choosing a payment method.','em-pro'); 
					  	?></em>
					  </td>
				  </tr>
				  <tr valign="top">
					  <th scope="row"><?php _e('Booking Form Information', 'em-pro') ?></th>
					  <td>
					  	<textarea name="<?php echo $this->gateway; ?>_form"><?php esc_html_e(get_option('em_'. $this->gateway . "_form" )); ?></textarea><br />
					  	<em><?php 
					  		echo sprintf(__('Only if you have not enabled quick pay buttons in your <a href="%s">gateway settings</a>.','em-pro'),$gateway_link).
							' '.__('If a user chooses to pay with this gateway, or it is selected by default, this message will be shown just below the selection.', 'em-pro'); 
					  	?></em>
					  </td>
				  </tr>
				</tbody>
				</table>
				<?php $this->mysettings(); ?>
				<?php if($this->button_enabled): ?>
				<h3><?php echo _e('Quick Pay Buttons','em-pro'); ?></h3>
				<p><?php echo sprintf(__('If you have chosen to only use quick pay buttons in your <a href="%s">gateway settings</a>, these settings below will be used.','em-pro'), $gateway_link); ?></p>
				<table class="form-table">
				<tbody>
				  <tr valign="top">
					  <th scope="row"><?php _e('Payment Button', 'em-pro') ?></th>
					  <td>
					  	<input type="text" name="<?php echo $this->gateway ?>_button" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_button", 'http://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif' )); ?>" style='width: 40em;' /><br />
					  	<em><?php echo sprintf(__('Choose the button text. To use an image instead, enter the full url starting with %s or %s.', 'em-pro' ), '<code>http://www.paypal.com/en_US/i/btn/btn_xpressCheckout.gif</code>','<code>https://...</code>'); ?></em>
					  </td>
				  </tr>
				</tbody>
				</table>
				<?php endif; ?>
				<?php do_action('em_gateway_settings_footer', $this); ?>
				<p class="submit">
				<input type="submit" name="Submit" class="button-primary" value="<?php esc_attr_e('Save Changes') ?>" />
				</p>
			</form>
		</div> <!-- wrap -->
		<?php
	}
}

?>