<?php
namespace PoPSchema\CommentMeta\WP;

class FunctionAPI extends \PoPSchema\CommentMeta\FunctionAPI_Base
{
    public function getMetaKey($meta_key)
    {
        return '_'.$meta_key;
    }
    public function getCommentMeta($comment_id, $key, $single = false)
    {
        return get_comment_meta($comment_id, $key, $single);
    }
    public function deleteCommentMeta($comment_id, $meta_key, $meta_value = '')
    {
        return delete_comment_meta($comment_id, $meta_key, $meta_value);
    }
    public function addCommentMeta($comment_id, $meta_key, $meta_value, $unique = false)
    {
        return add_comment_meta($comment_id, $meta_key, $meta_value, $unique);
    }
}

/**
 * Initialize
 */
new FunctionAPI();
