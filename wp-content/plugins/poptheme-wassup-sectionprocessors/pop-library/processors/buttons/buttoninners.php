<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECT_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-project-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECTLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-projectlink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSION_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-discussion-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSIONLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-discussionlink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_STORY_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-story-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_STORYLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-storylink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENT_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-announcement-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENTLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-announcementlink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTONINNER_FEATURED_CREATE', PoP_ServerUtils::get_template_definition('custom-buttoninner-featured-create'));

class GD_Custom_Template_Processor_ButtonInners extends GD_Template_Processor_ButtonInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECTLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSION_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSIONLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_STORY_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_STORYLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENTLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_FEATURED_CREATE,
		);
	}

	function get_fontawesome($template_id, $atts) {

		$icons = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECT_CREATE => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECT,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECTLINK_CREATE => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDPROJECTLINK,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSION_CREATE => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSIONLINK_CREATE => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSIONLINK,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_STORY_CREATE => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORY,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_STORYLINK_CREATE => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDSTORYLINK,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENT_CREATE => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENT,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENTLINK_CREATE => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDANNOUNCEMENTLINK,
			GD_CUSTOM_TEMPLATE_BUTTONINNER_FEATURED_CREATE => POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDFEATURED,
		);
		if ($icon = $icons[$template_id]) {

			return 'fa-fw '.gd_navigation_menu_item($icon, false);
		}
		
		return parent::get_fontawesome($template_id, $atts);
	}

	function get_btn_title($template_id) {
		
		$titles = array(
			GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECT_CREATE => __('Project', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECTLINK_CREATE => __('as Link', 'poptheme-wassup-sectionprocessors'),//__('Project link', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSION_CREATE => __('Article', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSIONLINK_CREATE => __('as Link', 'poptheme-wassup-sectionprocessors'),//__('Article link', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_STORY_CREATE => __('Story', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_STORYLINK_CREATE => __('as Link', 'poptheme-wassup-sectionprocessors'),//__('Story link', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENT_CREATE => __('Announcement', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENTLINK_CREATE => __('as Link', 'poptheme-wassup-sectionprocessors'),//__('Announcement link', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTONINNER_FEATURED_CREATE => __('Featured', 'poptheme-wassup-sectionprocessors'),
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_btn_title($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_ButtonInners();