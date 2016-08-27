<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT', PoP_ServerUtils::get_template_definition('layout-opinionatedvotereferencedby-appendtoscript'));
define ('GD_TEMPLATE_LAYOUT_OPINIONATEDVOTEREFERENCEDBYEMPTY_APPENDTOSCRIPT', PoP_ServerUtils::get_template_definition('layout-opinionatedvotereferencedbyempty-appendtoscript'));

class GD_Template_Processor_OpinionatedVotedReferencesFramesLayouts extends GD_Template_Processor_OpinionatedVotedReferencesScriptFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT,
			GD_TEMPLATE_LAYOUT_OPINIONATEDVOTEREFERENCEDBYEMPTY_APPENDTOSCRIPT,
		);
	}

	function do_append($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_OPINIONATEDVOTEREFERENCEDBYEMPTY_APPENDTOSCRIPT:

				return false;
		}
		
		return parent::do_append($template_ids);
	}

	function get_layout_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_LAYOUT_OPINIONATEDVOTEREFERENCEDBY_APPENDTOSCRIPT:
			case GD_TEMPLATE_LAYOUT_OPINIONATEDVOTEREFERENCEDBYEMPTY_APPENDTOSCRIPT:

				return GD_TEMPLATE_SUBCOMPONENT_OPINIONATEDVOTEREFERENCEDBY;
		}
		
		return parent::get_layout_template($template_ids);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_OpinionatedVotedReferencesFramesLayouts();