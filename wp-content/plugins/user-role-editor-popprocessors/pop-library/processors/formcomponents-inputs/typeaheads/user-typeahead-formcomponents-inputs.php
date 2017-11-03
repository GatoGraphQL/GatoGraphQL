<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// define ('GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES', 'formcomponent-selectabletypeahead-ure-profiles');
define ('GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES', PoP_TemplateIDUtils::get_template_definition('formcomponent-selectabletypeahead-ure-communities'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES', PoP_TemplateIDUtils::get_template_definition('users', true));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITYUSERS', PoP_TemplateIDUtils::get_template_definition('community-users', true));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST', PoP_TemplateIDUtils::get_template_definition('communities-post', true));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_USER', PoP_TemplateIDUtils::get_template_definition('communities-user', true));

class GD_URE_Template_Processor_UserSelectableTypeaheadFormComponentInputs extends GD_Template_Processor_UserSelectableTypeaheadFormComponentInputs {

	function get_templates_to_process() {
	
		return array(
			// GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES,
			GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_USER,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITYUSERS,
		);
	}

	function is_filtercomponent($template_id) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITYUSERS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_USER:

				return true;
		}
		
		return parent::is_filtercomponent($template_id);
	}

	function is_hidden($template_id, $atts) {
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST:
				
				// The communities is not visible. Instead, we use the Profiles component with a checkbox "Include members?"
				// which will trigger the typeahead for this one communities component
				return true;
		}
		
		return parent::is_hidden($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST:
				
				// The communities is not visible. Instead, we use the Profiles component with a checkbox "Include members?"
				// which will trigger the typeahead for this one communities component
				$this->add_att($template_id, $atts, 'hidden', true);
				break;

			// case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:

			// 	$this->merge_att(GD_TEMPLATE_LAYOUTUSER_FILTERTYPEAHEAD_SELECTED, $atts, 'extra-templates', array(
			// 		GD_URE_TEMPLATE_LAYOUTUSER_TYPEAHEAD_SELECTED_FILTERBYCOMMUNITY
			// 	));
			// 	break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_override_from_itemobject($template_id) {

		$ret = parent::get_override_from_itemobject($template_id);;
	
		switch ($template_id) {

			case GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
				
				$ret[] = array('key' => 'value', 'field' => 'communities');
				break;
		}
		
		return $ret;
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {
				
			// case GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
			
			// 	return __('Users', 'ure-popprocessors');
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_USER:

				return __('Member of which Organizations', 'ure-popprocessors');

			case GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:

				return __('Are you member of any organization(s)? Which ones?', 'ure-popprocessors');
		}
		
		return parent::get_label($template_id, $atts);
	}

	function get_input_template($template_id) {

		switch ($template_id) {
				
			case GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_USER:

				return GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADORGANIZATIONS;
				
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITYUSERS:
			
				return GD_TEMPLATE_FORMCOMPONENT_TEXT_TYPEAHEADPROFILES;
		}

		return parent::get_input_template($template_id);
	}

	function get_components($template_id) {

		switch ($template_id) {
				
			// case GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:
			
				return array(
					GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_ORGANIZATION,
					GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_INDIVIDUAL
				);

			case GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_USER:

				return array(
					GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY
				);

			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITYUSERS:

				return array(
					GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITYUSERS
				);
		}

		return parent::get_components($template_id);
	}

	function get_trigger_template($template_id) {

		switch ($template_id) {
				
			// case GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITYUSERS:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_SELECTABLETYPEAHEAD_PROFILES_TRIGGER;

			case GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_USER:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES_TRIGGER;

		}

		return parent::get_trigger_template($template_id);;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_UserSelectableTypeaheadFormComponentInputs();