<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_FARM', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-farm'));

class OP_Template_Processor_PostMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_FARM,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_FARM:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_FARMINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_PostMultipleSidebarComponents();