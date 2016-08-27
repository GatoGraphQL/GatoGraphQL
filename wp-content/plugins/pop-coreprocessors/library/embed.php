<?php

/**---------------------------------------------------------------------------------------------------------------
 *
 * Embed functions
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// From wp-admin/includes/ajax-actions.php function wp_ajax_send_link_to_editor()
// If the URL is embeddable, then embed it already
// This way, we can allow users to use this function to embed Youtube videos
add_filter('file_send_to_editor_url', 'gd_embed_file_send_to_editor_url', 10000, 3);
function gd_embed_file_send_to_editor_url($html, $src, $link_text) {

  if ($embed = wp_oembed_get($src)) {

    return '[embed]'.$src.'[/embed]';
  }

  return $html;
}
