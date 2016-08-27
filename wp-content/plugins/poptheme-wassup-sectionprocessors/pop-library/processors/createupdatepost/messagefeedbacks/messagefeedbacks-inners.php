<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_PROJECT_CREATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-project-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_PROJECT_UPDATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-project-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORY_CREATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-story-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORY_UPDATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-story-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENT_CREATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-announcement-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENT_UPDATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-announcement-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSION_CREATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-discussion-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSION_UPDATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-discussion-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED_CREATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-featured-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED_UPDATE', PoP_ServerUtils::get_template_definition('messagefeedbackinner-featured-update'));

class GD_Custom_Template_Processor_CreateUpdatePostFormMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_PROJECT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_PROJECT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORY_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORY_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSION_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSION_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED_UPDATE,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_PROJECT_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_PROJECT_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORY_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORY_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORY_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORY_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENT_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENT_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSION_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSION_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSION_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSION_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED_UPDATE,
		);

		if ($layout = $layouts[$template_id]) {

			$ret[] = $layout;
		}

		return $ret;
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CreateUpdatePostFormMessageFeedbackInners();