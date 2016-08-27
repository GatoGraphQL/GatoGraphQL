<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_PreviewUserLayoutsBase extends GD_Template_Processor_PreviewObjectLayoutsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_PREVIEWUSER;
	}

	function get_data_fields($template_id, $atts) {

		$ret = array_merge(
			parent::get_data_fields($template_id, $atts),
			array('display-name', 'is-profile')
		);

		if ($this->show_title($template_id)) {
			$ret[] = 'title';
		}
		if ($this->show_short_description($template_id)) {
			$ret[] = 'short-description-formatted';
		}

		return $ret;
	}

	function show_short_description($template_id) {

		return true;
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($belowavatar_templates = $this->get_belowavatar_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$belowavatar_templates
			);
		}
		if ($belowexcerpt_templates = $this->get_belowexcerpt_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$belowexcerpt_templates
			);
		}
		if ($useravatar = $this->get_useravatar_template($template_id)) {
			$ret[] = $useravatar;
		}

		return $ret;
	}

	protected function show_title($template_id) {

		return false;
	}

	function get_useravatar_template($template_id) {

		return null;
	}

	function get_belowavatar_layouts($template_id) {

		return array();
	}
	function get_belowexcerpt_layouts($template_id) {

		return array();
	}
	// function get_extra_class($template_id) {

	// 	return '';
	// }

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		if ($belowavatar_templates = $this->get_belowavatar_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['belowavatar'] = $belowavatar_templates;
			foreach ($belowavatar_templates as $belowavatar_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$belowavatar_template] = $gd_template_processor_manager->get_processor($belowavatar_template)->get_settings_id($belowavatar_template);
			}
		}
		if ($belowexcerpt_templates = $this->get_belowexcerpt_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['belowexcerpt'] = $belowexcerpt_templates;
			foreach ($belowexcerpt_templates as $belowexcerpt_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$belowexcerpt_template] = $gd_template_processor_manager->get_processor($belowexcerpt_template)->get_settings_id($belowexcerpt_template);
			}
		}
		if ($this->show_short_description($template_id)) {
			$ret['show-short-description'] = true;
		}

		if ($useravatar = $this->get_useravatar_template($template_id)) {
			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['useravatar'] = $gd_template_processor_manager->get_processor($useravatar)->get_settings_id($useravatar);
		}

		return $ret;
	}
}