<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_LOGIN', PoP_ServerUtils::get_template_definition('block-login'));
define ('GD_TEMPLATE_BLOCK_LOSTPWD', PoP_ServerUtils::get_template_definition('block-lostpwd'));
define ('GD_TEMPLATE_BLOCK_LOSTPWDRESET', PoP_ServerUtils::get_template_definition('block-lostpwdreset'));
define ('GD_TEMPLATE_BLOCK_LOGOUT', PoP_ServerUtils::get_template_definition('block-logout'));
define ('GD_TEMPLATE_BLOCK_USERLOGGEDINWELCOME', PoP_ServerUtils::get_template_definition('block-userloggedinwelcome'));

class GD_Template_Processor_LoginBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_LOGIN,
			GD_TEMPLATE_BLOCK_LOSTPWD,
			GD_TEMPLATE_BLOCK_LOSTPWDRESET,
			GD_TEMPLATE_BLOCK_LOGOUT,
			GD_TEMPLATE_BLOCK_USERLOGGEDINWELCOME,
		);
	}

	function get_submenu($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOGIN:
			case GD_TEMPLATE_BLOCK_LOSTPWD:
			case GD_TEMPLATE_BLOCK_LOSTPWDRESET:

				return GD_TEMPLATE_SUBMENU_ACCOUNT;
		}
		
		return parent::get_submenu($template_id);
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOGIN:
			case GD_TEMPLATE_BLOCK_LOSTPWD:
			case GD_TEMPLATE_BLOCK_LOSTPWDRESET:

				return GD_TEMPLATE_CONTROLGROUP_ACCOUNT;
		}
		
		return parent::get_controlgroup_top($template_id);
	}

	protected function get_messagefeedback($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOGIN:

				return GD_TEMPLATE_MESSAGEFEEDBACK_LOGIN;
			
			case GD_TEMPLATE_BLOCK_LOSTPWD:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_LOSTPWD;
			
			case GD_TEMPLATE_BLOCK_LOSTPWDRESET:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_LOSTPWDRESET;

			case GD_TEMPLATE_BLOCK_LOGOUT:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_LOGOUT;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOGIN:

				$ret[] = GD_TEMPLATE_FORM_LOGIN;
				$ret[] = GD_TEMPLATE_USERACCOUNT_USERLOGGEDINWELCOME;
				break;

			case GD_TEMPLATE_BLOCK_LOSTPWD:

				$ret[] = GD_TEMPLATE_FORM_LOSTPWD;
				break;

			case GD_TEMPLATE_BLOCK_LOSTPWDRESET:

				$ret[] = GD_TEMPLATE_FORM_LOSTPWDRESET;
				break;

			case GD_TEMPLATE_BLOCK_LOGOUT:

				$ret[] = GD_TEMPLATE_FORM_LOGOUT;
				break;

			case GD_TEMPLATE_BLOCK_USERLOGGEDINWELCOME:
		
				$ret[] = GD_TEMPLATE_USERACCOUNT_USERLOGGEDINWELCOME;
				break;
		}
	
		return $ret;
	}

	protected function get_description($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOGOUT:

				// Notice that it works for the domain from wherever this block is being fetched from!
				return sprintf(
					'<p class="visible-notloggedin-%s"><em>%s</em></p>',
					GD_TemplateManager_Utils::get_domain_id(get_site_url()),
					__('You are not logged in.', 'pop-coreprocessors')
				);
		}
	
		return parent::get_description($template_id, $atts);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCK_USERLOGGEDINWELCOME:

				$this->add_jsmethod($ret, 'addDomainClass');
				break;
		}
		
		return $ret;
	}
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_BLOCK_USERLOGGEDINWELCOME:

				// For function addDomainClass
				$ret['prefix'] = 'visible-loggedin-';
				break;
		}

		return $ret;
	}
	
	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOGIN:
			case GD_TEMPLATE_BLOCK_LOSTPWD:
			case GD_TEMPLATE_BLOCK_LOSTPWDRESET:
			case GD_TEMPLATE_BLOCK_LOGOUT:

				// Change the 'Loading' message in the Status
				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Sending...', 'pop-coreprocessors'));	
				break;
		
			case GD_TEMPLATE_BLOCK_USERLOGGEDINWELCOME:

				// Not visible if user not logged in
				// Notice that it works for the domain from wherever this block is being fetched from!
				// $this->append_att($template_id, $atts, 'class', 'visible-loggedin-'.GD_TemplateManager_Utils::get_domain_id(get_site_url()));
				$this->append_att($template_id, $atts, 'class', 'visible-loggedin');
				break;
		}

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_LOGIN:

				$this->append_att(GD_TEMPLATE_USERACCOUNT_USERLOGGEDINWELCOME, $atts, 'class', 'well');	
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LoginBlocks();