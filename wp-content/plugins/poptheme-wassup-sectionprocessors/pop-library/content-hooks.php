<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_SectionProcessors_ContentHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_Contents:inner_template', 
			array($this, 'content_inner'), 
			10, 
			2
		);
	}

	function content_inner($inner, $template_id) {

		if ($template_id == GD_TEMPLATE_CONTENT_SINGLE) {

			global $post;
			if (
				$post->post_type == 'post' && (
					(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTLINKS && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_ANNOUNCEMENTLINKS, $post)) ||
					(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONLINKS && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_DISCUSSIONLINKS, $post)) ||
					(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTLINKS && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_PROJECTLINKS, $post)) ||
					(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORYLINKS && has_category(POPTHEME_WASSUP_SECTIONPROCESSORS_CAT_STORYLINKS, $post))
				)) {

				return GD_TEMPLATE_CONTENTINNER_LINKSINGLE;
			}
		}
		
		return $inner;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_SectionProcessors_ContentHooks();
