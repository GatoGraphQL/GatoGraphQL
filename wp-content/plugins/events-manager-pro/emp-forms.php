<?php
class EM_Forms {
	function init(){
		if( is_admin() ){
			add_action('em_create_events_submenu',array('EM_Forms', 'admin_menu'),1,1);
			add_action('em_options_page_footer_bookings',array('EM_Forms', 'admin_options'),10);
		}
	}
	
	function admin_menu($plugin_pages){
		$plugin_pages[] = add_submenu_page('edit.php?post_type='.EM_POST_TYPE_EVENT, __('Forms Editor','em-pro'),__('Forms Editor','em-pro'),get_option('dbem_capability_forms_editor', 'list_users'),'events-manager-forms-editor',array('EM_Forms','admin_page'));
		return $plugin_pages; //use wp action/filters to mess with the menus
	}
	
	function admin_page(){
		?>
		<div class='wrap'>
			<div class="icon32" id="icon-plugins"><br></div>
			<h2><?php _e('Forms Editor','em-pro'); ?></h2>
			<p><?php _e('On this page you can create/edit various forms used within Events Manager Pro.', 'em-pro' ); ?></p>
			<?php do_action('emp_forms_admin_page'); ?>
		</div> <!-- wrap -->
		<?php
	}
	
	function admin_options(){
		global $save_button;
		if( current_user_can('list_users') ){
		?>
			<div  class="postbox " id="em-opt-pro-booking-form-options" >
			<div class="handlediv" title="<?php __('Click to toggle', 'dbem'); ?>"><br /></div><h3 class='hndle'><span><?php _e ( 'PRO Booking Form Options', 'em-pro' ); ?> </span></h3>
			<div class="inside">
				<table class='form-table'>
					<?php 
						em_options_radio_binary ( __( 'Show profile fields to logged in users?', 'em-pro' ), 'dbem_emp_booking_form_reg_show', __( 'When logged in, users usually don\'t see their profile fields, with this enabled, users will be able to update their profile fields alongside their booking. This is particularly useful if the user is missing key information. Note that this does not include usernames, name, passwords and emails, these must be modified from their user profile page.','em-pro' ) );
						em_options_radio_binary ( __( 'Make profile fields editable?', 'em-pro' ), 'dbem_emp_booking_form_reg_input', __( 'If profile fields are set to show to logged in users, you can also choose whether or not to make these fields editable or just for viewing reference.','em-pro' ) );
					?>
				</table>
				<?php echo $save_button; ?> 
			</div> <!-- . inside -->
			</div> <!-- .postbox -->
		<?php
		}
	}
	
}
EM_Forms::init();


class EM_Form extends EM_Object {
	
	public $form_fields = array();
	public $form_name = 'Default';
	public $field_values = array();
	public $user_fields = array();
	public $core_user_fields = array(
		'name' => 'Name',
		'user_login' => 'Username Login',
		'user_email' => 'E-mail (required)',
		'user_password' => 'Password',
		'first_name' => 'First Name',
		'last_name' => 'Last Name',
		'user_url' => 'Website',
		'aim' => 'AIM',
		'yim' => 'Yahoo IM',
		'jabber' => 'Jabber / Google Talk',
		'about' => 'Biographical Info'
	);
	protected $custom_user_fields = array();
	public $form_required_error = '';
	static $validate;
	
	function __construct( $form_data, $form_name=false, $user_fields = true ){
		if( is_array($form_data) ){
			//load form data from array
			$this->form_fields = $form_data;
		}else{
			//assume the text is the form name
			$this->form_fields = get_option($form_data);
			$this->form_name = $form_data;
		}
		if( !empty($form_name) ){
			$this->form_name = $form_name;
		}
		if( $user_fields ){
			$this->user_fields = apply_filters('emp_form_user_fields',$this->core_user_fields, $this);
			$this->custom_user_fields = array_diff($this->user_fields, $this->core_user_fields);
		}
	}
	
	function get_post( $validate = true ){
	    $custom_user_fields = EM_User_Fields::get_form()->form_fields;
		foreach($this->form_fields as $field){
		    $fieldid = $field['fieldid'];
			$value = '';
			if( !isset($_REQUEST[$fieldid]) ){ //for things like checkboxes when editing
			    $_REQUEST[$fieldid] = '';
			}
			if( !is_array($_REQUEST[$fieldid])){
				$this->field_values[$fieldid] = wp_kses_data(stripslashes($_REQUEST[$fieldid]));
			}elseif( is_array($_REQUEST[$fieldid])){
			    $array = array();
			    foreach( $_REQUEST[$fieldid] as $key => $array_value ){
			        $array[$key] = wp_kses_data(stripslashes($array_value));
			    }
				$this->field_values[$fieldid] = $array;
			}
			//if this is a custom user field, change $filed to the original field so the right date/time info is retreived
	    	if( array_key_exists($field['type'], $this->custom_user_fields) && array_key_exists($field['fieldid'], $custom_user_fields) ){
	    	    $field = $custom_user_fields[$field['fieldid']];
	    	}
			//dates and time are special
			if( in_array( $field['type'], array('date','time')) ){
				if( !empty($_REQUEST[$fieldid]['start']) ){
					$this->field_values[$fieldid] = wp_kses_data($_REQUEST[$fieldid]['start']);
					if( $field['options_'.$field['type'].'_range'] && !empty($_REQUEST[$fieldid]['end']) ){
						$this->field_values[$fieldid] .= ','. wp_kses_data($_REQUEST[$fieldid]['end']);
					}
				}else{
				    $this->field_values[$fieldid] = '';
				}
			}
		}
		return true;
	}
	
	function get_values(){
		return $this->field_values;
	}
	
	function get_formatted_value( $field, $field_value ){
		//FIX FOR BUG IN 2.2.5.4 and earlier for bookings using use no-user-mode
		if( in_array($field['type'], array('time','date')) ){
			if( is_array($field_value) && !empty($field_value['start']) ){
				$temp_val = $field_value['start'];
				if( !empty($field_value['end']) ){
					$temp_val .= ','.$field_value['start'];
				}
				$field_value = $temp_val;
			}elseif( is_array($field_value) && empty($field_value['start']) ){
			    $field_value = 'n/a'; //fix for empty value saves in 2.2.8
			}
		}
		//output formatted value for special fields
		switch( $field['type'] ){
			case 'checkbox':
				$field_value = ($field_value && $field_value != 'n/a') ? __('Yes','dbem'):__('No','dbem');
				break;
			case 'date':
			    //split ranges (or create single array) and format, then re-implode
			    if( $field_value != 'n/a' ){
					$date_format = ( get_option('dbem_date_format') ) ? get_option('dbem_date_format'):get_option('date_format');
				    $field_values = explode(',', $field_value);
				    foreach($field_values as $key => $value){
						$field_values[$key] = date($date_format, strtotime($value));
					}
				    $field_value = implode(',', $field_values);
				    //set seperator and replace the comma
					$seperator = empty($field['options_date_range_seperator']) ? ' - ': $field['options_date_range_seperator'];
					$field_value = str_replace(',',' '.$seperator.' ', $field_value);
			    }
				break;
			case 'time':
			    //split ranges (or create single array) and format, then re-implode
			    if( $field_value != 'n/a' ){
					$time_format = ( get_option('dbem_time_format') ) ? get_option('dbem_time_format'):get_option('time_format');
				    $field_values = explode(',', $field_value);
				    foreach($field_values as $key => $value){
						$field_values[$key] = date($time_format, strtotime('2010-01-01 '.$value));
					}
				    $field_value = implode(',', $field_values);
					//set seperator and replace the comma
					$seperator = empty($field['options_time_range_seperator']) ? ' - ': $field['options_time_range_seperator'];
					$field_value = str_replace(',',' '.$seperator.' ', $field_value);
				}
				break;
			case 'booking_comment':
				if( $field_value == 'n/a' && !empty($EM_Booking->booking_comment) ){ $field_value = $EM_Booking->booking_comment; }
				break;
			case 'country':
				if( $field_value != 'n/a' ){ 
					$countries = em_get_countries();
					$field_value = $countries[$field_value];
				}
				break;
			default:
			    if( is_array($field_value) ){ $field_value = implode(', ', $field_value); }
			    break;
		}
		return $field_value;
	}
	
	/**
	 * Returns true if this field is not a user field or an html field, meaning it is stored information not at a user-account level, false if not.
	 * @param mixed $field_or_id
	 * @return boolean
	 */
	public function is_normal_field( $field_or_id ){
        $field_id = is_array($field_or_id) ? $field_or_id['fieldid'] : $field_or_id;
	    return array_key_exists($field_id, $this->form_fields) && !array_key_exists($field_id, $this->user_fields) && !in_array($field_id, array('user_email','user_name')) && $this->form_fields[$field_id]['type'] != 'html';
	}
	
	/**
	 * Returns true if this is a field stored as at a user-account level, false if not.
	 * @param mixed $field_or_id
	 * @return boolean
	 */
	public function is_user_field( $field_or_id ){
        $field_id = ( is_object($field_or_id) ) ? $field_or_id['fieldid'] : $field_or_id;
	    return array_key_exists($field_id, $this->user_fields) || in_array($field_id, array('user_email','user_name'));
	}
	
	/**
	 * Prints html fields according to this field structure.
	 * @param array $booking_form_fields
	 */
	function __toString(){
		$return = '';
		if( is_array($this->form_fields) ){
			foreach($this->form_fields as $field){
				$return .= self::output_field($field);
			}
		}
		return apply_filters('emp_form_output',$return, $this);
	}
	
	function output_field($field, $post=true){
		ob_start();
		$required = ( !empty($field['required']) ) ? ' '.apply_filters('emp_forms_output_field_required','<span class="em-form-required">*</span>'):'';
		switch($field['type']){
			case 'html':
			     echo $this->output_field_input($field, $post);
			     break;
			case 'text':
			case 'textarea':
			case 'checkbox':
			case 'date':
			case 'checkboxes':
			case 'radio':
			case 'select':
			case 'country':
			case 'multiselect':
			case 'time':
				$tip_type = $field['type'];
				if( $field['type'] == 'textarea' ) $tip_type = 'text';
				if( in_array($field['type'], array('select','multiselect')) ) $tip_type = 'select';
				if( in_array($field['type'], array('checkboxes','radio')) ) $tip_type = 'selection';
				?>
				<p class="input-group input-<?php echo $field['type']; ?> input-field-<?php echo $field['fieldid'] ?>">
					<label for='<?php echo $field['fieldid'] ?>'>
						<?php if( !empty($field['options_'.$tip_type.'_tip']) ): ?>
							<span class="form-tip" title="<?php echo $field['options_'.$tip_type.'_tip']; ?>">
								<?php echo $field['label'] ?> <?php echo $required  ?>
							</span>
						<?php else: ?>
							<?php echo $field['label'] ?> <?php echo $required  ?>
						<?php endif; ?>
					</label>
					<?php echo $this->output_field_input($field, $post); ?>
				</p>
				<?php
				break;
			case 'captcha':
				if( !function_exists('recaptcha_get_html') ) { include_once(trailingslashit(plugin_dir_path(__FILE__)).'includes/lib/recaptchalib.php'); }
				if( function_exists('recaptcha_get_html') && !is_user_logged_in() ){
					?>
					<p class="input-group input-<?php echo $field['type']; ?> input-field-<?php echo $field['fieldid'] ?>">
					<label for='<?php echo $field['fieldid'] ?>'><?php echo $field['label']. $required  ?></label>
					<?php
					echo $this->output_field_input($field, $post);
				}
				break;
			default:
			    $is_hidden_reg = is_user_logged_in() && in_array($field['type'], apply_filters('emp_hidden_reg_fields', array('first_name','last_name', 'user_email','user_login', 'name', 'user_password')));
				if( array_key_exists($field['type'], $this->user_fields) && self::show_reg_fields() && !(!defined('EM_FORCE_REGISTRATION') && $is_hidden_reg) ){
					if( empty($_REQUEST[$field['fieldid']]) && is_user_logged_in() && !defined('EM_FORCE_REGISTRATION') ){
						if( $field['type'] == 'name' ){
							$EM_Person = new EM_Person(get_current_user_id());
							$post = $EM_Person->get_name();
						}else{
							$post = get_user_meta(get_current_user_id(), $field['fieldid'], true);
						}
					}
					if( array_key_exists($field['type'], $this->core_user_fields) ){
						if( $field['type'] == 'user_password' ){
							?>
							<p class="input-<?php echo $field['type']; ?> input-user-field">
								<label for='<?php echo $field['fieldid'] ?>'>
									<?php if( !empty($field['options_reg_tip']) ): ?>
										<span class="form-tip" title="<?php echo $field['options_reg_tip']; ?>">
											<?php echo $field['label'] ?> <?php echo $required  ?>
										</span>
									<?php else: ?>
										<?php echo $field['label'] ?> <?php echo $required  ?>
									<?php endif; ?>
								</label>
								<input type="password" name="<?php echo $field['fieldid'] ?>" />
							</p>
							<?php
						}else{
							//registration fields
							?>
							<p class="input-<?php echo $field['type']; ?> input-user-field">
								<label for='<?php echo $field['fieldid'] ?>'>
									<?php if( !empty($field['options_reg_tip']) ): ?>
										<span class="form-tip" title="<?php echo $field['options_reg_tip']; ?>">
											<?php echo $field['label'] ?> <?php echo $required  ?>
										</span>
									<?php else: ?>
										<?php echo $field['label'] ?> <?php echo $required  ?>
									<?php endif; ?>
								</label> 
								<?php echo $this->output_field_input($field, $post); ?>
							</p>
							<?php	
						}
					}elseif( array_key_exists($field['type'], $this->custom_user_fields) ) {
						?>
						<p class="input-<?php echo $field['type']; ?> input-user-field">
							<label for='<?php echo $field['fieldid'] ?>'>
								<?php if( !empty($field['options_reg_tip']) ): ?>
									<span class="form-tip" title="<?php echo $field['options_reg_tip']; ?>">
										<?php echo $field['label'] ?> <?php echo $required  ?>
									</span>
								<?php else: ?>
									<?php echo $field['label'] ?> <?php echo $required  ?>
								<?php endif; ?>
							</label>
							<?php do_action('em_form_output_field_custom_'.$field['type'], $field, $post); ?>
						</p>
						<?php
					}
				}
				break;
		}	
		return apply_filters('emp_forms_output_field', ob_get_clean(), $this, $field);	
	}
	
	function output_field_input($field, $post=true){
		ob_start();
		$default = '';
		$default_html = '';
		if($post === true && !empty($_REQUEST[$field['fieldid']])) {
			$default = is_array($_REQUEST[$field['fieldid']]) ? $_REQUEST[$field['fieldid']]:esc_attr($_REQUEST[$field['fieldid']]);
			$default_html = esc_attr($_REQUEST[$field['fieldid']]);
		}elseif( $post !== true && !empty($post) ){
			$default = is_array($post) ? $post:esc_attr($post);
			$default_html = esc_attr($post);
		}
		$field_name = !empty($field['name']) ? $field['name']:$field['fieldid'];
		switch($field['type']){
			case 'html':
			    echo $field['options_html_content'];
			    break;			
			case 'text':
				?>
				<input type="text" name="<?php echo $field_name ?>" id="<?php echo $field['fieldid'] ?>" class="input" value="<?php echo $default; ?>"  />
				<?php
				break;	
			case 'textarea':
				$size = 'rows="2" cols="20"';
			    if( defined('EMP_FORMS_TEXTAREA_SIZE') && EMP_FORMS_TEXTAREA_SIZE ){
			        $sizes = explode(',', EMP_FORMS_TEXTAREA_SIZE);
			        if( count($sizes) > 1 ){
						$size = 'rows="'.$sizes[0].'" cols="'.$sizes[1].'"';
					}else{
						$size = EMP_FORMS_TEXTAREA_SIZE;				
					}
			    }
				?>
				<textarea name="<?php echo $field_name ?>" id="<?php echo $field['fieldid'] ?>" class="input" <?php echo $size; ?>><?php echo $default_html; ?></textarea>
				<?php
				break;
			case 'checkbox':
				?>
				<input type="checkbox" name="<?php echo $field_name ?>" id="<?php echo $field['fieldid'] ?>" value="1" <?php if( ($default && $default != 'n/a') || $field['options_checkbox_checked']) echo 'checked="checked"'; ?> />
				<?php
				break;
			case 'checkboxes':
				echo "<span class=\"input-group\">";
				if(!is_array($default)) $default = array();
				$values = explode("\r\n",$field['options_selection_values']);
				foreach($values as $value){ 
					$value = trim($value); 
					?><input type="checkbox" name="<?php echo $field_name ?>[]" class="<?php echo $field['fieldid'] ?>" value="<?php echo esc_attr($value) ?>" <?php if(in_array($value, $default)) echo 'checked="checked"'; ?> /> <?php echo $value ?><br /><?php 
				}
				echo "</span>";
				break;
			case 'radio':
				echo "<span class=\"input-group\">";
				$values = explode("\r\n",$field['options_selection_values']);
				foreach($values as $value){
					$value = trim($value); 
					?><input type="radio" name="<?php echo $field_name ?>" class="<?php echo $field['fieldid'] ?>" value="<?php echo esc_attr($value) ?>" <?php if($value == $default) echo 'checked="checked"'; ?> /> <?php echo $value ?><br /><?php
				}
				echo "</span>";
				break;
			case 'select':
			case 'multiselect':
				$values = explode("\r\n",$field['options_select_values']);
				$multi = $field['type'] == 'multiselect';
				if($multi && !is_array($default)) $default = (empty($default)) ? array():array($default);
				?>
				<select name="<?php echo $field_name ?><?php echo ($multi) ? '[]':''; ?>" class="<?php echo $field['fieldid'] ?>" <?php echo ($multi) ? 'multiple':''; ?>>
				<?php 
					//calculate default value to be checked
					if( !$field['options_select_default'] ){
						?>
						<option value=""><?php echo esc_html($field['options_select_default_text']); ?></option>
						<?php
					}
					$count = 0;
				?>
				<?php foreach($values as $value): $value = trim($value); $count++; ?>
					<option <?php echo (($count == 1 && $field['options_select_default']) || ($multi && in_array($value, $default)) || ($value == $default) )?'selected="selected"':''; ?>>
						<?php echo esc_html($value) ?>
					</option>
				<?php endforeach; ?>
				</select>
				<?php
				break;
			case 'country':
				?>
				<select name="<?php echo $field_name ?>" class="<?php echo $field['fieldid'] ?>">
				<?php foreach(em_get_countries(__('none selected','dbem')) as $country_key => $country_name): ?>
					<option value="<?php echo $country_key; ?>"  <?php echo ($country_key == $default) ?'selected="selected"':''; ?>><?php echo $country_name; ?></option>
					<?php endforeach; ?>
				</select>
				<?php	
				break;
			case 'date':
			    $date_type = !empty($field['options_date_range']) ? 'em-date-range':'em-date-single';
				if( !empty($_REQUEST[$field_name]['start']) && preg_match('/\d{4}-\d{2}-\d{2}/', $_REQUEST[$field_name]['start'])) {
					$default = array( $_REQUEST[$field_name]['start'] );
					if( !empty($_REQUEST[$field_name]['end']) && preg_match('/\d{4}-\d{2}-\d{2}/', $_REQUEST[$field_name]['end'])){
						$default[] = $_REQUEST[$field_name]['end'];
					}
				}else{
					$default = explode(',',$default);
					if( !empty($default[0]) && !preg_match('/\d{4}-\d{2}-\d{2}/', $default[0]) ) $default = ''; //make sure the value is a date
				}
				//we're adding a [%s] to the field id and replacing this for the start-end field names because this way other bits (e.g. attendee forms) can manipulate where the [start] and [end] are placed in the element name. 
				$field_id = strstr($field_name,'[') ? $field_name:$field_name.'[%s]';
				?>
    			<span class="<?php echo $date_type; ?>">			
					<input class="em-date-start em-date-input-loc" type="text" name="<?php echo str_replace('[%s]','[start_loc]', $field_id); ?>" />
					<input class="em-date-input" type="hidden" name="<?php echo str_replace('[%s]','[start]', $field_id); ?>" value="<?php echo !empty($default[0]) ? $default[0]:'' ?>" />
					<?php if( !empty($field['options_date_range']) ) : ?>
					<?php echo $field['options_date_range_seperator']; ?>
					<input class="em-date-end em-date-input-loc" type="text" name="<?php echo str_replace('[%s]','[end_loc]', $field_id); ?>" />
					<input class="em-date-input" type="hidden" name="<?php echo str_replace('[%s]','[end]', $field_id); ?>" value="<?php echo !empty($default[1]) ? $default[1]:'' ?>" />
					<?php endif; ?>
				</span>
    			<?php
    			break;	
			case 'time':
			    $date_type = !empty($field['options_time_range']) ? 'em-time-range':'em-time-single';
				if( !empty($_REQUEST[$field_name]['start']) && !preg_match('/^([01]\d|2[0-3]):([0-5]\d) ?(AM|PM)?$/', $_REQUEST[$field_name]['start']) ) {
					$default = array( $_REQUEST[$field_name]['start'] );
					if( !empty($_REQUEST[$field_name]['end']) && !preg_match('/^([01]\d|2[0-3]):([0-5]\d) ?(AM|PM)?$/', $_REQUEST[$field_name]['end']) ){
						$default[] = $_REQUEST[$field_name]['end'];
					}
				}else{
					if( !is_array($default) ) $default = explode(',',$default);
					if( !empty($default[0]) && !preg_match('/^([01]\d|2[0-3]):([0-5]\d) ?(AM|PM)?$/', $default[0]) ) $default = ''; //make sure the value is a date
				}
				//we're adding a [%s] to the field id and replacing this for the start-end field names because this way other bits (e.g. attendee forms) can manipulate where the [start] and [end] are placed in the element name. 
				$field_id = strstr($field_name,'[') ? $field_name:$field_name.'[%s]';
				?>
    			<span class="<?php echo $date_type; ?>">			
					<input class="em-time-input em-time-start" type="text" size="8" maxlength="8" name="<?php echo str_replace('[%s]','[start]', $field_id); ?>" value="<?php echo !empty($default[0]) ? $default[0]:'' ?>" />
					<?php if( !empty($field['options_time_range']) ) : ?>
					<?php echo $field['options_time_range_seperator']; ?>
					<input class="em-time-input em-time-end" type="text" size="8" maxlength="8" name="<?php echo str_replace('[%s]','[end]', $field_id); ?>" value="<?php echo !empty($default[1]) ? $default[1]:'' ?>" />
					<?php endif; ?>					
				</span>
    			<?php
    			break;	
			case 'captcha':
				if( !function_exists('recaptcha_get_html') ) { include_once(trailingslashit(plugin_dir_path(__FILE__)).'includes/lib/recaptchalib.php'); }
				if( function_exists('recaptcha_get_html') && !is_user_logged_in() ){
					?>
					<span> 
						<?php echo recaptcha_get_html($field['options_captcha_key_pub'], $field['options_captcha_error'], is_ssl()); ?>
					</span>
					<?php
				}
				break;
			default:
				if( array_key_exists($field['type'], $this->user_fields) && self::show_reg_fields() ){
					//registration fields
				    if( get_option('dbem_emp_booking_form_reg_input') || !is_user_logged_in() || defined('EM_FORCE_REGISTRATION') ){
						?>
						<input type="text" name="<?php echo $field_name ?>" id="<?php echo $field['fieldid'] ?>" class="input" value="<?php echo $default; ?>"  />
						<?php
					}else{
						echo $default;
					}
				}
				break;
		}	
		return apply_filters('emp_forms_output_field_input', ob_get_clean(), $this, $field, $post);	
	}
	
	/**
	 * Validates all fields, if false, an array of objects is returned.
	 * @return array|string
	 */
	function validate(){
		$reg_fields = self::validate_reg_fields();
		foreach( $this->form_fields as $field ){
			$field_id = $field['fieldid'];
			if( $reg_fields || ( !$reg_fields && !array_key_exists($field['type'], $this->user_fields) ) ){ //don't validate reg info if we won't grab anything in get_post
				$value = ( array_key_exists($field_id, $this->field_values) ) ? $this->field_values[$field_id] : '';
				$this->validate_field($field_id, $value);
			}
		}
		if( count($this->get_errors()) > 0 ){
			return false;
		}
		return true;
	}
	
	/**
	 * Validates a field and adds errors to the object it's referring to (can be any extension of EM_Object)
	 * @param array $field
	 * @param mixed $value
	 */
	function validate_field( $field_id, $value ){
		$field = array_key_exists($field_id, $this->form_fields) ? $this->form_fields[$field_id]:false;
		$value = (is_array($value)) ? $value:trim($value);
		$err = sprintf($this->form_required_error, $field['label']);
		if( is_array($field) ){
			$result = true; //innocent until proven guilty
			switch($field['type']){
				case 'text':
				case 'textarea':
					//regex
					if( trim($value) != '' && !empty($field['options_text_regex']) && !@preg_match('/'.$field['options_text_regex'].'/',$value) ){
						$this_err = (!empty($field['options_text_error'])) ? $field['options_text_error']:$err;
						$this->add_error($this_err);
						$result = false;
					}
					//non-empty match
					if( $result && trim($value) == '' && !empty($field['required']) ){
						$this->add_error($err);
						$result = false;
					}
					break;
				case 'checkbox':
					//non-empty match
					if( empty($value) && !empty($field['required']) ){
						$this_err = (!empty($field['options_checkbox_error'])) ? $field['options_checkbox_error']:$err;
						$this->add_error($this_err);
						$result = false;
					}
					break;
				case 'checkboxes':
					$values = explode("\r\n",$field['options_selection_values']);
					array_walk($values,'trim');
					if( !is_array($value) ) $value = array();
					//in-values
					if( (empty($value) && !empty($field['required'])) || count(array_diff($value, $values)) > 0 ){
						$this_err = (!empty($field['options_selection_error'])) ? $field['options_selection_error']:$err;
						$this->add_error($this_err);
						$result = false;
					}
					break;
				case 'radio':
					$values = explode("\r\n",$field['options_selection_values']);
					array_walk($values,'trim');
					//in-values
					if( (!empty($value) && !in_array($value, $values)) || (empty($value) && !empty($field['required'])) ){
						$this_err = (!empty($field['options_selection_error'])) ? $field['options_selection_error']:$err;
						$this->add_error($this_err);
						$result = false;
					}				
					break;
				case 'multiselect':
					$values = explode("\r\n",$field['options_select_values']);
					array_walk($values,'trim');
					if( !is_array($value) ) $value = array();
					//in_values
					if( (empty($value) && !empty($field['required'])) || count(array_diff($value, $values)) > 0 ){
						$this_err = (!empty($field['options_select_error'])) ? $field['options_select_error']:$err;
						$this->add_error($this_err);
						$result = false;
					}				
					break;
				case 'select':
					$values = explode("\r\n",$field['options_select_values']);
					array_walk($values,'trim');
					//in-values
					if( (!empty($value) && !in_array($value, $values)) || (empty($value) && !empty($field['required'])) ){
						$this_err = (!empty($field['options_select_error'])) ? $field['options_select_error']:$err;
						$this->add_error($this_err);
						$result = false;
					}				
					break;
				case 'country':
					$values = em_get_countries(__('none selected','dbem'));
					//in-values
					if( (!empty($value) && !array_key_exists($value, $values)) || (empty($value) && !empty($field['required'])) ){
						$this_err = (!empty($field['options_select_error'])) ? $field['options_select_error']:$err;
						$this->add_error($this_err);
						$result = false;
					}				
					break;			
				case 'date':
				    $dates = !is_array($value) ? explode(',',$value):$value;
				    $start_date = $dates[0];
				    $end_date = !empty($dates[1]) ? $dates[1]:'';
				    if( !empty($start_date) ){
						if( preg_match('/\d{4}-\d{2}-\d{2}/', $start_date) ){
							if( $field['options_date_range'] ){
								if( empty($end_date) ){
									$this_err = (!empty($field['options_date_error_end'])) ? $field['options_date_error_end']:$this->add_error(__('You must also add an end date.','em-pro'));
									$this->add_error($this_err);
									$result = false;
								}elseif( !preg_match('/\d{4}-\d{2}-\d{2}/', $end_date) ){
									$this_err = (!empty($field['options_date_error_format'])) ? $field['options_date_error_format']:__('Dates must have correct formatting. Please use the date picker provided.','dbem');
									$this->add_error($this_err);
									$result = false;
								}else{
									//valid end date, check for date order
									if( strtotime($start_date) > strtotime($end_date) ){
										$this_err = (!empty($field['options_date_range_order'])) ? $field['options_date_range_order']:__('Please provide a later end date.','dbem');
										$this->add_error($this_err);
										$result = false;
									}
								}
							}
						}else{
							$this_err = (!empty($field['options_date_error_format'])) ? $field['options_date_error_format']:__('Dates must have correct formatting. Please use the date picker provided.','dbem');
							$this->add_error($this_err);
							$result = false;
						}
					}elseif( !empty($field['required']) ){
						if( $field['options_date_range'] && !empty($end_date) ){
							$this_err = (!empty($field['options_date_error_start'])) ? $field['options_date_error_start']:$this->add_error(__('You must provide a start date.','em-pro'));
							$this->add_error($this_err);
							$result = false;
						}else{
							$this_err = (!empty($field['options_date_error'])) ? $field['options_date_error']:$err;
							$this->add_error($this_err);
							$result = false;
						}
					}
					break;			
				case 'time':
				    $times = !is_array($value) ? explode(',',$value):$value;
				    $start_time = $times[0];
				    $end_time = !empty($times[1]) ? $times[1]:'';
				    if( !empty($start_time) ){
						if( preg_match('/^([01]\d|2[0-3]):([0-5]\d) ?(AM|PM)?$/', $start_time) ){
							if( $field['options_time_range'] ){
								if( empty($end_time) ){
									$this_err = (!empty($field['options_time_error_end'])) ? $field['options_time_error_end']:$this->add_error(__('You must provide an end time.','em-pro'));
									$this->add_error($this_err);
									$result = false;
								}elseif( !preg_match('/^([01]\d|2[0-3]):([0-5]\d) ?(AM|PM)?$/', $end_time) ){
									$this_err = (!empty($field['options_time_error_format'])) ? $field['options_time_error_format']:$this->add_error(__('Please use the time picker provided to select the appropriate time format.','em-pro'));
									$this->add_error($this_err);
									$result = false;
								}
							}
						}else{
							$this_err = (!empty($field['options_time_error_format'])) ? $field['options_time_error_format']:__('Please use the time picker provided to select the appropriate time format.','em-pro');
							$this->add_error($this_err);
							$result = false;
						}
					}elseif( !empty($field['required']) ){
						if( $field['options_time_range'] && !empty($end_time) ){
							$this_err = (!empty($field['options_time_error_start'])) ? $field['options_time_error_start']:$this->add_error(__('You must provide a start time.','em-pro'));
							$this->add_error($this_err);
							$result = false;
						}else{
							$this_err = (!empty($field['options_time_error'])) ? $field['options_time_error']:$err;
							$this->add_error($this_err);
							$result = false;
						}
					}
					break;		
				case 'captcha':
					if( !function_exists('recaptcha_get_html') ) { include_once(trailingslashit(plugin_dir_path(__FILE__)).'includes/lib/recaptchalib.php'); }
					if( function_exists('recaptcha_check_answer') && !is_user_logged_in() && !defined('EMP_CHECKED_CAPTCHA') ){
						$resp = recaptcha_check_answer($field['options_captcha_key_priv'], $_SERVER['REMOTE_ADDR'], $_REQUEST['recaptcha_challenge_field'], $_REQUEST['recaptcha_response_field']);
						$result = $resp->is_valid;
						if(!$result){
							$err = !empty($field['options_captcha_error']) ? $field['options_captcha_error']:$err;
							$this->add_error($err);
						}
						define('EMP_CHECKED_CAPTCHA', true); //captchas can only be checked once, and since we only need one captcha per submission....
					}
					break;
				default:
					//Registration and custom fields
					$is_manual_booking_new_user = ( !empty($_REQUEST['manual_booking']) && wp_verify_nonce($_REQUEST['manual_booking'], 'em_manual_booking_'.$_REQUEST['event_id']) && $_REQUEST['person_id'] == -1 );
				    $is_hidden_reg = (is_user_logged_in() && !$is_manual_booking_new_user) && in_array($field['type'], array('first_name','last_name', 'user_email','user_login', 'name', 'user_password'));
					if( array_key_exists($field['type'], $this->user_fields) && self::validate_reg_fields() && !(!defined('EM_FORCE_REGISTRATION') && $is_hidden_reg) ){
						//preliminary checks/exceptions
						if( in_array($field['type'], array('user_login','first_name','last_name')) && (is_user_logged_in() && !$is_manual_booking_new_user) && !defined('EM_FORCE_REGISTRATION') ) break;
						if( is_user_logged_in() && !get_option('dbem_emp_booking_form_reg_input') ) break;
						//add field-specific validation
						if ( $field['type'] == 'user_email' && ! is_email( $value ) ) {
							$this->add_error( __( '<strong>ERROR</strong>: The email address isn&#8217;t correct.', 'dbem') );
							$result = false;
						}
						//regex
						if( trim($value) != '' && !empty($field['options_reg_regex']) && !@preg_match('/'.$field['options_reg_regex'].'/',$value) ){
							$this_err = (!empty($field['options_reg_error'])) ? $field['options_reg_error']:$err;
							$this->add_error($this_err);
							$result = false;
						}
						//non-empty match
						if( empty($value) && !empty($field['required']) ){
							$this->add_error($err);
							$result = false;
						}
						//custom field chekcs
						if( array_key_exists($field['type'], $this->custom_user_fields)) {
							//custom field, so just apply 
							$result = apply_filters('em_form_validate_field_custom', $result, $field, $value, $this);
						}
					}
					break;
			}
		}else{
			$result = false;
		}
		return apply_filters('emp_form_validate_field',$result, $field, $value, $this);
	}
	
	/**
	 * Gets an array of field keys accepted by the booking form 
	 */
	function get_fields_map(){
		$map = array (
			'fieldid','label','type','required',
			'options_select_values','options_select_default','options_select_default_text','options_select_error','options_select_tip',
			'options_selection_values','options_selection_default','options_selection_error','options_selection_tip',
			'options_checkbox_error','options_checkbox_checked','options_checkbox_tip',
			'options_text_regex','options_text_error','options_text_tip',
			'options_reg_regex', 'options_reg_error','options_reg_tip',
			'options_captcha_theme','options_captcha_key_priv','options_captcha_key_pub', 'options_captcha_error', 'options_captcha_tip',
			'options_date_error','options_date_range','options_date_range_seperator','options_date_error_format','options_date_error_end','options_date_tip',
			'options_time_error','options_time_range','options_time_range_seperator','options_time_error_format','options_time_error_end','options_time_error_start','options_time_tip',
			'options_html_content'
		);
		return apply_filters('em_form_get_fields_map', $map);
	}
	
	/*
	 * --------------------------------------------------------
	 * Admin-Side Functions
	 * --------------------------------------------------------
	 */
	
	function get_input_default($key, $field_values, $type='text', $value=""){
		$return = '';
		if(is_array($field_values)){
			switch ($type){
				case 'text':
					$return = (array_key_exists($key,$field_values)) ? 'value="'.esc_attr($field_values[$key]).'"':'value="'.esc_attr($value).'"';
					break;
				case 'textarea':
					$return = (array_key_exists($key,$field_values)) ? esc_html($field_values[$key]):esc_html($value);
					break;
				case 'select':
					$return = ( array_key_exists($key,$field_values) && $value == $field_values[$key] ) ? 'selected="selected"':'';
					break;
				case 'checkbox':
					$return = ( !empty($field_values[$key]) && $field_values[$key] == 1 ) ? 'checked="checked"':'';
					break;
				case 'radio':
					$return = ( $value == $field_values[$key] ) ? 'checked="checked"':'';
					break;
			}
		}
		return apply_filters('emp_form_get_input_default',$return, $key, $field_values, $type, $value);
	}
	function input_default($key, $fields, $type = 'text', $value=""){ echo self::get_input_default($key, $fields, $type, $value); }

	
	private static function show_reg_fields(){
		$show_reg = ((!is_user_logged_in() || defined('EM_FORCE_REGISTRATION')) && get_option('dbem_bookings_anonymous')) || (is_user_logged_in() && get_option('dbem_emp_booking_form_reg_show'));
		return apply_filters('emp_form_show_reg_fields', $show_reg); 
	}

	private static function validate_reg_fields(){
		$validate = is_user_logged_in() ? get_option('dbem_emp_booking_form_reg_show') && get_option('dbem_emp_booking_form_reg_input') : true;
		return self::show_reg_fields() && $validate;
	}
	
	function editor($user_fields = true, $custom_fields = true, $captcha_fields = true){
		$fields = $this->form_fields;
		if( empty($fields) ){ $fields = array(self::get_fields_map());  }
		$fields['blank_em_template'] = self::get_fields_map();
		$form_name = "em-form-". sanitize_title_with_dashes($this->form_name);		 
		?>
		<form method="post" action="" class="em-form-custom" id="<?php echo $form_name; ?>">
			<div>
				<div class="booking-custom-head">
					<div class='bc-col-sort bc-col'>&nbsp;</div>
					<div class='bc-col-label bc-col'><?php _e('Label','em-pro'); ?></div>
					<div class='bc-col-id bc-col'><?php _e('Field ID','em-pro'); ?><a title="<?php _e('DO NOT change these values if you want to keep your field settings associated with previous booking fields.','em-pro'); ?>">?</a></div>
					<div class='bc-col-type bc-col'><?php _e('Type','em-pro'); ?></div>
					<div class='bc-col-required bc-col'><?php _e('Required','em-pro'); ?></div>
				</div>
				<ul class="booking-custom-body">
					<?php foreach($fields as $field_key => $field_values): ?>
					<li class="booking-custom-item" <?php if( $field_key === 'blank_em_template' ){ echo 'id="booking-custom-item-template"'; }; ?>>
						<div class='bc-col-sort bc-col'>&nbsp;</div>
						<div class='bc-col-label bc-col'><input type="text" name="label[]" class="booking-form-custom-label" <?php self::input_default('label',$field_values); ?> /></div>
						<div class='bc-col-id bc-col'><input type="text" name="fieldid[]" class="booking-form-custom-fieldid" <?php self::input_default('fieldid',$field_values); ?> /></div>
						<div class='bc-col-type bc-col'>
							<select name="type[]" class="booking-form-custom-type">
								<option value=""><?php echo _e('Select Type','em-pro'); ?></option>
								<?php if($custom_fields): ?>
								<optgroup label="<?php _e('Customizable Fields','em-pro'); ?>">
									<option <?php self::input_default('type',$field_values,'select','text'); ?>>text</option>
									<option <?php self::input_default('type',$field_values,'select','html'); ?>>html</option>
									<option <?php self::input_default('type',$field_values,'select','checkbox'); ?>>checkbox</option>
									<option <?php self::input_default('type',$field_values,'select','textarea'); ?>>textarea</option>
									<option <?php self::input_default('type',$field_values,'select','checkboxes'); ?>>checkboxes</option>
									<option <?php self::input_default('type',$field_values,'select','radio'); ?>>radio</option>
									<option <?php self::input_default('type',$field_values,'select','select'); ?>>select</option>
									<option <?php self::input_default('type',$field_values,'select','multiselect'); ?>>multiselect</option>
									<option <?php self::input_default('type',$field_values,'select','country'); ?>>country</option>
									<option <?php self::input_default('type',$field_values,'select','date'); ?>>date</option>
									<option <?php self::input_default('type',$field_values,'select','time'); ?>>time</option>
									<?php if($captcha_fields): ?>
									<option <?php self::input_default('type',$field_values,'select','captcha'); ?>>captcha</option>
									<?php endif; ?>
								</optgroup>
								<?php endif; ?>
								<?php if($user_fields): ?>
								<optgroup label="<?php _e('Registration Fields','em-pro'); ?>">
									<?php foreach( $this->core_user_fields as $field_id => $field_name ): ?>
									<option value="<?php echo $field_id; ?>" <?php self::input_default('type',$field_values,'select',$field_id); ?>><?php echo $field_name; ?></option>
									<?php endforeach; ?>
								</optgroup>
								<?php 
									if( count($this->custom_user_fields) > 0 ){
										?>
										<optgroup label="<?php _e('Custom Registration Fields','em-pro'); ?>">
											<?php foreach( $this->custom_user_fields as $field_id => $field_name ): ?>
											<option value="<?php echo $field_id; ?>" <?php self::input_default('type',$field_values,'select',$field_id); ?>><?php echo $field_name; ?></option>
											<?php endforeach; ?>
										</optgroup>
										<?php
									} 
								?>
								<?php endif; ?>
							</select>
						</div>
						<div class='bc-col-required bc-col'>
							<input type="checkbox" class="booking-form-custom-required" value="1" <?php self::input_default('required',$field_values,'checkbox'); ?> />
							<input type="hidden" name="required[]" <?php self::input_default('required',$field_values,'text'); ?> />
						</div>
						<div class='bc-col-options bc-col'><a href="#" class="booking-form-custom-field-remove"><?php _e('remove','em-pro'); ?></a> | <a href="#" class="booking-form-custom-field-options"><?php _e('options','em-pro'); ?></a></div>
						<div class='booking-custom-types'>
							<?php if($custom_fields): ?>
							<div class="bct-select bct-options" style="display:none;">
								<!-- select,multiselect -->
								<div class="bct-field">
									<div class="bct-label"><?php _e('Options','em-pro'); ?></div>
									<div class="bct-input">
										<textarea name="options_select_values[]"><?php self::input_default('options_select_values',$field_values,'textarea'); ?></textarea>
										<em><?php _e('Available options, one per line.','em-pro'); ?></em>	
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Use Default?','em-pro'); ?></div>
									<div class="bct-input">
										<input type="checkbox" <?php self::input_default('options_select_default',$field_values,'checkbox'); ?> value="1" />
										<input type="hidden" name="options_select_default[]" <?php self::input_default('options_select_default',$field_values); ?> /> 
										<em><?php _e('If checked, the first value above will be used.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Default Text','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_select_default_text[]" <?php self::input_default('options_select_default_text',$field_values,'text',__('Select ...','em-pro')); ?> />
										<em><?php _e('Shown when a default value isn\'t selected, selected by default.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Tip Text','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_select_tip[]" <?php self::input_default('options_select_tip',$field_values); ?> />
										<em><?php _e('Will appear next to your field label as a question mark with a popup tip bubble.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Error Message','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_select_error[]" <?php self::input_default('options_select_error',$field_values); ?> />
										<em>
											<?php _e('This error will show if a value isn\'t chosen.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.sprintf(get_option('em_booking_form_error_required'), '[FIELD]').'</code>'; ?>
										</em>
									</div>
								</div>
								<?php do_action('emp_forms_editor_select_multiselect_options', $this, $field_values); ?>
							</div>
							<div class="bct-html bct-options" style="display:none;">
								<!-- html -->
								<div class="bct-field">
									<div class="bct-label"><?php _e('Content','em-pro'); ?></div>
									<div class="bct-input">
										<em><?php _e('This html will be displayed on your form, the label for this field is used only for reference purposes.','em-pro'); ?></em>
										<textarea name="options_html_content[]"><?php if( !empty($field_values['options_html_content']) ) echo $field_values['options_html_content']; ?></textarea>
									</div>
								</div>
								<?php do_action('emp_forms_editor_html_options', $this, $field_values); ?>
							</div>	
							<div class="bct-country bct-options" style="display:none;">
								<!-- country -->
								<div class="bct-field">
									<div class="bct-label"><?php _e('Error Message','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_country_error[]" <?php self::input_default('options_country_error',$field_values); ?> />
										<em>
											<?php _e('This error will show if a value isn\'t chosen.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.sprintf(get_option('em_booking_form_error_required'), '[FIELD]').'</code>'; ?>
										</em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Tip Text','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_country_tip[]" <?php self::input_default('options_country_tip',$field_values); ?> />
										<em><?php _e('Will appear next to your field label as a question mark with a popup tip bubble.','em-pro'); ?></em>
									</div>
								</div>
								<?php do_action('emp_forms_editor_country_options', $this, $field_values); ?>
							</div>	
							<div class="bct-date bct-options" style="display:none;">
								<!-- country -->
								<div class="bct-field">
									<div class="bct-label"><?php _e('Date Range?','em-pro'); ?></div>
									<div class="bct-input">
										<input type="checkbox" <?php self::input_default('options_date_range',$field_values,'checkbox'); ?> value="1" />
										<input type="hidden" name="options_date_range[]" <?php self::input_default('options_date_range',$field_values); ?> /> 
										<em><?php _e('If selected, this field will also have an end-date.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Separator','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_date_range_seperator[]" <?php self::input_default('options_date_range_seperator',$field_values); ?> />
										<em><?php _e('This text will appear between the two date fields if this is a date range.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Tip Text','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_date_tip[]" <?php self::input_default('options_date_tip',$field_values); ?> />
										<em><?php _e('Will appear next to your field label as a question mark with a popup tip bubble.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<p><strong><?php _e('Error Messages','em-pro'); ?></strong></p>
									<div class="bct-label"><?php _e('Field Required','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_date_error[]" <?php self::input_default('options_date_error',$field_values); ?> />
										<em>
											<?php _e('This error will show this field is required and no value is entered.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.sprintf(get_option('em_booking_form_error_required'), '[FIELD]').'</code>'; ?>
										</em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Incorrect Formatting','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_date_error_format[]" <?php self::input_default('options_date_error_format',$field_values); ?> />
										<em>
											<?php _e('This error will show if an incorrect date format is used.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.__('Please use the date picker provided to select the appropriate date format.','em-pro').'</code>'; ?>
										</em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('End date required','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_date_error_end[]" <?php self::input_default('options_date_error_end',$field_values); ?> />
										<em>
											<?php _e('This error will show if the field is a date-range and no end date is selected.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.__('You must provide an end date.','em-pro').'</code>'; ?>
										</em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Start date required','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_date_error_start[]" <?php self::input_default('options_date_error_start',$field_values); ?> />
										<em>
											<?php _e('This error will show if the field is a date-range and no start date is selected.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.__('You must provide a start date.','em-pro').'</code>'; ?>
										</em>
									</div>
								</div>
								<?php do_action('emp_forms_editor_date_options', $this, $field_values); ?>
							</div>	
							<div class="bct-time bct-options" style="display:none;">
								<div class="bct-field">
									<div class="bct-label"><?php _e('Time Range?','em-pro'); ?></div>
									<div class="bct-input">
										<input type="checkbox" <?php self::input_default('options_time_range',$field_values,'checkbox'); ?> value="1" />
										<input type="hidden" name="options_time_range[]" <?php self::input_default('options_time_range',$field_values); ?> /> 
										<em><?php _e('If selected, this field will also have an end-time.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Separator','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_time_range_seperator[]" <?php self::input_default('options_time_range_seperator',$field_values); ?> />
										<em><?php _e('This text will appear between the two date fields if this is a date range.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Tip Text','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_time_tip[]" <?php self::input_default('options_time_tip',$field_values); ?> />
										<em><?php _e('Will appear next to your field label as a question mark with a popup tip bubble.','em-pro'); ?></em>
									</div>
								</div>
								<p><strong><?php _e('Error Messages','em-pro'); ?></strong></p>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Field Required','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_time_error[]" <?php self::input_default('options_time_error',$field_values); ?> />
										<em>
											<?php _e('This error will show this field is required and no value is entered.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.sprintf(get_option('em_booking_form_error_required'), '[FIELD]').'</code>'; ?>
										</em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Incorrect Formatting','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_time_error_format[]" <?php self::input_default('options_time_error_format',$field_values); ?> />
										<em>
											<?php _e('This error will show if an incorrect time format is used.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.__('Please use the time picker provided to select the appropriate time format.','em-pro').'</code>'; ?>
										</em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('End time required','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_time_error_end[]" <?php self::input_default('options_time_error_end',$field_values); ?> />
										<em>
											<?php _e('This error will show if the field is a time-range and no end time is selected.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.__('You must provide an end time.','em-pro').'</code>'; ?>
										</em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Start time required','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_time_error_start[]" <?php self::input_default('options_time_error_start',$field_values); ?> />
										<em>
											<?php _e('This error will show if the field is a time-range and no start time is selected.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.__('You must provide a start time.','em-pro').'</code>'; ?>
										</em>
									</div>
								</div>
								<?php do_action('emp_forms_editor_time_options', $this, $field_values); ?>
							</div>				
							<div class="bct-selection bct-options" style="display:none;">
								<!-- checkboxes,radio -->
								<div class="bct-field">
									<div class="bct-label"><?php _e('Options','em-pro'); ?></div>
									<div class="bct-input">
										<textarea name="options_selection_values[]"><?php self::input_default('options_selection_values',$field_values,'textarea'); ?></textarea>
										<em><?php _e('Available options, one per line.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Tip Text','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_selection_tip[]" <?php self::input_default('options_selection_tip',$field_values); ?> />
										<em><?php _e('Will appear next to your field label as a question mark with a popup tip bubble.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Error Message','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_selection_error[]" <?php self::input_default('options_selection_error',$field_values); ?> />
										<em>
											<?php _e('This error will show if a value isn\'t chosen.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.sprintf(get_option('em_booking_form_error_required'), '[FIELD]').'</code>'; ?>
										</em>
									</div>
								</div>
								<?php do_action('emp_forms_editor_selection_radio_options', $this, $field_values); ?>
							</div>
							<div class="bct-checkbox bct-options" style="display:none;">
								<!-- checkbox -->
								<div class="bct-field">
									<div class="bct-label"><?php _e('Checked by default?','em-pro'); ?></div>
									<div class="bct-input">
										<input type="checkbox" <?php self::input_default('options_checkbox_checked',$field_values,'checkbox'); ?> value="1" />
										<input type="hidden" name="options_checkbox_checked[]" <?php self::input_default('options_checkbox_checked',$field_values); ?> /> 
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Tip Text','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_checkbox_tip[]" <?php self::input_default('options_checkbox_tip',$field_values); ?> />
										<em><?php _e('Will appear next to your field label as a question mark with a popup tip bubble.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Error Message','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_checkbox_error[]" <?php self::input_default('options_checkbox_error',$field_values); ?> />
										<em>
											<?php _e('This error will show if this box is not checked.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.sprintf(get_option('em_booking_form_error_required'), '[FIELD]').'</code>'; ?>
										</em>
									</div>
								</div>
								<?php do_action('emp_forms_editor_checkbox_options', $this, $field_values); ?>
							</div>
							<div class="bct-text bct-options" style="display:none;">
								<!-- text,textarea,email,name -->
								<div class="bct-field">
									<div class="bct-label"><?php _e('Tip Text','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_text_tip[]" <?php self::input_default('options_text_tip',$field_values); ?> />
										<em><?php _e('Will appear next to your field label as a question mark with a popup tip bubble.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Regex','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_text_regex[]" <?php self::input_default('options_text_regex',$field_values); ?> />
										<em><?php _e('By adding a regex expression, you can limit the possible values a user can input, for example the following only allows numbers: ','em-pro'); ?><code>^[0-9]+$</code></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Error Message','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_text_error[]" <?php self::input_default('options_text_error',$field_values); ?> />
										<em>
											<?php _e('If the regex above does not match this error will be displayed.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.sprintf(get_option('em_booking_form_error_required'), '[FIELD]').'</code>'; ?>
										</em>
									</div>
								</div>
								<?php do_action('emp_forms_editor_text_options', $this, $field_values); ?>
							</div>	
							<?php endif; ?>
							<?php if($user_fields): ?>
							<div class="bct-registration bct-options" style="display:none;">
								<!-- registration -->
								<div class="bct-field">
									<div class="bct-label"><?php _e('Tip Text','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_reg_tip[]" <?php self::input_default('options_reg_tip',$field_values); ?> />
										<em><?php _e('Will appear next to your field label as a question mark with a popup tip bubble.','em-pro'); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Regex','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_reg_regex[]" <?php self::input_default('options_reg_regex',$field_values); ?> />
										<em><?php _e('By adding a regex expression, you can limit the possible values a user can input, for example the following only allows numbers: ','em-pro'); ?><code>^[0-9]+$</code></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Error Message','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_reg_error[]" <?php self::input_default('options_reg_error',$field_values); ?> />
										<em>
											<?php _e('If the regex above does not match this error will be displayed.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.sprintf(get_option('em_booking_form_error_required'), '[FIELD]').'</code>'; ?>
										</em>
									</div>
								</div>
							</div>
							<?php endif; ?>
							<?php if($captcha_fields): ?>
							<div class="bct-captcha bct-options" style="display:none;">
								<!-- captcha -->
								<?php 
									$uri = parse_url(get_option('siteurl')); 
									$recaptcha_url = "https://www.google.com/recaptcha/admin/create?domains={$uri['host']}&amp;app=wordpress"; 
								?>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Public Key','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_captcha_key_pub[]" <?php self::input_default('options_captcha_key_pub',$field_values); ?> />
										<em><?php echo sprintf(__('Required, get your keys <a href="%s">here</a>','em-pro'),$recaptcha_url); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Private Key','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_captcha_key_priv[]" <?php self::input_default('options_captcha_key_priv',$field_values); ?> />
										<em><?php echo sprintf(__('Required, get your keys <a href="%s">here</a>','em-pro'),$recaptcha_url); ?></em>
									</div>
								</div>
								<div class="bct-field">
									<div class="bct-label"><?php _e('Error Message','em-pro'); ?></div>
									<div class="bct-input">
										<input type="text" name="options_captcha_error[]" <?php self::input_default('options_captcha_error',$field_values); ?> />
										<em>
											<?php _e('This error will show if the captcha is not correct.','em-pro'); ?>
											<br /><?php _e('Default:','em-pro'); echo ' <code>'.sprintf(get_option('em_booking_form_error_required'), '[FIELD]').'</code>'; ?>
										</em>
									</div>
								</div>
							</div>
							<?php endif; ?>
						</div>
						<br style="clear:both" />
					</li>
					<?php endforeach; ?>
				</ul>
			</div>
			<p>
				<input type="hidden" name="_wpnonce" value="<?php echo wp_create_nonce('form_fields'); ?>" />
				<input type="hidden" name="form_action" value="form_fields" />
				<input type="hidden" name="form_name" value="<?php echo $this->form_name; ?>" />
				<input type="button" value="<?php _e('Add booking field','em-pro'); ?>" class="booking-form-custom-field-add button-secondary">
				<input type="submit" name="events_update" value="<?php _e ( 'Save Form', 'em-pro' ); ?> &raquo;" class="button-primary" />
			</p>
		</form>	
		<script type="text/javascript">
			jQuery(document).ready( function($){
				$('.bct-options').hide();
				//Booking Form
				var booking_template = $('#<?php echo $form_name; ?> #booking-custom-item-template').detach();
				$('#<?php echo $form_name; ?>').delegate('.booking-form-custom-field-remove', 'click', function(e){
					e.preventDefault();
					$(this).parents('.booking-custom-item').remove();
				});
				$('#<?php echo $form_name; ?> .booking-form-custom-field-add').click(function(e){
					e.preventDefault();
					booking_template.clone().appendTo($(this).parents('.em-form-custom').find('ul.booking-custom-body').first());
				});
				$('#<?php echo $form_name; ?>').delegate('.booking-form-custom-field-options', 'click', function(e){
					e.preventDefault();
					if( $(this).attr('rel') != '1' ){
						$(this).parents('.em-form-custom').find('.booking-form-custom-field-options').text('<?php _e('options','em-pro'); ?>').attr('rel','0')
						$(this).parents('.booking-custom-item').find('.booking-form-custom-type').trigger('change');
					}else{
						$(this).text('<?php _e('options','em-pro'); ?>').parents('.booking-custom-item').find('.bct-options').slideUp();
						$(this).attr('rel','0');
					}
				});
				//specifics
				$('#<?php echo $form_name; ?>').delegate('.booking-form-custom-label', 'change', function(e){
					var parent_div =  $(this).parents('.booking-custom-item').first();
					var field_id = parent_div.find('input.booking-form-custom-fieldid').first();
					if( field_id.val() == '' ){
						field_id.val(escape($(this).val()).replace(/%[0-9]+/g,'_').toLowerCase());
					}
				});
				$('#<?php echo $form_name; ?>').delegate('input[type="checkbox"]', 'change', function(){
					var checkbox = $(this);
					if( checkbox.next().attr('type') == 'hidden' ){
						if( checkbox.is(':checked') ){
							checkbox.next().val(1);
						}else{
							checkbox.next().val(0);
						}
					}
				});
				$('#<?php echo $form_name; ?>').delegate('.booking-form-custom-type', 'change', function(){
					$('.bct-options').slideUp();
					var type_keys = {
						select : ['select','multiselect'],
						country : ['country'],
						date : ['date'],
						time : ['time'],
						html : ['html'],
						selection : ['checkboxes','radio'],
						checkbox : ['checkbox'],
						text : ['text','textarea','email'],
						registration : ['<?php echo implode("', '", array_keys($this->user_fields)); ?>'],
						captcha : ['captcha']							
					}
					var select_box = $(this);
					var selected_value = select_box.val();
					$.each(type_keys, function(option,types){
						if( $.inArray(selected_value,types) > -1 ){
							//get parent div
							parent_div =  select_box.parents('.booking-custom-item').first();
							//slide the right divs in/out
							parent_div.find('.bct-'+option).slideDown();
							parent_div.find('.booking-form-custom-field-options').text('<?php _e('hide options','em-pro'); ?>').attr('rel','1');
						}
					});
				});
				$('#<?php echo $form_name; ?>').delegate('.bc-link-up, #<?php echo $form_name; ?> .bc-link-down', 'click', function(e){
					e.preventDefault();
					item = $(this).parents('.booking-custom-item').first();
					if( $(this).hasClass('bc-link-up') ){
						if(item.prev().length > 0){
							item.prev().before(item);
						}
					}else{
						if( item.next().length > 0 ){
							item.next().after(item);
						}
					}
				});
				$('#<?php echo $form_name; ?>').delegate('.bc-col-sort', 'mousedown', function(){
					parent_div =  $(this).parents('.booking-custom-item').first();
					parent_div.find('.bct-options').hide();
					parent_div.find('.booking-form-custom-field-options').text('<?php _e('options','em-pro'); ?>').attr('rel','0');
				});
				$("#<?php echo $form_name; ?> .booking-custom-body" ).sortable({
					placeholder: "bc-highlight",
					handle:'.bc-col-sort'
				});
			});
		</script>
		<?php
	}
	
	function editor_save(){
		//Update Values
		return update_option('em_booking_form_fields',$this->form_fields);
	}
	
	function editor_get_post(){
		if( !empty($_REQUEST['form_action']) && $_REQUEST['form_action'] == 'form_fields' && wp_verify_nonce($_REQUEST['_wpnonce'], 'form_fields') ){
			//Booking form fields
			$fields_map = self::get_fields_map();
			//extract request info back into item lists, but first assign fieldids to new items
			foreach( $_REQUEST['fieldid'] as $fieldid_key => $fieldid ){
				if( $_REQUEST['type'][$fieldid_key] == 'name' ){ //name field
					$_REQUEST['fieldid'][$fieldid_key] = 'user_name';
				}elseif( array_key_exists($_REQUEST['type'][$fieldid_key], $this->user_fields) ){ //other fields
					$_REQUEST['fieldid'][$fieldid_key] = $_REQUEST['type'][$fieldid_key];
				}elseif( empty($fieldid) ){
					$_REQUEST['fieldid'][$fieldid_key] = sanitize_title($_REQUEST['label'][$fieldid_key]); //assign unique id
				}
			}
			//get field values
			$this->form_fields = array();
			foreach( $_REQUEST as $key => $value){
				global $allowedposttags;
				if( is_array($value) && in_array($key,$fields_map) ){
					foreach($value as $item_index => $item_value){
						if( !empty($_REQUEST['fieldid'][$item_index]) ){
							$item_value = preg_replace('/  +/', ' ', stripslashes(wp_kses($item_value, $allowedposttags)));
							$this->form_fields[$_REQUEST['fieldid'][$item_index]][$key] = $item_value;
						}
					}
				}
			}
			return true;
		}
		return false;
	}

}