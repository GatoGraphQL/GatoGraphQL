<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLCONTENT', PoP_TemplateIDUtils::get_template_definition('formcomponent-typeaheadcomponent-allcontent'));

class GD_Template_Processor_PostTypeaheadComponentFormComponentInputs extends GD_Template_Processor_PostTypeaheadComponentFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLCONTENT,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLCONTENT:

				return gd_navigation_menu_item(POP_WPAPI_PAGE_ALLCONTENT, true).__('Content:', 'pop-coreprocessors');
		}

		return parent::get_label_text($template_id, $atts);
	}

	protected function get_typeahead_dataload_source($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLCONTENT:

				return get_permalink(POP_WPAPI_PAGE_ALLCONTENT);
		}

		return parent::get_typeahead_dataload_source($template_id, $atts);
	}


	protected function get_source_filter($template_id, $atts) {

		return GD_FILTER_WILDCARDPOSTS;
	}
	protected function get_source_filter_params($template_id, $atts) {

		$ret = parent::get_source_filter_params($template_id, $atts);

		// bring the posts ordering by comment count
		global $gd_filtercomponent_orderpost;
		$ret[$gd_filtercomponent_orderpost->get_name()] = 'comment_count|DESC';

		return $ret;
	}
	protected function get_remote_url($template_id, $atts) {

		$url = parent::get_remote_url($template_id, $atts);
		
		// Add the query from typeahead.js to filter (http://twitter.github.io/typeahead.js/examples/)
		global $gd_filtercomponent_search;
		$url = add_query_arg($gd_filtercomponent_search->get_name(), GD_JSPLACEHOLDER_QUERY/*'%QUERY'*/, $url);

		return $url;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_PostTypeaheadComponentFormComponentInputs();