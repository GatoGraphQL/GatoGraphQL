<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_CONTACTUS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-contactus'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_CONTACTUSER', PoP_ServerUtils::get_template_definition('messagefeedbackinner-contactuser'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_SHAREBYEMAIL', PoP_ServerUtils::get_template_definition('messagefeedbackinner-sharebyemail'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_VOLUNTEER', PoP_ServerUtils::get_template_definition('messagefeedbackinner-volunteer'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FLAG', PoP_ServerUtils::get_template_definition('messagefeedbackinner-flag'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_NEWSLETTER', PoP_ServerUtils::get_template_definition('messagefeedbackinner-newsletter'));

class GD_GF_Template_Processor_MessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_CONTACTUS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_CONTACTUSER,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_SHAREBYEMAIL,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_VOLUNTEER,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FLAG,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_NEWSLETTER,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_CONTACTUS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CONTACTUS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_CONTACTUSER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_CONTACTUSER,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_SHAREBYEMAIL => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_SHAREBYEMAIL,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_VOLUNTEER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_VOLUNTEER,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FLAG => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FLAG,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_NEWSLETTER => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_NEWSLETTER,
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
new GD_GF_Template_Processor_MessageFeedbackInners();