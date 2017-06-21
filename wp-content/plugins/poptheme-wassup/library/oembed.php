<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * oEmbed functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// Priority 7: Just before calling in file wp-includes/class-wp-embed.php:
// `add_filter( 'the_content', array( $this, 'run_shortcode' ), 8 );`
add_filter( 'the_content', 'popwassup_oembed_unsupported', 7);
function popwassup_oembed_unsupported($content) {

	// Problem in function "shortcode" in file wp-includes/class-wp-embed.php:
	// oEmbed has no support for whenever the global $post is not set, as in a tag (it does the oEmbed logic only after the condition `if ( $post_ID ) {`)
	// however we need to still use oEmbed in this case, since using SimpleView
	// so for these cases, set an external $post_ID, where the cache will be saved
	// Can use the Homepage page
	$vars = GD_TemplateManager_Utils::get_vars();
	if (($vars['global-state']['is-home']/*is_home()*/ || $vars['global-state']['is-front-page']/*is_front_page()*/) && defined('POPTHEME_WASSUP_PAGEPLACEHOLDER_HOME')) {

		$GLOBALS['wp_embed']->post_ID = POPTHEME_WASSUP_PAGEPLACEHOLDER_HOME;
	}
	elseif ($vars['global-state']['is-tag']/*is_tag()*/ && defined('POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG')) {

		$GLOBALS['wp_embed']->post_ID = POPTHEME_WASSUP_PAGEPLACEHOLDER_TAG;
	}
	elseif ($vars['global-state']['is-author']/*is_author()*/ && defined('POP_COREPROCESSORS_PAGE_MAIN')) {

		// Comment Leo 25/10/2016: indicating straight POP_COREPROCESSORS_PAGE_MAIN is not right, it should be place inside a placeholder as with the cases above
		// The place where it's defined that this constant is used for the author page, is in function `get_custom_default_hierarchy_page_id`
		$GLOBALS['wp_embed']->post_ID = POP_COREPROCESSORS_PAGE_MAIN;
	}

	return $content;
}