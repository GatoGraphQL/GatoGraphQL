<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_FARM', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-farm'));

class OP_Template_Processor_CustomVerticalSingleSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_FARM,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_FARM:

				$ret = array_merge(
					$ret,
					OP_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_FARM)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OP_Template_Processor_CustomVerticalSingleSidebarInners();