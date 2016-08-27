<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT', PoP_ServerUtils::get_template_definition('layout-highlightreferencedby-appendtoscript'));
define ('GD_TEMPLATE_LAYOUT_HIGHLIGHTREFERENCEDBYEMPTY_APPENDTOSCRIPT', PoP_ServerUtils::get_template_definition('layout-highlightreferencedbyempty-appendtoscript'));

class GD_Template_Processor_HighlightReferencesFramesLayouts extends GD_Template_Processor_HighlightReferencesScriptFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT,
			GD_TEMPLATE_LAYOUT_HIGHLIGHTREFERENCEDBYEMPTY_APPENDTOSCRIPT,
		);
	}

	function do_append($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_HIGHLIGHTREFERENCEDBYEMPTY_APPENDTOSCRIPT:

				return false;
		}
		
		return parent::do_append($template_ids);
	}

	function get_layout_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_HIGHLIGHTREFERENCEDBY_APPENDTOSCRIPT:
			case GD_TEMPLATE_LAYOUT_HIGHLIGHTREFERENCEDBYEMPTY_APPENDTOSCRIPT:

				return GD_TEMPLATE_SUBCOMPONENT_HIGHLIGHTREFERENCEDBY;
		}
		
		return parent::get_layout_template($template_ids);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_HighlightReferencesFramesLayouts();