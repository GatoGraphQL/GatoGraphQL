<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMINNER_SETTINGS', PoP_TemplateIDUtils::get_template_definition('forminner-settings'));

class GD_Template_Processor_SettingsFormInners extends GD_Template_Processor_FormInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMINNER_SETTINGS
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_FORMINNER_SETTINGS:

				$languages = qtranxf_getSortedLanguages();
				$ret[] = GD_TEMPLATE_FORMCOMPONENT_BROWSERURL;
				if ($languages && count($languages) > 1) {
					$ret[] = GD_QT_TEMPLATE_FORMCOMPONENTGROUP_LANGUAGE;
				}
				$ret[] = GD_TEMPLATE_FORMCOMPONENTGROUP_SETTINGSFORMAT;
				$ret[] = GD_TEMPLATE_SUBMITBUTTON_OK;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_SettingsFormInners();