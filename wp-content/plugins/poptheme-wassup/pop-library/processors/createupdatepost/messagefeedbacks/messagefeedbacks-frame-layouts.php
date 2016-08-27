<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINK_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-link-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINK_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-link-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHT_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-highlight-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHT_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-highlight-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOST_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-webpost-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOST_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-webpost-update'));

class Wassup_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINK_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINK_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOST_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOST_UPDATE,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINK_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINK_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_LINK_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHT_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHT_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOST_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOST_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_WEBPOST_UPDATE,
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
new Wassup_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts();