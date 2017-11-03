<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBARINNER_TAG', PoP_TemplateIDUtils::get_template_definition('vertical-sidebarinner-tag'));

class Wassup_Template_Processor_CustomVerticalTagSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBARINNER_TAG,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBARINNER_TAG:

				$ret = array_merge(
					$ret,
					FullTagSidebarSettings::get_components(GD_SIDEBARSECTION_TAG)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_CustomVerticalTagSidebarInners();