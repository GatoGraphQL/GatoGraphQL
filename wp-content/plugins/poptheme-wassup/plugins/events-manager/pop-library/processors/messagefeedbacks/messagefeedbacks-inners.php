<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENTS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-events'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_PASTEVENTS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-pastevents'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYEVENTS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-myevents'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYPASTEVENTS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-mypastevents'));

class GD_EM_Template_Processor_CustomListMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_PASTEVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYEVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYPASTEVENTS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_EVENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_PASTEVENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PASTEVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYEVENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYEVENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYPASTEVENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPASTEVENTS,
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
new GD_EM_Template_Processor_CustomListMessageFeedbackInners();