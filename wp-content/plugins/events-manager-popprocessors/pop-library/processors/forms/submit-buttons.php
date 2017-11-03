<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_SUBMITBUTTON_ADDLOCATION', PoP_TemplateIDUtils::get_template_definition('em-submitbutton-addlocation'));

class GD_EM_Template_Processor_SubmitButtons extends GD_Template_Processor_SubmitButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_SUBMITBUTTON_ADDLOCATION,
		);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_EM_TEMPLATE_SUBMITBUTTON_ADDLOCATION:

				return __('Add Location', 'em-popprocessors');
		}

		return parent::get_label($template_id, $atts);
	}
	
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_SubmitButtons();