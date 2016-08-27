<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYCOMMUNITIES', PoP_ServerUtils::get_template_definition('messagefeedback-mycommunities'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_INVITENEWMEMBERS', PoP_ServerUtils::get_template_definition('messagefeedback-invitemembers'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_EDITMEMBERSHIP', PoP_ServerUtils::get_template_definition('messagefeedback-editmembership'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYMEMBERS', PoP_ServerUtils::get_template_definition('messagefeedback-mymembers'));

class GD_URE_Template_Processor_ProfileMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_MYCOMMUNITIES,
			GD_TEMPLATE_MESSAGEFEEDBACK_INVITENEWMEMBERS,
			GD_TEMPLATE_MESSAGEFEEDBACK_EDITMEMBERSHIP,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYMEMBERS,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_MYCOMMUNITIES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYCOMMUNITIES,
			GD_TEMPLATE_MESSAGEFEEDBACK_INVITENEWMEMBERS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_INVITENEWMEMBERS,
			GD_TEMPLATE_MESSAGEFEEDBACK_EDITMEMBERSHIP => GD_TEMPLATE_MESSAGEFEEDBACKINNER_EDITMEMBERSHIP,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYMEMBERS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYMEMBERS,
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
new GD_URE_Template_Processor_ProfileMessageFeedbacks();