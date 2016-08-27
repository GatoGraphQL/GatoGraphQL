<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUTPOST_AUTHORNAME', PoP_ServerUtils::get_template_definition('layoutpost-authorname'));

class GD_Template_Processor_PostAuthorNameLayouts extends GD_Template_Processor_PostAuthorNameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUTPOST_AUTHORNAME,
			// GD_TEMPLATE_LAYOUTPOST_AUTHORNAME_SUMMARY,
		);
	}

	// function get_url_field($template_id, $atts) {
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUTPOST_AUTHORNAME_SUMMARY:

	// 			return 'summary-tab-url';
	// 	}
		
	// 	return parent::get_url_field($template_id, $atts);
	// }

	// function get_link_target($template_id, $atts) {
	
	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_LAYOUTPOST_AUTHORNAME_SUMMARY:

	// 			return GD_URLPARAM_TARGET_QUICKVIEW;
	// 	}
		
	// 	return parent::get_link_target($template_id, $atts);
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostAuthorNameLayouts();