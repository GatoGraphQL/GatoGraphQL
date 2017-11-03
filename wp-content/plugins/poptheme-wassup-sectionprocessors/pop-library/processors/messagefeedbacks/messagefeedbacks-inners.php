<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-locationposts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORIES', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-stories'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-announcements'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-discussions'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-featured'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_BLOG', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-blog'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_THOUGHTS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-thoughts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYLOCATIONPOSTS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-mylocationposts'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYSTORIES', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-mystories'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYANNOUNCEMENTS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-myannouncements'));
define ('GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYDISCUSSIONS', PoP_TemplateIDUtils::get_template_definition('messagefeedbackinner-mydiscussions'));

class GD_Custom_Template_Processor_CustomListMessageFeedbackInners extends GD_Template_Processor_MessageFeedbackInnersBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOCATIONPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORIES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSIONS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_BLOG,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_THOUGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYLOCATIONPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYSTORIES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYANNOUNCEMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYDISCUSSIONS,
		);
	}

	function get_layouts($template_id) {

		$ret = parent::get_layouts($template_id);

		$layouts = array(
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_LOCATIONPOSTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_LOCATIONPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_STORIES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORIES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_DISCUSSIONS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSIONS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_FEATURED => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_BLOG => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_BLOG,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_THOUGHTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_THOUGHTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYLOCATIONPOSTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYLOCATIONPOSTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYSTORIES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYSTORIES,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYANNOUNCEMENTS,
			GD_TEMPLATE_MESSAGEFEEDBACKINNER_MYDISCUSSIONS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYDISCUSSIONS,
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
new GD_Custom_Template_Processor_CustomListMessageFeedbackInners();