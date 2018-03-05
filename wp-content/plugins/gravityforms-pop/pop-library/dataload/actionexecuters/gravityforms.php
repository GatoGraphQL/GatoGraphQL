<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_GF_DATALOAD_ACTIONEXECUTER_GRAVITYFORMS', 'gravityforms');

class GD_DataLoad_ActionExecuter_GravityForms extends GD_DataLoad_FormActionExecuterBase {

	function __construct() {
    
		parent::__construct();

		// Execute before add_action('wp',  array('RGForms', 'maybe_process_form'), 9);
		if (doing_post()) {
			
			add_action('wp',  array($this, 'setup'), 5);
			add_action('wp',  array($this, 'maybe_fill_fields'), 7);
			add_action('wp',  array($this, 'maybe_process_form'), 8);
		}
	}

    function get_name() {
    
		return GD_GF_DATALOAD_ACTIONEXECUTER_GRAVITYFORMS;
	}

 //    function execute(&$block_data_settings, $block_atts, &$block_execution_bag) {

	// 	// If the post has been submitted, execute the Gravity Forms shortcode
	// 	if ('POST' == $_SERVER['REQUEST_METHOD']) {

	// 		global $gd_template_processor_manager;

	// 		// Before submitting the form, validate the captcha (otherwise, the form is submitted independently of the result of this validation)
	// 		$captcha_validation = $this->validate_captcha($block_data_settings, $block_atts);
	// 		if (is_wp_error($captcha_validation)) {
				
	// 			return sprintf('{{gd:es:%s}}', $captcha_validation->get_error_message());
	// 		}

	// 		$formid_processor = $gd_template_processor_manager->get_processor(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID);
	// 		$form_id = $formid_processor->get_value(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $block_atts);

	// 		return do_shortcode('[gravityform id="'.$form_id.'" title="false" description="false" ajax="false"]');
	// 	}

	// 	return parent::execute($block_data_settings, $block_atts, $block_execution_bag);
	// }

    protected function execute_form(&$block_data_settings, $block_atts, &$block_execution_bag) {

		global $gd_template_processor_manager;

		$formid_processor = $gd_template_processor_manager->get_processor(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID);
		$form_id = $formid_processor->get_value(GD_GF_TEMPLATE_FORMCOMPONENT_FORMID, $block_atts);

		return do_shortcode('[gravityform id="'.$form_id.'" title="false" description="false" ajax="false"]');
	}

	protected function get_captcha_error($captcha_error) {

		return sprintf(
			'{{gd:es:%s}}', 
			$captcha_error->get_error_message()
		);
	}

	function setup() {

		// Since GF 1.9.44, they setup field $_POST[ 'is_submit_' . $form['id'] ] )
		// (in file plugins/gravityforms/form_display.php function validate)
		// So here re-create that field
		$form_id = isset($_POST["gform_submit"]) ? $_POST["gform_submit"] : 0;
		if ($form_id){

			$_POST['is_submit_'.$form_id] = true;
		}
	}

	function maybe_fill_fields() {

		// Pre-populate values when the user is logged in
		// These are needed since implementing PoP where the user is always logged in, so we can't print the name/email
		// on the front-end anymore, instead fields GD_TEMPLATE_FORMCOMPONENT_NAME and GD_TEMPLATE_FORMCOMPONENT_EMAIL are
		// not visible when the user is logged in
		$vars = GD_TemplateManager_Utils::get_vars();
		if (PoP_FormUtils::use_loggedinuser_data() && $vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {
			
			$form_id = isset($_POST["gform_submit"]) ? $_POST["gform_submit"] : 0;
	        if ($form_id) {

	        	// Hook the fieldnames from the configuration
	        	if ($fieldnames = apply_filters(
	        		'GD_DataLoad_ActionExecuter_GravityForms:fieldnames',
	        		array(),
	        		$form_id
	        	)) {

					$current_user = $vars['global-state']['current-user']/*wp_get_current_user()*/;
					
					if (isset($_POST[$fieldnames['name']])) {
						$_POST[$fieldnames['name']] = $current_user->display_name;
					}

					if (isset($_POST[$fieldnames['email']])) {
						$_POST[$fieldnames['email']] = $current_user->user_email;
					}
				}
	        }    
       }    
	}

	function maybe_process_form() {

		// This is a workaround to validate the form which takes place in advance based on if the captcha is present or not
		// this is done now because GF sends the email at the beginning, this can't be postponed
		// Check only if the user is not logged in. When logged in, we never use the captcha
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!(PoP_FormUtils::use_loggedinuser_data() && $vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/)) {
			
			$form_id = isset($_POST["gform_submit"]) ? $_POST["gform_submit"] : 0;
	        if($form_id){
	            
	        	// Check if there's a captcha sent along
	            global $gd_template_processor_manager;
				$captcha = $gd_template_processor_manager->get_processor(GD_TEMPLATE_FORMCOMPONENT_CAPTCHA)->get_value(GD_TEMPLATE_FORMCOMPONENT_CAPTCHA, $atts);
				if ($captcha) {

					// Validate the captcha. If it fails, remove the attr "gform_submit" from $_POST
					$captcha_validation = GD_Captcha::validate($captcha['input'], $captcha['session']);
					if (is_wp_error($captcha_validation)) {
						
						// By unsetting this value in the $_POST, the email won't be processed by function RGForms::maybe_process_form
						unset($_POST["gform_submit"]);
					}
				}
	        }    
       }    
	}
}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_ActionExecuter_GravityForms();