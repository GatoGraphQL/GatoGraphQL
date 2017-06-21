<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_ORGANIZATION', PoP_ServerUtils::get_template_definition('ure-typeahead-component-organization'));
define ('GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_INDIVIDUAL', PoP_ServerUtils::get_template_definition('ure-typeahead-component-individual'));
define ('GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY', PoP_ServerUtils::get_template_definition('ure-typeahead-component-community'));
define ('GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITYUSERS', PoP_ServerUtils::get_template_definition('ure-typeahead-component-communityusers'));

class GD_URE_Template_Processor_UserTypeaheadComponentFormComponentInputs extends GD_Template_Processor_UserTypeaheadComponentFormComponentInputs {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_ORGANIZATION,
			GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_INDIVIDUAL,
			GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY,
			GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITYUSERS,
		);
	}

	function get_label_text($template_id, $atts) {

		switch ($template_id) {
		
			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_ORGANIZATION:
			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY:

				return __('Organizations', 'ure-popprocessors');

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_INDIVIDUAL:

				return __('Individuals', 'ure-popprocessors');

			// case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITYUSERS:

			// 	return __('Users', 'ure-popprocessors');

			// case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY:

			// 	return __('Communities', 'ure-popprocessors');
		}

		return parent::get_label_text($template_id, $atts);
	}

	protected function get_typeahead_dataload_source($template_id, $atts) {

		switch ($template_id) {
		
			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_ORGANIZATION:
			// case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY:

				return get_permalink(POP_URE_POPPROCESSORS_PAGE_ORGANIZATIONS);

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_INDIVIDUAL:

				return get_permalink(POP_URE_POPPROCESSORS_PAGE_INDIVIDUALS);

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITYUSERS:

				$vars = GD_TemplateManager_Utils::get_vars();
				$author = $vars['global-state']['author']/*global $author*/;
				$url = get_author_posts_url($author);
				return GD_TemplateManager_Utils::add_tab($url, POP_WPAPI_PAGE_ALLUSERS);

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY:

				return get_permalink(POP_URE_POPPROCESSORS_PAGE_COMMUNITIES);
		}

		return parent::get_typeahead_dataload_source($template_id, $atts);
	}

	protected function get_thumbprint_query($template_id, $atts) {

		$ret = parent::get_thumbprint_query($template_id, $atts);

		switch ($template_id) {
		
			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_ORGANIZATION:
			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY:

				$ret['role'] = GD_URE_ROLE_ORGANIZATION;
				break;

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_INDIVIDUAL:

				$ret['role'] = GD_URE_ROLE_INDIVIDUAL;
				break;

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY:

				$ret['role'] = GD_URE_ROLE_COMMUNITY;
				break;
		}

		return $ret;
	}

	protected function get_pending_msg($template_id) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_ORGANIZATION:
			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY:

				return __('Loading Organizations', 'ure-popprocessors');

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_INDIVIDUAL:

				return __('Loading Individuals', 'ure-popprocessors');
		}

		return parent::get_pending_msg($template_id);
	}

	protected function get_notfound_msg($template_id) {

		switch ($template_id) {

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_ORGANIZATION:
			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_COMMUNITY:

				return __('No Organizations found', 'ure-popprocessors');

			case GD_URE_TEMPLATE_TYPEAHEAD_COMPONENT_INDIVIDUAL:

				return __('No Individuals found', 'ure-popprocessors');
		}

		return parent::get_notfound_msg($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_UserTypeaheadComponentFormComponentInputs();