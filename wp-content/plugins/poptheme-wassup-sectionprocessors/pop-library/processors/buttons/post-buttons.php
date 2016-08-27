<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-project-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_PROJECTLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-projectlink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-discussion-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSIONLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-discussionlink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-story-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_STORYLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-storylink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-announcement-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENTLINK_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-announcementlink-create'));
define ('GD_CUSTOM_TEMPLATE_BUTTON_FEATURED_CREATE', PoP_ServerUtils::get_template_definition('custom-postbutton-featured-create'));

class GD_Custom_Template_Processor_Buttons extends GD_Custom_Template_Processor_ButtonsBase {

	function get_templates_to_process() {
	
		return array(
			GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_PROJECTLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSIONLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_STORYLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENTLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_FEATURED_CREATE,
		);
	}

	function get_buttoninner_template($template_id) {

		$buttoninners = array(
			GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_PROJECTLINK_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_PROJECTLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSION_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSIONLINK_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_DISCUSSIONLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_STORY_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_STORYLINK_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_STORYLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENT_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENTLINK_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_ANNOUNCEMENTLINK_CREATE,
			GD_CUSTOM_TEMPLATE_BUTTON_FEATURED_CREATE => GD_CUSTOM_TEMPLATE_BUTTONINNER_FEATURED_CREATE,
		);
		if ($buttoninner = $buttoninners[$template_id]) {

			return $buttoninner;
		}

		return parent::get_buttoninner_template($template_id);
	}

	function get_selectabletypeahead_template($template_id) {

		switch ($template_id) {
					
			case GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_PROJECTLINK_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSIONLINK_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_STORYLINK_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENTLINK_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_FEATURED_CREATE:

				return GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES;
		}

		return parent::get_selectabletypeahead_template($template_id);
	}

	function get_linktarget($template_id, $atts) {

		switch ($template_id) {
					
			case GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_PROJECTLINK_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSIONLINK_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_STORYLINK_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENTLINK_CREATE:
			case GD_CUSTOM_TEMPLATE_BUTTON_FEATURED_CREATE:

				if (PoPTheme_Wassup_Utils::get_addcontent_target() == GD_URLPARAM_TARGET_ADDONS) {
					
					return GD_URLPARAM_TARGET_ADDONS;
				}
				break;
		}

		return parent::get_linktarget($template_id, $atts);
	}

	// function get_btn_class($template_id, $atts) {

	// 	$ret = parent::get_btn_class($template_id, $atts);

	// 	switch ($template_id) {
					
	// 		case GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE:
	// 		case GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE:
	// 		case GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE:
	// 		case GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE:
	// 		case GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE:
	// 		case GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE:
	// 		case GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE:
	// 		case GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE:
	// 		case GD_CUSTOM_TEMPLATE_BUTTON_FEATURED_CREATE:

	// 			$ret .= 'btn btn-lg btn-link btn-compact';
	// 	}

	// 	return $ret;
	// }

	function get_title($template_id) {

		$titles = array(
			GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE => __('Project', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTON_PROJECTLINK_CREATE => __('Project link', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE => __('Article', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSIONLINK_CREATE => __('Article link', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE => __('Story', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTON_STORYLINK_CREATE => __('Story link', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE => __('Announcement', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENTLINK_CREATE => __('Announcement link', 'poptheme-wassup-sectionprocessors'),
			GD_CUSTOM_TEMPLATE_BUTTON_FEATURED_CREATE => __('Featured', 'poptheme-wassup-sectionprocessors'),
		);
		if ($title = $titles[$template_id]) {

			return $title;
		}
		
		return parent::get_title($template_id);
	}

	function get_url_field($template_id) {

		$fields = array(
			GD_CUSTOM_TEMPLATE_BUTTON_PROJECT_CREATE => 'addproject-url',
			GD_CUSTOM_TEMPLATE_BUTTON_PROJECTLINK_CREATE => 'addprojectlink-url',
			GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSION_CREATE => 'adddiscussion-url',
			GD_CUSTOM_TEMPLATE_BUTTON_DISCUSSIONLINK_CREATE => 'adddiscussionlink-url',
			GD_CUSTOM_TEMPLATE_BUTTON_STORY_CREATE => 'addstory-url',
			GD_CUSTOM_TEMPLATE_BUTTON_STORYLINK_CREATE => 'addstorylink-url',
			GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENT_CREATE => 'addannouncement-url',
			GD_CUSTOM_TEMPLATE_BUTTON_ANNOUNCEMENTLINK_CREATE => 'addannouncementlink-url',
			GD_CUSTOM_TEMPLATE_BUTTON_FEATURED_CREATE => 'addfeatured-url',
		);
		if ($field = $fields[$template_id]) {

			return $field;
		}
		
		return parent::get_url_field($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_Buttons();