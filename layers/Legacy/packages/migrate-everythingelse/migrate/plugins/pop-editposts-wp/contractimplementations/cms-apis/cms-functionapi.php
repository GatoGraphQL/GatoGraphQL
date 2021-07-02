<?php

namespace PoP\EditPosts\WP;

class FunctionAPI extends \PoP\EditPosts\FunctionAPI_Base
{
    public function getEditPostLink($post_id)
    {
        return get_edit_post_link($post_id);
    }
    public function getDeletePostLink($post_id)
    {
        return get_delete_post_link($post_id);
    }
    public function getPostEditorContent($post_id)
    {
        $post = get_post($post_id);
        return apply_filters('the_editor_content', $post->post_content);
    }
    public function getAllowedPostTags()
    {
        global $allowedposttags;
        return $allowedposttags;
    }
}

/**
 * Initialize
 */
new FunctionAPI();
