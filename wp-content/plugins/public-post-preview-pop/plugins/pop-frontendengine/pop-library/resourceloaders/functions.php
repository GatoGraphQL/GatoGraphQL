<?php

// Add the path to the post as a param in the URL, so the ResourceLoader knows what resources to load when previewing a post
add_filter('ppp_preview_link', 'pop_ppp_resourceloader_preview_link', 10, 3);
function pop_ppp_resourceloader_preview_link($link,  $post_id, $post) {

    return add_query_arg(POP_PARAMS_PATH, trailingslashit(GD_TemplateManager_Utils::get_post_path($post_id, true)), $link);
}