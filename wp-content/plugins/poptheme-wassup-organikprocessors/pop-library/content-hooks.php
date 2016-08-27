<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_OrganikProcessors_ContentHooks {

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
					(POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMLINKS && has_category(POPTHEME_WASSUP_ORGANIKPROCESSORS_CAT_FARMLINKS, $post))
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
new PoPTheme_OrganikProcessors_ContentHooks();
