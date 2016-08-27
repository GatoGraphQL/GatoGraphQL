<?php
include('attendee-form.php');
class EM_Attendees_Form {
	static $validate;
	/**
	 * @var EM_Attendee_Form
	 */
	static $form;
	static $form_id;
	static $form_name;
	static $form_template;
	
	function init(){
		//Menu/admin page
		if( is_admin() ){
			add_action('admin_init',array('EM_Attendees_Form', 'admin_init'), 10);
		}
		if( get_option('em_attendee_fields_enabled') ){
			//Booking Admin Pages
			//add_action('em_bookings_single_custom', array('EM_Attendees_Form', 'em_bookings_single_custom'),1,1); //show booking form and ticket summary
			add_action('em_bookings_single_footer', array('EM_Attendees_Form', 'em_bookings_single_footer'),1,1); //show booking form and ticket summary
			add_action('em_bookings_admin_ticket_row', array('EM_Attendees_Form', 'em_bookings_admin_ticket'),1,2); //show booking form and ticket summary
			//Exporting
			add_action('init', array('EM_Attendees_Form', 'intercept_csv_export'),10); //show booking form and ticket summary
			add_action('em_bookings_table_export_options', array('EM_Attendees_Form', 'em_bookings_table_export_options')); //show booking form and ticket summary
			// Actions and Filters
			add_action('em_gateway_js', array('EM_Attendees_Form','js'),10,2);
			add_action('em_booking_form_ticket_spaces', array('EM_Attendees_Form','ticket_form'),1,1);
			add_action('em_booking_form_tickets_loop_footer', array('EM_Attendees_Form','tickets_form'),1,1); 
			//Booking interception - will not trigger on multi-booking checkout
			add_filter('em_booking_get_post', array('EM_Attendees_Form', 'em_booking_get_post'), 2, 2); //get post data + validate
			add_filter('em_booking_validate', array('EM_Attendees_Form', 'em_booking_validate'), 2, 2); //validate object
			//Placeholder overriding	
			add_filter('em_booking_output_placeholder',array('EM_Attendees_Form','placeholders'),1,3); //for emails
			//custom form chooser in event bookings meta box:
			add_action('emp_bookings_form_select_footer',array('EM_Attendees_Form', 'event_attendee_custom_form'),20,1);
			add_action('em_event_save_meta_pre',array('EM_Attendees_Form', 'em_event_save_meta_pre'),10,1);
			self::$form_template = array (
				'attendee_intro' => array ( 'label' => __('Title','dbem'), 'type' => 'html', 'fieldid'=>'attendee_intro', 'options_html_content'=>'<strong>'.sprintf(__('Attendee %s','em-pro'), '#NUM#'). '</strong>'),
				'attendee_name' => array ( 'label' => __('Name','dbem'), 'type' => 'text', 'fieldid'=>'attendee_name', 'required'=>1 )
			);
		}
	}
	
	function admin_init(){
	    if( current_user_can(get_option('dbem_capability_forms_editor', 'list_users')) ){
			self::admin_page_actions();
			add_action('emp_forms_admin_page',array('EM_Attendees_Form', 'admin_page'),11);
		}
	}
	
	/**
	 * Get the EM_Attendee_Form (Extended EM_Form)
	 * @param EM_Event $EM_Event
	 * @return EM_Attendee_Form
	 */
	function get_form($EM_Event = false){
		if( empty(self::$form) || (!empty($EM_Event) && !empty(self::$form->event_id) && $EM_Event->event_id == self::$form->event_id) ){
			global $wpdb;
			if(is_numeric($EM_Event)){ $EM_Event = em_get_event($EM_Event); }
			$form_id = self::get_form_id($EM_Event);
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
				self::$form = new EM_Attendee_Form($form_data['form'], 'em_attendee_form', false);
			}else{
			    self::$form = new EM_Attendee_Form(array(), 'em_attendee_form', false); //empty form to avoid errors
			}
			self::$form->form_required_error = get_option('em_booking_form_error_required');
			//modify field ids to contain ticket number and []
			if( is_array( self::$form->form_fields) ){
				foreach(self::$form->form_fields as $field_id => $form_data){
				    if( $form_data['type'] == 'date' || $form_data['type'] == 'time'){
						self::$form->form_fields[$field_id]['name'] = "em_attendee_fields[%T][$field_id][%s][]";
				    }elseif( in_array($form_data['type'], array('radio','checkboxes','multiselect')) ){
				        self::$form->form_fields[$field_id]['name'] = "em_attendee_fields[%T][$field_id][%n]";
				    }else{
						self::$form->form_fields[$field_id]['name'] = "em_attendee_fields[%T][$field_id][]";
				    }
				}
			}
		}
		return self::$form;
	}
	
	/**
	 * Gets the form ID to use from a given EM_Event object or returns the default form id if not defined or no object passed
	 * @param EM_Event $EM_Event
	 */
	function get_form_id($EM_Event = false){
		$custom_form_id = ( !empty($EM_Event->post_id) ) ? get_post_meta($EM_Event->post_id, '_custom_attendee_form', true):0;
		$form_id = empty($custom_form_id) ? get_option('em_attendee_form_fields') : $custom_form_id;
	    return $form_id;
	}
	
	/**
	 * Gets all the attendee forms stored in the wp_em_meta table 
	 * @return array
	 */
	function get_forms(){
		global $wpdb;
		$forms = array();
		$forms_data = $wpdb->get_results("SELECT meta_id, meta_value FROM ".EM_META_TABLE." WHERE meta_key = 'attendee-form'");
		foreach($forms_data as $form_data){
			$form = unserialize($form_data->meta_value);
			$forms[$form_data->meta_id] = $form['form'];
		}
		return $forms;
	}
	
	/**
	 * Returns an associative array of form ID => Name
	 * @return array
	 */
	function get_forms_names(){
		global $wpdb;
		$forms = array();
		$forms_data = $wpdb->get_results("SELECT meta_id, meta_value FROM ".EM_META_TABLE." WHERE meta_key = 'attendee-form'");
		foreach($forms_data as $form_data){
			$form = unserialize($form_data->meta_value);
			$forms[$form_data->meta_id] = $form['name'];
		}
		return $forms;
	}
	
	/**
	 * Converts the relevant field names to be relevant for attendees format (i.e. in an array due to unknown number of attendees per booking)
	 * @param EM_Attendee_Form $form
	 * @param EM_Ticket $EM_Ticket
	 * @return EM_Attendee_Form
	 */
	function get_ticket_form($form, $EM_Ticket){
		//modify field ids to contain ticket number and []
		foreach($form->form_fields as $field_id => $form_data){
		    if( $form_data['type'] == 'date' || $form_data['type'] == 'time'){
				$form->form_fields[$field_id]['name'] = "em_attendee_fields[".$EM_Ticket->ticket_id."][$field_id][%s][]";
		    }elseif( in_array($form_data['type'], array('radio','checkboxes','multiselect')) ){
			    $form->form_fields[$field_id]['name'] = "em_attendee_fields[".$EM_Ticket->ticket_id."][$field_id][%n]";
			}else{
				$form->form_fields[$field_id]['name'] = "em_attendee_fields[".$EM_Ticket->ticket_id."][$field_id][]";
		    }
		}
		return $form;
	}
	
	/**
	 * Returns a formatted multi-dimensional associative array of attendee information for a specific booking, split by ticket > attendee > attendee data.
	 * example : array('ticket_id' => array('Attendee 1' => array('Label'=>'Value', 'Label 2'=>'Value 2'), 'Attendee 2' => array(...)...)...);
	 * @param EM_Booking $EM_Booking
	 */
	public static function get_booking_attendees( $EM_Booking ){
		$attendee_data = array();
		foreach( $EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking ){ /* @var $EM_Ticket_Booking EM_Ticket_Booking */
			//Display ticket info
			if( !empty($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id]) && is_array($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id]) ){
			    $EM_Ticket_Booking->booking = $EM_Booking; //avoid extra loading in sub-function
			    $attendee_data[$EM_Ticket_Booking->ticket_id] = self::get_ticket_attendees($EM_Ticket_Booking);
			}else{
				$attendee_data[$EM_Ticket_Booking->ticket_id] = array();
				for($i=1; $i <= $EM_Ticket_Booking->ticket_booking_spaces; $i++){
					$key = sprintf(__('Attendee %s','em-pro'), $i);
					$attendee_data[$EM_Ticket_Booking->ticket_id][$key] = array();
				}
			}
		}
		return $attendee_data;
	}
	
	/**
	 * Returns a formatted multi-dimensional associative array of attendee information for a specific booking ticket.
	 * example : array('Attendee 1' => array('Label'=>'Value', 'Label 2'=>'Value 2'), 'Attendee 2' => array(...)...);
	 * @param EM_Ticket_Booking $EM_Ticket_Booking
	 */
	public static function get_ticket_attendees( $EM_Ticket_Booking ){
	    $attendees = array();
	    if( is_array($EM_Ticket_Booking->get_booking()->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id]) ){
	    	$EM_Form = EM_Attendees_Form::get_form($EM_Ticket_Booking->get_booking()->event_id); //can be repeated since object is stored temporarily
			$i = 1; //counter
	    	foreach( $EM_Ticket_Booking->get_booking()->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id] as $field_values ){
	    		$EM_Form->field_values = $field_values;
	    		//backward compatibility for old booking forms and saved comments
	    		if( empty($EM_Form->field_values['booking_comment']) && !empty($EM_Booking->booking_comment) ){ $EM_Form->field_values['booking_comment'] = $EM_Booking->booking_comment; }
	    		//output the field values
	    		$key = sprintf(__('Attendee %s','em-pro'), $i);
	    		$attendees[$key] = array();
	    		foreach( $EM_Form->form_fields as $fieldid => $field){
	    			if( !array_key_exists($fieldid, $EM_Form->user_fields) && $field['type'] != 'html' ){
	    				$field_value = (isset($EM_Form->field_values[$fieldid])) ? $EM_Form->field_values[$fieldid]:'n/a';
	    				$attendees[$key][$field['label']] = $EM_Form->get_formatted_value($field, $field_value);
	    			}
	    		}
	    		$i++;
		    }
	    }
	    return $attendees;
	}
	
	/**
	 * Adds JS to the bottom of a page with an EM booking form
	 * @param string $original_js
	 * @param EM_Event $EM_Event
	 * 
	 * @return string
	 */
	function js(){
		include('attendee-forms.js');
	}
	
	/**
	 * For each ticket in the booking table, add a hidden row with ticket form
	 * @param EM_Ticket $EM_Ticket
	 */
	function tickets_form($EM_Ticket){
		$col_numbers = $EM_Ticket->get_event()->get_bookings()->get_tickets()->get_ticket_collumns();
		$min_spaces = $EM_Ticket->get_spaces_minimum();
		if( !$EM_Ticket->is_required() ) $min_spaces = 0; //zero value allowed
		if( !empty($_REQUEST['em_tickets'][$EM_Ticket->ticket_id]['spaces']) ) $min_spaces = $_REQUEST['em_tickets'][$EM_Ticket->ticket_id]['spaces'];
		?>
		<tr class="em-attendee-details" id="em-attendee-details-<?php echo $EM_Ticket->ticket_id; ?>" <?php if( $min_spaces == 0 ) echo 'style="display:none;"'?>>
			<td colspan="<?php echo count($col_numbers); ?>">
				<?php self::ticket_form($EM_Ticket); ?>
			</td>
		</tr>
		<?php
	}
	
	/**
	 * For each ticket row in the booking table, add a hidden row with ticket form
	 * @param EM_Ticket $EM_Ticket
	 */
	function ticket_form($EM_Ticket){
		$form = self::get_ticket_form(self::get_form($EM_Ticket->event_id),$EM_Ticket);
		if( self::$form_id > 0 ){
			$available_spaces = $EM_Ticket->get_available_spaces();
			$min_spaces = 0;
			if( $EM_Ticket->is_available() ) {
				$min_spaces = $EM_Ticket->get_spaces_minimum();
				if( !$EM_Ticket->is_required() ) $min_spaces = 0; //zero value allowed
				if( !empty($_REQUEST['em_tickets'][$EM_Ticket->ticket_id]['spaces']) ) $min_spaces = $_REQUEST['em_tickets'][$EM_Ticket->ticket_id]['spaces'];
				?>
				<div class="em-attendee-fieldset">
					<?php
					for($i = 0; $i < $min_spaces; $i++ ){
						$form->attendee_number = $i;
						?>
						<div class="em-attendee-fields">
							<?php echo str_replace('#NUM#', $i+1, $form->__toString()); ?>
						</div>
						<?php
					}
					$form->attendee_number = false;
					?>
				</div>
				<div class="em-attendee-fields-template" style="display:none;">
					<?php echo $form; ?>
				</div>
				<?php
			}
		}
	}
	
	/**
	 * Hooks into em_booking_get_post and validates the 
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return bool
	 */
	function em_booking_get_post($result, $EM_Booking){
		//get, store and validate post data 
		$EM_Form = self::get_form($EM_Booking->event_id);
		if( self::$form_id > 0 ){
			if( (empty($EM_Booking->booking_id) || (!empty($EM_Booking->booking_id) && $EM_Booking->can_manage())) ){
				foreach ($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking ){
				    for( $i = 0; $i < $EM_Ticket_Booking->ticket_booking_spaces; $i++ ){
						$EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i] = array();
						foreach($EM_Form->fields as $field){
							$field['label'] = str_replace('#NUM#', $i+1, $field['label']);
						}
					    if( $EM_Form->get_post(false, $EM_Ticket_Booking->ticket_id, $i) ){ //passing false for $validate, since it'll be done in em_booking_validate hook
							foreach($EM_Form->get_values() as $fieldid => $value){
								//get results and put them into booking meta
								$EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i][$fieldid] = $value;
							}			
					    }
				    }
				}		
			}
			if( count($EM_Form->get_errors()) > 0 ){
				$result = false;
				$EM_Booking->add_error($EM_Form->get_errors());
			}
		}
		return $result;
	}
	
	/**
	 * Validates a booking against the attendee fields provided
	 * @param boolean $result
	 * @param EM_Booking $EM_Booking
	 * @return boolean
	 */
	function em_booking_validate($result, $EM_Booking){
		//going through each ticket type booked
		$EM_Form = self::get_form($EM_Booking->event_id);
		if( self::$form_id > 0 ){
			foreach ($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking ){
				//get original field labels for replacement of #NUM#
				$original_fields = array();
				foreach($EM_Form->form_fields as $key => $field){
					$original_fields[$key] = $EM_Form->form_fields[$key]['label'];
				}
				//validate a form for each space booked
				for( $i = 0; $i < $EM_Ticket_Booking->ticket_booking_spaces; $i++ ){
					if( isset($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i]) ){ //unlike post values each attendee has an array within the array of a ticket attendee info
						$EM_Form->field_values = $EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$i];
						$EM_Form->errors = array();
						//change the field labels in case of #NUM#
						foreach($EM_Form->form_fields as $key => $field){
							$EM_Form->form_fields[$key]['label'] = str_replace('#NUM#', $i+1, $original_fields[$key]);
						}
						//validate and save errors within this ticket user
						if( !$EM_Form->validate() ){
							$title = $EM_Ticket_Booking->get_ticket()->ticket_name . " - " . sprintf(__('Attendee %s','em-pro'), $i+1);
							$error = array( $title => $EM_Form->get_errors());
						    $EM_Booking->add_error($error);
						    $result = false;
						}
					}
				}
			}
		}
		return $result;
	}
	
	/*
	 * ----------------------------------------------------------
	 * Booking Table and CSV Export
	 * ----------------------------------------------------------
	 */
	
	/**
	 * Intercepts a CSV export request before the core version hooks in and using similar code generates a breakdown of bookings with all attendees included at the end.
	 * Hooking into the original version of this will cause more looping, which is why we're flat out overriding this here.
	 */
	function intercept_csv_export(){
		if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'export_bookings_csv' && !empty($_REQUEST['show_attendees']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'export_bookings_csv')){
			//sort out cols
			if( !empty($_REQUEST['cols']) && is_array($_REQUEST['cols']) ){
				$cols = array();
				foreach($_REQUEST['cols'] as $col => $active){
					if( $active ){ $cols[] = $col; }
				}
				$_REQUEST['cols'] = $cols;
			}
			$_REQUEST['limit'] = 0;
		
			//generate bookings export according to search request
			$EM_Bookings_Table = new EM_Bookings_Table(true);
			header("Content-Type: application/octet-stream; charset=utf-8");
			header("Content-Disposition: Attachment; filename=".sanitize_title(get_bloginfo())."-bookings-export.csv");
			do_action('em_csv_header_output');
			echo "\xEF\xBB\xBF"; // UTF-8 for MS Excel (a little hacky... but does the job)
			if( !defined('EM_CSV_DISABLE_HEADERS') || !EM_CSV_DISABLE_HEADERS ){
				if( !empty($_REQUEST['event_id']) ){
					$EM_Event = em_get_event($_REQUEST['event_id']);
					echo __('Event','dbem') . ' : ' . $EM_Event->event_name .  "\n";
					if( $EM_Event->location_id > 0 ) echo __('Where','dbem') . ' - ' . $EM_Event->get_location()->location_name .  "\n";
					echo __('When','dbem') . ' : ' . $EM_Event->output('#_EVENTDATES - #_EVENTTIMES') .  "\n";
				}
				echo sprintf(__('Exported bookings on %s','dbem'), date_i18n('D d M Y h:i', current_time('timestamp'))) .  "\n";
			}
			$headers = $EM_Bookings_Table->get_headers(true);
			if( !empty($_REQUEST['event_id']) ){
				foreach(self::get_form($_REQUEST['event_id'])->form_fields as $field ){
					if( $field['type'] != 'html' ){ $headers[] = $field['label']; }
				}
			}
			echo '"'. implode('","', $headers). '"' .  "\n";
			//Rows
			$EM_Bookings_Table->limit = 150; //if you're having server memory issues, try messing with this number
			$EM_Bookings = $EM_Bookings_Table->get_bookings();
			$handle = fopen("php://output", "w");
			while(!empty($EM_Bookings->bookings)){
				foreach( $EM_Bookings->bookings as $EM_Booking ) {
					/* @var $EM_Booking EM_Booking */
					/* @var $EM_Ticket_Booking EM_Ticket_Booking */
					$attendees_data = self::get_booking_attendees($EM_Booking);
					foreach($EM_Booking->get_tickets_bookings()->tickets_bookings as $EM_Ticket_Booking){
						$orig_row = $EM_Bookings_Table->get_row_csv($EM_Ticket_Booking);
						if( !empty($attendees_data[$EM_Ticket_Booking->ticket_id]) ){ 
							foreach($attendees_data[$EM_Ticket_Booking->ticket_id] as $attendee_title => $attendee_data){
								$row = $orig_row;
								foreach( $attendee_data as $field_value){
									$row[] = $field_value;
								}
								fputcsv($handle, $row);
							}
						}
					}
				}
				//reiterate loop
				$EM_Bookings_Table->offset += $EM_Bookings_Table->limit;
				$EM_Bookings = $EM_Bookings_Table->get_bookings();
			}
			fclose($handle);
			exit();
		}
	}
	
	function em_bookings_table_export_options(){
		?>
		<p><?php _e('Split bookings by attendee','dbem')?> <input type="checkbox" name="show_attendees" value="1" />
		<a href="#" title="<?php _e('A row will be created for each space booked and will automatically include any attendee information associated with that booked ticket.'); ?>">?</a>
		<script type="text/javascript">
			jQuery(document).ready(function($){
				$('#em-bookings-table-export-form input[name=show_attendees]').click(function(){
					$('#em-bookings-table-export-form input[name=show_tickets]').attr('checked',true);
					//copied from export_overlay_show_tickets function:
					$('#em-bookings-table-export-form .em-bookings-col-item-ticket').show();
					$('#em-bookings-table-export-form #em-bookings-export-cols-active .em-bookings-col-item-ticket input').val(1);
				});
				$('#em-bookings-table-export-form input[name=show_tickets]').change(function(){
					if( !this.checked ){
						$('#em-bookings-table-export-form input[name=show_attendees]').attr('checked',false);
					}
				});
			});
		</script>
		<?php
		if( !get_option('dbem_bookings_tickets_single') ){ //single ticket mode means no splitting by ticket type 
			
		}
	}
	
	/*
	 * ----------------------------------------------------------
	 * Booking Admin Functions
	 * ----------------------------------------------------------
	 */


	/**
	 * Displayed when viewing/editing info about a single booking under each ticket.
	 * @param EM_Ticket $EM_Ticket
	 * @param EM_Booking $EM_Booking
	 */
	function em_bookings_admin_ticket( $EM_Ticket, $EM_Booking ){
		//if you want to mess with these values, intercept the em_bookings_single_custom action instead
		$EM_Tickets_Bookings = $EM_Booking->get_tickets_bookings();
		if( !empty($EM_Tickets_Bookings->tickets_bookings[$EM_Ticket->ticket_id]) ){
			$EM_Ticket_Booking = $EM_Tickets_Bookings->tickets_bookings[$EM_Ticket->ticket_id];
			$EM_Form = self::get_form($EM_Booking->event_id);
			//validate a form for each space booked
			if( self::$form_id > 0 ){
				?>
				<tr>
				<td colspan="3" class="em-attendee-form-admin">
					<div class="em-attendee-details" id="em-attendee-details-<?php echo $EM_Ticket->ticket_id; ?>" <?php if( empty($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id]) ) echo 'style="display:none;"'?>>
						<div class="em-attendee-fieldset">
							<?php
							//output the field values
							$attendees_data = self::get_ticket_attendees($EM_Ticket_Booking);
							$attendee_index = 0;
							foreach($attendees_data as $attendee_title => $attendee_data){
								//preload the form object with this attendee information
								if( !empty($EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$attendee_index]) ){
									$EM_Form->field_values = $EM_Booking->booking_meta['attendees'][$EM_Ticket_Booking->ticket_id][$attendee_index];
									$EM_Form->attendee_number = $attendee_index;
								}
								?>
								<div class="em-booking-single-info">
									<h4><?php echo $attendee_title; ?></h4>
									<?php foreach( $attendee_data as $attendee_label => $attendee_value): ?>
									<p>
										<label><?php echo $attendee_label ?></label>
										<span><?php echo $attendee_value; ?></span>
									</p>
									<?php endforeach; ?>
								</div>
								<?php
								//output fields form
								?>
								<div class="em-attendee-fields em-booking-single-edit">
									<h4><?php echo $attendee_title; ?></h4>
									<?php self::admin_form($EM_Form, $EM_Ticket_Booking->ticket_id); ?>
								</div>
								<?php
								$attendee_index++;
							}
							//reset form fields to blank for template
							$EM_Form->field_values = array();
							$EM_Form->errors = array();
							$EM_Form->attendee_number = false;
							?>
						</div>
						<div class="em-attendee-fields-template" style="display:none;">
							<h4><?php echo sprintf(__('Attendee %s','em-pro'), '#NUM#'); ?></h4>
							<?php self::admin_form($EM_Form, $EM_Ticket_Booking->ticket_id); ?>
						</div>
					</div>
				</td>
				</tr>
				<?php
			}
		}
	}


	/**
	 * Adds JS to the bottom of a page with an EM booking form
	 * @param string $original_js
	 * @param EM_Event $EM_Event
	 *
	 * @return string
	 */
	function em_bookings_single_footer(){
		?>
		<script type="text/javascript">
			jQuery(document).ready( function($){
				<?php include('attendee-forms.js'); ?>
			});
		</script>
		<?php
	}
	
	/*
	 * ----------------------------------------------------------
	 * Event Admin Functions
	 * ----------------------------------------------------------
	 */
		
	/**
	 * Generates a condensed attendee form for admins, stripping away HTML fields.
	 * @param EM_Attendee_Form $EM_Form
	 * @param int $ticket_id
	 */
	function admin_form( $EM_Form, $ticket_id ){
		?>
		<table class="em-form-fields" cellspacing="0" cellpadding="0">
		<?php
		foreach( $EM_Form->form_fields as $fieldid => $field){
			if( !array_key_exists($fieldid, $EM_Form->user_fields) && $field['type'] != 'html' ){
				?>
				<tr class="input-group input-<?php echo $field['type']; ?> input-field-<?php echo $field['fieldid'] ?>">
					<th><?php echo $field['label'] ?></th>
					<td>
					<?php
						$value = !empty($EM_Form->field_values[$fieldid]) ? $EM_Form->field_values[$fieldid]:''; 
						echo str_replace('%T', $ticket_id, $EM_Form->output_field_input($field, $value)); 
					?>
					</td>
				</tr>
				<?php
			}
		}
		?>
		</table>
		<?php
	}
	
	/**
	 * Saves the custom attendee form as post meta. This is done on em_event_save_meta_pre since at that point we know the post id and this will get passed onto recurrences as well.
	 * @param EM_Event $EM_Event
	 */
	function em_event_save_meta_pre($EM_Event){
		global $wpdb;
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
	
	/**
	 * Generates a dropdown of available custom attendee forms for selection when editing the event bookings settings
	 */
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
	 * Placeholders
	 * ----------------------------------------------------------
	 */
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
			if( $full_result == '#_BOOKINGATTENDEES' ){
				$replace = '';
				ob_start();
				emp_locate_template('placeholders/bookingattendees.php', true, array('EM_Booking'=>$EM_Booking));
				$replace = ob_get_clean();
			}
		}
		return $replace; //no need for a filter, use the em_booking_email_placeholders filter
	}
	
	/*
	 * ----------------------------------------------------------
	 * ADMIN Functions
	 * ----------------------------------------------------------
	 */
	
	/**
	 * Catches posted data when editing custom attendee forms in the Form Editor 
	 */
	function admin_page_actions(){
		global $EM_Pro, $EM_Notices, $wpdb;
		if( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'events-manager-forms-editor' ){
			//Load the right form
			if( isset($_REQUEST['em_attendee_fields_enabled']) && wp_verify_nonce($_REQUEST['_wpnonce'], 'em_attendee_fields_enabled') ){
				update_option('em_attendee_fields_enabled', $_REQUEST['em_attendee_fields_enabled']);
			}
			if( get_option('em_attendee_fields_enabled') ){
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
	}
	
	/**
	 *  Outputs the form editor in the admin area
	 */
	function admin_page() {
		$EM_Form = self::get_form();
		?>
		<div id="poststuff" class="metabox-holder">
			<div id="post-body">
				<div id="post-body-content">
					<?php do_action('em_booking_attendee_form_admin_page_header'); ?>
					<div id="attendee-form-settings" class="stuffbox">
						<h3 id="attendee-form">
							<?php _e ( 'Attendee Form', 'em-pro' ); ?>
						</h3>
						<div class="inside">
							<p><?php _e ( "If enabled, this form will be shown and required for every space booked.", 'em-pro' )?></p>
							<form method="post" action="#attendee-form"> 
								<p>
								<?php _e('Enable Attendee Forms','dbem'); ?> :
								<input type="radio" name="em_attendee_fields_enabled" value="1" class="attendee-enable" <?php if(get_option('em_attendee_fields_enabled')){ echo 'checked="checked"'; } ?> /> Yes
								<input type="radio" name="em_attendee_fields_enabled" value="0" class="attendee-enable" <?php if(!get_option('em_attendee_fields_enabled')){ echo 'checked="checked"'; } ?> /> No
								<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('em_attendee_fields_enabled'); ?>" />
								<input type="submit" class="button-secondary" value="Save" />
								</p>
							</form>
							<?php if(get_option('em_attendee_fields_enabled')): ?>
							<div id="em-attendee-form-editor">
								<form method="get" action="#attendee-form"> 
									<?php _e('Selected Attendee Form','dbem'); ?> :
									<select name="att_form_id" onchange="this.parentNode.submit()">
										<option value="0" <?php if(!self::$form_id) echo 'selected="selected"'; ?>><?php _e('None','em-pro'); ?></option>
										<?php foreach( self::get_forms_names() as $form_key => $form_name_option ): ?>
										<option value="<?php echo $form_key; ?>" <?php if($form_key == self::$form_id) echo 'selected="selected"'; ?>><?php echo $form_name_option; ?></option>
										<?php endforeach; ?>
									</select>
									<input type="hidden" name="post_type" value="<?php echo EM_POST_TYPE_EVENT; ?>" />
									<input type="hidden" name="page" value="<?php echo esc_attr($_REQUEST['page']); ?>" />
								</form>
								<?php if( self::$form_id != get_option('em_attendee_form_fields') ): ?>
								<form method="post" action="<?php echo add_query_arg(array('att_form_id'=>null)); ?>#attendee-form"> 
									<input type="hidden" name="att_form_id" value="<?php echo esc_attr($_REQUEST['att_form_id']); ?>" />
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
								<?php do_action('em_attendees_form_admin_page_actions', $EM_Form); ?>
								<?php if( self::$form_id == get_option('em_attendee_form_fields') && self::$form_id > 0 ): ?>
								<br /><em><?php _e('This is the default attendee form and will be used for any event where you have not chosen a speficic form to use.','em-pro'); ?></em>
								<?php endif; ?>
								<br /><em><?php _e("If you don't want to ask for attendee information by default, select None as your booking form and make it the default form.", 'em-pro'); ?></em>
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
										<input type="hidden" name="att_form_id" value="<?php echo esc_attr($_REQUEST['att_form_id']); ?>" />
										<input type="hidden" name="attendee_form_action" value="delete" />
										<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('attendee_form_delete'); ?>" />
										<input type="submit" value="<?php _e ( 'Delete', 'em-pro' ); ?> &raquo;" class="button-secondary" onclick="return confirm('<?php _e('Are you sure you want to delete this form?\n\n All events using this form will start using the default form automatically.'); ?>');" />
									</form>
									<?php endif; ?>
									<p><?php _e ( '<strong>Important:</strong> When editing this form, to make sure your old booking information is displayed, make sure new field ids correspond with the old ones.', 'em-pro' )?></p>
									<br /><br />
									<?php echo $EM_Form->editor(false, true, false); ?>
								<?php else: ?>
									<p><em><?php if( self::$form_id == get_option('em_attendee_form_fields')  ) echo __('Default Value','em-pro').' - '; ?> <?php _e('No attendee form selected. Choose a form, or create a new one above.','em-pro'); ?></em></p>
								<?php endif; ?>
							</div>
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
}
EM_Attendees_Form::init();

?>