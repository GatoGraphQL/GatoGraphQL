<?php
namespace PoP\EditPosts;

interface FunctionAPI
{
    public function getEditPostLink($post_id);
    public function getDeletePostLink($post_id);
    public function getPostEditorContent($post_id);
    public function getAllowedPostTags();
}
