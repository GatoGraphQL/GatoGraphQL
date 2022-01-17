<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\Taxonomies\TypeAPIs;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
interface TaxonomyTypeAPIInterface
{
    public function getCustomPostTypeTaxonomies($post_type);
    public function isTaxonomyHierarchical($taxonomy);
    public function getTaxonomyTerms($taxonomy, $options = []);
    public function getCustomPostTaxonomyTerms($post_id, $taxonomy, $options = []);
    public function setPostTerms($post_id, $tags, $taxonomy, $append = false);
    public function hasTerm($term_id, $taxonomy, $post_id);
    public function getTermLink($term_id);
    public function getTermName($term_id, $taxonomy);
}
