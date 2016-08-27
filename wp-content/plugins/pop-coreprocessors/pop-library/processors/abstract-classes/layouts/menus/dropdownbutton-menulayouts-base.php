<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_DropdownButtonMenuLayoutsBase extends GD_Template_Processor_MenuLayoutsBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_LAYOUT_MENU_DROPDOWNBUTTON;
	}

	function get_dropdownbtn_class($template_id, $atts) {
	
		return 'btn-group';
	}
	function get_btn_class($template_id, $atts) {
	
		return '';
	}
	function get_dropdownmenu_class($template_id, $atts) {
	
		return '';
	}
	function get_btn_title($template_id, $atts) {
	
		return '';
	}
	function inner_list($template_id, $atts) {
	
		return false;
	}

	function get_template_configuration($template_id, $atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);

		$ret[GD_JS_CLASSES/*'classes'*/]['dropdown-btn'] = $this->get_dropdownbtn_class($template_id, $atts);
		$ret[GD_JS_CLASSES/*'classes'*/]['dropdown-menu'] = $this->get_att($template_id, $atts, 'dropdownmenu-class');
		$ret[GD_JS_CLASSES/*'classes'*/]['btn'] = $this->get_btn_class($template_id, $atts);
		$ret[GD_JS_TITLES/*'titles'*/]['btn'] = $this->get_btn_title($template_id, $atts);

		if ($this->inner_list($template_id, $atts)) {
			$ret['inner-list'] = true;
		}
		
		return $ret;
	}

	function init_atts($template_id, &$atts) {
	
		$ret = parent::get_template_configuration($template_id, $atts);
		$this->append_att($template_id, $atts, 'dropdownmenu-class', $this->get_dropdownmenu_class($template_id, $atts));
		return parent::init_atts($template_id, $atts);
	}
}