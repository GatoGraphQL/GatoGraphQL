<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_MultiTargetIndentMenuLayoutsBase extends GD_Template_Processor_MenuLayoutsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_MENU_MULTITARGETINDENT;
	}

	function get_targets($template_id, $atts) {

		return array();
	}
	// function get_dropdownmenu_class($template_id, $atts) {

	// 	return '';
	// }
	function get_multitarget_class($template_id, $atts) {

		return '';
	}
	function get_multitarget_tooltip($template_id, $atts) {

		return '';
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);	

		if ($this->get_multitarget_tooltip($template_id, $atts)) {
			$this->add_jsmethod($ret, 'tooltip', 'tooltip');
		}

		return $ret;
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		$ret['targets'] = $this->get_targets($template_id, $atts);
		
		if ($class = $this->get_multitarget_class($template_id, $atts)) {
			$ret[GD_JS_CLASSES/*'classes'*/]['multitarget'] = $class;
		}
		if ($tooltip = $this->get_multitarget_tooltip($template_id, $atts)) {
			$ret[GD_JS_TITLES/*'titles'*/]['tooltip'] = $tooltip;
		}

		return $ret;
	}
}