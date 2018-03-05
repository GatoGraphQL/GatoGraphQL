<?php

class PoP_AutomatedEmails_Frontend_ResourceLoader_Hooks {

	function __construct() {

		add_filter('get_templateresources_include_type', array($this, 'get_templateresources_include_type'));
	}

	function get_templateresources_include_type($type) {

		// Making the include-type "header" will avoid types "body" or "body-inline" which are of no use in the email
		if ($this->is_automatedemail_page()) {

			return 'header';
		}

		return $type;
	}

	protected function is_automatedemail_page() {

		$vars = GD_TemplateManager_Utils::get_vars();
		if ($vars['global-state']['is-page']) {

			$page_id = $vars['global-state']['post']->ID;
			$automatedemail_pages = PoP_AutomatedEmails_Frontend_ResourceLoader_Utils::get_automatedemail_pages();
			if (in_array($page_id, $automatedemail_pages)) {

				return true;
			}
		}

		return false;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_AutomatedEmails_Frontend_ResourceLoader_Hooks();