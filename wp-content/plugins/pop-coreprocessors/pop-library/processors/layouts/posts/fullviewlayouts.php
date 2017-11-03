<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_FULLVIEW_STICKY', PoP_TemplateIDUtils::get_template_definition('layout-fullview-sticky'));

class GD_Template_Processor_StickyPostLayouts extends GD_Template_Processor_FullViewLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_FULLVIEW_STICKY
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_StickyPostLayouts();