<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FullObjectTitleLayoutsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_FULLOBJECTTITLE;
	}

	function get_data_fields($template_id, $atts) {

		return array_merge(
			parent::get_data_fields($template_id, $atts),
			array(
				$this->get_condition_field($template_id, $atts),
				$this->get_url_field($template_id, $atts),
				$this->get_title_field($template_id, $atts),
				$this->get_titleattr_field($template_id, $atts),
			)
		);
	}

	// function get_title_class($template_id, $atts) {
		
	// 	return 'title';
	// }
	
	function get_condition_field($template_id, $atts) {
		
		// By returning 'id', the condition will always be true by default since all objects have an id >= 1
		return 'id';
	}

	function get_url_field($template_id, $atts) {
		
		return 'url';
	}
	
	function get_title_field($template_id, $atts) {
		
		return '';
	}
	
	function get_titleattr_field($template_id, $atts) {
		
		return $this->get_title_field($template_id, $atts);
	}

	function get_htmlmarkup($template_id, $atts) {
		
		return 'h1';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		$ret[GD_JS_CLASSES/*'classes'*/]['title'] = 'title';

		$ret['html-markup'] = $this->get_htmlmarkup($template_id, $atts);

		$ret['url-field'] = $this->get_url_field($template_id, $atts);
		$ret['condition-field'] = $this->get_condition_field($template_id, $atts);
		$ret['title-field'] = $this->get_title_field($template_id, $atts);
		if ($titleattr_field = $this->get_titleattr_field($template_id, $atts)) {
			$ret['titleattr-field'] = $titleattr_field;
		}

		return $ret;
	}
}