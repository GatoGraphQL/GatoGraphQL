<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserViewComponentButtonsBase extends GD_Template_Processor_ViewComponentButtonsBase {

	function get_header_template($template_id) {
		
		if ($this->header_show_url($template_id)) {

			return GD_TEMPLATE_VIEWCOMPONENT_HEADER_USER_URL;
		}

		return GD_TEMPLATE_VIEWCOMPONENT_HEADER_USER;
	}

	// function get_title_field($template_id) {
		
	// 	return 'display-name';
	// }

	// function fixed_target_url($template_id) {

	// 	return true;
	// }

	function get_itemobject_params($template_id) {

		$ret = parent::get_itemobject_params($template_id);

		// $ret['data-target-ids'] = 'id';
		$ret['data-target-title'] = 'display-name';
		$ret['data-target-url'] = 'url';
		// if ($this->fixed_target_url($template_id)) {
		// 	$ret['data-target-url'] = 'url';
		// }
		
		return $ret;
	}

	// function get_data_fields($template_id, $atts) {

	// 	$ret = parent::get_data_fields($template_id, $atts);
	
	// 	// Add the url
	// 	$ret[] = 'url';
		
	// 	return $ret;
	// }
}