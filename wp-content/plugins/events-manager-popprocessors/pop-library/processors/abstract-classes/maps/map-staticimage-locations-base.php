<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MapStaticImageLocationsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_MAP_STATICIMAGE_LOCATIONS;
	}

	function get_subcomponent_modules($template_id) {
	
		$urlparam = $this->get_urlparam_template($template_id);
		return array(
			'locations' => array(
				'modules' => array($urlparam),
				'dataloader' => GD_DATALOADER_LOCATIONLIST
			)
		);
	}

	function get_urlparam_template($template_id) {
	
		return GD_TEMPLATE_MAP_STATICIMAGE_URLPARAM;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$urlparam = $this->get_urlparam_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['urlparam'] = $gd_template_processor_manager->get_processor($urlparam)->get_settings_id($urlparam);
		
		return $ret;
	}
}
