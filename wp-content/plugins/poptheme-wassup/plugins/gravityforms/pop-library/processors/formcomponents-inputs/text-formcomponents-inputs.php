<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Do not change the name of this input below!
define ('GD_GF_TEMPLATE_FORMCOMPONENT_FORMID', PoP_ServerUtils::get_template_definition('gform_submit', true));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_NAME', PoP_ServerUtils::get_template_definition('gf-field-name'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_EMAIL', PoP_ServerUtils::get_template_definition('gf-field-email'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME', PoP_ServerUtils::get_template_definition('gf-field-newslettername'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL', PoP_ServerUtils::get_template_definition('gf-field-newsletteremail'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE', PoP_ServerUtils::get_template_definition('gf-field-newsletteremailverificationcode'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL', PoP_ServerUtils::get_template_definition('gf-field-destinationemail'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_SUBJECT', PoP_ServerUtils::get_template_definition('gf-field-subject'));
define ('GD_GF_TEMPLATE_FORMCOMPONENT_PHONE', PoP_ServerUtils::get_template_definition('gf-field-phone'));

class GD_GF_Template_Processor_TextFormComponentInputs extends GD_Template_Processor_TextFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_GF_TEMPLATE_FORMCOMPONENT_FORMID,
			GD_GF_TEMPLATE_FORMCOMPONENT_NAME,
			GD_GF_TEMPLATE_FORMCOMPONENT_EMAIL,
			GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME,
			GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL,
			GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE,
			GD_GF_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL,
			GD_GF_TEMPLATE_FORMCOMPONENT_SUBJECT,
			GD_GF_TEMPLATE_FORMCOMPONENT_PHONE
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENT_NAME:
				
				return __('Your Name', 'poptheme-wassup');

			case GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME:

				return __('Your Name', 'poptheme-wassup');
			
			case GD_GF_TEMPLATE_FORMCOMPONENT_EMAIL:
			case GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL:
				
				return __('Your Email', 'poptheme-wassup');

			case GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE:

				return __('Verification code', 'poptheme-wassup');

			case GD_GF_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL:
				
				return __('To Email(s)', 'poptheme-wassup');

			case GD_GF_TEMPLATE_FORMCOMPONENT_SUBJECT:
				
				return  __('Subject', 'poptheme-wassup');
				
			case GD_GF_TEMPLATE_FORMCOMPONENT_PHONE:

				return __('Your Phone number', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function is_mandatory($template_id, $atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENT_NAME:
			case GD_GF_TEMPLATE_FORMCOMPONENT_EMAIL:
			case GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL:
			case GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE:
			case GD_GF_TEMPLATE_FORMCOMPONENT_DESTINATIONEMAIL:
				
				return true;
		}
		
		return parent::is_mandatory($template_id, $atts);
	}

	function is_hidden($template_id, $atts) {
	
		switch ($template_id) {
		
			case GD_GF_TEMPLATE_FORMCOMPONENT_FORMID:
			case GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE:
			
				return true;
		}
		
		return parent::is_hidden($template_id, $atts);
	}

	function get_value($template_id, $atts_or_nothing = array()) {
	
		// $atts_or_nothing: Needed to call get_value from GD_FilterComponent::function get_filterformcomponent_value() which doesn't have $atts
		$atts = $atts_or_nothing;
		
		// When submitting the form, if user is logged in, then use these values. 
		// Otherwise, use the values sent in the form
		$vars = GD_TemplateManager_Utils::get_vars();
		if (PoP_FormUtils::use_loggedinuser_data() && doing_post() && $vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {
				
			$current_user = $vars['global-state']['current-user']/*wp_get_current_user()*/;
			switch ($template_id) {

				case GD_GF_TEMPLATE_FORMCOMPONENT_NAME:

					return $current_user->display_name;

				case GD_GF_TEMPLATE_FORMCOMPONENT_EMAIL:

					return $current_user->user_email;
			}
		}
		
		return parent::get_value($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {
		
			case GD_GF_TEMPLATE_FORMCOMPONENT_NAME:
			case GD_GF_TEMPLATE_FORMCOMPONENT_EMAIL:

				$this->add_jsmethod($ret, 'addDomainClass');
				break;
		}
		
		return $ret;
	}
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {
		
			case GD_GF_TEMPLATE_FORMCOMPONENT_NAME:
			case GD_GF_TEMPLATE_FORMCOMPONENT_EMAIL:

				// For function addDomainClass
				$ret['prefix'] = 'visible-notloggedin-';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_GF_TEMPLATE_FORMCOMPONENT_SUBJECT:
			case GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTERNAME:
			case GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAIL:
			case GD_GF_TEMPLATE_FORMCOMPONENT_NEWSLETTEREMAILVERIFICATIONCODE:

				$this->add_att($template_id, $atts, 'pop-form-clear', true);
				break;

			case GD_GF_TEMPLATE_FORMCOMPONENT_NAME:
			case GD_GF_TEMPLATE_FORMCOMPONENT_EMAIL:
				
				$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');

				// If we don't use the loggedinuser-data, then show the inputs always
				if (!PoP_FormUtils::use_loggedinuser_data()) {
					$this->append_att($template_id, $atts, 'class', 'visible-always');
				}
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_GF_Template_Processor_TextFormComponentInputs();