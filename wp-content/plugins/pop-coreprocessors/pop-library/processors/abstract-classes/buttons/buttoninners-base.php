<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ButtonInnersBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_BUTTONINNER;
	}

	// function get_glyphicon($template_id) {
	// 	return null;
	// }
	function get_fontawesome($template_id, $atts) {
		return null;
	}
	function get_tag($template_id) {
		return 'span';
	}
	function get_btn_title($template_id) {
		return null;
	}
	function get_text_field($template_id, $atts) {
		return null;
	}
	function get_textfield_open($template_id, $atts) {
		return null;
	}
	function get_textfield_close($template_id, $atts) {
		return null;
	}
	function get_btntitle_class($template_id, $atts) {
		return null;
	}

	function get_data_fields($template_id, $atts) {

		$ret = array();
		if ($text_field = $this->get_text_field($template_id, $atts)) {

			$ret[] = $text_field;
		}
		return $ret;
	}
	
	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['tag'] = $this->get_tag($template_id);

		if ($btn_title = $this->get_btn_title($template_id)) {
			$ret[GD_JS_TITLES/*'titles'*/]['btn'] = $btn_title;
			if ($btntitle_class = $this->get_btntitle_class($template_id, $atts)) {
				$ret[GD_JS_CLASSES/*'classes'*/]['btn-title'] = $btntitle_class;
			}
		}
		if ($text_field = $this->get_text_field($template_id, $atts)) {

			$ret['text-field'] = $text_field;
			if ($textfield_open = $this->get_textfield_open($template_id, $atts)) {

				$ret['textfield-open'] = $textfield_open;
			}
			if ($textfield_close = $this->get_textfield_close($template_id, $atts)) {

				$ret['textfield-close'] = $textfield_close;
			}
		}

		// if ($glyphicon = $this->get_glyphicon($template_id)) {
		// 	$ret['glyphicon'] = $glyphicon;
		// }
		if ($fontawesome = $this->get_fontawesome($template_id, $atts)) {
			$ret[GD_JS_FONTAWESOME/*'fontawesome'*/] = $fontawesome;
		}
		
		return $ret;
	}
}