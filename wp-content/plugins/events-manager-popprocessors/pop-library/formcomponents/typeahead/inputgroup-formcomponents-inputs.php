<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION', PoP_TemplateIDUtils::get_template_definition('formcomponent-inputgroup-typeaheadaddlocation'));

class GD_EM_Template_Processor_InputGroupFormComponentInputs extends GD_Template_Processor_InputGroupFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION,
		);
	}

	function get_input_template($template_id) {

		$ret = parent::get_input_template($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION:
				return GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADADDLOCATION;
		}

		return $ret;
	}

	function get_controls($template_id) {

		$ret = parent::get_controls($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADADDLOCATION:
		
				// $ret[] = GD_TEMPLATE_BUTTONCONTROL_TYPEAHEADMAPMARKER;
				$ret[] = GD_TEMPLATE_ANCHORCONTROL_CREATELOCATION;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_InputGroupFormComponentInputs();