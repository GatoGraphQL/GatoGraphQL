<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORM_SETTINGS', PoP_TemplateIDUtils::get_template_definition('form-settings'));

class GD_Template_Processor_SettingsForms extends GD_Template_Processor_FormsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORM_SETTINGS
		);
	}

	function get_inner_template($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_FORM_SETTINGS:

				return GD_TEMPLATE_FORMINNER_SETTINGS;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SettingsForms();