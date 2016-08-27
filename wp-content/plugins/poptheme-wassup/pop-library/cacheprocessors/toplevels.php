<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class GD_Template_CacheProcessor_CustomHierarchy extends GD_Template_CacheProcessor {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_TOPLEVEL_HOME,
			GD_TEMPLATE_TOPLEVEL_TAG,
			GD_TEMPLATE_TOPLEVEL_PAGE,
			GD_TEMPLATE_TOPLEVEL_SINGLE,
			GD_TEMPLATE_TOPLEVEL_AUTHOR,
			GD_TEMPLATE_TOPLEVEL_404,
		);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_CacheProcessor_CustomHierarchy();
