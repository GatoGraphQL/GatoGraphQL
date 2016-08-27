<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_WIDGET_PROJECT_CATEGORIES', PoP_ServerUtils::get_template_definition('widget-project-categories'));
define ('GD_TEMPLATE_WIDGET_DISCUSSION_CATEGORIES', PoP_ServerUtils::get_template_definition('widget-discussion-categories'));

define ('GD_TEMPLATE_WIDGETCOMPACT_PROJECTINFO', PoP_ServerUtils::get_template_definition('widgetcompact-project-info'));
define ('GD_TEMPLATE_WIDGETCOMPACT_DISCUSSIONINFO', PoP_ServerUtils::get_template_definition('widgetcompact-discussion-info'));
define ('GD_TEMPLATE_WIDGETCOMPACT_STORYINFO', PoP_ServerUtils::get_template_definition('widgetcompact-story-info'));
define ('GD_TEMPLATE_WIDGETCOMPACT_ANNOUNCEMENTINFO', PoP_ServerUtils::get_template_definition('widgetcompact-announcement-info'));
define ('GD_TEMPLATE_WIDGETCOMPACT_FEATUREDINFO', PoP_ServerUtils::get_template_definition('widgetcompact-featured-info'));
define ('GD_TEMPLATE_WIDGETCOMPACT_BLOGINFO', PoP_ServerUtils::get_template_definition('widgetcompact-blog-info'));

class GD_Custom_Template_Processor_PostWidgets extends GD_Template_Processor_WidgetsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_WIDGET_PROJECT_CATEGORIES,
			GD_TEMPLATE_WIDGET_DISCUSSION_CATEGORIES,

			GD_TEMPLATE_WIDGETCOMPACT_PROJECTINFO,
			GD_TEMPLATE_WIDGETCOMPACT_DISCUSSIONINFO,
			GD_TEMPLATE_WIDGETCOMPACT_STORYINFO,
			GD_TEMPLATE_WIDGETCOMPACT_ANNOUNCEMENTINFO,
			GD_TEMPLATE_WIDGETCOMPACT_FEATUREDINFO,
			GD_TEMPLATE_WIDGETCOMPACT_BLOGINFO,	
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);
	
		switch ($template_id) {

			case GD_TEMPLATE_WIDGET_PROJECT_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_PROJECT_CATEGORIES;
				break;

			case GD_TEMPLATE_WIDGET_DISCUSSION_CATEGORIES:

				$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_DISCUSSION_CATEGORIES;
				break;

			case GD_TEMPLATE_WIDGETCOMPACT_PROJECTINFO:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_VIEWCOMPONENT_BUTTONWRAPPER_POSTSIDEBARLOCATIONS;
				break;

			case GD_TEMPLATE_WIDGETCOMPACT_DISCUSSIONINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_STORYINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_ANNOUNCEMENTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_FEATUREDINFO:

				if (PoPTheme_Wassup_Utils::add_categories()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_CATEGORIES;
				}
				if (PoPTheme_Wassup_Utils::add_appliesto()) {
					$ret[] = GD_TEMPLATE_LAYOUTWRAPPER_APPLIESTO;
				}
				$ret[] = GD_TEMPLATE_LAYOUT_WIDGETPUBLISHED;
				break;
			
			case GD_TEMPLATE_WIDGETCOMPACT_BLOGINFO:

				$ret[] = GD_TEMPLATE_LAYOUT_WIDGETPUBLISHED;
				break;
		}

		return $ret;
	}

	function get_menu_title($template_id, $atts) {

		$categories = __('Categories', 'poptheme-wassup-sectionprocessors');
		$menu = __('Section links', 'poptheme-wassup-sectionprocessors');

		$titles = array(
			GD_TEMPLATE_WIDGET_PROJECT_CATEGORIES => $categories,
			GD_TEMPLATE_WIDGET_DISCUSSION_CATEGORIES => $categories,
			GD_TEMPLATE_WIDGETCOMPACT_PROJECTINFO => __('Project', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGETCOMPACT_DISCUSSIONINFO => __('Article', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGETCOMPACT_STORYINFO => __('Story', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGETCOMPACT_ANNOUNCEMENTINFO => __('Announcement', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGETCOMPACT_FEATUREDINFO => __('Featured', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_WIDGETCOMPACT_BLOGINFO => __('Blog', 'poptheme-wassup-sectionprocessors'),
		);

		return $titles[$template_id];
	}
	function get_fontawesome($template_id, $atts) {

		$categories = 'fa-info-circle';
		$fontawesomes = array(
			GD_TEMPLATE_WIDGET_PROJECT_CATEGORIES => $categories,
			GD_TEMPLATE_WIDGET_DISCUSSION_CATEGORIES => $categories,
			GD_TEMPLATE_WIDGETCOMPACT_PROJECTINFO => gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_PROJECTS, false),
			GD_TEMPLATE_WIDGETCOMPACT_DISCUSSIONINFO => gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_DISCUSSIONS, false),
			GD_TEMPLATE_WIDGETCOMPACT_STORYINFO => gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_STORIES, false),
			GD_TEMPLATE_WIDGETCOMPACT_ANNOUNCEMENTINFO => gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ANNOUNCEMENTS, false),
			GD_TEMPLATE_WIDGETCOMPACT_FEATUREDINFO => gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_FEATURED, false),
			GD_TEMPLATE_WIDGETCOMPACT_BLOGINFO => gd_navigation_menu_item(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_BLOG, false),
		);

		return $fontawesomes[$template_id];
	}

	function get_body_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_PROJECTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_DISCUSSIONINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_STORYINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_ANNOUNCEMENTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_FEATUREDINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_BLOGINFO:

				return 'list-group list-group-sm';
		}

		return parent::get_body_class($template_id, $atts);
	}
	function get_item_wrapper($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_PROJECTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_DISCUSSIONINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_STORYINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_ANNOUNCEMENTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_FEATUREDINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_BLOGINFO:

				return 'pop-hide-empty list-group-item';
		}

		return parent::get_item_wrapper($template_id, $atts);
	}
	function get_widget_class($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_WIDGETCOMPACT_PROJECTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_DISCUSSIONINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_STORYINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_ANNOUNCEMENTINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_FEATUREDINFO:
			case GD_TEMPLATE_WIDGETCOMPACT_BLOGINFO:

				return 'panel panel-default panel-sm';
		}

		return parent::get_widget_class($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_PostWidgets();