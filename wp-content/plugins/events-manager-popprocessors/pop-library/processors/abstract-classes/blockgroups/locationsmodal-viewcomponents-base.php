<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_LocationsModalViewComponentBlockGroupsBase extends GD_Template_Processor_ModalViewComponentBlockGroupsBase {

	function init_atts($template_id, &$atts) {
	
		$this->append_att($template_id, $atts, 'class', 'pop-map-modal');
		return parent::init_atts($template_id, $atts);
	}
	function get_dialog_class($template_id) {

		$ret = parent::get_dialog_class($template_id);
		$ret .= ' modal-lg';
		return $ret;
	}
	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'modalMap', $this->get_type($template_id));
		return $ret;
	}
}