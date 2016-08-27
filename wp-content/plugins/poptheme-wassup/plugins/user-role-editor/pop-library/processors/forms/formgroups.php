<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_INDIVIDUALINTERESTS', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-individualinterests'));
define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-organizationcategories'));
define ('GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ORGANIZATIONTYPES', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-organizationtypes'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_INDIVIDUALINTERESTS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-individualinterests'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-organizationcategories'));
define ('GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONTYPES', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-organizationtypes'));

class GD_URE_Custom_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_INDIVIDUALINTERESTS,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ORGANIZATIONTYPES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_INDIVIDUALINTERESTS,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONTYPES,
		);
	}

	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_INDIVIDUALINTERESTS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONTYPES:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_INDIVIDUALINTERESTS:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES:
			case GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONTYPES:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}

	function get_component($template_id) {

		$components = array(
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_INDIVIDUALINTERESTS => GD_URE_TEMPLATE_FORMCOMPONENT_INDIVIDUALINTERESTS,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES => GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONCATEGORIES,
			GD_URE_TEMPLATE_FORMCOMPONENTGROUP_ORGANIZATIONTYPES => GD_URE_TEMPLATE_FORMCOMPONENT_ORGANIZATIONTYPES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_INDIVIDUALINTERESTS => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_INDIVIDUALINTERESTS,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONCATEGORIES => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONCATEGORIES,
			GD_URE_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORGANIZATIONTYPES => GD_URE_TEMPLATE_FILTERFORMCOMPONENT_ORGANIZATIONTYPES,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Custom_Template_Processor_FormGroups();