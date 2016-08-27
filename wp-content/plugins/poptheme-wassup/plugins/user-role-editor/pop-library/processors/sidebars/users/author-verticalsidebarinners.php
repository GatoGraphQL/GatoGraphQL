<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-author-organization'));
define ('GD_TEMPLATE_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-author-individual'));

class GD_URE_Template_Processor_CustomVerticalAuthorSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION,
			GD_TEMPLATE_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBARINNER_AUTHOR_ORGANIZATION:

				$ret = array_merge(
					$ret,
					URE_FullUserSidebarSettings::get_components(GD_SIDEBARSECTION_ORGANIZATION)
				);
				break;

			case GD_TEMPLATE_VERTICALSIDEBARINNER_AUTHOR_INDIVIDUAL:

				$ret = array_merge(
					$ret,
					URE_FullUserSidebarSettings::get_components(GD_SIDEBARSECTION_INDIVIDUAL)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_URE_Template_Processor_CustomVerticalAuthorSidebarInners();