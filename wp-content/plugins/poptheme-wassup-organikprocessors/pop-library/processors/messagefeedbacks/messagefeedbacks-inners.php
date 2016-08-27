<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARMS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-farms'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYFARMS', PoP_ServerUtils::get_template_definition('messagefeedbackinner-myfarms'));

class OP_Template_Processor_CustomListMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARMS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYFARMS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FARMS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FARMS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYFARMS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYFARMS,
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
new OP_Template_Processor_CustomListMessageFeedbackInners();