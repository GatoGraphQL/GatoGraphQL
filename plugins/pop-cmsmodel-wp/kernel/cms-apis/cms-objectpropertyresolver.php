<?php
namespace PoP\CMSModel\WP;

class ObjectPropertyResolver extends \PoP\CMS\WP\ObjectPropertyResolver implements \PoP\CMSModel\ObjectPropertyResolver
{

    /**
     * Functions to access object properties
     */
    public function getMenuObjectTermId($menu_object)
    {
        return $menu_object->term_id;
    }
    public function getPostId($post)
    {
        return $post->ID;
    }
    public function getCommentContent($comment)
    {
        return $comment->comment_content;
    }
    public function getCommentUserId($comment)
    {
        return $comment->user_id;
    }
    public function getCommentPostId($comment)
    {
        return $comment->comment_post_ID;
    }
    public function getCommentApproved($comment)
    {
        return $comment->comment_approved;
    }
    public function getCommentType($comment)
    {
        return $comment->comment_type;
    }
    public function getCommentParent($comment)
    {
        return $comment->comment_parent;
    }
    public function getCommentDateGmt($comment)
    {
        return $comment->comment_date_gmt;
    }
    public function getCommentId($comment)
    {
        return $comment->comment_ID;
    }
    public function getMenuItemTitle($menu_item)
    {
        return $menu_item->title;
    }
    public function getMenuItemObjectId($menu_item)
    {
        return $menu_item->object_id;
    }
    public function getMenuItemUrl($menu_item)
    {
        return $menu_item->url;
    }
    public function getMenuItemClasses($menu_item)
    {
        return $menu_item->classes;
    }
    public function getMenuItemId($menu_item)
    {
        return $menu_item->ID;
    }
    public function getMenuItemParent($menu_item)
    {
        return $menu_item->menu_item_parent;
    }
    public function getMenuItemTarget($menu_item)
    {
        return $menu_item->target;
    }
    public function getMenuItemDescription($menu_item)
    {
        return $menu_item->description;
    }
    public function getMenuTermId($menu)
    {
        return $menu->term_id;
    }
    public function getPostType($post)
    {
        return $post->post_type;
    }
    public function getCategoryTermId($cat)
    {
        return $cat->term_id;
    }
    public function getPostTitle($post)
    {
        return $post->post_title;
    }
    public function getPostContent($post)
    {
        return $post->post_content;
    }
    public function getPostAuthor($post)
    {
        return $post->post_author;
    }
    public function getPostDate($post)
    {
        return $post->post_date;
    }
    public function getTagName($tag)
    {
        return $tag->name;
    }
    public function getTagSlug($tag)
    {
        return $tag->slug;
    }
    public function getTagTermGroup($tag)
    {
        return $tag->term_group;
    }
    public function getTagTermTaxonomyId($tag)
    {
        return $tag->term_taxonomy_id;
    }
    public function getTagTaxonomy($tag)
    {
        return $tag->taxonomy;
    }
    public function getTagDescription($tag)
    {
        return $tag->description;
    }
    public function getTagParent($tag)
    {
        return $tag->parent;
    }
    public function getTagCount($tag)
    {
        return $tag->count;
    }
    public function getTagTermId($tag)
    {
        return $tag->term_id;
    }
    public function getUserRoles($user)
    {
        return $user->roles;
    }
    public function getUserLogin($user)
    {
        return $user->user_login;
    }
    public function getUserNicename($user)
    {
        return $user->user_nicename;
    }
    public function getUserDisplayName($user)
    {
        return $user->display_name;
    }
    public function getUserFirstname($user)
    {
        return $user->user_firstname;
    }
    public function getUserLastname($user)
    {
        return $user->user_lastname;
    }
    public function getUserEmail($user)
    {
        return $user->user_email;
    }
    public function getUserId($user)
    {
        return $user->ID;
    }
    public function getUserDescription($user)
    {
        return $user->description;
    }
    public function getUserUrl($user)
    {
        return $user->user_url;
    }
    public function getTaxonomyHierarchical($taxonomy)
    {
        return $taxonomy->hierarchical;
    }
    public function getTaxonomyName($taxonomy)
    {
        return $taxonomy->name;
    }
}

/**
 * Initialize
 */
new ObjectPropertyResolver();
