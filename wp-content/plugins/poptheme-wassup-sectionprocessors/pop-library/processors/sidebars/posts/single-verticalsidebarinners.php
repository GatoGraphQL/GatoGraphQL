<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_PROJECT', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-project'));
define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_STORY', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-story'));
define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_DISCUSSION', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-discussion'));
define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_BLOG', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-blog'));
define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_ANNOUNCEMENT', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-announcement'));
define ('GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_FEATURED', PoP_ServerUtils::get_template_definition('vertical-sidebarinner-single-featured'));

class GD_Template_Processor_CustomVerticalSingleSidebarInners extends GD_Template_Processor_SidebarInnersBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_PROJECT,
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_STORY,
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_DISCUSSION,
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_BLOG,
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_ANNOUNCEMENT,
			GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_FEATURED,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_PROJECT:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_PROJECT)
				);
				break;

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_STORY:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_STORY)
				);
				break;

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_DISCUSSION:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_DISCUSSION)
				);

				// Add the disclaimer for the discussions
				$ret[] = GD_TEMPLATE_CODE_DISCUSSIONDISCLAIMER;
				break;

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_BLOG:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_BLOG)
				);
				break;

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_FEATURED:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_FEATURED)
				);
				break;

			case GD_TEMPLATE_VERTICALSIDEBARINNER_SINGLE_ANNOUNCEMENT:

				$ret = array_merge(
					$ret,
					Custom_FullViewSidebarSettings::get_components(GD_SIDEBARSECTION_ANNOUNCEMENT)
				);
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomVerticalSingleSidebarInners();