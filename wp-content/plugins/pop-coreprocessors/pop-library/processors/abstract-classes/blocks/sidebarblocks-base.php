<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_SidebarBlocksBase extends GD_Template_Processor_BlocksBase {

	function init_atts($template_id, &$atts) {
	
		$this->add_att($template_id, $atts, 'show-filter', false);
		$this->add_att($template_id, $atts, 'filter-hidden', true);
			
		return parent::init_atts($template_id, $atts);
	}
}