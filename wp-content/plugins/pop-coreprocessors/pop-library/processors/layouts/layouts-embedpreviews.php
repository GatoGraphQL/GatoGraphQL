<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Embed
define ('GD_TEMPLATE_LAYOUT_EMBEDPREVIEW', PoP_ServerUtils::get_template_definition('layout-urlembedpreview'));
define ('GD_TEMPLATE_LAYOUT_USERINPUTEMBEDPREVIEW', PoP_ServerUtils::get_template_definition('layout-userinputembedpreview'));

class GD_Template_Processor_EmbedPreviewLayouts extends GD_Template_Processor_EmbedPreviewLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_EMBEDPREVIEW,
			GD_TEMPLATE_LAYOUT_USERINPUTEMBEDPREVIEW,
		);
	}
	function get_frame_src($template_id, $atts) {

		switch ($template_id) {
			
			case GD_TEMPLATE_LAYOUT_USERINPUTEMBEDPREVIEW:
				
				return apply_filters('GD_Template_Processor_EmbedPreviewLayouts:get_frame_src', '');
		}

		return parent::get_frame_src($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_EmbedPreviewLayouts();