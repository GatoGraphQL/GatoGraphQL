<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_Processor_CustomDelegatorFiltersBase extends GD_Template_Processor_DelegatorFiltersBase {

	function get_block_target($template_id, $atts) {

		// The proxied block is in the Main PageSection
		// Comment Leo 10/12/2016: in the past, we did .active, however that doesn't work anymore for when alt+click to open a link, instead must pick the last added .tab-pane with selector "last-child"
		// Comment Leo 12/01/2017: Actually, for the forms we must use .active instead of :last-child, because the selector is executed
		// on runtime, and not when initializing the JS
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
