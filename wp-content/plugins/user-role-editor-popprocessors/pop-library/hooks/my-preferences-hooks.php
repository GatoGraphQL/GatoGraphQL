<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * User Role Editor plugin Functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_URE_Hooks_MyPreferences {

	function __construct() {
		
		add_filter(
			'GD_UpdateMyPreferences:inputs-metakeys',
			array($this, 'get_inputs_metakeys')
		);

		add_filter(
			'GD_Template_Processor_UserMultipleComponents:emailnotifications:network:modules',
			array($this, 'get_formcomponentgroups')
		);

		add_filter(
			'GD_Template_Processor_UserFormInners:mypreferences:inputs',
			array($this, 'get_formcomponentinputs')
		);
	}

	function get_formcomponentinputs($modules) {

		// Add "Joins communities"
		$modules[] = GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY;
		return $modules;
	}

	function get_formcomponentgroups($modules) {

		// Add "Joins communities" at the end of the group
		$modules[] = GD_URE_TEMPLATE_FORMCOMPONENTGROUP_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY;
		return $modules;
	}

	function get_inputs_metakeys($inputs_metakeys) {

		// Add "Joins communities"
		$inputs_metakeys[GD_URE_TEMPLATE_FORMCOMPONENT_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY] = GD_URE_METAKEY_PROFILE_EMAILNOTIFICATIONS_NETWORK_JOINSCOMMUNITY;
		return $inputs_metakeys;
	}

}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Hooks_MyPreferences();