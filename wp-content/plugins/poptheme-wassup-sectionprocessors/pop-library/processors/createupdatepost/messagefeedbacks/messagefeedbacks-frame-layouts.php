<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/


define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECT_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-project-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECT_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-project-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORY_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-story-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORY_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-story-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENT_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-announcement-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENT_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-announcement-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSION_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-discussion-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSION_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-discussion-update'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED_CREATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-featured-create'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED_UPDATE', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-featured-update'));

class GD_Custom_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORY_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORY_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSION_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSION_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED_UPDATE,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECT_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECT_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORY_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORY_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORY_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENT_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENT_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSION_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSION_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSION_UPDATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED_CREATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_CREATE,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED_UPDATE => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED_UPDATE,
		);

		if ($layout = $layouts[$template_id]) {

			return $layout;
		}

		return parent::get_layout($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CreateUpdatePostFormMessageFeedbackFrameLayouts();