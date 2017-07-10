<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_EM_Template_Processor_DateTimeLayoutsBase extends GD_Template_ProcessorBase {

	function add_downloadlinks($template_id) {

		return false;
	}
	function get_downloadlinks_class($template_id) {

		return 'pull-right';
	}
	function get_separator($template_id, $atts) {

		return '<br/>';
	}

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_DATETIME;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		if ($this->add_downloadlinks($template_id)) {

			$ret[] = GD_EM_TEMPLATE_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN;
		}
		
		return $ret;
	}
	
	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;
	
		if ($this->add_downloadlinks($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['layout-downloadlinks'] = $gd_template_processor_manager->get_processor(GD_EM_TEMPLATE_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN)->get_settings_id(GD_EM_TEMPLATE_QUICKLINKBUTTONGROUP_DOWNLOADLINKSDROPDOWN);

			if ($downloadlinks_class = $this->get_downloadlinks_class($template_id)) {
				$ret[GD_JS_CLASSES/*'classes'*/]['downloadlinks'] = $downloadlinks_class;
			}
		}
		$ret[GD_JS_CLASSES/*'classes'*/]['calendar'] = 'calendar';
		$ret[GD_JS_CLASSES/*'classes'*/]['date'] = 'date';
		$ret[GD_JS_CLASSES/*'classes'*/]['time'] = 'time';
		if ($separator = $this->get_separator($template_id, $atts)) {
			$ret['separator'] = $separator;
		}
		
		return $ret;
	}

	function get_data_fields($template_id, $atts) {
	
		return array('dates', 'times');
	}	
}