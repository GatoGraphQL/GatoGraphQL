<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_CUU_DESCRIPTION', PoP_ServerUtils::get_template_definition('formcomponent-cuu-description'));

class GD_Template_Processor_CreateUpdateUserTextareaFormComponentInputs extends GD_Template_Processor_TextareaFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_CUU_DESCRIPTION
		);
	}

	function get_rows($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_CUU_DESCRIPTION:
				
				return 10;
		}

		return parent::get_rows($template_id, $atts);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUU_DESCRIPTION:
				
				return __('Description', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_CUU_DESCRIPTION:
				
				$ret[] = array('key' => 'value', 'field' => 'description');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdateUserTextareaFormComponentInputs();