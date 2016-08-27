<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_EVENT', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-event'));
define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_PASTEVENT', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-pastevent'));

class GD_EM_Template_Processor_CustomVerticalSingleSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_EVENT,
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_PASTEVENT,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_EVENT:

				$ret = array_merge(
					$ret,
					EM_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_EVENT)
				);
				break;

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_PASTEVENT:

				$ret = array_merge(
					$ret,
					EM_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_PASTEVENT)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_EM_Template_Processor_CustomVerticalSingleSidebarInners();