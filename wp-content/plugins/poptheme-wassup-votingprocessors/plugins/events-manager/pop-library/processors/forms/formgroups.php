<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP', PoP_ServerUtils::get_template_definition('votingprocessors-em-formcomponentgroup-locationsmap'));

class VotingProcessors_EM_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP,
		);
	}

	function get_component($template_id) {

		$components = array(
			VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP => GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}

	// function get_label($template_id, $atts) {

	// 	$ret = parent::get_label($template_id, $atts);

	// 	switch ($template_id) {

	// 		case VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP:
				
	// 			// Because we have made the Locations mandatory for the TPP Debate website, gotta add the "*"
	// 			$ret .= GD_CONSTANT_MANDATORY;
	// 			break;
	// 	}
		
	// 	return $ret;
	// }

	function get_info($template_id, $atts) {

		switch ($template_id) {

			case VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP:
				
				return __('If you can\'t find the location in the input below, click on the "+" button to add a new one.', 'poptheme-wassup-votingprocessors');
		}
		
		return parent::get_info($template_id, $atts);
	}

	function get_description($template_id, $atts) {

		switch ($template_id) {

			case VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP:
				
				return sprintf(
					'<p><em>%s</em></p>',
					__('Please select your location(s). You can give as much detail as you want: your full address, or your city, or if not just your country. We ask for this information to be able to identify opinion towards TPP by people from each country.', 'poptheme-wassup-votingprocessors')
				);
		}
		
		return parent::get_description($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		global $gd_template_processor_manager;

		switch ($template_id) {

			case VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP:
				
				$component = $this->get_component($template_id);
				// $locations_typeahead = GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_LOCATIONS;
				$locations_typeahead = $gd_template_processor_manager->get_processor($component)->get_locations_typeahead($component);

				$countries = array(
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_MALAYSIA,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_SINGAPORE,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_JAPAN,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_VIETNAM,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_BRUNEI,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_AUSTRALIA,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_NEWZEALAND,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_UNITEDSTATES,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_CANADA,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_MEXICO,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_CHILE,
					POPTHEME_WASSUP_VOTINGPROCESSORS_LOCATION_PERU,
				);
				$this->add_att($locations_typeahead, $atts, 'suggestions', $countries);
				$this->add_att($component, $atts, 'mandatory', true);

				// Make it mandatory

				break;
		}

		return parent::init_atts($template_id, $atts);
	}


}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new VotingProcessors_EM_Template_Processor_FormGroups();