<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS', PoP_ServerUtils::get_template_definition('formcomponent-buttongroup-webpostsections'));

define ('GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES', PoP_ServerUtils::get_template_definition('categories-bg', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS', PoP_ServerUtils::get_template_definition('sections-bg', true));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS', PoP_ServerUtils::get_template_definition('webpostsections-bg', true));

class GD_Template_Processor_CreateUpdatePostButtonGroupFormComponentInputs extends GD_Template_Processor_ButtonGroupFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS,
			GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES,
			GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS,
			GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS,
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:
			
				return __('Section', 'poptheme-wassup');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_name($template_id, $atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:

				// Return the name of the other Categories input (the multiselect), so we can filter by this input using the DelegatorFilter pretending to be the other one
				$inputs = array(
					GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES => GD_TEMPLATE_FILTERFORMCOMPONENT_CATEGORIES,
					GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS => GD_TEMPLATE_FILTERFORMCOMPONENT_SECTIONS,
					GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS => GD_TEMPLATE_FILTERFORMCOMPONENT_WEBPOSTSECTIONS,
				);
				$input = $inputs[$template_id];
				return $gd_template_processor_manager->get_processor($input)->get_name($input, $atts);
		}
		
		return parent::get_name($template_id, $atts);
	}

	function get_input($template_id, $atts) {

		$options = $this->get_input_options($template_id, $atts);

		// Set the inputs and labels
		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES:

				return new GD_FormInput_Categories($options);

			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS:

				return new GD_FormInput_Sections($options);

			case GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:

				return new GD_FormInput_WebPostSection($options);

			// case GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:

				return new GD_FormInput_WebPostSections($options);
		}
		
		return parent::get_input($template_id, $atts);
	}

	function is_multiple($template_id, $atts) {

		switch ($template_id) {

			// case GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_CATEGORIES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_SECTIONS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:

				return true;
		}
		
		return parent::is_multiple($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:
				
				// These are the post categories
				$ret[] = array('key' => 'value', 'field' => 'cats-string');
				break;
		}
		
		return $ret;
	}

	function get_compareby($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_BUTTONGROUP_WEBPOSTSECTIONS:
				
				// Even though it's multiple, it needs to do the 'in' comparison because it sends more than 1 value:
				// the WebPost category + the secondary category (Article, Announcement, etc)
				return 'in';
		}
		
		return parent::get_compareby($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CreateUpdatePostButtonGroupFormComponentInputs();