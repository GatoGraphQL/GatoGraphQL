<?php

// From wp-admin/includes/ajax-actions.php function wp_ajax_send_link_to_editor()
// If the URL is embeddable, then embed it already
// This way, we can allow users to use this function to embed Youtube videos
\PoP\Root\App::getHookManager()->addFilter('file_send_to_editor_url', 'gdEmbedFileSendToEditorUrl', 10000, 3);
function gdEmbedFileSendToEditorUrl($html, $src, $link_text)
{
    if ($embed = wp_oembed_get($src)) {
        return '[embed]'.$src.'[/embed]';
    }

    return $html;
}
