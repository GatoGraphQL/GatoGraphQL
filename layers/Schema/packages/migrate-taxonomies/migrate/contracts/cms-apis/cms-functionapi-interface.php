<?php
namespace PoPSchema\Taxonomies;

interface FunctionAPI
{
    public function getCustomPostTypeTaxonomies($post_type);
    public function isTaxonomyHierarchical($taxonomy);
    public function getTaxonomyTerms($taxonomy, $options = []);
    public function getCustomPostTaxonomyTerms($post_id, $taxonomy, $options = []);
    public function setPostTerms($post_id, $tags, $taxonomy, $append = false);
    public function hasTerm($term_id, $taxonomy, $post_id);
    public function getTermLink($term_id);
}
