<?php
class EM_Attendee_Form {
	static $validate;
	/**
	 * @var EM_Form
	 */
	static $form;
	static $form_id;
	static $form_name;
	static $form_template;
	
	function init(){	
		//Menu/admin page
		add_action('admin_init',array('EM_Attendee_Form', 'admin_page_actions'), 10);
		add_action('emp_forms_admin_page',array('EM_Attendee_Form', 'admin_page'),11);
		/*
		//Booking Admin Pages & Exports
		add_action('em_bookings_single_custom', array('EM_Attendee_Form', 'em_bookings_single_custom'),1,1); //show booking form and ticket summary
		add_action('em_csv_bookings_loop_after', array('EM_Attendee_Form', 'em_csv_bookings_loop_after'),1,3); //show booking form and ticket summary
		add_action('em_csv_bookings_headers', array('EM_Attendee_Form', 'em_csv_bookings_headers'),1,1); //show booking form and ticket summary
		//Booking Tables UI
		add_filter('em_bookings_table_rows_col', array('EM_Attendee_Form','em_bookings_table_rows_col'),10,5);
		add_filter('em_bookings_table_cols_template', array('EM_Attendee_Form','em_bookings_table_cols_template'),10,2);
		*/
		// Actions and Filters
		add_action('em_gateway_js', array('EM_Attendee_Form','js'),10,2);
		add_action('em_booking_form_ticket_spaces', array('EM_Attendee_Form','ticket_form'),10,1);
		add_action('em_booking_form_tickets_loop_footer', array('EM_Attendee_Form','tickets_form'),10,1); 
		//Booking interception
		add_filter('em_booking_get_post_pre', array('EM_Attendee_Form', 'em_booking_get_post_pre'), 1, 1); //turns on flag so we know not to double validate
		add_filter('em_booking_get_post', array('EM_Attendee_Form', 'em_booking_get_post'), 1, 2); //get post data + validate
		//add_filter('em_booking_validate', array('EM_Attendee_Form', 'em_booking_validate'), 1, 2); //validate object
		//add_filter('em_bookings_add', array('EM_Attendee_Form', 'em_bookings_add'), 1, 1); //add extra use reg data
		//Placeholder overriding	
		add_filter('em_booking_output_placeholder',array('EM_Attendee_Form','placeholders'),1,3); //for emails
		//custom form chooser in event bookings meta box:
		add_action('emp_bookings_form_select_footer',array('EM_Attendee_Form', 'event_attendee_custom_form'),20,1);
		add_filter('em_event_save_meta',array('EM_Attendee_Form', 'em_event_save_meta'),10,2);
		self::$form_template = array (
			'name' => array ( 'label' => __('Name','dbem'), 'type' => 'text', 'fieldid'=>'attendee_name', 'required'=>1 )
		);
	}
	
	/**
	 * @param EM_Booking $EM_Booking
	 */
	function get_form($EM_Event = false){
		if( empty(self::$form) ){
			global $wpdb;
			if(is_numeric($EM_Event)){ $EM_Event = new EM_Event($EM_Event); }
			$custom_form_id = ( !empty($EM_Event->post_id) ) ? get_post_meta($EM_Event->post_id, '_custom_attendee_form', true):0;
			$form_id = empty($custom_form_id) ? get_option('em_attendee_form_fields') : $custom_form_id;
			if( $form_id > 0 ){
				$sql = $wpdb->prepare("SELECT meta_id, meta_value FROM ".EM_META_TABLE." WHERE meta_key = 'attendee-form' AND meta_id=%d", $form_id);
				$form_data_row = $wpdb->get_row($sql, ARRAY_A);
				if( empty($form_data_row) ){
					$form_data = self::$form_template;
					self::$form_name = __('Default','em-pro');
				}else{
					$form_data = unserialize($form_data_row['meta_value']);
					self::$form_id = $form_data_row['meta_id'];
					self::$form_name = $form_data['name'];
				}
				self::$form = new EM_Form($form_data['form'], 'em_attendee_form', false);
			}else{
			    self::$form = new EM_Form(array(), 'em_attendee_form', false); //empty form to avoid errors
			}
			self::$form->form_required_error = get_option('em_attendee_form_error_required');
		}
		//modify field ids to contain ticket number and []
		foreach(self::$form->form_fields as $field_id => $form_data){
		    if( $form_data['type'] == 'date' || $form_data['type'] == 'time'){
				self::$form->form_fields[$field_id]['name'] = "em_attendee_fields[{$EM_Ticket->ticket_id}][$field_id][%s][]";
		    }else{
				self::$form->form_fields[$field_id]['name'] = "em_attendee_fields[{$EM_Ticket->ticket_id}][$field_id][]";
		    }
		}
		return self::$form;
	}
	
	function get_forms(){
		global $wpdb;
		$forms_data = $wpdb->get_results("SELECT meta_id, meta_value FROM ".EM_META_TABLE." WHERE meta_key = 'attendee-form'");
		foreach($forms_data as $form_data){
			$form = unserialize($form_data->meta_value);
			$forms[$form_data->meta_id] = $form['form'];
		}
		return $forms;
	}
	
	function get_forms_names(){
		global $wpdb;
		$forms_data = $wpdb->get_results("SELECT meta_id, meta_value FROM ".EM_META_TABLE." WHERE meta_key = 'attendee-form'");
		foreach($forms_data as $form_data){
			$form = unserialize($form_data->meta_value);
			$forms[$form_data->meta_id] = $form['name'];
		}
		return $forms;
	}
	
	/**
	 * replaces default js to 
	 * @param string $original_js
	 * @param EM_Event $EM_Event
	 * 
	 * @return string
	 */
	function js(){
		include('bookings-attendees-form.js');
	}
	
	/**
	 * Fore each ticket row in the booking table, add a hidden row with ticket form
	 * @param EM_Ticket $EM_Tickets
	 */
	function tickets_form($EM_Ticket){
		$form = self::get_form();
		$col_numbers = $EM_Ticket->get_event()->get_bookings()->get_tickets()->get_ticket_collumns();
		?>
		<tr class="em-attendee-details" id="em-attendee-details-<?php echo $EM_Ticket->ticket_id; ?>" <?php if($EM_Ticket->ticket_min == 0) echo 'style="display:none;"'?>>
			<td colspan="<?php echo count($col_numbers); ?>">
				<div class="em-attendee-fieldset">
					<?php
					for($i = 0; $i < $EM_Ticket->ticket_min; $i++ ){
						?>
						<div class="em-attendee-fields">
							<?php echo str_replace('#NUM#', $i+1, $form->__toString()); ?>
						</div>
						<?php
					}
					?>
				</div>
				<div class="em-attendee-fields-template" style="display:none;">
					<?php echo $form; ?>
				</div>
			</td>
		</tr>
		<?php
	}
	
	/**
	 * Fore each ticket row in the booking table, add a hidden row with ticket form
	 * @param EM_Ticket $EM_Ticket
	 */
	function ticket_form($EM_Ticket){
		$form = self::get_form($EM_Ticket->event_id);
		if( $EM_Ticket->ticket_min < 1 ) $EM_Ticket->ticket_min = 1;
		?>
		<div class="em-attendee-fieldset">
			<?php
			for($i = 0; $i < $EM_Ticket->ticket_min; $i++ ){
				?>
				<div class="em-attendee-fields">
					<?php echo str_replace('#NUM#', $i+1, $form->__toString()); ?>
				</div>
				<?php
			}
			?>
		</div>
		<div class="em-attendee-fields-template" style="display:none;">
		<?php echo $form; ?>
		</div>
		<?php
	}
	
	function em_booking_get_post_pre($EM_Booking){
		//echo "<pre>"; print_r($_REQUEST); echo "</pre>"; die();
		self::$validate = false;  //no need for a filter, use the em_booking_get_post_pre filter
	}
	
	/**
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return bool
	 */
	function em_booking_get_post($result, $EM_Booking){
		//get, store and validate post data 
		$EM_Form = self::get_form($EM_Booking->event_id);
		$attendee_values = self::get_post($EM_Booking);
		if( (empty($EM_Booking->booking_id) || (!empty($EM_Booking->booking_id) && $EM_Booking->can_manage())) ){
			foreach($EM_Form->form_fields as $fieldid => $value){
				//get results and put them into booking meta
				$EM_Booking->booking_meta['attendees'][$fieldid] = $value;
			}			
			if( self::$validate ){
				return self::validate();
			}			
		}
		if( count($EM_Form->get_errors()) > 0 ){
			$result = false;
			$EM_Booking->add_error($EM_Form->get_errors());
		}
		self::$validate = true;
		return $result;
	}
	
	/**
	 * @param EM_Booking $EM_Booking
	 * @return bool
	 */
	function get_post($EM_Booking){
		$EM_Form = self::get_form();
		$field_values = array();
		if( !empty($_REQUEST['em_attendee_fields']) ){
			foreach ($EM_Booking->get_tickets() as $EM_Ticket ){
			    if( !empty($_REQUEST['em_attendee_fields'][$EM_Ticket->ticket_id]) ){
			        $request = $_REQUEST['em_attendee_fields'][$EM_Ticket->ticket_id];
				    foreach($EM_Form->form_fields as $fieldid => $field){
				    	$value = '';
				    	if( isset($request[$fieldid]) ){
					    	if( in_array($field['type'], array('date','time')) ){
					    	    //print_r($request[$fieldid]);
					    	    foreach($request[$fieldid]['start'] as $i => $attendee){
					    	    	if( !empty($attendee) ){
					    	    		$field_values[$EM_Ticket->ticket_id][$fieldid][$i] = $attendee;
					    	    	}
					    	    }
					    	    foreach($request[$fieldid]['end'] as $i => $attendee){
					    	    	if( $field['options_'.$field['type'].'_range'] && !empty($attendee) ){
					    	    		$field_values[$EM_Ticket->ticket_id][$fieldid][$i] .= ','. $attendee;
					    	    	}
					    	    }
					    	}else{
					    	    foreach($request[$fieldid] as $i => $attendee){
						    	    //dates and time are special
							    	if( !is_array($attendee)){
							    		$field_values[$EM_Ticket->ticket_id][$fieldid][$i] = wp_kses_data(stripslashes($attendee));
							    	}elseif( is_array($attendee)){
							    		$field_values[$EM_Ticket->ticket_id][$fieldid][$i] = $attendee;
							    	}
					    	    }
					    	}
					    }
				    }
			    }
			}
		}
		return $field_values;
	}
	
	function validate(){
	    return false;
	}
	
	/**
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	function em_booking_validate($result, $EM_Booking){
		if( empty($EM_Booking->booking_id) && self::$validate ){
			//only run if taking post data, because validation could fail elsewhere
			$EM_Form = self::get_form($EM_Booking->event_id);		
			if( !$EM_Form->get_post() ){
			    $EM_Booking->add_error($EM_Form->get_errors());
				return false;
			}
		}
		return $result;
	}
	
	function em_bookings_add($result){
		global $EM_Booking;
		$EM_Form = self::get_form($EM_Booking->event_id);
		if( !empty($EM_Booking->booking_meta['registration']) && is_array($EM_Booking->booking_meta['registration']) &&  !get_option('dbem_bookings_registration_disable') ){
			$user_data = array();
			foreach($EM_Booking->booking_meta['registration'] as $fieldid => $field){
				if( trim($field) !== '' && array_key_exists($fieldid, $EM_Form->form_fields) ){
					$user_data[$fieldid] = $field;
				}
			}
			foreach($user_data as $userkey => $uservalue){
				update_user_meta($EM_Booking->person_id, $userkey, $uservalue);
			}
		}
		return $result;
	}

	/**
	 * @param string $replace
	 * @param EM_Booking $EM_Booking
	 * @param string $full_result
	 * @return string
	 */
	function placeholders($replace, $EM_Booking, $full_result){
		if( empty($replace) || $replace == $full_result ){
			$user = $EM_Booking->get_person();
			$EM_Form = self::get_form($EM_Booking->event_id);
			if( $full_result == '#_BOOKINGFORMAttendee' ){
				$replace = '';
				foreach($EM_Form->form_fields as $field){
					$replace .= "\r\n". $field['label'] .': ';
					if( !empty($user->$field['fieldid']) ){
						//user profile is freshest, using this
						$replace .= $user->$field['fieldid'];
					}elseif( !empty($EM_Booking->booking_meta['registration'][$field['fieldid']]) ){
						//reg fields only exist as reg fields
						if(!is_array($EM_Booking->booking_meta['registration'][$field['fieldid']])){
							$replace .= $EM_Booking->booking_meta['registration'][$field['fieldid']];
						}else{
							$replace .= implode(', ', $EM_Booking->booking_meta['registration'][$field['fieldid']]);
						}
					}elseif( !empty($EM_Booking->booking_meta['booking'][$field['fieldid']]) ){
						//match for custom field value
						if(!is_array($EM_Booking->booking_meta['booking'][$field['fieldid']])){
							$replace .= $EM_Booking->booking_meta['booking'][$field['fieldid']];
						}else{
							$replace .= implode(', ', $EM_Booking->booking_meta['booking'][$field['fieldid']]);
						}
					}
				}
			}
		}
		return $replace; //no need for a filter, use the em_booking_email_placeholders filter
	}
	
	/*
	 * ----------------------------------------------------------
	 * Booking Table and CSV Export
	 * ----------------------------------------------------------
	 */
	
	function em_bookings_table_rows_col($value, $col, $EM_Booking, $EM_Bookings_Table, $csv){
		global $EM_Event;
		$event_id = (!empty($EM_Booking->get_event()->event_id) && !empty($EM_Event->event_id) && $EM_Event->event_id == $EM_Booking->get_event()->event_id ) ? $EM_Event->event_id:false;
		$EM_Form = self::get_form($event_id);
		if( array_key_exists($col, $EM_Form->form_fields) ){
			$field = $EM_Form->form_fields[$col];
			$value = get_user_meta($EM_Booking->get_person()->ID, $col, true);
			if( empty($value) && !empty($EM_Booking->booking_meta['booking'][$col]) ){
				$value = is_array($EM_Booking->booking_meta['booking'][$col]) ? implode(', ', $EM_Booking->booking_meta['booking'][$col]): $EM_Booking->booking_meta['booking'][$col];
			}elseif( empty($value) ){
				$value = "";			 
			}
		}
		return $value;
	}
	
	function em_bookings_table_cols_template($template, $EM_Bookings_Table){
		global $EM_Event;
		$event_id = (!empty($EM_Event->event_id)) ? $EM_Event->event_id:false;
		$EM_Form = self::get_form($event_id);
		foreach($EM_Form->form_fields as $field_id => $field ){
			$template[$field_id] = $field['label'];
		}
		return $template;
	}
	
	/*
	 * ----------------------------------------------------------
	 * Event Admin Functions
	 * ----------------------------------------------------------
	 */
	
	/**
	 * Depreciated, see self::em_bookings_table_cols_template()
	 * @param array $headers
	 * @return array
	 */
	function em_csv_bookings_headers($headers){
		$EM_Form = self::get_form($EM_Booking->event_id);
		foreach($EM_Form->form_fields as $fieldid => $field){
			if( !array_key_exists($fieldid, $EM_Form->user_fields) && !in_array($fieldid, array('user_email','user_name')) && $fieldid != 'booking_comment' ){
				$headers[] = $field['label']; 
			}
		}
		return $headers; //no filter needed, use the em_csv_bookings_headers filter instead
	}
	
	/**
	 * Depreciated, see self::em_bookings_table_rows_col()
	 * @param array $headers
	 * @return array
	 */	
	function em_csv_bookings_loop_after($file, $EM_Ticket_Booking, $EM_Booking){
		$EM_Form = self::get_form($EM_Booking->event_id);
		foreach($EM_Form->form_fields as $fieldid => $field){
			if( !array_key_exists($fieldid, $EM_Form->user_fields) && !in_array($fieldid, array('user_email','user_name')) && $fieldid != 'booking_comment' ){
				$field_value = (isset($EM_Booking->booking_meta['booking'][$fieldid])) ? $EM_Booking->booking_meta['booking'][$fieldid]:'n/a';
				if(is_array($field_value)){ $field_value = implode(', ', $field_value); }
				if($field['type'] == 'checkbox'){ $field_value = ($field_value) ? __('Yes','dbem'):__('No','dbem'); }
				//backward compatibility for old booking forms
				$file .= '"' .  preg_replace("/\n\r|\r\n|\n|\r/", ".     ", $field_value) . '",'; 
			}
		}
		return $file; //no filter needed, use the em_csv_bookings_loop_after filter instead
	}

	function em_bookings_single_custom( $EM_Booking ){
		//if you want to mess with these values, intercept the em_bookings_single_custom instead
		$EM_Form = self::get_form($EM_Booking->event_id);
		foreach($EM_Form->form_fields as $fieldid => $field){
			if( !array_key_exists($fieldid, $EM_Form->user_fields) && !in_array($fieldid, array('user_email','user_name')) ){
				$input_value = $field_value = (isset($EM_Booking->booking_meta['booking'][$fieldid])) ? $EM_Booking->booking_meta['booking'][$fieldid]:'n/a';
				if(is_array($field_value)){ $field_value = implode(', ', $field_value); }
				if($field['type'] == 'checkbox'){ $field_value = ($field_value) ? __('Yes','dbem'):__('No','dbem'); }
				if($field['type'] == 'date'){ $field_value = str_replace(',',' '.$field['options_date_range_seperator'].' ', $field_value); }
				//backward compatibility for old booking forms
				if( $field['fieldid'] == 'booking_comment' && $field_value == 'n/a' && !empty($EM_Booking->booking_comment) ){ $field_value = $EM_Booking->booking_comment; }
				?>
				<tr>
					<th><?php echo $field['label'] ?></th>
					<td>
						<span class="em-booking-single-info"><?php echo $field_value; ?></span>
						<div class="em-booking-single-edit"><?php echo $EM_Form->output_field_input($field, $input_value)?></div>
					</td>
				</tr>
				<?php
			}
		}
	}
	
	function em_event_save_meta($result, $EM_Event){
		global $wpdb;
		if( $result ){
			if( !empty($_REQUEST['custom_attendee_form']) && is_numeric($_REQUEST['custom_attendee_form']) ){
				//Make sure form id exists
				$id = $wpdb->get_var('SELECT meta_id FROM '.EM_META_TABLE." WHERE meta_id='{$_REQUEST['custom_attendee_form']}'");
				if( $id == $_REQUEST['custom_attendee_form'] ){
					//add or modify custom booking form id in post data
					update_post_meta($EM_Event->post_id, '_custom_attendee_form', $id);
				}
			}else{
				update_post_meta($EM_Event->post_id, '_custom_attendee_form', 0);
			}
		}
		return $result;
	}
	
	function event_attendee_custom_form(){
		//Get available coupons for user
		global $wpdb, $EM_Event;
		self::get_form($EM_Event);
		$default_form_id = get_option('em_attendee_form_fields');
		?>
		<br />
		<?php _e('Selected Attendee Form','dbem'); ?> :
		<select name="custom_attendee_form">
			<option value="0">[ <?php _e('Default','em-pro'); ?> ]</option>
			<?php foreach( self::get_forms_names() as $form_key => $form_name_option ): ?>
				<?php if( $form_key != $default_form_id): ?>
				<option value="<?php echo $form_key; ?>" <?php if($form_key == self::$form_id) echo 'selected="selected"'; ?>><?php echo $form_name_option; ?></option>
				<?php endif; ?>
			<?php endforeach; ?>
		</select>
		<?php
	}
	
	/*
	 * ----------------------------------------------------------
	 * ADMIN Functions
	 * ----------------------------------------------------------
	 */
	
	function admin_page_actions(){
		global $EM_Pro, $EM_Notices, $wpdb;
		if( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'events-manager-forms-editor' ){
			//Load the right form
			if( isset($_REQUEST['att_form_id']) ){
				$sql = $wpdb->prepare("SELECT meta_value FROM ".EM_META_TABLE." WHERE meta_key = 'attendee-form' AND meta_id=%d", $_REQUEST['att_form_id']);
				$form_data = unserialize($wpdb->get_var($sql));
				$EM_Form = self::$form =  new EM_Form($form_data['form'], 'em_attendee_form');
				self::$form_name = $form_data['name'];
				self::$form_id = $_REQUEST['att_form_id'];
			}else{
				$EM_Form = self::get_form();
				if( !self::$form_id ){
					update_option('em_attendee_form_fields',0);
				}
			}
			if( !empty($_REQUEST['form_name']) && $EM_Form->form_name == $_REQUEST['form_name'] && empty($_REQUEST['attendee_form_action']) ){
				//set up booking form field map and save/retreive previous data
				if( $EM_Form->editor_get_post() ){
					//Save into DB rather than as an option
					$booking_form_data = array( 'name'=> self::$form_name, 'form'=> $EM_Form->form_fields );
					$saved = $wpdb->update(EM_META_TABLE, array('meta_value'=>serialize($booking_form_data)), array('meta_id' => self::$form_id));
					//Update Values
					if( $saved !== false ){
						$EM_Notices->add_confirm(__('Changes Saved','em-pro'));
					}elseif( count($EM_Form->get_errors()) > 0 ){
						$EM_Notices->add_error($EM_Form->get_errors());
					}
				}
			}elseif( !empty($_REQUEST['attendee_form_action']) ){
				if( $_REQUEST['attendee_form_action'] == 'default' && wp_verify_nonce($_REQUEST['_wpnonce'], 'attendee_form_default') ){
					//make this booking form the default
					update_option('em_attendee_form_fields', $_REQUEST['att_form_id']);
					$EM_Notices->add_confirm(sprintf(__('The form <em>%s</em> is now the default booking form. All events without a pre-defined booking form will start using this form from now on.','em-pro'), self::$form_name));
				}elseif( $_REQUEST['attendee_form_action'] == 'delete' && wp_verify_nonce($_REQUEST['_wpnonce'], 'attendee_form_delete') ){
					//load and save booking form object with new name
					$saved = $wpdb->query($wpdb->prepare("DELETE FROM ".EM_META_TABLE." WHERE meta_id='%s'", $_REQUEST['att_form_id']));
					if( $saved ){
						self::$form = false;
						$EM_Notices->add_confirm(sprintf(__('%s Deleted','dbem'), __('Booking Form','em-pro')), 1);
						
					}
				}elseif( $_REQUEST['attendee_form_action'] == 'rename' && wp_verify_nonce($_REQUEST['_wpnonce'], 'attendee_form_rename') ){
					//load and save booking form object with new name
					$booking_form_data = array( 'name'=> wp_kses_data($_REQUEST['form_name']), 'form'=>$EM_Form->form_fields );
					self::$form_name = $booking_form_data['name'];
					$saved = $wpdb->update(EM_META_TABLE, array('meta_value'=>serialize($booking_form_data)), array('meta_id' => self::$form_id));
					$EM_Notices->add_confirm( sprintf(__('Form renamed to <em>%s</em>.', 'em-pro'), self::$form_name));
				}elseif( $_REQUEST['attendee_form_action'] == 'add' && wp_verify_nonce($_REQUEST['_wpnonce'], 'attendee_form_add') ){
					//create new form with this name and save first off
					$EM_Form = new EM_Form(self::$form_template, 'em_attendee_form');
					$booking_form_data = array( 'name'=> wp_kses_data($_REQUEST['form_name']), 'form'=> $EM_Form->form_fields );
					self::$form = $EM_Form;
					self::$form_name = $booking_form_data['name'];
					$saved = $wpdb->insert(EM_META_TABLE, array('meta_value'=>serialize($booking_form_data), 'meta_key'=>'attendee-form','object_id'=>0));
					self::$form_id = $wpdb->insert_id;
					if( !get_option('em_attendee_form_fields') ){
						update_option('em_attendee_form_fields', self::$form_id);
					}
					$EM_Notices->add_confirm(__('New form created. You are now editing your new form.', 'em-pro'), true);
					wp_redirect( add_query_arg(array('att_form_id'=>self::$form_id), wp_get_referer()) );
					exit();
				}
			}
		}	
	}
	
	function admin_page() {
		$EM_Form = self::get_form();
		?>
		<div id="poststuff" class="metabox-holder">
			<!-- END OF SIDEBAR -->
			<div id="post-body">
				<div id="post-body-content">
					<?php do_action('em_booking_attendee_form_admin_page_header'); ?>
					<div id="em-attendee-form-editor" class="stuffbox">
						<h3 id="attendee-form">
							<?php _e ( 'Attendee Form', 'em-pro' ); ?>
						</h3>
						<div class="inside">
							<p><?php _e ( "This form will be shown and required for every space booked. If you don't want to ask for attendee information, select None as your booking form.", 'em-pro' )?></p>
							<p><?php _e ( '<strong>Important:</strong> When editing this form, to make sure your old booking information is displayed, make sure new field ids correspond with the old ones.', 'em-pro' )?></p>
							<div>
								<form method="get" action="#attendee-form"> 
									<?php _e('Selected Attendee Form','dbem'); ?> :
									<select name="att_form_id" onchange="this.parentNode.submit()">
										<option value="0" <?php if(!self::$form_id) echo 'selected="selected"'; ?>><?php _e('None','em-pro'); ?></option>
										<?php foreach( self::get_forms_names() as $form_key => $form_name_option ): ?>
										<option value="<?php echo $form_key; ?>" <?php if($form_key == self::$form_id) echo 'selected="selected"'; ?>><?php echo $form_name_option; ?></option>
										<?php endforeach; ?>
									</select>
									<input type="hidden" name="post_type" value="<?php echo EM_POST_TYPE_EVENT; ?>" />
									<input type="hidden" name="page" value="<?php echo $_REQUEST['page']; ?>" />
								</form>
								<?php if( self::$form_id != get_option('em_attendee_form_fields') ): ?>
								<form method="post" action="<?php echo add_query_arg(array('att_form_id'=>null)); ?>#attendee-form"> 
									<input type="hidden" name="att_form_id" value="<?php echo $_REQUEST['att_form_id']; ?>" />
									<input type="hidden" name="attendee_form_action" value="default" />
									<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('attendee_form_default'); ?>" />
									<input type="submit" value="<?php _e ( 'Make Default', 'em-pro' ); ?> &raquo;" class="button-secondary" onclick="return confirm('<?php _e('You are about to make this your default booking form. All events without an existing specifically chosen booking form will use this new default form from now on.\n\n Are you sure you want to do this?') ?>');" />
								</form>
								<?php endif; ?> | 
								<form method="post" action="<?php echo add_query_arg(array('att_form_id'=>null)); ?>#attendee-form" id="attendee-form-add">
									<input type="text" name="form_name" />
									<input type="hidden" name="attendee_form_action" value="add" />
									<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('attendee_form_add'); ?>" />
									<input type="submit"  value="<?php _e ( 'Add New', 'em-pro' ); ?> &raquo;" class="button-secondary" />
								</form>
								<?php if( self::$form_id == get_option('em_attendee_form_fields') && self::$form_id > 0 ): ?>
								<br /><em><?php _e('This is the default attendee form and will be used for any event where you have not chosen a speficic form to use.','em-pro'); ?></em>
								<?php endif; ?>
							</div>
							<?php if( self::$form_id > 0 ): ?>
								<br /><br />
								<form method="post" action="<?php echo add_query_arg(array('att_form_id'=>null)); ?>#attendee-form" id="attendee-form-rename">
									<span style="font-weight:bold;"><?php echo sprintf(__("You are now editing ",'em-pro'),self::$form_name); ?></span>
									<input type="text" name="form_name" value="<?php echo self::$form_name;?>" />
									<input type="hidden" name="att_form_id" value="<?php echo self::$form_id; ?>" />
									<input type="hidden" name="attendee_form_action" value="rename" />
									<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('attendee_form_rename'); ?>" />
									<input type="submit" value="<?php _e ( 'Rename', 'em-pro' ); ?> &raquo;" class="button-secondary" />
								</form>
								<?php if( self::$form_id != get_option('em_attendee_form_fields') ): ?>
								<form method="post" action="<?php echo add_query_arg(array('att_form_id'=>null)); ?>#attendee-form" id="attendee-form-rename">
									<input type="hidden" name="att_form_id" value="<?php echo $_REQUEST['att_form_id']; ?>" />
									<input type="hidden" name="attendee_form_action" value="delete" />
									<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('attendee_form_delete'); ?>" />
									<input type="submit" value="<?php _e ( 'Delete', 'em-pro' ); ?> &raquo;" class="button-secondary" onclick="return confirm('<?php _e('Are you sure you want to delete this form?\n\n All events using this form will start using the default form automatically.'); ?>');" />
								</form>
								<?php endif; ?>
								<br /><br />
								<?php echo $EM_Form->editor(false, true, false); ?>
							<?php else: ?>
								<p><em><?php if( self::$form_id == get_option('em_attendee_form_fields')  ) echo __('Default Value','em-pro').' - '; ?> <?php _e('No attendee form selected. Choose a form, or create a new one above.','em-pro'); ?></em></p>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
EM_Attendee_Form::init();

?>