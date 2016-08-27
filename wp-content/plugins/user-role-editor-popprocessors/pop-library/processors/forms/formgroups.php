<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-selectabletypeahead-ure-communities'));

define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-users'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITYUSERS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-community-users'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_POST', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-communities-post'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_USER', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-communities-user'));

class GD_URE_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITYUSERS,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_POST,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_USER,
		);
	}

	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITYUSERS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_POST:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_USER:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITYUSERS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_POST:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_USER:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}

	function get_component($template_id) {

		$components = array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES => GD_URE_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_COMMUNITIES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITYUSERS => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITYUSERS,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_POST => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_POST,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_COMMUNITIES_USER => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_COMMUNITIES_USER,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}

	function get_info($template_id, $atts) {

		switch ($template_id) {
				
			case GD_URE_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_COMMUNITIES:

				return __('Please select the organizations you are a member of: all your content will also show under their profiles.', 'ure-popprocessors');
		}
		
		return parent::get_info($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_FormGroups();