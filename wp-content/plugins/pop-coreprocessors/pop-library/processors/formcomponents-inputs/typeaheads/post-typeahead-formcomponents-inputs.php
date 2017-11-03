<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Do not change the name of these templates! They are used in the front-end (pre-select related posts/filter), so make them look nice
define ('GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES', PoP_TemplateIDUtils::get_template_definition('related', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_REFERENCES', PoP_TemplateIDUtils::get_template_definition('references', true));

class GD_Template_Processor_PostSelectableTypeaheadFormComponentInputs extends GD_Template_Processor_PostSelectableTypeaheadFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES,
			GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_REFERENCES
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_REFERENCES:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:

				return __('Posted in response / as an addition to', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input_template($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_REFERENCES:
			
				return GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADRELATEDCONTENT;
		}

		return parent::get_input_template($template_id);
	}

	function get_components($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_REFERENCES:

				return array(
					GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLCONTENT,
				);
		}

		return parent::get_components($template_id);
	}
	
	function get_trigger_template($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_REFERENCES:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES_TRIGGER;
		}

		return parent::get_trigger_template($template_id);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES:
				
				$ret[] = array('key' => 'value', 'field' => 'references');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostSelectableTypeaheadFormComponentInputs();