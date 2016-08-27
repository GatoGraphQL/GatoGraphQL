<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARM_CREATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-farm-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARM_UPDATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-farm-update'));

class OP_Template_Processor_CreateUpdatePostFormMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARM_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARM_UPDATE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARM_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARM_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARM_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARM_UPDATE,
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
new OP_Template_Processor_CreateUpdatePostFormMessageFeedbackInners();