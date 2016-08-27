<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_StructureInnersBase extends GD_Template_ProcessorBase {

	//-------------------------------------------------
	// PUBLIC Functions
	//-------------------------------------------------

	function get_layouts($template_id) {
	
		return array();
	}

	//-------------------------------------------------
	// PUBLIC Overriding Functions
	//-------------------------------------------------

	function get_modules($template_id) {

		$ret = array();

		if ($layouts = $this->get_layouts($template_id)) {

			$ret = array_merge(
				$ret,
				$layouts
			);
		}

		return $ret;
	}

	// Comment Leo 04/06/2015: No need anymore here, it is set in the atts in GD_Template_Processor_StructuresBase
	// function get_template_cb($template_id, $atts) {
	
	// 	return true;
	// }
	
	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($layouts = $this->get_layouts($template_id, $atts)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['layouts'] = $layouts;
			foreach ($layouts as $layout) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$layout] = $gd_template_processor_manager->get_processor($layout)->get_settings_id($layout);
			}
		}

		return $ret;
	}
}
