<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_CREATEPROFILE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-createprofile'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_UPDATEPROFILE', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-updateprofile'));

class GD_Template_Processor_ProfileMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_CREATEPROFILE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UPDATEPROFILE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_CREATEPROFILE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CREATEPROFILE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_UPDATEPROFILE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_UPDATEPROFILE,
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
new GD_Template_Processor_ProfileMessageFeedbackInners();