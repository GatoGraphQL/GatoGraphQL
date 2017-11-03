<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINK_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-link-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINK_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-link-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHT_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-highlight-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHT_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-highlight-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOST_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-webpost-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOST_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-webpost-update'));

class Wassup_Template_Processor_CreateUpdatePostFormMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINK_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINK_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOST_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOST_UPDATE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINK_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINK_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LINK_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LINK_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHT_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_HIGHLIGHT_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_HIGHLIGHT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOST_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOST_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_WEBPOST_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_WEBPOST_UPDATE,
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
new Wassup_Template_Processor_CreateUpdatePostFormMessageFeedbackInners();