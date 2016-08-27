<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_UserLoggedInsBase extends GD_Template_ProcessorBase {

	function get_template_source($template_id, $atts) {

		return GD_TEMPLATESOURCE_USERLOGGEDIN;
	}

	function add_link($template_id, $atts) {
	
		return false;
	}

	function get_title_top($template_id, $atts) {
	
		return '';
	}

	function get_title_bottom($template_id, $atts) {
	
		return '';
	}

	function get_name_htmlmarkup($template_id, $atts) {
	
		return 'h2';
	}

	function get_template_configuration($template_id, $atts) {

		$ret = parent::get_template_configuration($template_id, $atts);

		global $gd_template_processor_manager;

		if ($this->add_link($template_id, $atts)) {
			$ret['add-link'] = true;
		}

		if ($title_top = $this->get_title_top($template_id, $atts)) {
			$ret[GD_JS_TITLES/*'titles'*/]['top'] = $title_top;
		}
		if ($title_bottom = $this->get_title_bottom($template_id, $atts)) {
			$ret[GD_JS_TITLES/*'titles'*/]['bottom'] = $title_bottom;
		}
	
		$ret['name-htmlmarkup'] = $this->get_name_htmlmarkup($template_id, $atts);
		
		return $ret;
	}
	
	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', 'visible-loggedin');			
		return parent::init_atts($template_id, $atts);
	}
}
