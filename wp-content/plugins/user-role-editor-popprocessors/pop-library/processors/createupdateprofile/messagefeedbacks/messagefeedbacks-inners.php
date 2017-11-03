<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYCOMMUNITIES', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-mycommunities'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_INVITENEWMEMBERS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-invitemembers'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_EDITMEMBERSHIP', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-editmembership'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYMEMBERS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-mymembers'));

class GD_URE_Template_Processor_ProfileMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYCOMMUNITIES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_INVITENEWMEMBERS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_EDITMEMBERSHIP,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYMEMBERS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYCOMMUNITIES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYCOMMUNITIES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_INVITENEWMEMBERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INVITENEWMEMBERS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_EDITMEMBERSHIP => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EDITMEMBERSHIP,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYMEMBERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYMEMBERS,
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
new GD_URE_Template_Processor_ProfileMessageFeedbackInners();