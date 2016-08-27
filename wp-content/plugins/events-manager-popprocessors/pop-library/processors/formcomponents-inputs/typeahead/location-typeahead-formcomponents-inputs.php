<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS', PoP_ServerUtils::get_template_definition('formcomponent-selectabletypeahead-locations'));

class GD_Template_Processor_LocationSelectableTypeaheadFormComponentInputs extends GD_Template_Processor_LocationSelectableTypeaheadFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS,
		);
	}

	function get_components($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:

				return array(
					GD_TEMPLATE_TYPEAHEAD_COMPONENT_LOCATIONS
				);
		}

		return parent::get_components($template_id);
	}
	function get_trigger_template($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS_TRIGGER;
		}

		return parent::get_trigger_template($template_id);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS:
				
				$ret[] = array('key' => 'value', 'field' => 'locations');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationSelectableTypeaheadFormComponentInputs();