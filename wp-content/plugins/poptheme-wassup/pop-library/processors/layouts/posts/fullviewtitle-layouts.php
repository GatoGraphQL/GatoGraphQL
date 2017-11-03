<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FULLVIEWTITLE', PoP_TemplateIDUtils::get_template_definition('layout-fullviewtitle'));
define ('GD_TEMPLATE_LAYOUT_PREVIEWPOSTTITLE', PoP_TemplateIDUtils::get_template_definition('layout-previewposttitle'));
define ('GD_TEMPLATE_LAYOUT_POSTTITLE', PoP_TemplateIDUtils::get_template_definition('layout-posttitle'));

class GD_Template_Processor_CustomFullViewTitleLayouts extends GD_Template_Processor_FullViewTitleLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FULLVIEWTITLE,
			GD_TEMPLATE_LAYOUT_PREVIEWPOSTTITLE,
			GD_TEMPLATE_LAYOUT_POSTTITLE,
		);
	}

	function get_htmlmarkup($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_PREVIEWPOSTTITLE:
				
				return 'h4';

			case GD_TEMPLATE_LAYOUT_POSTTITLE:

				return 'span';
		}
		
		return parent::get_htmlmarkup($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomFullViewTitleLayouts();