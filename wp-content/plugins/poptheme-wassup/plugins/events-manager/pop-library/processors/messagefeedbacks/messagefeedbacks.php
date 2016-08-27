<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_EVENTS', PoP_ServerUtils::get_template_definition('messagefeedback-events'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_PASTEVENTS', PoP_ServerUtils::get_template_definition('messagefeedback-pastevents'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYEVENTS', PoP_ServerUtils::get_template_definition('messagefeedback-myevents'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYPASTEVENTS', PoP_ServerUtils::get_template_definition('messagefeedback-mypastevents'));

class GD_EM_Template_Processor_CustomListMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_EVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_PASTEVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYEVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYPASTEVENTS,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_EVENTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_PASTEVENTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_PASTEVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYEVENTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYEVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYPASTEVENTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYPASTEVENTS,
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
new GD_EM_Template_Processor_CustomListMessageFeedbacks();