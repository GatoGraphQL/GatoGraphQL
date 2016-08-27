<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class VotingProcessors_EM_CreateUpdate_Profile_Hooks {

	function __construct() {

		// Priority 100: execute after similar function in GD_EM_CreateUpdate_Profile_Hooks
		add_filter('gd_template:createprofile:components', array($this, 'get_components'), 100, 3);

		add_filter('gd_createupdate_profile:validatecontent', array($this, 'validatecontent'), 10, 2);
	}

	function validatecontent($errors, $form_data) {

		// Validate that the Location is mandatory
		if (!$form_data['locations']) {
			$errors[] = __('Your location is missing', 'poptheme-wassup-votingprocessors');
		}

		return $errors;
	}

	function get_components($components, $template_id, $processor) {

		// Replace GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP with VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP
		// This last one adds the suggestions for the TPP countries
		$replacements = array(
			VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP,
		);
		array_splice($components, array_search(GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP, $components), 1, $replacements);
		
		return $components;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
// Comment Leo 05/02/2016: No need to use VOTINGPROCESSORS_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP anymore
// new VotingProcessors_EM_CreateUpdate_Profile_Hooks();