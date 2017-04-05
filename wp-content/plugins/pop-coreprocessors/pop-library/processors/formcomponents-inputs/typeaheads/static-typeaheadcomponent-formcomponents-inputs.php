<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TYPEAHEAD_COMPONENT_STATICSEARCH', PoP_ServerUtils::get_template_definition('formcomponent-typeaheadcomponent-staticsearch'));

class GD_Template_Processor_StaticTypeaheadComponentFormComponentInputs extends GD_Template_Processor_StaticTypeaheadComponentFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TYPEAHEAD_COMPONENT_STATICSEARCH
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_STATICSEARCH:

				// return '<hr/>';
				return gd_navigation_menu_item(POP_WPAPI_PAGE_SEARCHPOSTS, true).__('Search:', 'pop-coreprocessors');
		}

		return parent::get_label_text($template_id, $atts);
	}

	protected function get_static_suggestions($template_id, $atts) {

		$ret = parent::get_static_suggestions($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_STATICSEARCH:

				global $gd_filter_manager, $gd_filtercomponent_search, $gd_filtercomponent_name;

				$query_wildcard = GD_JSPLACEHOLDER_QUERY/*'%QUERY'*/;
				$ret[] = array(
					'title' => gd_navigation_menu_item(POP_WPAPI_PAGE_ALLCONTENT, true).__('Content with ', 'pop-coreprocessors').'"'.GD_JSPLACEHOLDER_QUERY/*'%QUERY'*/.'"',
					'value' => $query_wildcard,
					'url' => GD_StaticSearchUtils::get_content_search_url($query_wildcard),
				);
				$ret[] = array(
					'title' => gd_navigation_menu_item(POP_WPAPI_PAGE_ALLUSERS, true).__('Users with ', 'pop-coreprocessors').'"'.GD_JSPLACEHOLDER_QUERY/*'%QUERY'*/.'"',
					'value' => $query_wildcard,
					'url' => GD_StaticSearchUtils::get_users_search_url($query_wildcard),
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_StaticTypeaheadComponentFormComponentInputs();