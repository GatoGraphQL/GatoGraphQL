<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ContentLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_CONTENT;
	}
	
	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($abovecontent_templates = $this->get_abovecontent_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$abovecontent_templates
			);
		}

		return $ret;
	}

	function get_data_fields($template_id, $atts) {

		return array_merge(
			parent::get_data_fields($template_id, $atts),
			array(
				'content',
			)
		);
	}

	function get_abovecontent_layouts($template_id) {

		return array();
	}

	function get_content_maxlength($template_id, $atts) {

		return null;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		if ($this->get_content_maxlength($template_id, $atts)) {
		
			$this->add_jsmethod($ret, 'showmore', 'inner');
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {

		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		if ($abovecontent_templates = $this->get_abovecontent_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['abovecontent'] = $abovecontent_templates;
			foreach ($abovecontent_templates as $abovecontent_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$abovecontent_template] = $gd_template_processor_manager->get_processor($abovecontent_template)->get_settings_id($abovecontent_template);
			}
		}

		if ($length = $this->get_content_maxlength($template_id, $atts)) {
			$ret['content-maxlength'] = $length;
		}

		return $ret;
	}
}