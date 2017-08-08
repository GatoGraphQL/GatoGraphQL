<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_INITIALIZEDOMAIN', PoP_ServerUtils::get_template_definition('messagefeedback-initializedomain'));

class GD_Template_Processor_DomainMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_INITIALIZEDOMAIN,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_INITIALIZEDOMAIN => GD_TEMPLATE_MESSAGEFEEDBACKINNER_INITIALIZEDOMAIN,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_DomainMessageFeedbacks();