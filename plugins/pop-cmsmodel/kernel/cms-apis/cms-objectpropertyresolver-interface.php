<?php
namespace PoP\CMSModel;

interface ObjectPropertyResolver extends \PoP\CMS\ObjectPropertyResolver
{

    /**
     * Functions to access object properties
     */
    public function getMenuObjectTermId($menu_object);
    public function getPostId($post);
    public function getCommentContent($comment);
    public function getCommentUserId($comment);
    public function getCommentPostId($comment);
    public function getCommentApproved($comment);
    public function getCommentType($comment);
    public function getCommentParent($comment);
    public function getCommentDateGmt($comment);
    public function getCommentId($comment);
    public function getMenuItemTitle($menu_item);
    public function getMenuItemObjectId($menu_item);
    public function getMenuItemUrl($menu_item);
    public function getMenuItemClasses($menu_item);
    public function getMenuItemId($menu_item);
    public function getMenuItemParent($menu_item);
    public function getMenuItemTarget($menu_item);
    public function getMenuItemDescription($menu_item);
    public function getMenuTermId($menu);
    public function getPostType($post);
    public function getCategoryTermId($cat);
    public function getPostTitle($post);
    public function getPostContent($post);
    public function getPostAuthor($post);
    public function getPostDate($post);
    public function getTagName($tag);
    public function getTagSlug($tag);
    public function getTagTermGroup($tag);
    public function getTagTermTaxonomyId($tag);
    public function getTagTaxonomy($tag);
    public function getTagDescription($tag);
    public function getTagParent($tag);
    public function getTagCount($tag);
    public function getTagTermId($tag);
    public function getUserRoles($user);
    public function getUserLogin($user);
    public function getUserNicename($user);
    public function getUserDisplayName($user);
    public function getUserFirstname($user);
    public function getUserLastname($user);
    public function getUserEmail($user);
    public function getUserId($user);
    public function getUserDescription($user);
    public function getUserUrl($user);
    public function getTaxonomyHierarchical($taxonomy);
    public function getTaxonomyName($taxonomy);
}
