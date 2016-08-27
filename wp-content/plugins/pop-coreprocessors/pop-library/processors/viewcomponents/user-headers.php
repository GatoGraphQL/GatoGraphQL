<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_USER', PoP_ServerUtils::get_template_definition('viewcomponent-header-user'));
define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_USER_URL', PoP_ServerUtils::get_template_definition('viewcomponent-header-user-url'));

class GD_Template_Processor_UserViewComponentHeaders extends GD_Template_Processor_UserViewComponentHeadersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_USER,
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_USER_URL,
		);
	}

	function header_show_url($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_USER_URL:

				// Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
				return true;
		}

		return parent::header_show_url($template_id, $atts);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_USER_URL:

				$this->append_att($template_id, $atts, 'class', 'alert alert-warning alert-sm');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserViewComponentHeaders();