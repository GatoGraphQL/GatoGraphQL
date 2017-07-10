<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_ButtonsBase extends GD_Template_ProcessorBase {

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);
	
		$ret[] = $this->get_buttoninner_template($template_id);

		return $ret;
	}

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_BUTTON;
	}

	function get_data_fields($template_id, $atts) {

		return array(
			$this->get_url_field($template_id),
			// $this->get_condition_field($template_id),
		);
	}

	function get_url_field($template_id) {
		
		return 'url';
	}
	function get_buttoninner_template($template_id) {
		
		return null;
	}
	function get_title($template_id) {
		return null;
	}
	function get_linktarget($template_id, $atts) {
		return null;
	}
	function get_link_class($template_id) {
		
		return '';
	}
	function get_btn_class($template_id, $atts) {

		return '';
	}
	function show_title($template_id, $atts) {

		return false;
	}
	function show_tooltip($template_id, $atts) {

		return false;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);	

		if ($this->show_tooltip($template_id, $atts)) {
			$this->add_jsmethod($ret, 'tooltip');		
		}

		return $ret;
	}
	
	// function get_condition_field($template_id) {
		
	// 	// By returning 'id', the condition will always be true by default since all objects have an id >= 1
	// 	return 'id';
	// }
	
	// function get_block_jsmethod($template_id, $atts) {

	// 	$ret = parent::get_block_jsmethod($template_id, $atts);
		
	// 	$this->add_jsmethod($ret, 'hideEmpty', 'hide-empty');

	// 	return $ret;
	// }

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		if ($this->show_title($template_id, $atts) || $this->show_tooltip($template_id, $atts)) {
			if ($title = $this->get_title($template_id)) {
				$ret['title'] = $title;
			}
		}
		
		// if ($condition_field = $this->get_condition_field($template_id)) {
		// 	$ret['condition-field'] = $condition_field;
		// }
		$ret['url-field'] = $this->get_url_field($template_id);
		// $ret['link-class'] = $this->get_link_class($template_id);
		$ret[GD_JS_CLASSES/*'classes'*/]['link'] = $this->get_link_class($template_id);
		if ($btn_class = $this->get_att($template_id, $atts, 'btn-class')) {
			// $ret['btn-class'] = $btn_class;
			$ret[GD_JS_CLASSES/*'classes'*/]['btn'] = $btn_class;
		}
		
		if ($linktarget = $this->get_linktarget($template_id, $atts)) {
			$ret['linktarget'] = $linktarget;
		}

		global $gd_template_processor_manager;
		$buttoninner = $this->get_buttoninner_template($template_id);
		$ret[GD_JS_SETTINGSIDS/*'settings-ids'*/]['buttoninner'] = $gd_template_processor_manager->get_processor($buttoninner)->get_settings_id($buttoninner);
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {

		if ($btn_class = $this->get_btn_class($template_id, $atts)) {
			$this->append_att($template_id, $atts, 'btn-class', $btn_class);
		}
		return parent::init_atts($template_id, $atts);
	}
}