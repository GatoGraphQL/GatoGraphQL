<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENT_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-event-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENT_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-event-update'));

class GD_EM_Template_Processor_CreateUpdatePostFormMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENT_UPDATE
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENT_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENT_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENT_UPDATE,
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
new GD_EM_Template_Processor_CreateUpdatePostFormMessageFeedbackInners();