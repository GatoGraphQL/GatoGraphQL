<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_FormModalViewComponentBlockGroupsBase extends GD_Template_Processor_ModalViewComponentBlockGroupsBase {

	function show_label($template_id) {

		return false;
	}
	function get_title($template_id) {

		return '';
	}

	function init_atts($template_id, &$atts) {
	
		if ($this->show_label($template_id) === false) {
			$this->append_att($template_id, $atts, 'class', 'nolabel');		
		}
		// if ($this->show_title($template_id) === false) {
		if ($this->get_att($template_id, $atts, 'title') === false) {
			$this->append_att($template_id, $atts, 'class', 'notitle');		
		}
		return parent::init_atts($template_id, $atts);
	}

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'modalForm', $this->get_type($template_id));
		return $ret;
	}
}