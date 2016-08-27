<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-opinionatedvotes'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYOPINIONATEDVOTES', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-myopinionatedvotes'));

class VotingProcessors_Template_Processor_CustomListMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYOPINIONATEDVOTES,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_OPINIONATEDVOTES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_OPINIONATEDVOTES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYOPINIONATEDVOTES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYOPINIONATEDVOTES,
		);

		if ($layout = $layouts[$template_id]) {

			return $layout;
		}

		return parent::get_layout($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_Template_Processor_CustomListMessageFeedbackFrameLayouts();