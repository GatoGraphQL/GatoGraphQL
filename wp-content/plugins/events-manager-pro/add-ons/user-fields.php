<?php
class EM_User_Fields {
	static $form;
	
	static function init(){
		//Menu/admin page
		add_action('admin_init',array('EM_User_Fields', 'admin_page_actions'),9); //before bookings
		add_action('emp_forms_admin_page',array('EM_User_Fields', 'admin_page'),10);
		add_action('emp_form_user_fields',array('EM_User_Fields', 'emp_booking_user_fields'),1,1); //hook for booking form editor
		//Booking interception
		add_filter('em_form_validate_field_custom', array('EM_User_Fields', 'validate'), 1, 4); //validate object
		$custom_fields = get_option('em_user_fields', array());
		foreach($custom_fields as $field_id => $field){
			add_action('em_form_output_field_custom_'.$field_id, array('EM_User_Fields', 'output_field'), 1, 2); //validate object
		}
		//disable EM user fields and override with our filter
		remove_filter( 'user_contactmethods' , array('EM_People','user_contactmethods'),10,1);
		add_action( 'show_user_profile', array('EM_User_Fields','show_profile_fields'), 1 );
		add_action( 'edit_user_profile', array('EM_User_Fields','show_profile_fields'), 1 );
		add_action( 'personal_options_update', array('EM_User_Fields','save_profile_fields') );
		add_action( 'edit_user_profile_update', array('EM_User_Fields','save_profile_fields') );
		//admin area additions
		add_filter('em_person_display_summary', array('EM_User_Fields','em_person_display_summary'),10,2);
		//booking no-user mode functions - editing/saving user data
		add_filter('em_booking_get_person_editor', 'EM_User_Fields::em_booking_get_person_editor', 10, 2);
		if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'booking_modify_person' ){ //only hook in if we're editing a no-user booking
			add_filter('em_booking_get_person_post', 'EM_User_Fields::em_booking_get_person_post', 10, 2);
		}
		//Booking Table and CSV Export
		add_filter('em_bookings_table_rows_col', array('EM_User_Fields','em_bookings_table_rows_col'),10,5);
		add_filter('em_bookings_table_cols_template', array('EM_User_Fields','em_bookings_table_cols_template'),10,2);
	}
	
	static function get_form(){
		if( empty(self::$form) ){
			self::$form = new EM_Form('em_user_fields');
			self::$form->form_required_error = get_option('em_booking_form_error_required');
		}
		return self::$form;
	}
	
	static function emp_booking_user_fields( $fields ){
		//just get an array of options here
		$custom_fields = get_option('em_user_fields');
		foreach($custom_fields as $field_id => $field){
			if( !in_array($field_id, $fields) ){
				$fields[$field_id] = $field['label'];
			}
		}
		return $fields;
	}
	
	static function validate($result, $field, $value, $form){
		$EM_Form = self::get_form();
		if( array_key_exists($field['fieldid'], $EM_Form->user_fields) ){
			//override default regex and error message
			//first figure out the type to modify
			$true_field_type = $EM_Form->form_fields[$field['fieldid']]['type'];
			$true_option_type = $true_field_type;
			if( $true_field_type == 'textarea' ) $true_option_type = 'text';
			if( in_array($true_field_type, array('select','multiselect')) ) $true_option_type = 'select';
			if( in_array($true_field_type, array('checkboxes','radio')) ) $true_option_type = 'selection';
			//now do the overriding
			if( !empty($field['options_reg_error']) ){
				$EM_Form->form_fields[$field['fieldid']]['options_'.$true_option_type.'_error'] = $field['options_reg_error'];
			}
			if( !empty($field['options_reg_regex']) ){
				$EM_Form->form_fields[$field['fieldid']]['options_'.$true_option_type.'_regex'] = $field['options_reg_regex'];
			}
			//validate the original field type
			if( !$EM_Form->validate_field($field['fieldid'], $value) ){
				$form->add_error($EM_Form->get_errors());
				return false;
			}
			return $result && true;
		}
		return $result;
	}
	
	static function output_field( $field, $post ){
		$EM_Form = self::get_form();
		if( array_key_exists($field['fieldid'], $EM_Form->user_fields) ){
			$real_field = $EM_Form->form_fields[$field['fieldid']];
			$real_field['label'] = $field['label'];
			if( empty($_REQUEST[$field['fieldid']]) && is_user_logged_in() && !defined('EM_FORCE_REGISTRATION') ){
				$post = get_user_meta(get_current_user_id(), $field['fieldid'], true);
			}
			if( get_option('dbem_emp_booking_form_reg_input') || !is_user_logged_in() || defined('EM_FORCE_REGISTRATION') ){
				echo $EM_Form->output_field_input($real_field, $post);
			}else{
				echo $post;
			}
		}
	}
	
	/*
	 * ----------------------------------------------------------
	 * Booking Table and CSV Export
	 * ----------------------------------------------------------
	 */
	static function em_bookings_table_rows_col($value, $col, $EM_Booking, $EM_Bookings_Table, $csv){
		$EM_Form = self::get_form();
		if( $EM_Form->is_user_field($col) ){
			$field = $EM_Form->form_fields[$col];
			$EM_Person = $EM_Booking->get_person();
			$guest_user = get_option('dbem_bookings_registration_disable') && $EM_Person->ID == get_option('dbem_bookings_registration_user');
			$value = !$guest_user ? get_user_meta($EM_Person->ID, $col, true):'';
			if( empty($value) && isset($EM_Booking->booking_meta['registration'][$col]) ){
				$value = $EM_Booking->booking_meta['registration'][$col];
			}
			if( !empty($value) ) $value = $EM_Form->get_formatted_value($field, $value);
		}
		return $value;
	}
	
	static function em_bookings_table_cols_template($template, $EM_Bookings_Table){
		$EM_Form = self::get_form();
		foreach($EM_Form->form_fields as $field_id => $field ){
			$template[$field_id] = $field['label'];
		}
		return $template;
	}


	/*
	 * ----------------------------------------------------------
	* No-User Booking Functions - Edit/Save User Info
	* ----------------------------------------------------------
	*/

	static function em_booking_get_person_editor( $content, $EM_Booking ){
	    //if you want to mess with these values, intercept the em_bookings_single_custom instead
	    ob_start();
	    $EM_Form = self::get_form();
		$name = $EM_Booking->get_person()->get_name();
		$email = $EM_Booking->get_person()->user_email;
		if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'booking_modify_person' ){
		    $name = !empty($_REQUEST['user_name']) ? $_REQUEST['user_name']:$name;
		    $email = !empty($_REQUEST['user_email']) ? $_REQUEST['user_email']:$email;
		}
		?>
		<table class="em-form-fields">
			<tr><th><?php _e('Name','dbem'); ?> : </th><td><input type="text" name="user_name" value="<?php echo esc_attr($name); ?>" /></td></tr>
			<tr><th><?php _e('Email','dbem'); ?> : </th><td><input type="text" name="user_email" value="<?php echo esc_attr($email); ?>" /></td></tr>
		    <?php
			foreach($EM_Form->form_fields as $field_id => $field){
				$value = !empty($EM_Booking->booking_meta['registration'][$field_id]) ? $EM_Booking->booking_meta['registration'][$field_id]:'';
				if( !empty($_REQUEST['action']) && $_REQUEST['action'] == 'booking_modify_person' ){
					$value = !empty($_REQUEST[$field_id]) ? $_REQUEST[$field_id]:$value;
			    }
				?>
				<tr>
					<th><label for="<?php echo $field_id; ?>"><?php echo $field['label']; ?></label></th>
					<td>
						<?php echo $EM_Form->output_field_input($field, $value); ?>
					</td>
				</tr>
				<?php
			}
		    ?>
		</table>
	    <?php
	    return ob_get_clean();
	}
	
	static function em_booking_get_person_post( $result, $EM_Booking ){
		//get, store and validate post data
		$EM_Form = self::get_form();
		if( $EM_Form->get_post() && $EM_Form->validate() && $result ){
			foreach($EM_Form->get_values() as $fieldid => $value){
				//registration fields
				$EM_Booking->booking_meta['registration'][$fieldid] = $value;
			}
		}elseif( count($EM_Form->get_errors()) > 0 ){
			$result = false;
			$EM_Booking->add_error($EM_Form->get_errors());
		}
		return $result;
	}
	
	/*
	 * ----------------------------------------------------------
	 * Display Functions
	 * ----------------------------------------------------------
	 */
	
	/**
	 * @param string $summary
	 * @param EM_Person $EM_Person
	 * @return string|unknown
	 */
	static function em_person_display_summary($summary, $EM_Person){
		global $EM_Booking;
		$EM_Form = self::get_form();
		$no_user = get_option('dbem_bookings_registration_disable') && $EM_Person->ID == get_option('dbem_bookings_registration_user');
		if( !get_option('dbem_bookings_registration_disable') || ($no_user && is_object($EM_Booking)) ){
			ob_start();
			//a bit of repeated stuff from the original EM_Person::display_summary() static function
			?>
			<table class="em-form-fields">
				<tr>
					<td><?php echo get_avatar($EM_Person->ID); ?></td>
					<td style="padding-left:10px; vertical-align: top;">
						<table>
							<?php if( $no_user ): ?>
							<tr><th><?php _e('Name','dbem'); ?> : </th><th><?php echo $EM_Person->get_name() ?></th></tr>
							<?php else: ?>
							<tr><th><?php _e('Name','dbem'); ?> : </th><th><a href="<?php echo $EM_Person->get_bookings_url(); ?>"><?php echo $EM_Person->get_name() ?></a></th></tr>
							<?php endif; ?>
							<tr><th><?php _e('Email','dbem'); ?> : </th><td><?php echo esc_html($EM_Person->user_email); ?></td></tr>
							<?php foreach( $EM_Form->form_fields as $field_id => $field ){
								$value = esc_html(get_user_meta($EM_Person->ID, $field_id, true));
								//override by registration value in case value is now empty, otherwise show n/a
								if( !empty($EM_Booking->booking_meta['registration'][$field_id]) && (empty($value) || $no_user) ){
									$value = $EM_Booking->booking_meta['registration'][$field_id];
								}elseif( empty($value) || $no_user ){
									$value = "<em>".__('n/a','em-pro')."</em>";
								}								
								if( $value != "<em>".__('n/a','em-pro')."</em>"){ 
									$value = $EM_Form->get_formatted_value($field, $value);
								}
								?>
								<tr><th><?php echo $field['label']; ?> : </th><td><?php echo $value; ?></td></tr>	
							<?php } ?>
						</table>
					</td>
				</tr>
			</table>
			<?php
			return ob_get_clean();
		}
		return $summary;
	}
	
	/*
	 * ----------------------------------------------------------
	 * ADMIN Functions
	 * ----------------------------------------------------------
	 */
	
	/**
	 * Adds phone number to contact info of users, compatible with previous phone field method
	 * @param $array
	 * @return array
	 */
	static function show_profile_fields($user){
		$EM_Form = self::get_form();
		?>
		<h3><?php _e('Further Information','dbem'); ?></h3>
		<table class="form-table">
			<?php 
			foreach($EM_Form->form_fields as $field_id => $field){
				?>
				<tr>
					<th><label for="<?php echo esc_attr($field_id); ?>"><?php echo esc_html($field['label']); ?></label></th>
					<td>
						<?php
							$field_val = isset($_REQUEST[$field['fieldid']]) ? $_REQUEST[$field['fieldid']] : get_user_meta($user->ID, $field_id, true); 
							echo $EM_Form->output_field_input($field, $field_val); 
						?>
					</td>
				</tr>
				<?php
			}
			?>	
		</table>
		<?php
	}
	
	static function save_profile_fields($user_id){
		global $EM_Notices;
		if ( !current_user_can( 'edit_user', $user_id ) )
			return false;
		$EM_Form = self::get_form();
		if( $EM_Form->get_post() && $EM_Form->validate() ){ //get post and validate at once
			foreach($EM_Form->form_fields as $field_id => $field){
				update_user_meta( $user_id, $field_id, $EM_Form->field_values[$field_id] );
			}
		}
		if( count($EM_Form->errors) > 0 ){
			$EM_Notices->add_error($EM_Form->get_errors());
			add_filter('user_profile_update_errors', 'EM_User_Fields::save_profile_fields_errors', 10, 3);
		}
	}
	
	static function save_profile_fields_errors( $errors, $update, $user ){
		global $EM_Notices;
		$errors->add('em_user_fields', $EM_Notices->get_errors(false));
		$EM_Notices = new EM_Notices();
		return $errors;
	}
	
	static function admin_page_actions() {
		global $EM_Notices;
		$EM_Form = self::get_form();
		if( !empty($_REQUEST['page']) && $_REQUEST['page'] == 'events-manager-forms-editor' ){
			if( !empty($_REQUEST['form_name']) && $EM_Form->form_name == $_REQUEST['form_name'] ){
				//set up booking form field map and save/retreive previous data
				if( empty($_REQUEST['bookings_form_action']) && $EM_Form->editor_get_post() ){
					//Update Values
					if( count($EM_Form->get_errors()) == 0 ){
						//prefix all with dbem
						$form_fields = array();
						foreach($EM_Form->form_fields as $field_id => $field){
							if( substr($field_id, 0, 5) != 'dbem_' && (!defined('EMP_SHARED_CUSTOM_FIELDS') || !EMP_SHARED_CUSTOM_FIELDS) ){
								$field_id = $field['fieldid'] = 'dbem_'.$field_id;
							}
							$form_fields[$field_id] = $field;
						}
						update_option('em_user_fields', $form_fields);
						$EM_Notices->add_confirm(__('Changes Saved','em-pro'));
						self::$form = false; //reset form
						$EM_Form = new EM_Form($form_fields);
					}else{
						$EM_Notices->add_error($EM_Form->get_errors());
					}
				}
			}
		}
		//enable dbem_bookings_tickets_single_form if enabled
	}
	
	static function admin_page() {
		$EM_Form = self::get_form();
		//enable dbem_bookings_tickets_single_form if enabled
		?>
		<a id="user_fields"></a>
		<div id="poststuff" class="metabox-holder">
			<!-- END OF SIDEBAR -->
			<div id="post-body">
				<div id="post-body-content">
					<div id="em-booking-form-editor" class="stuffbox">
						<h3>
							<?php _e ( 'User Fields', 'em-pro' ); ?>
						</h3>
						<div class="inside">
							<p><?php echo sprintf( __('Registration fields are only shown to guest visitors. If you add new fields here and save, they will then be available as custom registrations in your bookings editor, and this information will be accessible and editable on each user <a href="%s">profile page</a>.', 'em-pro' ), 'profile.php'); ?></p>
							<p><?php _e ( '<strong>Important:</strong> When editing this form, to make sure your current user information is displayed, do not change their field names.', 'em-pro' )?></p>
							<?php echo $EM_Form->editor(false, true, false); ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
	}
	
	private static function show_reg_fields(){
		return !is_user_logged_in() && get_option('dbem_bookings_anonymous'); 
	}
}
EM_User_Fields::init();