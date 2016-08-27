<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS', PoP_ServerUtils::get_template_definition('layout-referencedby-appendtoscript-details'));
define ('GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS', PoP_ServerUtils::get_template_definition('layout-referencedbyempty-appendtoscript-details'));
define ('GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-referencedby-appendtoscript-simpleview'));
define ('GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW', PoP_ServerUtils::get_template_definition('layout-referencedbyempty-appendtoscript-simpleview'));
define ('GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW', PoP_ServerUtils::get_template_definition('layout-referencedby-appendtoscript-fullview'));
define ('GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW', PoP_ServerUtils::get_template_definition('layout-referencedbyempty-appendtoscript-fullview'));

class GD_Template_Processor_ReferencesFramesLayouts extends GD_Template_Processor_ReferencesScriptFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS,
			GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS,
			GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW,
			GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW,
			GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW,
		);
	}

	function do_append($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS:
			case GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW:

				return false;
		}
		
		return parent::do_append($template_ids);
	}

	function get_layout_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_DETAILS:
			case GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_DETAILS:

				return GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_DETAILS;

			case GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_SIMPLEVIEW:
			case GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_SIMPLEVIEW:

				return GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_SIMPLEVIEW;

			case GD_TEMPLATE_LAYOUT_REFERENCEDBY_APPENDTOSCRIPT_FULLVIEW:
			case GD_TEMPLATE_LAYOUT_REFERENCEDBYEMPTY_APPENDTOSCRIPT_FULLVIEW:

				return GD_TEMPLATE_SUBCOMPONENT_REFERENCEDBY_FULLVIEW;
		}
		
		return parent::get_layout_template($template_ids);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_ReferencesFramesLayouts();