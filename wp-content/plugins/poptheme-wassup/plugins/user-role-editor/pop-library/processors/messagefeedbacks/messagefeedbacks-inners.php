<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MEMBERS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-members'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_ORGANIZATIONS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-organizations'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_INDIVIDUALS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-individuals'));

class GD_URE_Template_Processor_CustomListMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MEMBERS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ORGANIZATIONS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_INDIVIDUALS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MEMBERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MEMBERS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ORGANIZATIONS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ORGANIZATIONS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_INDIVIDUALS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INDIVIDUALS,
		);

		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomListMessageFeedbackInners();