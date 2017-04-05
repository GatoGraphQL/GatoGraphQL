<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_TagTypeaheadComponentFormComponentsBase extends GD_Template_Processor_TypeaheadComponentFormComponentsBase {

	protected function get_value_key($template_id, $atts) {

		return 'namedescription';//'name';
	}
	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUTTAG_TYPEAHEAD_COMPONENT;
	}
	protected function get_tokenizer_keys($template_id, $atts) {

		// return array('name');
		return array('namedescription');
	}

	protected function get_source_filter($template_id, $atts) {

		return GD_FILTER_WILDCARDTAGS;
	}
	
	protected function get_source_filter_params($template_id, $atts) {

		$ret = parent::get_source_filter_params($template_id, $atts);

		// bring the tags ordering by tag count
		global $gd_filtercomponent_ordertag;
		$ret[$gd_filtercomponent_ordertag->get_name()] = 'count|DESC';

		return $ret;
	}
	protected function get_remote_url($template_id, $atts) {

		$url = parent::get_remote_url($template_id, $atts);
		
		// Add the query from typeahead.js to filter (http://twitter.github.io/typeahead.js/examples/)
		global $gd_filtercomponent_search;
		$url = add_query_arg($gd_filtercomponent_search->get_name(), GD_JSPLACEHOLDER_QUERY/*'%QUERY'*/, $url);

		return $url;
	}

	protected function get_thumbprint_query($template_id, $atts) {

		return array(
			'fields' => 'ids',
			'limit' => 1,
			'orderby' => 'term_id',
			'order' => 'DESC',
		);
	}
	protected function execute_thumbprint($query) {

		return get_tags($query);
	}

	protected function get_pending_msg($template_id) {

		return __('Loading Tags', 'pop-coreprocessors');
	}
	protected function get_notfound_msg($template_id) {

		return __('No Tags found', 'pop-coreprocessors');
	}
}
