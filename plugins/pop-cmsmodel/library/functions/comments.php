<?php

function gdCommentsApplyFilters($comment_content, $embed = false)
{

    // Cannot call filter "the_content" since it also executes unwanted filters
    // So create new filter "gd_fieldprocessor_comment:content" and selectively
    // from WP and all plugins copy the needed filters
    // If needed, Do the autoembed before executing all other shortcodes (check wp-includes/class-wp-embed.php)
    if ($embed) {
        $wp_embed = $GLOBALS['wp_embed'];
        $comment_content = $wp_embed->autoembed($comment_content);
    }
    return \PoP\CMS\HooksAPI_Factory::getInstance()->applyFilters('gd_comments_content', $comment_content);
}
