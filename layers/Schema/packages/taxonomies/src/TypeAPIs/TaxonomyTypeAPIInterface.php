<?php

declare(strict_types=1);

namespace PoPSchema\Taxonomies\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TaxonomyTypeAPIInterface
{
    /**
     * Retrieves the taxonomy name of the object ("post_tag", "category", etc)
     */
    public function getTermTaxonomyName(string | int | object $termObjectOrID): string;
    public function getCustomPostTypeTaxonomies($post_type);
    public function isTaxonomyHierarchical($taxonomy);
    public function getTaxonomyTerms($taxonomy, $options = []);
    public function getCustomPostTaxonomyTerms($post_id, $taxonomy, $options = []);
    public function setPostTerms($post_id, $tags, $taxonomy, $append = false);
    public function hasTerm($term_id, $taxonomy, $post_id);
    public function getTermLink($term_id);
}
