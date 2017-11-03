<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_LOCATIONPOSTCATEGORIES', PoP_TemplateIDUtils::get_template_definition('formcomponent-locationpostcategories'));
define ('GD_TEMPLATE_FORMCOMPONENT_DISCUSSIONCATEGORIES', PoP_TemplateIDUtils::get_template_definition('formcomponent-discussioncategories'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_LOCATIONPOSTCATEGORIES', PoP_TemplateIDUtils::get_template_definition('locationpostcategories', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_DISCUSSIONCATEGORIES', PoP_TemplateIDUtils::get_template_definition('discussioncategories', true));

class GD_Custom_Template_Processor_MultiSelectFormComponentInputs extends GD_Template_Processor_MultiSelectFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_LOCATIONPOSTCATEGORIES,
			GD_TEMPLATE_FORMCOMPONENT_DISCUSSIONCATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENT_LOCATIONPOSTCATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENT_DISCUSSIONCATEGORIES,
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_LOCATIONPOSTCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_DISCUSSIONCATEGORIES:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_LOCATIONPOSTCATEGORIES:
			case GD_TEMPLATE_FORMCOMPONENT_DISCUSSIONCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_LOCATIONPOSTCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_DISCUSSIONCATEGORIES:
			
				return __('Categories', 'poptheme-wassup-sectionprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENT_LOCATIONPOSTCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_LOCATIONPOSTCATEGORIES:
			
				return new GD_FormInput_LocationPostCategories($options);
				
			case GD_TEMPLATE_FORMCOMPONENT_DISCUSSIONCATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_DISCUSSIONCATEGORIES:
			
				return new GD_FormInput_DiscussionCategories($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_LOCATIONPOSTCATEGORIES:
				
				$ret[] = array('key' => 'value', 'field' => 'locationpostcategories');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_DISCUSSIONCATEGORIES:
				
				$ret[] = array('key' => 'value', 'field' => 'discussioncategories');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_MultiSelectFormComponentInputs();