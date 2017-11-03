<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONNAME', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-location_name'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONADDRESS', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-location_address'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONTOWN', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-location_town'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONSTATE', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-location_state'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONPOSTCODE', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-location_postcode'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONREGION', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-location_region'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONCOUNTRY', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-location_country'));

class GD_EM_Template_Processor_CreateLocationFormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONADDRESS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONTOWN,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONSTATE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONPOSTCODE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONREGION,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONCOUNTRY,
		);
	}

	function get_component($template_id) {

		$components = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONNAME => GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONADDRESS => GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONADDRESS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONTOWN => GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONTOWN,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONSTATE => GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONSTATE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONPOSTCODE => GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONPOSTCODE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONREGION => GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONREGION,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONCOUNTRY => GD_TEMPLATE_FORMCOMPONENT_EM_LOCATIONCOUNTRY,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}

	function use_component_configuration($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONNAME:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONADDRESS:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONTOWN:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONSTATE:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONPOSTCODE:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONREGION:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EM_LOCATIONCOUNTRY:

				return false;
		}

		return parent::use_component_configuration($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CreateLocationFormGroups();