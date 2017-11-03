<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES', PoP_TemplateIDUtils::get_template_definition('formcomponent-selectabletypeahead-profiles'));
define ('GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS', PoP_TemplateIDUtils::get_template_definition('formcomponent-selectabletypeahead-postauthors'));
define ('GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS', PoP_TemplateIDUtils::get_template_definition('formcomponent-selectabletypeahead-postcoauthors'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES', PoP_TemplateIDUtils::get_template_definition('profiles', true));

class GD_Template_Processor_UserSelectableTypeaheadFormComponentInputs extends GD_Template_Processor_UserSelectableTypeaheadFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES,
			GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES,
			GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS,
			GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS,
		);
	}

	function get_selected_layout_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:

				return GD_TEMPLATE_LAYOUTUSER_FILTERTYPEAHEAD_SELECTED;
		}

		return parent::get_selected_layout_template($template_id);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:
			
				return __('Users', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:

				return __('Author(s)', 'pop-coreprocessors');

			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:

				return __('Co-authors', 'pop-coreprocessors');
		}
		
		return parent::get_label_text($template_id, $atts);
	}

	function get_input_template($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
				
				// return GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADPROFILES;
				return GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADPROFILES;

			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:

				// return GD_TEMPLATE_FORMCOMPONENT_INPUTGROUP_TYPEAHEADPOSTAUTHORS;
				return GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADPOSTAUTHORS;
		}

		return parent::get_input_template($template_id);
	}

	// function get_info($template_id, $atts) {

	// 	switch ($template_id) {
				
	// 		case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:

	// 			return __('Co-authoring this post with other Organizations/Individuals? Select them all here, they will not only appear as co-owners in the webpage, but will also be able to edit this post.', 'pop-coreprocessors');
	// 	}
		
	// 	return parent::get_info($template_id, $atts);
	// }

	function get_components($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:

				return array(
					// GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLPROFILES
					GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLUSERS
				);
		}

		return parent::get_components($template_id);
	}

	function get_trigger_template($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES_TRIGGER;

			case GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES_TRIGGER;
		}

		return parent::get_trigger_template($template_id);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS:
				
				$ret[] = array('key' => 'value', 'field' => 'authors');
				break;

			case GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS:
				
				$ret[] = array('key' => 'value', 'field' => 'coauthors');
				break;
		}
		
		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserSelectableTypeaheadFormComponentInputs();