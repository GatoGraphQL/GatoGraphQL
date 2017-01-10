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
	function get_textfield_class($template_id, $atts) {
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
			if ($classs = $this->get_att($template_id, $atts, 'textfield-class')) {

				// This class will very likely be "pop-show-notempty". We add it here so that in file
				// layout-dataquery-updatedata.tmpl we can ask if (target.hasClass('pop-show-notempty'))
				// and only then check for all ancestors, thus avoiding extra DOM traversing when not needed (aka when that class is not set)
				$ret[GD_JS_CLASSES]['text-field'] = $classs;
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

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'textfield-class', $this->get_textfield_class($template_id, $atts));
		return parent::init_atts($template_id, $atts);
	}
}