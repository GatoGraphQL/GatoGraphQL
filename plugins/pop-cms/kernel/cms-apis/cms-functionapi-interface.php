<?php
namespace PoP\CMS;

interface FunctionAPI
{

    /**
     * Most functions below are 1 to 1 with WordPress signature
     */
    public function getOption($option);
    public function getPostMeta($post_id, $key = '', $single = false);
    public function deletePostMeta($post_id, $meta_key, $meta_value = '');
    public function addPostMeta($post_id, $meta_key, $meta_value, $unique = false);
    public function getTermMeta($term_id, $key = '', $single = false);
    public function deleteTermMeta($term_id, $meta_key, $meta_value = '');
    public function addTermMeta($term_id, $meta_key, $meta_value, $unique = false);
    public function getUserMeta($user_id, $key = '', $single = false);
    public function deleteUserMeta($user_id, $meta_key, $meta_value = '');
    public function addUserMeta($user_id, $meta_key, $meta_value, $unique = false);
    public function getCommentMeta($comment_id, $key = '', $single = false);
    public function deleteCommentMeta($comment_id, $meta_key, $meta_value = '');
    public function addCommentMeta($comment_id, $meta_key, $meta_value, $unique = false);
    public function redirect($url);
    public function getGlobalQuery();
    public function queryIsHierarchy($query, $hierarchy);
    public function getSiteName();
    public function getErrorClass();
}
