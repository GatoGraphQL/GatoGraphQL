<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_PROJECT', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-project'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_STORY', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-story'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_DISCUSSION', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-discussion'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_BLOG', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-blog'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_ANNOUNCEMENT', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-announcement'));
define ('GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATURED', PoP_ServerUtils::get_template_definition('sidebarmulticomponent-featured'));

class GD_Custom_Template_Processor_PostMultipleSidebarComponents extends GD_Template_Processor_MultiplesBase {
	
	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_PROJECT,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_STORY,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_DISCUSSION,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_BLOG,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_ANNOUNCEMENT,
			GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATURED,
		);
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_ANNOUNCEMENT:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_ANNOUNCEMENTINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_PROJECT:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_PROJECTINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_DISCUSSION:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_DISCUSSIONINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_STORY:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_STORYINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_FEATURED:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_FEATUREDINFO;
				$ret[] = GD_TEMPLATE_WIDGETWRAPPER_REFERENCES;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;

			case GD_TEMPLATE_SIDEBARMULTICOMPONENT_BLOG:

				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_BLOGINFO;
				$ret[] = GD_TEMPLATE_WIDGETCOMPACT_POST_AUTHORS;
				break;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_PostMultipleSidebarComponents();