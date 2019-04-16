<?php
namespace PoP\Taxonomy;

interface FunctionAPI
{
	public function getPostTypeTaxonomies($post_type);
	public function isTaxonomyHierarchical($taxonomy);
	public function getTaxonomyTerms($taxonomy, $options = []);
	public function getCategories($query, $options = []);
    public function getPostCategories($post_id, array $options = []);
    public function getPostTags($post_id, array $options = []);
    public function getTag($tag_id);
    public function getTagByName($tag_name);
    public function getTags($query, array $options = []);
    public function getTagLink($tag_id);
    // public function getTagTitle($tag);
    public function getTagName($tag_id);
    public function getTagBase();
    // public function getCategoryTitle($cat);
    // public function getCategoryIds($args = array());
    public function getCategoryName($cat_id);
    public function getCategoryParent($cat_id);
    public function getCategorySlug($cat_id);
    public function getCategoryPath($category_id);
    public function getPostTaxonomyTerms($post_id, $taxonomy, $options = []);
    public function setPostTags($post_id, array $tags, bool $append = false);
    public function setPostTerms($post_id, $tags, $taxonomy, $append = false);
    public function hasCategory($cat_id, $post_id);
    public function hasTerm($term_id, $taxonomy, $post_id);
}
