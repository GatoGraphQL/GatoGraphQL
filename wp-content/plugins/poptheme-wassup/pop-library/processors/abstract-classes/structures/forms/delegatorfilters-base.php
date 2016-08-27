<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomDelegatorFiltersBase extends GD_Template_Processor_DelegatorFiltersBase {

	function get_block_target($template_id, $atts) {

		// The proxied block is in the Main PageSection
		return '#'.GD_TEMPLATEID_PAGESECTIONID_MAIN.' .pop-pagesection-page.toplevel.active > .pop-block.withfilter';
	}

	function get_classes($template_id, $atts) {

		return 'alert alert-info';
	}

	function init_atts($template_id, &$atts) {

		$this->append_att($template_id, $atts, 'class', $this->get_classes($template_id, $atts));
		return parent::init_atts($template_id, $atts);
	}
}
