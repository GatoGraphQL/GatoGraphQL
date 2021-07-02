<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\State\ApplicationState;

// Priority 7: Just before calling in file wp-includes/class-wp-embed.php:
// `HooksAPIFacade::getInstance()->addFilter( 'the_content', array( $this, 'run_shortcode' ), 8 );`
HooksAPIFacade::getInstance()->addFilter('the_content', 'popwassupOembedUnsupported', 7);
function popwassupOembedUnsupported($content)
{

    // Problem in function "shortcode" in file wp-includes/class-wp-embed.php:
    // oEmbed has no support for whenever the global $post is not set, as in a tag (it does the oEmbed logic only after the condition `if ( $post_ID ) {`)
    // however we need to still use oEmbed in this case, since using SimpleView
    // so for these cases, set an external $post_ID, where the cache will be saved
    // Can use the Homepage page
    $vars = ApplicationState::getVars();
    if (
        defined('POPTHEME_WASSUP_PAGEPLACEHOLDER_OEMBED') && POPTHEME_WASSUP_PAGEPLACEHOLDER_OEMBED &&
        (
            $vars['routing-state']['is-home'] ||
            $vars['routing-state']['is-user'] ||
            $vars['routing-state']['is-custompost'] ||
            $vars['routing-state']['is-tag'] ||
            $vars['routing-state']['is-404']
        )
    ) {
        $GLOBALS['wp_embed']->post_ID = POPTHEME_WASSUP_PAGEPLACEHOLDER_OEMBED;
    }

    return $content;
}
