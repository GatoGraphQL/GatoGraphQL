<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_FORMCOMPONENT_INDIVIDUALINTERESTS', PoP_ServerUtils::get_template_definition('formcomponent-individualinterests'));
define ('GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONCATEGORIES', PoP_ServerUtils::get_template_definition('formcomponent-organizationcategories'));
define ('GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONTYPES', PoP_ServerUtils::get_template_definition('formcomponent-organizationtypes'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_INDIVIDUALINTERESTS', PoP_ServerUtils::get_template_definition('individualinterests', true));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONCATEGORIES', PoP_ServerUtils::get_template_definition('organizationcategories', true));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONTYPES', PoP_ServerUtils::get_template_definition('organizationtypes', true));

class GD_URE_Template_Processor_MultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_FORMCOMPONENT_INDIVIDUALINTERESTS,
			GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONCATEGORIES,
			GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONTYPES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_INDIVIDUALINTERESTS,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONCATEGORIES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONTYPES
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_INDIVIDUALINTERESTS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONCATEGORIES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONTYPES:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_INDIVIDUALINTERESTS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_INDIVIDUALINTERESTS:

				return __('Interests', 'poptheme-wassup');
				
			case GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONCATEGORIES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONCATEGORIES:
			
				// Allow AgendaUrbana to Override
				return apply_filters(
					'GD_URE_Template_Processor_MultiSelectFormComponentInputs:label:categories',
					__('Organization Categories', 'poptheme-wassup')
				);
				
			case GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONTYPES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONTYPES:

				// Allow AgendaUrbana to Override
				return apply_filters(
					'GD_URE_Template_Processor_MultiSelectFormComponentInputs:label:types',
					__('Organization Types', 'poptheme-wassup')
				);
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_INDIVIDUALINTERESTS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_INDIVIDUALINTERESTS:
			
				return new GD_FormInput_IndividualInterests($options);
				
			case GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONCATEGORIES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONCATEGORIES:
			
				return new GD_FormInput_OrganizationCategories($options);
				
			case GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONTYPES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONTYPES:

				return new GD_FormInput_OrganizationTypes($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONTYPES:
				
				$ret[] = array('key' => 'value', 'field' => 'organizationtypes');
				break;

			case GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONCATEGORIES:
				
				$ret[] = array('key' => 'value', 'field' => 'organizationcategories');
				break;

			case GD_URE_TEMPLATE_FORMCOMPONENT_INDIVIDUALINTERESTS:
				
				$ret[] = array('key' => 'value', 'field' => 'individualinterests');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_MultiSelectFormComponentInputs();