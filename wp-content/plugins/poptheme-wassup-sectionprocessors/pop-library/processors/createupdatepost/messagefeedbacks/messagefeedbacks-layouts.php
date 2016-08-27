<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-project-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-project-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-story-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-story-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-announcement-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-announcement-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-discussion-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-discussion-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-featured-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedback-featured-update'));

class GD_Custom_Template_Processor_CreateUpdatePostFormMessageFeedbackLayouts extends GD_Template_Processor_FormMessageFeedbackLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_UPDATE,
		);
	}

	function get_messages($template_id, $atts) {

		$ret = parent::get_messages($template_id, $atts);

		$names = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_CREATE => __('Project', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_UPDATE => __('Project', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_CREATE => __('Story', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_UPDATE => __('Story', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_CREATE => __('Announcement', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_UPDATE => __('Announcement', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_CREATE => __('Article', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_UPDATE => __('Article', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_CREATE => __('Featured', 'poptheme-wassup-sectionprocessors'),
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_UPDATE => __('Featured', 'poptheme-wassup-sectionprocessors'),
		);
		$creates = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_CREATE,
		);
		$updates = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_UPDATE,
		);

		$name = $names[$template_id];
		$ret['checkpoint-error-header'] = __('Login/Register', 'poptheme-wassup-sectionprocessors');
		if (in_array($template_id, $creates)) {

			$ret['success-header'] = sprintf(
				// __('Yay! Your %s was created/updated successfully!', 'poptheme-wassup-sectionprocessors'),
				__('Yay! Your %s was created successfully!', 'poptheme-wassup-sectionprocessors'),
				$name
			);
			$ret['update-success-header'] = sprintf(
				__('Yay! Your %s was updated successfully!', 'poptheme-wassup-sectionprocessors'),
				$name
			);
			// $ret['success-footer'] = sprintf(
			// 	__('<hr/><div class="text-center"><a href="#" class="pop-reset"><span class="glyphicon glyphicon-refresh"></span> Click here to Reset / Create a new %s</a></div>', 'poptheme-wassup-sectionprocessors'),
			// 	$name
			// );

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please %s to create your %s.', 'poptheme-wassup-sectionprocessors'),
				gd_get_login_html(),
				$name
			);
			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to create your %s.', 'poptheme-wassup-sectionprocessors'),
				get_bloginfo('name'),
				$name
			);
		}
		elseif (in_array($template_id, $updates)) {

			$ret['success-header'] = sprintf(
				__('%s updated successfully.', 'poptheme-wassup-sectionprocessors'),
				$name
			);

			// User not yet logged in
			$ret['usernotloggedin'] = sprintf(
				__('Please <a href="%s">log in</a> to edit your %s.', 'poptheme-wassup-sectionprocessors'),
				wp_login_url(),
				$name
			);
			// User has no access to this functionality (eg: logged in with Facebook)
			$ret['usernoprofileaccess'] = sprintf(
				__('You need a %s account to edit your %s.', 'poptheme-wassup-sectionprocessors'),
				get_bloginfo('name'),
				$name
			);

			// User has no access to this post (eg: editing someone else's post)
			$ret['usercannotedit'] = sprintf(
				__('Ops, it seems you have no rights to edit this %s.', 'poptheme-wassup-sectionprocessors'),
				$name
			);
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CreateUpdatePostFormMessageFeedbackLayouts();