<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH', PoP_TemplateIDUtils::get_template_definition('formcomponent-inputgroup-typeaheadsearch'));

class GD_Template_Processor_InputGroupFormComponentInputs extends GD_Template_Processor_InputGroupFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH,
		);
	}

	function get_input_template($template_id) {

		$ret = parent::get_input_template($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH:
				return GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADSEARCH;
		}

		return $ret;
	}

	function get_controls($template_id) {

		$ret = parent::get_controls($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADSEARCH:
		
				$ret[] = GD_TEMPLATE_BUTTONCONTROL_TYPEAHEADSEARCH;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_InputGroupFormComponentInputs();