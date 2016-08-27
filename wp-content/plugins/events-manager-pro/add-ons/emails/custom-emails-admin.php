<?php
class EM_Custom_Emails_Admin {
    public static $caps = array('custom_emails'=>'manage_others_bookings');
    
    public static function init(){
		//Custom Event Emails
		if( get_option('dbem_custom_emails_events') ){
			if( !get_option('dbem_multiple_bookings') || get_option('dbem_multiple_bookings_contact_email') ){
				add_action('add_meta_boxes', 'EM_Custom_Emails_Admin::meta_boxes');
				add_filter('em_event_get_post', 'EM_Custom_Emails_Admin::em_event_get_post',100,2);
				if( get_option('dbem_custom_emails_events_admins')){
					add_filter('em_event_validate_meta', 'EM_Custom_Emails_Admin::em_event_validate_meta',100,2);
				}
				add_filter('em_event_save_meta', 'EM_Custom_Emails_Admin::em_event_save_meta',100,2);
			}
		}
		//Custom Gateway Emails
		if( get_option('dbem_custom_emails_gateways') ){
			add_action('em_gateway_settings_footer','EM_Custom_Emails_Admin::em_gateway_settings_footer', 10, 1);
			add_action('em_gateway_update','EM_Custom_Emails_Admin::em_gateway_update', 10, 1);
		}
    }
    
    /*
     * --------------------------------------------
     * General Editor Functions for Overriding Emails
     * --------------------------------------------
     */
	
	/**
	 * Builds form for overriding booking emails
	 * @param array $emails_array_custom
	 * @param array $emails_values_custom
	 */
	public static function emails_editor( $emails_values_custom = array(), $emails_array_custom = array(), $admin_emails_custom = array(), $param = 'em_custom_email' ){
		//build structure of emails to show
		$emails_array = !empty($emails_array_custom) ? $emails_array_custom : self::get_default_emails(); //override defaults
		$default_emails = self::get_default_email_values();
		$emails_values = !empty($emails_values_custom) ? $emails_values_custom : self::get_default_email_values(); //override defaults
		$group_count = count($emails_array);
		//output the structure in an html form format
		?>
		<div class="emp-cet">
			<?php foreach( $emails_array as $email_array_group_name => $email_array_group ): ?>
			<h4 class="emp-cet-group-trigger">
				<?php if($group_count > 1 ): ?>
				<a href="#" rel="#emp-cet-group-<?php echo $email_array_group_name; ?>">
				<?php endif; ?>
				<?php echo $email_array_group['title'] ?>
				<?php if($group_count > 1 ): ?>
				<span>+</span></a>
				<?php endif; ?>
			</h4>
			<div class="emp-cet-group" id="emp-cet-group-<?php echo $email_array_group_name; ?>">
				<div><?php if(!empty($email_array_group['text']) ) echo $email_array_group['text']; ?></div>
				
				<?php if( is_array($admin_emails_custom) ): ?>
				<label><?php _e('Also Send Event Owner Emails To:', 'em-pro'); ?></label>
				<input type="text" class="emp-cet-email" name="<?php echo $param ?>_admins[<?php echo $email_array_group_name; ?>]" value="<?php if( !empty($admin_emails_custom[$email_array_group_name]) ) echo $admin_emails_custom[$email_array_group_name]; ?>" /><br />
				<em><?php _e('For multiple emails, seperate by commas (e.g. email1@test.com,email2@test.com,etc.)','dbem'); ?></em>
				<?php endif; ?>				
				
				<?php foreach( $email_array_group['subgroups'] as $email_array_subgroup => $email_array ) : ?>
				<h5 class="emp-cet-subgroup-trigger"><a href="#" rel="#emp-cet-subgroup-<?php echo $email_array_subgroup; ?>"><?php echo $email_array['title']; ?> <span>+</span></a></h5>
				<div class="emp-cet-subgroup" id="emp-cet-subgroup-<?php echo $email_array_subgroup; ?>" >
					<p><?php echo $email_array['text']; ?></p>
					<?php foreach( $email_array['emails'] as $email_type_name => $email_type ): ?>
					<div class="emp-cet-subgroup-email">
						<?php 
							$status = !empty($emails_values[$email_array_subgroup][$email_type_name]['status']) ? $emails_values[$email_array_subgroup][$email_type_name]['status'] : 0;
							$subject = !empty($emails_values[$email_array_subgroup][$email_type_name]['subject']) ? $emails_values[$email_array_subgroup][$email_type_name]['subject'] : $default_emails[$email_array_subgroup][$email_type_name]['subject'];
							$message = !empty($emails_values[$email_array_subgroup][$email_type_name]['message']) ? $emails_values[$email_array_subgroup][$email_type_name]['message'] : $default_emails[$email_array_subgroup][$email_type_name]['message'];
						?>
						<select class="emp-cet-status" name="<?php echo $param; ?>[<?php echo $email_array_subgroup; ?>][<?php echo $email_type_name; ?>][status]">
							<option value="0" class="emp-default"><?php esc_html_e('Default','em-pro'); ?></option>
							<option value="1" class="emp-enabled" <?php if( $status == 1) echo 'selected="selected"' ?>><?php esc_html_e('Enabled','em-pro'); ?></option>
							<option value="2" class="emp-disabled" <?php if( $status == 2) echo 'selected="selected"' ?>><?php esc_html_e('Disabled','em-pro'); ?></option>
						</select> 
						<strong><?php echo $email_type['title'] ?></strong>
						<div class="emp-cet-vals">
							<div class="emp-cet-val">
								<label><?php _e('Subject','em-pro'); ?></label>
								<input type="text" name="<?php echo $param; ?>[<?php echo $email_array_subgroup; ?>][<?php echo $email_type_name; ?>][subject]" value="<?php echo esc_attr($subject); ?>" />
							</div>
							<div class="emp-cet-val">
								<label><?php _e('Title','em-pro'); ?></label>
								<textarea name="<?php echo $param; ?>[<?php echo $email_array_subgroup; ?>][<?php echo $email_type_name; ?>][message]"><?php echo esc_html($message); ?></textarea>
							</div>
						</div>
					</div>
					<?php endforeach; ?>
				</div>
				<?php endforeach; ?>
			</div>
			<?php endforeach; ?>
		</div>		
		<script type="text/javascript">
			jQuery(document).ready(function($){
				//hide all groups by default unless only 1 exists, then just subgroups
				var trigger_selector = '.emp-cet-subgroup-trigger a';
				$('.emp-cet-subgroup').hide();
				if( $('.emp-cet-group').length > 1 ){
					$('.emp-cet-group').hide();
					trigger_selector = '.emp-cet-group-trigger a, .emp-cet-subgroup-trigger a';
				}
				//add listener to triggers for groups and subgroups
				$(trigger_selector).click(function(e){
					e.preventDefault();
					var a = $(this);
					if( a.children('span').text() == '+'){
						$(a.attr('rel')).show();
						a.children('span').text('-');
					}else{
						$(a.attr('rel')).hide();
						a.children('span').text('+');
					}
				});
				
				//add listeners to individual email status changes
				$('.emp-cet select.emp-cet-status').change(function(){
					var el = $(this);
					var selected = el.find('option:selected');
					el.attr('class',selected.attr('class'));
					//show or hide
					if( selected.val() != 1 ){
						el.nextAll('.emp-cet-vals').hide();
					}else{
						el.nextAll('.emp-cet-vals').show();
					}
					//show subgroup and groups if not default value
					if( selected.val() != 0 ){
						var subgroup = el.closest('.emp-cet-subgroup').show();
						subgroup.prevAll('.emp-cet-subgroup-trigger').first().find('a span').text('-');
						if( $('.emp-cet-group').length > 1 ){
							var group = subgroup.closest('.emp-cet-group').show();
							group.prevAll('.emp-cet-group-trigger').first().find('a span').text('-');
						}
					}
				}).trigger('change');
				$('.emp-cet input.emp-cet-email').each(function(){
					if( this.value != '' ){
						var group = $(this).closest('.emp-cet-group').show();
						group.prevAll('.emp-cet-group-trigger').first().find('a span').text('-');
					}
				})
			});
		</script>
		<?php
	}
	
	public static function get_default_emails(){
		$emails = array(
			'default' => array(
				'title' => __('Default or Free Booking Emails','em-pro'),
				'subgroups' => array(
					'admin'=>array(
						'title' => __('Event Owner Emails','em-pro'),
						'text' => __('These emails get sent to the event owners when a person has made a booking.','em-pro'),
						'emails' => array(
							0 => array('title'=> __('Pending booking email','dbem')),
							1 => array('title'=> __('Confirmed booking email','dbem')),
							3 => array('title'=> __('Booking cancelled','dbem'))
						)
					),
					'user'=>array(
						'title' => __('Attendee Emails','em-pro'),
						'text' => __('These emails will be sent to the person who booked a place at your event.','em-pro'),
						'emails' => array(
							0 => array('title'=> __('Pending booking email','dbem')),
							1 => array('title'=> __('Confirmed booking email','dbem')),
							2 => array('title'=> __('Rejected booking email','dbem')),
							3 => array('title'=> __('Booking cancelled','dbem'))
						)
					)
				)
			)
		);
		if( get_option('dbem_custom_emails_gateways') ){
			$emails = array_merge($emails, self::get_gateway_default_emails());
		}
		return $emails;
	}
	
	public static function get_default_email_values(){
		//build structure of values to add to these emails
		$email_values = array(
			'admin'=>array(
				0 => array('subject'=>get_option('dbem_bookings_contact_email_subject'),'message'=>get_option('dbem_bookings_contact_email_body'),'status'=>0),
				1 => array('subject'=>get_option('dbem_bookings_contact_email_subject'),'message'=>get_option('dbem_bookings_contact_email_body'),'status'=>0),
				3 => array('subject'=>get_option('dbem_contactperson_email_cancelled_subject'),'message'=>get_option('dbem_contactperson_email_cancelled_body'),'status'=>0)
			),
			'user'=>array(
				0 => array('subject'=>get_option('dbem_bookings_email_pending_subject'),'message'=>get_option('dbem_bookings_email_pending_body'),'status'=>0),
				1 => array('subject'=>get_option('dbem_bookings_email_confirmed_subject'),'message'=>get_option('dbem_bookings_email_confirmed_body'),'status'=>0),
				2 => array('subject'=>get_option('dbem_bookings_email_rejected_subject'),'message'=>get_option('dbem_bookings_email_rejected_body'),'status'=>0),
				3 => array('subject'=>get_option('dbem_bookings_email_cancelled_subject'),'message'=>get_option('dbem_bookings_email_cancelled_body'),'status'=>0)
			)
		);
		if( get_option('dbem_custom_emails_gateways') ){
			$email_values = array_merge($email_values, self::get_gateway_default_values());
		}
		return $email_values;
	}
	
	public static function editor_get_post( $custom_email_defaults = array(), $param = 'em_custom_email' ){
		global $allowedposttags;
		$emails = !empty($custom_email_defaults) ? $custom_email_defaults : self::get_default_emails(); //override defaults
		$custom_emails = array();
		foreach( $emails as $email_group_name => $email_group ){
			foreach( $email_group['subgroups'] as $email_subgroup_name => $email_subgroup ){
				foreach( $email_subgroup['emails'] as $email_type_name => $email_type ){
					if( isset($_REQUEST[$param][$email_subgroup_name][$email_type_name]['status']) ){
						//status is set, save it and message if needed
						$custom_emails[$email_subgroup_name][$email_type_name]['status'] = $_REQUEST[$param][$email_subgroup_name][$email_type_name]['status'];
						if( $_REQUEST[$param][$email_subgroup_name][$email_type_name]['status'] == 1 ){
							//only if enabled do we need to save an email format
							$custom_emails[$email_subgroup_name][$email_type_name]['subject'] = stripslashes(wp_kses_data($_REQUEST[$param][$email_subgroup_name][$email_type_name]['subject']));
							$custom_emails[$email_subgroup_name][$email_type_name]['message'] = wp_kses(stripslashes($_REQUEST[$param][$email_subgroup_name][$email_type_name]['message']), $allowedposttags);
						}
					}
				}
			}
		}
		return $custom_emails;
	}
	
	public static function editor_admin_emails_get_post( $custom_email_defaults = array(), $param = 'em_custom_email_admins' ){
		$emails = !empty($custom_email_defaults) ? $custom_email_defaults : self::get_default_emails(); //override defaults
		$custom_admin_emails = array();
		foreach( $emails as $email_group_name => $email_group ){
			if( isset($_REQUEST[$param][$email_group_name]) ){
				$custom_admin_emails[$email_group_name] = str_replace(' ', '', $_REQUEST[$param][$email_group_name]);
			}
		}
		return $custom_admin_emails;
	}
	
    /*
     * --------------------------------------------
     * Custom Gateway Booking Emails
     * --------------------------------------------
     */
	public static function em_gateway_settings_footer( $EM_Gateway ){
		$default_emails = self::get_gateway_default_emails($EM_Gateway);
		$email_values = self::get_gateway_default_values($EM_Gateway);
		$admin_emails = false;
		if( get_option('dbem_custom_emails_gateways_admins') ){
			$admin_emails = EM_Custom_Emails::get_gateway_admin_emails($EM_Gateway);
		}
		$gateway = $EM_Gateway->gateway;
		$default_emails[$gateway]['title'] = __('Booking Email Templates','em-pro');
		$default_emails[$gateway]['text'] = '<p>'. sprintf(__('Below you can modify the emails that are sent when bookings are made. This will override the default emails located in your %s settings page.','em-pro'), '<a href="'.admin_url('edit.php?post_type=event&page=events-manager-options#emails:booking-emails').'">'.__('Booking Email Templates','dbem').'</a>');
		$default_emails[$gateway]['text'] .= __('Additionally, you can also override these emails at an event level when editing an event. Should you choose not to, any overriden emails on this page will be considered the default email for this gateway.','em-pro') .'</p>';
		$default_emails[$gateway]['text'] .= '<p>'. __('Note that some gateways do not automatically send pending or confirmed emails, in these cases they may only apply to when event admins manually change the status of a booking resulting in an automated email getting sent.','em-pro').'</p>';
		$default_emails[$gateway]['text'] .= '<p>'. __('Click on the title texts with a plus (+) next to them to reveal further options, and the minus (-) sign to hide them.','em-pro').'</p>';
		if( get_option('dbem_multiple_bookings') ){
			$default_emails[$gateway]['text'] =  '<p><strong>'.__('Important:','em-pro').'</strong> '. __('You are in Multiple Booking Mode. These emails will only be used when individually modifying bookings which trigger these individual event booking emails.','em-pro') .'</p>'. $default_emails[$gateway]['text'];
			//duplicate default emails array and give them different keys
			$default_emails = self::add_gateway_mb_default_emails($default_emails, $EM_Gateway);
			//alter texts
			$default_emails[$gateway.'-mb']['title'] = __('Multiple Booking Email Templates','em-pro');
			$default_emails[$gateway.'-mb']['text'] = '<p>'. sprintf(__('Below you can modify the emails that are sent when bookings are made. This will override the default emails located in your %s settings page.','em-pro'), '<a href="'.admin_url('edit.php?post_type=event&page=events-manager-options#emails:multiple-booking-emails').'">'.__('Multiple Booking Email Templates','em-pro').'</a>');
			$default_emails[$gateway.'-mb']['text'] .= '<p>'. __('Note that some gateways do not automatically send pending or confirmed emails, in these cases they may only apply to when event admins manually change the status of a booking resulting in an automated email getting sent.','em-pro').'</p>';
			$default_emails[$gateway.'-mb']['text'] .= '<p>'. __('Click on the title texts with a plus (+) next to them to reveal further options, and the minus (-) sign to hide them.','em-pro').'</p>';		
			//get default mb values and merge them into email values
			$mb_email_values = self::get_gateway_mb_default_values($gateway);
			$email_values = array_merge($email_values, $mb_email_values);
		}
		self::emails_editor($email_values, $default_emails, $admin_emails);
	}
	
	public static function add_gateway_mb_default_emails( $default_emails, $EM_Gateway ){
		$gateway = $EM_Gateway->gateway;
		$default_emails[$gateway.'-mb'] = $default_emails[$gateway];
		$default_emails[$gateway.'-mb']['subgroups'][$gateway.'-mb-admin'] = $default_emails[$gateway.'-mb']['subgroups'][$gateway.'-admin'];
		$default_emails[$gateway.'-mb']['subgroups'][$gateway.'-mb-user'] = $default_emails[$gateway.'-mb']['subgroups'][$gateway.'-user'];
		unset($default_emails[$gateway.'-mb']['subgroups'][$gateway.'-admin']);
		unset($default_emails[$gateway.'-mb']['subgroups'][$gateway.'-user']);
		return $default_emails;
	}
	
	public static function em_gateway_update( $EM_Gateway ){
		//update templates
		$default_emails = self::add_gateway_mb_default_emails(self::get_gateway_default_emails($EM_Gateway), $EM_Gateway);
		$custom_booking_emails = self::editor_get_post( $default_emails );
		$EM_Gateway->update_option('emails', serialize($custom_booking_emails));
		//update admin email addresses
		if( get_option('dbem_custom_emails_gateways_admins') ){
			$custom_admin_emails = self::editor_admin_emails_get_post( $default_emails );
			//validate emails
			$email_errors = false;
			if( !empty($custom_admin_emails) && is_array($custom_admin_emails) ){
				foreach($custom_admin_emails as $group_key => $emails){
					if( !empty($emails) ){
						$emails = explode(',',$emails);
						foreach ($emails as $email){
							if( !is_email($email) ){
								unset($custom_admin_emails[$group_key]);
								$email_errors = true;
							}
						}
					}else{
						$custom_admin_emails[$group_key] = ''; //empty = no email
					}
				}
			}
			if($email_errors){
				global $EM_Notices;
				$EM_Notices->add_error(__('An invalid admin email was supplied for your custom emails and was not saved in your settings.','em-pro'),true);
			}else{
				$EM_Gateway->update_option('emails_admins', serialize($custom_admin_emails));
			}
		}
	}

	public static function get_gateway_default_emails( $EM_Gateway = false ){
		global $EM_Gateways;
		$emails = array();
		$gateways = is_object($EM_Gateway) ? array($EM_Gateway->gateway => $EM_Gateway->title) : EM_Gateways::active_gateways();		
		foreach($gateways as $gateway => $gateway_name ){
			$emails[$gateway] = array(
				'title' => sprintf(__('%s Gateway Emails','em-pro'), $gateway_name),
				'subgroups' => array(
					$gateway.'-admin'=>array(
						'title' => __('Event Owner Emails','em-pro'),
						'text' => __('These emails get sent to the event owners when a person has made a booking using this specific gateway.','em-pro'),
						'emails' => array(
							0 => array('title'=> __('Pending booking email','dbem')),
							1 => array('title'=> __('Confirmed booking email','dbem')),
							3 => array('title'=> __('Booking cancelled','dbem'))
						)
					),
					$gateway.'-user'=>array(
						'title' => __('Attendee Emails','em-pro'),
						'text' => __('These emails will be sent to the person who booked a place at your event and selected this specific gateway.','em-pro'),
						'emails' => array(
							0 => array('title'=> __('Pending booking email','dbem')),
							1 => array('title'=> __('Confirmed booking email','dbem')),
							2 => array('title'=> __('Rejected booking email','dbem')),
							3 => array('title'=> __('Booking cancelled','dbem'))
						)
					)
				)
			);
			//temporary fix, we assume everything is online except for offline - maybe a good reason for split offline/online base gateway subclasses
			if( $gateway == 'offline' ){
				$emails[$gateway]['subgroups'][$gateway.'-admin']['emails'][5] = array('title'=> __('Awaiting Offline Payment','em-pro'));
				$emails[$gateway]['subgroups'][$gateway.'-user']['emails'][5] = array('title'=> __('Awaiting Offline Payment','em-pro'));
			}
		}
		return $emails;
	}
	
	public static function get_gateway_default_values( $EM_Gateway = false ){
		global $EM_Gateways;
		$email_values = array();
		$gateways = is_object($EM_Gateway) ? array($EM_Gateway->gateway => $EM_Gateway->title) : EM_Gateways::active_gateways();
		foreach($gateways as $gateway => $gateway_name ){
			$email_values[$gateway.'-admin'] = array(
				0 => array('subject'=>get_option('dbem_bookings_contact_email_subject'),'message'=>get_option('dbem_bookings_contact_email_body'),'status'=>0),
				1 => array('subject'=>get_option('dbem_bookings_contact_email_subject'),'message'=>get_option('dbem_bookings_contact_email_body'),'status'=>0),
				3 => array('subject'=>get_option('dbem_contactperson_email_cancelled_subject'),'message'=>get_option('dbem_contactperson_email_cancelled_body'),'status'=>0)
			);
			$email_values[$gateway.'-user'] = array(
				0 => array('subject'=>get_option('dbem_bookings_email_pending_subject'),'message'=>get_option('dbem_bookings_email_pending_body'),'status'=>0),
				1 => array('subject'=>get_option('dbem_bookings_email_confirmed_subject'),'message'=>get_option('dbem_bookings_email_confirmed_body'),'status'=>0),
				2 => array('subject'=>get_option('dbem_bookings_email_rejected_subject'),'message'=>get_option('dbem_bookings_email_rejected_body'),'status'=>0),
				3 => array('subject'=>get_option('dbem_bookings_email_cancelled_subject'),'message'=>get_option('dbem_bookings_email_cancelled_body'),'status'=>0)
			);
			//temporary fix, we assume everything is online except for offline - maybe a good reason for split offline/online base gateway subclasses
			if( $gateway == 'offline' ){
				$email_values[$gateway.'-admin'][5] = array('subject'=>get_option('dbem_bookings_contact_email_subject'),'message'=>get_option('dbem_bookings_contact_email_body'),'status'=>0);
				$email_values[$gateway.'-user'][5] = array('subject'=>get_option('dbem_bookings_email_pending_subject'),'message'=>get_option('dbem_bookings_email_pending_body'),'status'=>0);
			}
			//get custom values if applicable
			$possible_email_values = maybe_unserialize(get_option('em_'.$gateway . "_emails"));
			$email_values = self::merge_gateway_default_values($gateway, $email_values, $possible_email_values, count($gateways) > 1);
		}
		return $email_values;
	}
	
	public static function get_gateway_mb_default_values( $gateway ){
		$email_values = array();
		$email_values[$gateway.'-mb-admin'] = array(
			0 => array('subject'=>get_option('dbem_multiple_bookings_contact_email_subject'),'message'=>get_option('dbem_multiple_bookings_contact_email_body'),'status'=>0),
			1 => array('subject'=>get_option('dbem_multiple_bookings_contact_email_subject'),'message'=>get_option('dbem_multiple_bookings_contact_email_body'),'status'=>0),
			3 => array('subject'=>get_option('dbem_multiple_bookings_contact_email_cancelled_subject'),'message'=>get_option('dbem_multiple_bookings_contact_email_cancelled_body'),'status'=>0)
		);
		$email_values[$gateway.'-mb-user'] = array(
			0 => array('subject'=>get_option('dbem_multiple_bookings_email_pending_subject'),'message'=>get_option('dbem_multiple_bookings_email_pending_body'),'status'=>0),
			1 => array('subject'=>get_option('dbem_multiple_bookings_email_confirmed_subject'),'message'=>get_option('dbem_multiple_bookings_email_confirmed_body'),'status'=>0),
			2 => array('subject'=>get_option('dbem_multiple_bookings_email_rejected_subject'),'message'=>get_option('dbem_multiple_bookings_email_rejected_body'),'status'=>0),
			3 => array('subject'=>get_option('dbem_multiple_bookings_email_cancelled_subject'),'message'=>get_option('dbem_multiple_bookings_email_cancelled_body'),'status'=>0)
		);
		//temporary fix, we assume everything is online except for offline - maybe a good reason for split offline/online base gateway subclasses
		if( $gateway == 'offline' ){
			$email_values[$gateway.'-mb-admin'][5] = array('subject'=>get_option('dbem_multiple_bookings_contact_email_subject'),'message'=>get_option('dbem_multiple_bookings_contact_email_body'),'status'=>0);
			$email_values[$gateway.'-mb-user'][5] = array('subject'=>get_option('dbem_multiple_bookings_email_pending_subject'),'message'=>get_option('dbem_multiple_bookings_email_pending_body'),'status'=>0);
		}
		//get custom values if applicable
		$possible_email_values = maybe_unserialize(get_option('em_'.$gateway . "_emails"));
		$email_values = self::merge_gateway_default_values($gateway, $email_values, $possible_email_values);
		return $email_values;
	}
	
	public static function merge_gateway_default_values( $gateway, $email_values, $possible_email_values, $messages_only = false ){
		if( is_array($possible_email_values) ){
			foreach( $possible_email_values as $subgroup_name => $subgroup_msgs ){
				foreach($subgroup_msgs as $msg_key => $msg_data ){
					if( isset($email_values[$subgroup_name][$msg_key]['status']) && $email_values[$subgroup_name][$msg_key]['status'] != 1 ){
						if( $msg_data['status'] == 1 ){
							if( !$messages_only ){
								//gateway default is either disabled or set to the default, so we just override the status
								$email_values[$subgroup_name][$msg_key]['status'] = 1;
							}
							//add the new default subject/message to email values (without changing the status)
							$email_values[$subgroup_name][$msg_key]['subject'] = $msg_data['subject'];
							$email_values[$subgroup_name][$msg_key]['message'] = $msg_data['message'];
						}else{
							if( !$messages_only ){
								//gateway default is either disabled or set to the default, so we just override the status
								$email_values[$subgroup_name][$msg_key] = array_merge($email_values[$subgroup_name][$msg_key], $msg_data);
							}
						}
					}
				}
			}
		}
		return $email_values;
	}
	
    /*
     * --------------------------------------------
     * Custom Event Booking Emails
     * --------------------------------------------
     */
	
	public static function meta_boxes(){
		if( current_user_can(self::$caps['custom_emails']) ){
			add_meta_box('em-event-custom-emails', __('Custom Automated Emails','em-pro'), array('EM_Custom_Emails_Admin','event_meta_box'),EM_POST_TYPE_EVENT, 'normal','low');
		}
	}
	
	public static function event_meta_box(){
		global $EM_Event;
		//get custom email values if they exist
		$custom_email_values = EM_Custom_Emails::get_event_emails($EM_Event);
		$custom_admin_emails = false;
		if( get_option('dbem_custom_emails_events_admins')){
			$custom_admin_emails = EM_Custom_Emails::get_event_admin_emails($EM_Event);
		}
		echo '<p>'. sprintf(__('Below you can modify the emails that are sent when bookings are made. This will override the default emails located in your %s settings page.','em-pro'), '<a href="'.admin_url('edit.php?post_type=event&page=events-manager-options#emails:booking-emails').'">'.__('Booking Email Templates','dbem').'</a>');
		if( get_option('dbem_custom_emails_gateways') ){
			echo '<p>'. sprintf(__('You can also create default emails for specific gateways in your individual %s settings page.','em-pro'), '<a href="'.admin_url('edit.php?post_type=event&page=events-manager-gateways').'">'.__('Gateway Settings','dbem').'</a>');
		}
		echo "<p>".__('Click on the title texts with a plus (+) next to them to reveal further options, and the minus (-) sign to hide them.','em-pro')."</p>";
		self::emails_editor($custom_email_values, array(), $custom_admin_emails);
	}

	public static function em_event_get_post( $result, $EM_Event ){ 
		if( current_user_can(self::$caps['custom_emails']) ){
			$EM_Event->custom_booking_emails = self::editor_get_post();
			if( get_option('dbem_custom_emails_events_admins')){
				$EM_Event->custom_admin_emails = self::editor_admin_emails_get_post();
			}
		}
		return $result;
	}
	
	public static function em_event_validate_meta( $result, $EM_Event ){
		//validate emails
		$email_errors = false;
		if( !empty($EM_Event->custom_admin_emails) && is_array($EM_Event->custom_admin_emails) ){
			foreach($EM_Event->custom_admin_emails as $group_key => $emails){
				if( !empty($emails) ){
					$emails = explode(',',$emails);
					foreach ($emails as $email){
						if( !is_email($email) ){
							unset($EM_Event->custom_admin_emails[$group_key]);
							$email_errors = true;
						}
					}
				}
			}
		}
		if($email_errors){
			global $EM_Notices;
			$EM_Notices->add_error(__('An invalid admin email was supplied for your custom emails and was not saved in your settings.','em-pro'),true);
		}
		return $result;
	}
	
	public static function em_event_save_meta( $result, $EM_Event ){
		global $wpdb;
		if( current_user_can(self::$caps['custom_emails']) && !empty($EM_Event->post_id) ){
			if( !empty($EM_Event->custom_booking_emails) ){
				$sql = $wpdb->prepare('SELECT meta_id FROM '.EM_META_TABLE." WHERE object_id = %d AND meta_key = %s LIMIT 1", $EM_Event->event_id, 'event-emails');
				$meta_id = $wpdb->get_var($sql);
				if( $meta_id > 0 ){
					$wpdb->update(EM_META_TABLE, array('meta_value'=>serialize($EM_Event->custom_booking_emails)), array('meta_id'=>$meta_id), array('%s'));
				}else{
					$wpdb->insert(EM_META_TABLE, array('object_id'=>$EM_Event->event_id, 'meta_key'=>'event-emails', 'meta_value' => serialize($EM_Event->custom_booking_emails)), array('%d','%s','%s'));
				}
			}
			if( get_option('dbem_custom_emails_events_admins')){
				if( !empty($EM_Event->custom_admin_emails) ){
					$sql = $wpdb->prepare('SELECT meta_id FROM '.EM_META_TABLE." WHERE object_id = %d AND meta_key = %s LIMIT 1", $EM_Event->event_id, 'event-admin-emails');
					$meta_id = $wpdb->get_var($sql);
					if( $meta_id > 0 ){
						$wpdb->update(EM_META_TABLE, array('meta_value'=>serialize($EM_Event->custom_admin_emails)), array('meta_id'=>$meta_id), array('%s'));
					}else{
						$wpdb->insert(EM_META_TABLE, array('object_id'=>$EM_Event->event_id, 'meta_key'=>'event-admin-emails', 'meta_value' => serialize($EM_Event->custom_admin_emails)), array('%d','%s','%s'));
					}
				}
			}
		}
		return $result;
	}
}
EM_Custom_Emails_Admin::init();