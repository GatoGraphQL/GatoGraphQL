<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomSideInfoBlocksBase extends GD_Template_Processor_BlocksBase {

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);
		$this->add_jsmethod($ret, 'closePageSectionOnTabShown');
		return $ret;
	}
}