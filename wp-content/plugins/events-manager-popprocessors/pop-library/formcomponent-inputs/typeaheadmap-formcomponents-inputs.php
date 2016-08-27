<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP', PoP_ServerUtils::get_template_definition('formcomponent-locationsmap'));

class GD_Template_Processor_TypeaheadMapFormComponentInputs extends GD_Template_Processor_TypeaheadMapFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP
		);
	}

	function get_locations_typeahead($template_id) {
	
		return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS;
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP:

				return __('Location(s)', 'em-popprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP:
				
				$ret[] = array('key' => 'value', 'field' => 'locations');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_TypeaheadMapFormComponentInputs();