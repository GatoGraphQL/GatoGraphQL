<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_LINK', PoP_TemplateIDUtils::get_template_definition('vertical-sidebarinner-single-link'));
define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT', PoP_TemplateIDUtils::get_template_definition('vertical-sidebarinner-single-highlight'));
define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_WEBPOST', PoP_TemplateIDUtils::get_template_definition('vertical-sidebarinner-single-webpost'));

class Wassup_Template_Processor_CustomVerticalSingleSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_LINK,
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT,
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_WEBPOST,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_LINK:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_WEBPOSTLINK)
				);
				break;

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_HIGHLIGHT:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_HIGHLIGHT)
				);
				break;

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_WEBPOST:

				$ret = array_merge(
					$ret,
					FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_WEBPOST)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new Wassup_Template_Processor_CustomVerticalSingleSidebarInners();