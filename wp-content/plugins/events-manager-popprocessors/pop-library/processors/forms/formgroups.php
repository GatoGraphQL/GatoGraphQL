<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP', PoP_ServerUtils::get_template_definition('formcomponentgroup-locationsmap'));

class GD_EM_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP,
		);
	}

	function get_component($template_id) {

		$components = array(
			GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP => GD_EM_TEMPLATE_FORMCOMPONENT_TYPEAHEADMAP,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}

	function get_info($template_id, $atts) {

		switch ($template_id) {

			case GD_EM_TEMPLATE_FORMCOMPONENTGROUP_TYPEAHEADMAP:
				
				return __('If you can\'t find the location in the input below, click on the "+" button to add a new one.', 'em-popprocessors');
		}
		
		return parent::get_info($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_FormGroups();