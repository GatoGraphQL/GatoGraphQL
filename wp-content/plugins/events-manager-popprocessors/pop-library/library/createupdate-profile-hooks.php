<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_EM_CreateUpdate_Profile_Hooks {

	function __construct() {

		add_filter('gd_createupdate_profile:form_data', array($this, 'get_form_data'), 10, 2);
		add_action('gd_createupdate_profile:additionals', array($this, 'additionals'), 10, 2);
		add_filter('gd_template:createprofile:components', array($this, 'get_components'), 10, 3);
		add_filter('gd_template:updateprofile:components', array($this, 'get_components'), 10, 3);
		add_filter('gd_template:updateprofile:extralayouts', array($this, 'extralayouts'));
	}

	function get_form_data($form_data, $atts) {

		global $gd_template_processor_manager;

		$form_data['locations'] = $gd_template_processor_manager->get_processor(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP)->get_value(GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP, $atts);
		
		return $form_data;
	}

	function get_components($components, $template_id, $processor) {

		// Add before the Captcha
		$extra_components = array(
			GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP,
		);
		array_splice($components, array_search(GD_TEMPLATE_FORMCOMPONENTGROUP_CUU_DESCRIPTION, $components)+1, 0, $extra_components);
		
		return $components;
	}

	function additionals($user_id, $form_data) {

		GD_MetaManager::update_user_meta($user_id, GD_METAKEY_PROFILE_LOCATIONS, $form_data['locations']);
	}

	function extralayouts($extra_layouts) {

		$extra_layouts[] = GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP;
		return $extra_layouts;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_CreateUpdate_Profile_Hooks();