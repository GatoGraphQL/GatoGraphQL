<?php
namespace PoPCMSSchema\CommentMeta;

interface FunctionAPI
{
	public function getMetaKey($meta_key);
    public function deleteCommentMeta($comment_id, $meta_key, $meta_value = '');
    public function addCommentMeta($comment_id, $meta_key, $meta_value, $unique = false);
}
