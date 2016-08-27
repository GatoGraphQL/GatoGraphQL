<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_FARMCATEGORIES', PoP_ServerUtils::get_template_definition('formcomponent-farmcategories'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_FARMCATEGORIES', PoP_ServerUtils::get_template_definition('farmcategories', true));

class OP_Template_Processor_MultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_FARMCATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENT_FARMCATEGORIES,
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_FARMCATEGORIES:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_FARMCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_FARMCATEGORIES:
			
				return __('Categories', 'poptheme-wassup-organikprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_FARMCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_FARMCATEGORIES:
			
				return new GD_FormInput_FarmCategories($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_FARMCATEGORIES:
				
				$ret[] = array('key' => 'value', 'field' => 'farmcategories');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_MultiSelectFormComponentInputs();