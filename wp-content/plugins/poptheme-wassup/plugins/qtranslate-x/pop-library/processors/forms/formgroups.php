<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_QT_TEMPLATE_FORMCOMPONENTGROUP_LANGUAGE', PoP_ServerUtils::get_template_definition('qt-formcomponentgroup-language'));

class GD_QT_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_QT_TEMPLATE_FORMCOMPONENTGROUP_LANGUAGE,
		);
	}

	function get_component($template_id) {

		$components = array(
			GD_QT_TEMPLATE_FORMCOMPONENTGROUP_LANGUAGE => GD_QT_TEMPLATE_FORMCOMPONENT_LANGUAGE,
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
new GD_QT_Template_Processor_FormGroups();