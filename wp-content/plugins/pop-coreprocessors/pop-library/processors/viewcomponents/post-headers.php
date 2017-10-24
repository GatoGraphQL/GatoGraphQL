<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_POST', PoP_ServerUtils::get_template_definition('viewcomponent-header-post-'));
define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_POST_URL', PoP_ServerUtils::get_template_definition('viewcomponent-header-post-url'));

class GD_Template_Processor_PostViewComponentHeaders extends GD_Template_Processor_PostViewComponentHeadersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_POST,
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_POST_URL,
		);
	}

	function header_show_url($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_POST_URL:

				return true;
		}

		return parent::header_show_url($template_id, $atts);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_POST_URL:

				$this->append_att($template_id, $atts, 'class', 'alert alert-warning alert-sm');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostViewComponentHeaders();