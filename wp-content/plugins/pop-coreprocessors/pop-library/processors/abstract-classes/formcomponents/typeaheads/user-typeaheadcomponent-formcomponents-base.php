<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserTypeaheadComponentFormComponentsBase extends GD_Template_Processor_TypeaheadComponentFormComponentsBase {

	// protected function get_component_template($template_id) {

	// 	return GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT;
	// }
	protected function get_value_key($template_id, $atts) {

		return 'display-name';
	}
	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUTUSER_TYPEAHEAD_COMPONENT;
	}
	// protected function get_layout($template_id) {

	// 	return GD_TEMPLATE_LAYOUTUSER_TYPEAHEAD_COMPONENT;
	// }
	protected function get_tokenizer_keys($template_id, $atts) {

		return array('display-name');
	}


	protected function get_thumbprint_query($template_id, $atts) {

		return array(
			'fields' => 'ID', 
			'limit' => 1,
			'orderby' => 'ID',
			'order' => 'DESC',
			'role' => GD_ROLE_PROFILE
		);
	}
	protected function execute_thumbprint($query) {

		return get_users($query);
	}

	protected function get_pending_msg($template_id) {

		return __('Loading Users', 'pop-coreprocessors');
	}
	protected function get_notfound_msg($template_id) {

		return __('No Users found', 'pop-coreprocessors');
	}
}
