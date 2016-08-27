<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_FARM_CREATE', PoP_ServerUtils::get_template_definition('messagefeedback-farm-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_FARM_UPDATE', PoP_ServerUtils::get_template_definition('messagefeedback-farm-update'));

class OP_Template_Processor_CreateUpdatePostFormMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_FARM_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_FARM_UPDATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_FARM_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARM_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_FARM_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARM_UPDATE,
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
new OP_Template_Processor_CreateUpdatePostFormMessageFeedbacks();