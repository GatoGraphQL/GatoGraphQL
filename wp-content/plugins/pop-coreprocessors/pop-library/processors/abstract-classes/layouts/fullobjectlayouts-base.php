<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FullObjectLayoutsBase extends GD_Template_ProcessorBase {

	function get_sidebar_template($template_id) {

		return null;
	}

	function get_title_template($template_id) {

		return null;
	}

	function get_data_fields($template_id, $atts) {

		return array_merge(
			parent::get_data_fields($template_id, $atts),
			array('url')
		);
	}

	function get_header_templates($template_id) {

		return array();
	}

	function get_footer_templates($template_id) {

		return array();
	}

	function get_fullview_footer_templates($template_id) {

		// Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
		return apply_filters('GD_Template_Processor_FullObjectLayoutsBase:footer_templates', $this->get_footer_templates($template_id), $template_id);
	}

	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($title = $this->get_title_template($template_id)) {
			$ret[] = $title;
		}
		if ($sidebar = $this->get_sidebar_template($template_id)) {
			$ret[] = $sidebar;
		}
		if ($headers = $this->get_header_templates($template_id)) {
			$ret = array_merge(
				$ret,
				$headers
			);
		}
		if ($footers = $this->get_fullview_footer_templates($template_id)) {
			$ret = array_merge(
				$ret,
				$footers
			);
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {
	
		global $gd_template_processor_manager;

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_CLASSES/*'classes'*/] = array(
			'wrapper' => '',
			'inner-wrapper' => 'row',
			'socialmedia' => '',
			'content' => 'readable clearfix',
			'sidebar' => '',
			'content-body' => 'col-xs-12'

		);

		if ($title = $this->get_title_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['title'] = $gd_template_processor_manager->get_processor($title)->get_settings_id($title);
		}
		if ($sidebar = $this->get_sidebar_template($template_id)) {

			$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['sidebar'] = $gd_template_processor_manager->get_processor($sidebar)->get_settings_id($sidebar);
			$ret[GD_JS_CLASSES/*'classes'*/]['sidebar'] = 'col-xsm-3 col-xsm-push-9';
			$ret[GD_JS_CLASSES/*'classes'*/]['content-body'] = 'col-xsm-9 col-xsm-pull-3';
		}
		if ($headers = $this->get_header_templates($template_id)) {

			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['headers'] = $headers;
			foreach ($headers as $header) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$header] = $gd_template_processor_manager->get_processor($header)->get_settings_id($header);
			}
		}
		if ($footers = $this->get_fullview_footer_templates($template_id)) {

			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['footers'] = $footers;
			foreach ($footers as $footer) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$footer] = $gd_template_processor_manager->get_processor($footer)->get_settings_id($footer);
			}
		}
		
		return $ret;
	}
}