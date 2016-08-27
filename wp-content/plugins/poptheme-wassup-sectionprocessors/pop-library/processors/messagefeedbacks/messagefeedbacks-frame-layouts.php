<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECTS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-projects'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORIES', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-stories'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-announcements'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSIONS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-discussions'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-featured'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_BLOG', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-blog'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_THOUGHTS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-thoughts'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPROJECTS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-myprojects'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYSTORIES', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-mystories'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYANNOUNCEMENTS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-myannouncements'));
define ('GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYDISCUSSIONS', PoP_ServerUtils::get_template_definition('layout-messagefeedbackframe-mydiscussions'));

class GD_Custom_Template_Processor_CustomListMessageFeedbackFrameLayouts extends GD_Template_Processor_MessageFeedbackFrameLayoutsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSIONS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_BLOG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_THOUGHTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPROJECTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYSTORIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYANNOUNCEMENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYDISCUSSIONS,
		);
	}

	function get_layout($template_id) {

		$layouts = array(
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_PROJECTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_PROJECTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_STORIES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_STORIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_ANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_ANNOUNCEMENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_DISCUSSIONS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_DISCUSSIONS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_FEATURED => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_FEATURED,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_BLOG => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_BLOG,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_THOUGHTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_THOUGHTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYPROJECTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYPROJECTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYSTORIES => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYSTORIES,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYANNOUNCEMENTS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYANNOUNCEMENTS,
			GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACKFRAME_MYDISCUSSIONS => GD_TEMPLATE_LAYOUT_MESSAGEFEEDBACK_MYDISCUSSIONS,
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
new GD_Custom_Template_Processor_CustomListMessageFeedbackFrameLayouts();