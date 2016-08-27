<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYCOMMUNITIES', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-mycommunities'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INVITENEWMEMBERS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-invitemembers'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EDITMEMBERSHIP', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-editmembership'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYMEMBERS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-mymembers'));

class GD_URE_Template_Processor_ProfileMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYCOMMUNITIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INVITENEWMEMBERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EDITMEMBERSHIP,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYMEMBERS,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYCOMMUNITIES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYCOMMUNITIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INVITENEWMEMBERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_INVITENEWMEMBERS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_EDITMEMBERSHIP => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_EDITMEMBERSHIP,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYMEMBERS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYMEMBERS,
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
new GD_URE_Template_Processor_ProfileMessageFeedbackFrameLayouts();