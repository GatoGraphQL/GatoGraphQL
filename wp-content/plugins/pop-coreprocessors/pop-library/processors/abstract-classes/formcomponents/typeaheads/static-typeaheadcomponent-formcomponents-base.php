<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_StaticTypeaheadComponentFormComponentsBase extends GD_Template_Processor_TypeaheadComponentFormComponentsBase {

	// protected function get_component_template($template_id) {

	// 	return GD_TEMPLATE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT;
	// }
	protected function get_value_key($template_id, $atts) {

		return 'value';
	}
	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT;
	}
	// protected function get_layout($template_id) {

	// 	return GD_TEMPLATE_LAYOUTSTATIC_TYPEAHEAD_COMPONENT;
	// }
	protected function get_tokenizer_keys($template_id, $atts) {

		return array();
	}
}
