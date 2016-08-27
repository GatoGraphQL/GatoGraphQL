<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Configuration
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CONSTANT_FULLVIEW_TITLEPOSITION_TOP', 'top');
define ('GD_CONSTANT_FULLVIEW_TITLEPOSITION_BODY', 'body');

class GD_Template_Processor_FullViewLayoutsBase extends GD_Template_Processor_FullObjectLayoutsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_FULLVIEW;
	}

	function get_data_fields($template_id, $atts) {

		return array_merge(
			parent::get_data_fields($template_id, $atts),
			array('cat-slugs')
		);
	}

	function title_position($template_id, $atts) {

		return GD_CONSTANT_FULLVIEW_TITLEPOSITION_TOP;
	}
	
	function get_modules($template_id) {
	
		$ret = parent::get_modules($template_id);

		if ($abovecontent_templates = $this->get_abovecontent_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$abovecontent_templates
			);
		}

		if ($content_templates = $this->get_content_layouts($template_id)) {
			$ret = array_merge(
				$ret,
				$content_templates
			);
		}

		return $ret;
	}

	function get_abovecontent_layouts($template_id) {

		return array();
	}

	function get_content_layouts($template_id) {

		return array(
			GD_TEMPLATE_LAYOUT_CONTENT_POST,//GD_TEMPLATE_LAYOUTSINGLE
		);
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$ret[GD_JS_CLASSES/*'classes'*/]['title'] = '';

		if ($this->get_title_template($template_id, $atts)) {
			$ret['title-position'] = $this->title_position($template_id, $atts);
		}

		if ($abovecontent_templates = $this->get_abovecontent_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['abovecontent'] = $abovecontent_templates;
			foreach ($abovecontent_templates as $abovecontent_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$abovecontent_template] = $gd_template_processor_manager->get_processor($abovecontent_template)->get_settings_id($abovecontent_template);
			}
		}

		if ($content_templates = $this->get_content_layouts($template_id)) {
			
			$ret[GD_JS_TEMPLATEIDS/*'template-ids'*/]['content'] = $content_templates;
			foreach ($content_templates as $content_template) {

				$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/][$content_template] = $gd_template_processor_manager->get_processor($content_template)->get_settings_id($content_template);
			}
		}
		
		return $ret;
	}

	// function get_block_jsmethod($template_id, $atts) {

	// 	$ret = parent::get_block_jsmethod($template_id, $atts);
	// 	$this->add_jsmethod($ret, 'waypointsHistoryStateNew');
	// 	return $ret;
	// }

	function init_atts($template_id, &$atts) {
			
		// Make it waypoint
		$this->append_att($template_id, $atts, 'class', 'waypoint');

		// This one is independent of Waypoint because of the History, so here add them as separate instructions (just to make it clear)
		// $this->append_att($template_id, $atts, 'class', 'template-historystate newstate');
		return parent::init_atts($template_id, $atts);
	}
}