<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_LOGIN', PoP_TemplateIDUtils::get_template_definition('form-login'));
define ('GD_TEMPLATE_FORM_LOSTPWD', PoP_TemplateIDUtils::get_template_definition('form-lostpwd'));
define ('GD_TEMPLATE_FORM_LOSTPWDRESET', PoP_TemplateIDUtils::get_template_definition('form-lostpwdreset'));
define ('GD_TEMPLATE_FORM_LOGOUT', PoP_TemplateIDUtils::get_template_definition('form-logout'));

class GD_Template_Processor_LoginForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_LOGIN,
			GD_TEMPLATE_FORM_LOSTPWD,
			GD_TEMPLATE_FORM_LOSTPWDRESET,
			GD_TEMPLATE_FORM_LOGOUT
		);
	}

	function get_inner_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_LOGIN:

				return GD_TEMPLATE_FORMINNER_LOGIN;

			case GD_TEMPLATE_FORM_LOSTPWD:

				return GD_TEMPLATE_FORMINNER_LOSTPWD;

			case GD_TEMPLATE_FORM_LOSTPWDRESET:

				return GD_TEMPLATE_FORMINNER_LOSTPWDRESET;

			case GD_TEMPLATE_FORM_LOGOUT:

				return GD_TEMPLATE_FORMINNER_LOGOUT;
		}

		return parent::get_components($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_FORM_LOGIN:
			case GD_TEMPLATE_FORM_LOGOUT:

				$this->add_jsmethod($ret, 'addDomainClass');
				break;
		}
		
		return $ret;
	}
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_FORM_LOGIN:

				// For function addDomainClass
				$ret['prefix'] = 'visible-notloggedin-';
				break;
		
			case GD_TEMPLATE_FORM_LOGOUT:

				// For function addDomainClass
				$ret['prefix'] = 'visible-loggedin-';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_LOGIN:

				$description = sprintf(
					'<div class="pull-right"><p><em><a href="%s">%s</a></em></p></div>',
					get_permalink(POP_WPAPI_PAGE_LOSTPWD),
					get_the_title(POP_WPAPI_PAGE_LOSTPWD)
				);
				$this->add_att($template_id, $atts, 'description-bottom', $description);

				// Do not show if user already logged in
				$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');
				break;

			case GD_TEMPLATE_FORM_LOSTPWD:

				$description = sprintf(
					'<p class="bg-info text-info"><em>%s</em></p>',
					__('Please type in your account username or email, and you will receive a code in your email to reset your password.', 'pop-coreprocessors')
				);
				$description_bottom = sprintf(
					'<div class="pull-right"><p><em><a href="%s">%s</a></em></p></div>',
					get_permalink(POP_WPAPI_PAGE_LOGIN),
					get_the_title(POP_WPAPI_PAGE_LOGIN)
				);
				$this->add_att($template_id, $atts, 'description', $description);
				$this->add_att($template_id, $atts, 'description-bottom', $description_bottom);
				break;

			case GD_TEMPLATE_FORM_LOSTPWDRESET:

				$description = sprintf(
					'<p class="bg-info text-info"><strong>%s</strong> %s</p>',
					__('We sent you an email with a code.', 'pop-coreprocessors'),
					__('Please copy/paste it in the input below, and choose your new password.', 'pop-coreprocessors')
				);
				$this->add_att($template_id, $atts, 'description', $description);
				break;

			case GD_TEMPLATE_FORM_LOGOUT:

				// // Do not show if user already logged out
				// // Notice that it works for the domain from wherever this block is being fetched from!
				// $this->append_att($template_id, $atts, 'class', 'visible-loggedin-'.GD_TemplateManager_Utils::get_domain_id(get_site_url()));
				$this->append_att($template_id, $atts, 'class', 'visible-loggedin');

				// Add the description
				$description = sprintf(
					'<p><em>%s</em></p>',
					__('Are you sure you want to log out?', 'pop-coreprocessors')
				);
				$this->add_att($template_id, $atts, 'description', $description);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LoginForms();