<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_LOCATIONPOST_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-locationpost-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_LOCATIONPOST_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-locationpost-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_STORY_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-story-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_STORY_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-story-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_ANNOUNCEMENT_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-announcement-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_ANNOUNCEMENT_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-announcement-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_DISCUSSION_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-discussion-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_DISCUSSION_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-discussion-update'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_FEATURED_CREATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-featured-create'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_FEATURED_UPDATE', PoP_TemplateIDUtils::get_template_definition('messagefeedback-featured-update'));

class GD_Custom_Template_Processor_CreateUpdatePostFormMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_LOCATIONPOST_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_LOCATIONPOST_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_STORY_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_STORY_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_DISCUSSION_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_DISCUSSION_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_FEATURED_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_FEATURED_UPDATE,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_LOCATIONPOST_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOCATIONPOST_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_LOCATIONPOST_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOCATIONPOST_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_STORY_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORY_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_STORY_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORY_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_ANNOUNCEMENT_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENT_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_ANNOUNCEMENT_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENT_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_DISCUSSION_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSION_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_DISCUSSION_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSION_UPDATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_FEATURED_CREATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED_CREATE,
			GD_TEMPLATE_MESSAGEFEEDBACK_FEATURED_UPDATE => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED_UPDATE,
		);

		if ($inner = $inners[$template_id]) {

			return $inner;
		}

		return parent::get_inner_template($template_id);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Custom_Template_Processor_CreateUpdatePostFormMessageFeedbacks();