<?php
namespace PoP\CMSModel;

interface FunctionAPI extends \PoP\CMS\FunctionAPI
{
    // Pages
    public function getHomeStaticPage();
    public function getPageIdByPath($page_path);
    // public function getTheTitle($page_id);
    // public function getPageStatus($page_id);
    // public function getPages($query, array $options = []);
    // public function getPage($page_id);
    // public function getPermalink($page_id);
    // public function getTheExcerpt($page_id);
    // public function getEditPageLink($page_id);
    // public function getDeletePageLink($page_id);
    // public function getTheTitle($page_id);
    // public function insertPage($page_data);
    // public function updatePage($page_data);
    // public function getPageSlug($post_id);
    // public function getPageTitle($post_id);
    // public function getPageContent($post_id);
    // public function getPageEditorContent($post_id);
    // public function getBasicPageContent($post_id);
    // public function getPageCount($query);
    // public function getAllowedPageTags();
    // public function getExcerptMore();
    // public function getExcerptLength();

    // Posts
    public function getPostStatus($post_id);
    public function getPosts($query, array $options = []);
    public function getPostTypes($query = array());
    public function getPost($post_id);
    public function getPostType($post);
    public function getPermalink($post_id);
    public function getTheExcerpt($post_id);
    public function getEditPostLink($post_id);
    public function getDeletePostLink($post_id);
    public function getTheTitle($post_id);
    // public function getSinglePostTitle($post);
    public function insertPost($post_data);
    public function updatePost($post_data);
    public function getPostSlug($post_id);
    public function getPostTitle($post_id);
    public function getPostContent($post_id);
    public function getPostEditorContent($post_id);
    public function getBasicPostContent($post_id);
    public function getPostCount($query);
    public function getAllowedPostTags();
    public function getExcerptMore();
    public function getExcerptLength();
    
    // Comments
    public function getComments($query, array $options = []);
    public function getComment($comment_id);
    public function insertComment($comment_data);
    public function getCommentsNumber($post_id);

    // Menus
    public function getNavMenuLocations();
    public function getNavigationMenuObject($menu_object_id);
    public function getNavigationMenuItems($menu);
    public function getMenuItemTitle($menu_item);
    
    // Media
    public function getAttachmentImageSrc($image_id, $size = null);
    public function getPostMimeType($post_thumb_id);

    // PostMedia
    public function hasPostThumbnail($post_id);
    public function getPostThumbnailId($post_id);

    // PageMedia
    // public function hasPageThumbnail($page_id);
    // public function getPageThumbnailId($page_id);
    
    // Taxonomies
    public function getTrendingHashtagIds($days, $number, $offset);

    // Users
    public function getUserById($value);
    public function getUserByEmail($value);
    public function getUserBySlug($value);
    public function getUserByLogin($value);
    public function getUsers($query = array(), array $options = []);
    public function getTheAuthorMeta($field, $user_id);
    public function getUserDisplayName($user_id);
    public function getUserEmail($user_id);
    public function getUserFirstname($user_id);
    public function getUserLastname($user_id);
    public function getUserLogin($user_id);
    public function getUserDescription($user_id);
    public function getUserRegistrationDate($user_id);
    public function getAuthorPostsUrl($user_id);
    public function isUserLoggedIn();
    public function getCurrentUser();
    public function getCurrentUserId();
    public function insertUser($user_data);
    public function updateUser($user_data);
    public function login($credentials);
    public function logout();
    public function checkPassword($password, $hash);
    public function checkPasswordResetKey($key, $login);
    public function resetPassword($user, $pwd);
    public function getPasswordResetKey($user_data);
    public function getLoginURL();
    public function getLogoutURL();
    public function getLostPasswordURL();
    public function getAuthorBase();
    
    // User Roles
    public function addRole($role, $display_name, $capabilities = array());
    public function addRoleToUser($user_id, $role);
    public function removeRoleFromUser($user_id, $role);
    public function getUserRoles($user_id);
    public function getTheUserRole($user_id);
    public function userCan($user_id, $capability);
    public function currentUserCan($capability);
}
