<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACK_LOCATIONPOSTS', PoP_ServerUtils::get_template_definition('messagefeedback-locationposts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_STORIES', PoP_ServerUtils::get_template_definition('messagefeedback-stories'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_ANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('messagefeedback-announcements'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_DISCUSSIONS', PoP_ServerUtils::get_template_definition('messagefeedback-discussions'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_FEATURED', PoP_ServerUtils::get_template_definition('messagefeedback-featured'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_BLOG', PoP_ServerUtils::get_template_definition('messagefeedback-blog'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_THOUGHTS', PoP_ServerUtils::get_template_definition('messagefeedback-thoughts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYLOCATIONPOSTS', PoP_ServerUtils::get_template_definition('messagefeedback-mylocationposts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYSTORIES', PoP_ServerUtils::get_template_definition('messagefeedback-mystories'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('messagefeedback-myannouncements'));
define ('GD_TEMPLATE_MESSAGEFEEDBACK_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('messagefeedback-mydiscussions'));

class GD_Custom_Template_Processor_CustomListMessageFeedbacks extends GD_Template_Processor_MessageFeedbacksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACK_LOCATIONPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_STORIES,
			GD_TEMPLATE_MESSAGEFEEDBACK_ANNOUNCEMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_DISCUSSIONS,
			GD_TEMPLATE_MESSAGEFEEDBACK_FEATURED,
			GD_TEMPLATE_MESSAGEFEEDBACK_BLOG,
			GD_TEMPLATE_MESSAGEFEEDBACK_THOUGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYLOCATIONPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYSTORIES,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYANNOUNCEMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYDISCUSSIONS,
		);
	}

	function get_inner_template($template_id) {

		$inners = array(
			GD_TEMPLATE_MESSAGEFEEDBACK_LOCATIONPOSTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOCATIONPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_STORIES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORIES,
			GD_TEMPLATE_MESSAGEFEEDBACK_ANNOUNCEMENTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_DISCUSSIONS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSIONS,
			GD_TEMPLATE_MESSAGEFEEDBACK_FEATURED => GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED,
			GD_TEMPLATE_MESSAGEFEEDBACK_BLOG => GD_TEMPLATE_MESSAGEFEEDBACKINNER_BLOG,
			GD_TEMPLATE_MESSAGEFEEDBACK_THOUGHTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_THOUGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYLOCATIONPOSTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYLOCATIONPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYSTORIES => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYSTORIES,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYANNOUNCEMENTS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYANNOUNCEMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACK_MYDISCUSSIONS => GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYDISCUSSIONS,
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
new GD_Custom_Template_Processor_CustomListMessageFeedbacks();