<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PreviewObjectLayoutsBase extends GD_Template_ProcessorBase {

	function show_excerpt($template_id) {

		return false;
	}

	function get_url_field($template_id) {

		return 'url';
	}

	function get_title_htmlmarkup($template_id, $atts) {

		return 'h4';
	}

	function get_linktarget($template_id, $atts) {

		return '';
	}

	function get_quicklinkgroup_top($template_id) {

		return null;
	}
	function get_quicklinkgroup_bottom($template_id) {

		return null;
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($quicklinkgroup_top = $this->get_quicklinkgroup_top($template_id)) {
			$ret[] = $quicklinkgroup_top;
		}
		if ($quicklinkgroup_bottom = $this->get_quicklinkgroup_bottom($template_id)) {
			$ret[] = $quicklinkgroup_bottom;
		}


		return $ret;
	}

	function get_data_fields($template_id, $atts) {

		$ret = parent::get_data_fields($template_id, $atts);

		$ret[] = $this->get_url_field($template_id);
		if ($this->show_excerpt($template_id)) {
			$ret[] = 'excerpt';
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);	

		global $gd_template_processor_manager;

		$ret[GD_JS_CLASSES/*'classes'*/] = array();
		$ret['title-htmlmarkup'] = $this->get_title_htmlmarkup($template_id, $atts);
		$ret['url-field'] = $this->get_url_field($template_id);
		if ($this->show_excerpt($template_id)) {
			$ret['show-excerpt'] = true;
		}
		if ($target = $this->get_linktarget($template_id, $atts)) {
			$ret['link-target'] = $target;
		}

		if ($quicklinkgroup_top = $this->get_quicklinkgroup_top($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['quicklinkgroup-top'] = $gd_template_processor_manager->get_processor($quicklinkgroup_top)->get_settings_id($quicklinkgroup_top);
		}
		if ($quicklinkgroup_bottom = $this->get_quicklinkgroup_bottom($template_id)) {
			
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['quicklinkgroup-bottom'] = $gd_template_processor_manager->get_processor($quicklinkgroup_bottom)->get_settings_id($quicklinkgroup_bottom);
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		// Artificial property added to identify the template when adding template-resources
		$this->add_att($template_id, $atts, 'resourceloader', 'layout');

		return parent::init_atts($template_id, $atts);
	}
}