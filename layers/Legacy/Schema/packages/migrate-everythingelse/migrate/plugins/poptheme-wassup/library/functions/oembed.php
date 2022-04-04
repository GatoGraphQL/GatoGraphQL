<?php
use PoP\ComponentModel\State\ApplicationState;

// Priority 7: Just before calling in file wp-includes/class-wp-embed.php:
// `\PoP\Root\App::addFilter( 'the_content', array( $this, 'run_shortcode' ), 8 );`
\PoP\Root\App::addFilter('the_content', popwassupOembedUnsupported(...), 7);
function popwassupOembedUnsupported($content)
{

    // Problem in function "shortcode" in file wp-includes/class-wp-embed.php:
    // oEmbed has no support for whenever the global $post is not set, as in a tag (it does the oEmbed logic only after the condition `if ( $post_ID ) {`)
    // however we need to still use oEmbed in this case, since using SimpleView
    // so for these cases, set an external $post_ID, where the cache will be saved
    // Can use the Homepage page
    if (
        defined('POPTHEME_WASSUP_PAGEPLACEHOLDER_OEMBED') && POPTHEME_WASSUP_PAGEPLACEHOLDER_OEMBED &&
        (
            \PoP\Root\App::getState(['routing', 'is-home']) ||
            \PoP\Root\App::getState(['routing', 'is-user']) ||
            \PoP\Root\App::getState(['routing', 'is-custompost']) ||
            \PoP\Root\App::getState(['routing', 'is-tag']) ||
            \PoP\Root\App::getState(['routing', 'is-404'])
        )
    ) {
        $GLOBALS['wp_embed']->post_ID = POPTHEME_WASSUP_PAGEPLACEHOLDER_OEMBED;
    }

    return $content;
}
