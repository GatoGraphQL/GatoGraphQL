<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_INITIALIZEDOMAIN', PoP_ServerUtils::get_template_definition('messagefeedbackinner-initializedomain'));

class GD_Template_Processor_DomainMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_INITIALIZEDOMAIN,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_INITIALIZEDOMAIN => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_INITIALIZEDOMAIN,
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
new GD_Template_Processor_DomainMessageFeedbackInners();