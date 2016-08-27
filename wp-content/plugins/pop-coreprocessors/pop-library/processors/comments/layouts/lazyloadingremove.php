<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SCRIPT_LAZYLOADINGREMOVE', PoP_ServerUtils::get_template_definition('script-lazyloading-remove'));

class GD_Template_Processor_LazyLoadingRemoveLayouts extends GD_Template_Processor_LazyLoadingRemoveLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SCRIPT_LAZYLOADINGREMOVE,
		);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_LazyLoadingRemoveLayouts();