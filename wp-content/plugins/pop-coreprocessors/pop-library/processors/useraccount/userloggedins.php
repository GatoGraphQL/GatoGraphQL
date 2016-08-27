<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_USERACCOUNT_USERLOGGEDINWELCOME', PoP_ServerUtils::get_template_definition('useraccount-userloggedinwelcome'));
define ('GD_TEMPLATE_USERACCOUNT_USERLOGGEDINPROMPT', PoP_ServerUtils::get_template_definition('useraccount-userloggedinprompt'));

class GD_Template_Processor_UserLoggedIns extends GD_Template_Processor_UserLoggedInsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_USERACCOUNT_USERLOGGEDINWELCOME,
			GD_TEMPLATE_USERACCOUNT_USERLOGGEDINPROMPT,
		);
	}

	function get_title_top($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_USERACCOUNT_USERLOGGEDINWELCOME:

				return __('Welcome', 'pop-coreprocessors');

			case GD_TEMPLATE_USERACCOUNT_USERLOGGEDINPROMPT:

				return __('You are already logged in as', 'pop-coreprocessors');
		}
	
		return parent::get_title_top($template_id, $atts);
	}

	function get_title_bottom($template_id, $atts) {
	
		switch ($template_id) {

			case GD_TEMPLATE_USERACCOUNT_USERLOGGEDINPROMPT:

				return sprintf(
					'<p><a href="%s">%s</a></p>',
					wp_logout_url(),
					__('Logout?', 'pop-coreprocessors')
				);
		}
	
		return parent::get_title_bottom($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserLoggedIns();