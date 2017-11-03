<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_COMMENTEDITOR', PoP_TemplateIDUtils::get_template_definition('formcomponentgroupcommenteditor')); // Lowercase letters, no _ or - (http://codex.wordpress.org/Function_Reference/wp_editor)

class GD_Template_Processor_CommentFormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_COMMENTEDITOR,
		);
	}

	function get_component($template_id) {

		$components = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_COMMENTEDITOR => GD_TEMPLATE_FORMCOMPONENT_COMMENTEDITOR,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}

	function use_component_configuration($template_id) {

		switch ($template_id) {
			
			case GD_TEMPLATE_FORMCOMPONENTGROUP_COMMENTEDITOR:
			
				return false;
		}

		return parent::use_component_configuration($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentFormGroups();