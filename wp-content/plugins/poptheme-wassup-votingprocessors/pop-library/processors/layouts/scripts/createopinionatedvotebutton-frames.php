<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT', PoP_ServerUtils::get_template_definition('postbutton-opinionatedvote-createorupdate-appendtoscript'));

class GD_Template_Processor_CreateOrUpdateOpinionatedVotedButtonScriptFrameLayouts extends GD_Template_Processor_CreateOrUpdateOpinionatedVotedButtonScriptFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT,
		);
	}

	function get_layout_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT:

				return GD_TEMPLATE_BUTTONWRAPPER_OPINIONATEDVOTE_CREATEORUPDATE;
		}
		
		return parent::get_layout_template($template_ids);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BUTTON_OPINIONATEDVOTE_CREATEORUPDATE_APPENDTOSCRIPT:

				$this->append_att($template_id, $atts, 'class', 'inline');
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateOrUpdateOpinionatedVotedButtonScriptFrameLayouts();