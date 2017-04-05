<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLUSERS', PoP_ServerUtils::get_template_definition('formcomponent-typeaheadcomponent-allusers'));

class GD_Template_Processor_UserTypeaheadComponentFormComponentInputs extends GD_Template_Processor_UserTypeaheadComponentFormComponentsBase {

	function get_templates_to_process() {
	
		return array(
			// GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLPROFILES
			GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLUSERS
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
		
			// case GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLPROFILES:
			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLUSERS:

				// return '<hr/>';
				return gd_navigation_menu_item(POP_WPAPI_PAGE_ALLUSERS, true).__('Users:', 'pop-coreprocessors');
		}

		return parent::get_label_text($template_id, $atts);
	}

	// protected function get_limit($template_id, $atts) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLPROFILES:

	// 			return 3;
	// 	}

	// 	return parent::get_limit($template_id, $atts);
	// }

	protected function get_source_filter($template_id, $atts) {

		return GD_FILTER_WILDCARDUSERS;
	}
	protected function get_source_filter_params($template_id, $atts) {

		$ret = parent::get_source_filter_params($template_id, $atts);

		// bring the posts ordering by comment count
		global $gd_filtercomponent_orderuser;
		$ret[$gd_filtercomponent_orderuser->get_name()] = 'post_count|DESC';

		return $ret;
	}
	protected function get_remote_url($template_id, $atts) {

		$url = parent::get_remote_url($template_id, $atts);
		
		// Add the query from typeahead.js to filter (http://twitter.github.io/typeahead.js/examples/)
		global $gd_filtercomponent_name;
		$url = add_query_arg($gd_filtercomponent_name->get_name(), GD_JSPLACEHOLDER_QUERY/*'%QUERY'*/, $url);

		return $url;
	}

	protected function get_typeahead_dataload_source($template_id, $atts) {

		switch ($template_id) {
		
			// case GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLPROFILES:
			case GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLUSERS:

				return get_permalink(POP_WPAPI_PAGE_ALLUSERS);
		}

		return parent::get_typeahead_dataload_source($template_id, $atts);
	}

	// protected function get_thumbprint_query($template_id, $atts) {

	// 	$ret = parent::get_thumbprint_query($template_id, $atts);

	// 	switch ($template_id) {
		
	// 		case GD_TEMPLATE_TYPEAHEAD_COMPONENT_ALLPROFILES:
				
	// 			$ret['role'] = GD_ROLE_PROFILE;
	// 			break;
	// 	}

	// 	return $ret;
	// }
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_UserTypeaheadComponentFormComponentInputs();