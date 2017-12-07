<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomSideInfoBlocksBase extends GD_Template_Processor_BlocksBase {

	function get_pagesection_jsmethod($template_id, $atts) {

		$ret = parent::get_pagesection_jsmethod($template_id, $atts);

		// Comment Leo 07/12/2017: this function closes the sideinfo for GD_TEMPLATE_BLOCK_EMPTYSIDEINFO, and it must take place immediately,
		// or otherwise the sideinfo will show and then disappear a few seconds later and it looks ugly (eg: in Verticals homepage, where there is no sideinfo)
		$this->add_jsmethod($ret, 'closePageSectionOnTabpaneShown', '', false, POP_PROGRESSIVEBOOTING_CRITICAL);
		return $ret;
	}
}