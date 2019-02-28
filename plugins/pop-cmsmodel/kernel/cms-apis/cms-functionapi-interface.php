<?php
namespace PoP\CMSModel;

interface FunctionAPI extends \PoP\CMS\FunctionAPI
{

    /**
     * Most functions below are 1 to 1 with WordPress signature
     */
    public function getPostStatus($post_id);
    public function getPosts($query);
    public function getPostTypes($args = array());
    public function getObjectTaxonomies($object, $output = 'names');
    public function getPageByPath($page_path, $output = OBJECT, $post_type = 'page');
    public function getUserdata($user_id);
    public function getComments($query);
    public function getComment($comment_id);
    public function getPost($post_id);
    public function getTag($tag_id);
    public function getNavMenuLocations();
    public function wpGetNavMenuObject($menu_object_id);
    public function addRole($role, $display_name, $capabilities = array());
    public function getTags($query);
    public function getUserBy($field, $value);
    public function getUsers($query);
    public function getTheAuthorMeta($field = '', $user_id = false);
    public function getAuthorPostsUrl($user_id);
    public function wpGetNavMenuItems($menu);
    public function getPostType($post);
    public function getCommentsNumber($post_id);
    public function getTheCategory($post_id = false);
    public function wpGetPostCategories($post_id = 0, $args = array());
    public function wpGetPostTags($post_id = 0, $args = array());
    public function wpGetObjectTerms($object_ids, $taxonomies, $args = array());
    public function getPermalink($post_id);
    public function getTheExcerpt($post_id);
    public function hasPostThumbnail($post_id);
    public function getPostThumbnailId($post_id);
    public function wpGetAttachmentImageSrc($image_id, $size = null);
    public function getEditPostLink($post_id);
    public function getDeletePostLink($post_id);
    public function getTagLink($tag_id);
    public function getPostMimeType($post_thumb_id);
    public function getTheTitle($post = 0);
    public function getBloginfo($show = '', $filter = 'raw');
    public function getSinglePostTitle($post);
    public function getSearchQuery($escaped = true);
    public function getCatTitle($cat);
    public function getTagTitle($tag);
    public function getQueryVar($var, $default = '');
    public function homeUrl($path = '', $scheme = null);
    public function getTerms($args = array());
    public function getQueryFromRequestUri();
    public function isUserLoggedIn();
    public function getCurrentUser();
    public function getCurrentUserId();
    public function getTheUserRole($user_id);
    public function getHomeStaticPage();
    public function logout();
    public function insertComment($comment_data);
    public function getAllowedPostTags();
}
