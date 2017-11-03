<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_MULTISELECT', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-volunteersneededmulti'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_SELECT', PoP_TemplateIDUtils::get_template_definition('formcomponentgroup-volunteersneeded'));

define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_VOLUNTEERSNEEDED_MULTISELECT', PoP_TemplateIDUtils::get_template_definition('filterformcomponentgroup-volunteersneededmulti'));

class PoPTheme_Wassup_Template_Processor_FormGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_MULTISELECT,
			GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_SELECT,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_VOLUNTEERSNEEDED_MULTISELECT,
		);
	}


	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_VOLUNTEERSNEEDED_MULTISELECT:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_VOLUNTEERSNEEDED_MULTISELECT:

				$ret .= ' col-sm-10';
				break;
		}

		return $ret;
	}
	
	function get_info($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_SELECT:

				return __('Do you need volunteers? Each time a user applies to volunteer, you will get a notification email with the volunteer\'s contact information.', 'poptheme-wassup');
		}
		
		return parent::get_info($template_id, $atts);
	}

	function get_component($template_id) {

		switch ($template_id) {
				
			case GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_MULTISELECT:

				return GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT;

			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_VOLUNTEERSNEEDED_MULTISELECT:

				return GD_TEMPLATE_FILTERFORMCOMPONENT_VOLUNTEERSNEEDED_MULTISELECT;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_VOLUNTEERSNEEDED_SELECT:

				return GD_TEMPLATE_FORMCOMPONENT_VOLUNTEERSNEEDED_SELECT;
		}
		
		return parent::get_component($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_Wassup_Template_Processor_FormGroups();