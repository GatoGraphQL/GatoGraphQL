<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * PageSection Hooks
 *
 * ---------------------------------------------------------------------------------------------------------------*/

class PoPTheme_EM_Processors_ContentHooks {

	function __construct() {

		add_filter(
			'GD_Template_Processor_Contents:inner_template', 
			array($this, 'content_inner'), 
			10, 
			2
		);
	}

	function content_inner($inner, $template_id) {

		if (($template_id == GD_TEMPLATE_CONTENT_SINGLE)) {

			$vars = GD_TemplateManager_Utils::get_vars();
			$post = $vars['global-state']['post']/*global $post*/;
			if ($post->post_type == EM_POST_TYPE_EVENT) {
				
				$event = em_get_event($post->ID, 'post_id');
				if (POPTHEME_WASSUP_EM_CAT_EVENTLINKS && gd_em_has_category($event, POPTHEME_WASSUP_EM_CAT_EVENTLINKS)) {

					return GD_TEMPLATE_CONTENTINNER_LINKSINGLE;
				}
			}
		}
		
		return $inner;
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoPTheme_EM_Processors_ContentHooks();
