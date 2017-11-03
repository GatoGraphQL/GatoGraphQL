<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_TAG', PoP_TemplateIDUtils::get_template_definition('viewcomponent-header-tag'));
define ('GD_TEMPLATE_VIEWCOMPONENT_HEADER_TAG_URL', PoP_TemplateIDUtils::get_template_definition('viewcomponent-header-tag-url'));

class GD_Template_Processor_TagViewComponentHeaders extends GD_Template_Processor_TagViewComponentHeadersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_TAG,
			GD_TEMPLATE_VIEWCOMPONENT_HEADER_TAG_URL,
		);
	}

	function header_show_url($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_TAG_URL:

				// Add the URL in the header? Sometimes yes (eg: Addon) sometimes not (eg: modal)
				return true;
		}

		return parent::header_show_url($template_id, $atts);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_VIEWCOMPONENT_HEADER_TAG_URL:

				$this->append_att($template_id, $atts, 'class', 'alert alert-warning alert-sm');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TagViewComponentHeaders();