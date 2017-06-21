<?php

/**
 * Extension of the Offline Gateway to provide for Bank Details
 * @author leo
 */
if (class_exists("EM_Gateway_Offline")) {
 
	class EM_Gateway_BankTransfer extends EM_Gateway_Offline {
	
		var $gateway = 'banktransfer';
		var $title = 'Bank Transfer';
		var $button_enabled = false;
	
		/**
		 * Sets up gateway and registers actions/filters
		 */
		function __construct() {	
			parent::__construct();
			//Booking Interception		
			
			// These actions have already been added by gateway.offline.php, if they are not removed they are executed twice
			remove_action('em_admin_event_booking_options_buttons', array($this, 'event_booking_options_buttons'),10);
			remove_action('em_admin_event_booking_options', array($this, 'event_booking_options'),10);
			remove_action('em_bookings_single_metabox_footer', array($this, 'add_payment_form'),1,1); //add payment to booking
			remove_action('em_bookings_manual_booking', array($this, 'add_booking_form'),1,1);		
		}
		
		function em_wp_localize_script($vars){
			
			$main_vars = GD_TemplateManager_Utils::get_vars();
			if( $main_vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/ && get_option('dbem_rsvp_enabled') ){
				$vars[$this->gateway . '_confirm'] = __('Be aware that by approving a booking awaiting payment, a full payment transaction will be registered against this booking, meaning that it will be considered as paid.','dbem');
			}
			return $vars;
		}
		
		/* 
		 * --------------------------------------------------
		 * Booking Interception - functions that modify booking object behaviour
		 * --------------------------------------------------
		 */
		
		/**
		 * Intercepts return JSON and adjust feedback messages when booking with this gateway.
		 * @param array $return
		 * @param EM_Booking $EM_Booking
		 * @return array
		 */
		function booking_form_feedback( $return, $EM_Booking = false ){
			if( !empty($return['result']) && !empty($EM_Booking->booking_meta['gateway']) && !empty($EM_Booking->booking_status) ){ //check emtpies
				if( $EM_Booking->booking_status == 5 && $this->uses_gateway($EM_Booking) ){ //check values
					$return['message'] = get_option('em_'.$this->gateway.'_booking_feedback');
					if( !empty($EM_Booking->email_not_sent) ){
						$return['message'] .=  ' '.get_option('dbem_booking_feedback_nomail');
					}
					return apply_filters('em_gateway_' . $this->gateway . '_booking_add', $return, $EM_Booking->get_event(), $EM_Booking);
				}
			}						
			return $return;
		}
	
		
		/* 
		 * --------------------------------------------------
		 * Settings pages and functions
		 * --------------------------------------------------
		 */
		
		/**
		 * Outputs custom offline setting fields in the settings page 
		 */
		function mysettings() {
	
			global $EM_options;
			?>
			<table class="form-table">
			<tbody>
			  <tr valign="top">
				  <th scope="row"><?php _e('Success Message', 'em-pro') ?></th>
				  <td>
					<input type="text" name="<?php echo $this->gateway ?>_booking_feedback" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_booking_feedback" )); ?>" style='width: 40em;' /><br />
					<em><?php _e('The message that is shown to a user when a booking with bank transfer payments is successful.','em-pro'); ?></em>
				  </td>
			  </tr>
			  <tr valign="top">
				  <th scope="row"><?php _e('Customer Message', 'poptheme-wassup') ?></th>
				  <td>
					<input type="text" name="<?php echo $this->gateway ?>_form_message" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_form_message" )); ?>" style='width: 40em;' /><br />
					<em><?php _e("Give the customer instructions for paying via Bank Transfer, and let them know that the Event Booking will be confirmed once the money is received",'poptheme-wassup'); ?></em>
				  </td>
			  </tr>
			  <tr valign="top">
				  <th scope="row"><?php _e('Account details', 'poptheme-wassup') ?></th>
				  <td>
					<input type="text" name="<?php echo $this->gateway ?>_form_account_details" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_form_account_details" )); ?>" style='width: 40em;' /><br />
					<em><?php _e("Account details title",'poptheme-wassup'); ?></em>
				  </td>
			  </tr>		  
			</tbody>
			</table>
			
			<h3>Account Details</h3>
			<p>Optionally enter your bank details below for customers to pay into.</p>
			<table class="form-table">
			<tbody>
			  <tr valign="top">
				  <th scope="row"><?php _e('Account Name', 'poptheme-wassup') ?></th>
				  <td>
					<input type="text" name="<?php echo $this->gateway ?>_account_name" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_account_name" )); ?>" style='width: 40em;' /><br />
				  </td>
			  </tr>
			  <tr valign="top">
				  <th scope="row"><?php _e('Account Number', 'poptheme-wassup') ?></th>
				  <td>
					<input type="text" name="<?php echo $this->gateway ?>_account_number" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_account_number" )); ?>" style='width: 40em;' /><br />
				  </td>
			  </tr>	
			  <tr valign="top">
				  <th scope="row"><?php _e('Sort Code', 'poptheme-wassup') ?></th>
				  <td>
					<input type="text" name="<?php echo $this->gateway ?>_sort_code" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_sort_code" )); ?>" style='width: 40em;' /><br />
				  </td>
			  </tr>		
			  <tr valign="top">
				  <th scope="row"><?php _e('Bank Name', 'poptheme-wassup') ?></th>
				  <td>
					<input type="text" name="<?php echo $this->gateway ?>_bank_name" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_bank_name" )); ?>" style='width: 40em;' /><br />
				  </td>
			  </tr>		
			  <tr valign="top">
				  <th scope="row"><?php _e('IBAN', 'poptheme-wassup') ?></th>
				  <td>
					<input type="text" name="<?php echo $this->gateway ?>_iban" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_iban" )); ?>" style='width: 40em;' /><br />
				  </td>
			  </tr>		
			  <tr valign="top">
				  <th scope="row"><?php _e('BIC (formerly Swift)', 'poptheme-wassup') ?></th>
				  <td>
					<input type="text" name="<?php echo $this->gateway ?>_bic" value="<?php esc_attr_e(get_option('em_'. $this->gateway . "_bic" )); ?>" style='width: 40em;' /><br />
				  </td>
			  </tr>				  		  		  	  	  
			</tbody>
			</table>		
	
			<?php
		}
	
		/* 
		 * Run when saving PayPal settings, saves the settings available in EM_Gateway_Paypal::mysettings()
		 */
		function update() {
			parent::update();
			
			// Generate the 'form' field displaying all non-empty account details
			$form = '';
			$fields = array (	
						// Form message
						"_form_message" => "<p>%s</p>",
						"_form_account_details" => "<p><h4>%s</h4></p>", 
						
						// Account details
						"_account_name" => "<p>" . __("Account name: %s", "greendrinks") . "</p>",
						"_account_number" => "<p>" . __("Account number: %s", "greendrinks") . "</p>",
						"_sort_code" => "<p>" . __("Sort code: %s", "greendrinks") . "</p>",
						"_bank_name" => "<p>" . __("Bank name: %s", "greendrinks") . "</p>",
						"_iban" => "<p>" . __("IBAN: %s", "greendrinks") . "</p>",
						"_bic" => "<p>" . __("BIC (formerly Swift): %s", "greendrinks") . "</p>"
						);					
			foreach ($fields as $key => $format) {
			
				if ($_REQUEST[ $this->gateway . $key ])
					$form .= sprintf($format, $_REQUEST[ $this->gateway . $key ]);
			}		
				
			
			$gateway_options = array(
				$this->gateway . "_button" => $_REQUEST[ $this->gateway . '_button' ],
				//$this->gateway . "_form" => $_REQUEST[ $this->gateway . '_form' ],
				$this->gateway . "_booking_feedback" => $_REQUEST[ $this->gateway . '_booking_feedback' ],
				
				// Form message
				$this->gateway . "_form_message" => $_REQUEST[ $this->gateway . '_form_message' ],
				$this->gateway . "_form_account_details" => $_REQUEST[ $this->gateway . '_form_account_details' ],
				
				// Account details
				$this->gateway . "_account_name" => $_REQUEST[ $this->gateway . '_account_name' ],
				$this->gateway . "_account_number" => $_REQUEST[ $this->gateway . '_account_number' ],
				$this->gateway . "_sort_code" => $_REQUEST[ $this->gateway . '_sort_code' ],
				$this->gateway . "_bank_name" => $_REQUEST[ $this->gateway . '_bank_name' ],
				$this->gateway . "_iban" => $_REQUEST[ $this->gateway . '_iban' ],
				$this->gateway . "_bic" => $_REQUEST[ $this->gateway . '_bic' ],
				
				// Form		
				$this->gateway . "_form" => $form
			);
			
			foreach($gateway_options as $key=>$option){
				update_option('em_'.$key, stripslashes($option));
			}
			//default action is to return true
			return true;
	
		}
	}
	EM_Gateways::register_gateway('banktransfer', 'EM_Gateway_BankTransfer');
}