<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TYPEAHEAD_COMPONENT_LOCATIONS', PoP_ServerUtils::get_template_definition('formcomponent-typeaheadcomponent-locations'));

class GD_Template_Processor_LocationTypeaheadComponentFormComponentInputs extends GD_Template_Processor_LocationTypeaheadComponentFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TYPEAHEAD_COMPONENT_LOCATIONS,
		);
	}

	protected function get_limit($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_LOCATIONS:

				return 8;
		}

		return parent::get_limit($template_id, $atts);
	}

	protected function get_typeahead_dataload_source($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_LOCATIONS:

				return get_permalink(POP_EM_POPPROCESSORS_PAGE_LOCATIONS);
		}

		return parent::get_typeahead_dataload_source($template_id, $atts);
	}

	protected function get_source_filter($template_id, $atts) {

		return GD_FILTER_WILDCARDPOSTS;
	}
	// protected function get_source_filter_params($template_id, $atts) {

	// 	$ret = parent::get_source_filter_params($template_id, $atts);

	// 	// bring the posts ordering by comment count
	// 	global $gd_filtercomponent_orderpost;
	// 	$ret[$gd_filtercomponent_orderpost->get_name()] = 'comment_count|DESC';

	// 	return $ret;
	// }
	protected function get_remote_url($template_id, $atts) {

		$url = parent::get_remote_url($template_id, $atts);
		
		// Add the query from typeahead.js to filter (http://twitter.github.io/typeahead.js/examples/)
		global $gd_filtercomponent_search;
		$url = add_query_arg($gd_filtercomponent_search->get_name(), '%QUERY', $url);

		return $url;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LocationTypeaheadComponentFormComponentInputs();