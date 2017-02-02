<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_TAG', PoP_ServerUtils::get_template_definition('layout-tag'));
define ('GD_TEMPLATE_LAYOUT_TAGH4', PoP_ServerUtils::get_template_definition('layout-tagh4'));

class GD_Template_Processor_TagLayouts extends GD_Template_Processor_TagLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_TAG,
			GD_TEMPLATE_LAYOUT_TAGH4,
		);
	}

	function get_html_tag($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_TAGH4:
				
				return 'h4';
		}
	
		return parent::get_html_tag($template_id, $atts);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TagLayouts();