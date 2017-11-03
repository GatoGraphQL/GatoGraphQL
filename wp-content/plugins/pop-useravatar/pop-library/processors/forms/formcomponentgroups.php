<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-fileupload-picture'));

class PoP_UserAvatar_Template_Processor_FormComponentGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE,
		);
	}

	function get_component($template_id) {

		$components = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE => GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}
	function use_component_configuration($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE:
				
				return false;
		}
		
		return parent::use_component_configuration($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_UserAvatar_Template_Processor_FormComponentGroups();