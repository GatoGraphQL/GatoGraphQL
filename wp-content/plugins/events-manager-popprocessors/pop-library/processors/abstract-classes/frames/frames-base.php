<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_EM_Template_Processor_CreateLocationFramesBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {
	
		return GD_TEMPLATESOURCE_FRAME_CREATELOCATIONMAP;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'formMapLocationGeocode');
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', 'pop-map-locationgeocode');
		return parent::init_atts($template_id, $atts);
	}

	function get_modules($template_id) {
	
		switch ($template_id) {
		
			case GD_TEMPLATE_FRAME_CREATELOCATIONMAP:
			
				return array(
					$this->get_mapdiv_template($template_id),
					$this->get_form_template($template_id)
				);
		}

		return parent::get_modules($template_id);
	}

	function get_form_template($template_id) {
	
		return GD_TEMPLATE_FORM_CREATELOCATION;
	}

	function get_mapdiv_template($template_id) {
	
		return GD_TEMPLATE_MAP_DIV;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$mapdiv = $this->get_mapdiv_template($template_id);
		$form = $this->get_form_template($template_id);
		
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['form-createlocation'] = $gd_template_processor_manager->get_processor($form)->get_settings_id($form);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['map-div'] = $gd_template_processor_manager->get_processor($mapdiv)->get_settings_id($mapdiv);
		
		return $ret;
	}
}
