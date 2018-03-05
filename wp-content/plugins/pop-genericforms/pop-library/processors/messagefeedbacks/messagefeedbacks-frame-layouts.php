<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CONTACTUS', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-contactus'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CONTACTUSER', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-contactuser'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SHAREBYEMAIL', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-sharebyemail'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_VOLUNTEER', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-volunteer'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FLAG', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-flag'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_NEWSLETTER', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-newsletter'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_NEWSLETTERUNSUBSCRIPTION', PoP_TemplateIDUtils::get_template_definition('layout-messagefeedbackframe-newsletterunsubscription'));

class GenericForms_Template_Processor_MessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CONTACTUS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CONTACTUSER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SHAREBYEMAIL,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_VOLUNTEER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FLAG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_NEWSLETTER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_NEWSLETTERUNSUBSCRIPTION,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CONTACTUS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CONTACTUSER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_CONTACTUSER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SHAREBYEMAIL => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_SHAREBYEMAIL,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_VOLUNTEER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_VOLUNTEER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FLAG => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FLAG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_NEWSLETTER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTER,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_NEWSLETTERUNSUBSCRIPTION => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_NEWSLETTERUNSUBSCRIPTION,
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
new GenericForms_Template_Processor_MessageFeedbackFrameLayouts();