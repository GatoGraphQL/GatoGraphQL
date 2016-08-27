<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_LocationTypeaheadComponentFormComponentsBase extends GD_Template_Processor_PostTypeaheadComponentFormComponentsBase {

	protected function get_thumbprint_query($template_id, $atts) {

		$ret = parent::get_thumbprint_query($template_id, $atts);

		$ret['post_type'] = EM_POST_TYPE_LOCATION;
		
		return $ret;
	}

	// protected function get_component_template($template_id) {

	// 	return GD_TEMPLATE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT;
	// }
	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT;
	}
	protected function get_value_key($template_id, $atts) {

		return 'name';
	}
	// protected function get_layout($template_id) {

	// 	return GD_TEMPLATE_LAYOUTLOCATION_TYPEAHEAD_COMPONENT;
	// }
	protected function get_tokenizer_keys($template_id, $atts) {

		return array('name', 'address');
	}

	protected function get_pending_msg($template_id) {

		return __('Loading Locations', 'em-popprocessors');
	}
	protected function get_notfound_msg($template_id) {

		return __('No Locations found', 'em-popprocessors');
	}
}
