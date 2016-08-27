<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_FARMS', PoP_ServerUtils::get_template_definition('messagefeedback-farms'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYFARMS', PoP_ServerUtils::get_template_definition('messagefeedback-myfarms'));

class OP_Template_Processor_CustomListMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_FARMS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYFARMS,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_FARMS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARMS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYFARMS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYFARMS,
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
new OP_Template_Processor_CustomListMessageFeedbacks();