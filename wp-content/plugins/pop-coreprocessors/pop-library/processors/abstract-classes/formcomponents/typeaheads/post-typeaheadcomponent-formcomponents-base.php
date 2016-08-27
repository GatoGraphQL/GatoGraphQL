<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PostTypeaheadComponentFormComponentsBase extends GD_Template_Processor_TypeaheadComponentFormComponentsBase {

	protected function get_value_key($template_id, $atts) {

		return 'title';
	}
	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUTPOST_TYPEAHEAD_COMPONENT;
	}
	protected function get_tokenizer_keys($template_id, $atts) {

		return array('title');
	}

	protected function get_thumbprint_query($template_id, $atts) {

		return array(
			'fields' => 'ids',
			'limit' => 1,
			'orderby' => 'ID',
			'order' => 'DESC',
		);
	}
	protected function execute_thumbprint($query) {

		return get_posts($query);
	}

	protected function get_pending_msg($template_id) {

		return __('Loading Content', 'pop-coreprocessors');
	}
	protected function get_notfound_msg($template_id) {

		return __('No Content found', 'pop-coreprocessors');
	}
}
