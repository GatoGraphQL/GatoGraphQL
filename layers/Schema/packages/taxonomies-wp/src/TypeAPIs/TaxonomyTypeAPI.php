<?php

declare(strict_types=1);

namespace PoPSchema\TaxonomiesWP\TypeAPIs;

use PoP\ComponentModel\TypeDataResolvers\APITypeDataResolverTrait;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyTypeAPI implements TaxonomyTypeAPIInterface
{
    use APITypeDataResolverTrait;

    protected function getTermObjectAndID(string | int | object $termObjectOrID): array
    {
        return TaxonomyTypeAPIHelpers::getTermObjectAndID($termObjectOrID);
    }
    /**
     * Retrieves the taxonomy name of the object ("post_tag", "category", etc)
     */
    public function getTermTaxonomyName(string | int | object $termObjectOrID): string
    {
        list(
            $termObject,
            $termObjectID,
        ) = $this->getTermObjectAndID($termObjectOrID);
        return $termObject->taxonomy;
    }

    public function getCustomPostTypeTaxonomies($post_type)
    {
        return get_object_taxonomies($post_type);
    }
    public function isTaxonomyHierarchical($taxonomy)
    {
        $taxonomy_object = get_taxonomy($taxonomy);
        return $taxonomy_object->hierarchical;
    }
    public function getTaxonomyTerms($taxonomy, $options = [])
    {
        $query = [
            'taxonomy' => $taxonomy,
        ];
        $return_type = $options['return-type'] ?? null;
        if ($return_type == ReturnTypes::IDS) {
            $query['fields'] = 'ids';
        }
        return get_terms($query);
    }
    public function getCustomPostTaxonomyTerms($post_id, $taxonomy, $options = [])
    {
        if ($terms = get_the_terms($post_id, $taxonomy)) {
            if ($return_type = $options['return-type'] ?? null) {
                if ($return_type == ReturnTypes::IDS) {
                    return array_map(
                        function ($term_object) {
                            return $term_object->term_id;
                        },
                        $terms
                    );
                } elseif ($return_type == ReturnTypes::SLUGS) {
                    return array_map(
                        function ($term_object) {
                            return $term_object->slug;
                        },
                        $terms
                    );
                }
            }
            return $terms;
        }
        return [];
    }

    public function setPostTerms($post_id, $tags, $taxonomy, $append = false)
    {
        return wp_set_post_terms($post_id, $tags, $taxonomy, $append);
    }

    public function getTermLink($term_id)
    {
        return get_term_link($term_id);
    }

    public function getTermName($term_id, $taxonomy)
    {
        $term = get_term($term_id, $taxonomy);
        return $term->name;
    }

    public function hasTerm($term_id, $taxonomy, $post_id)
    {
        return has_term($term_id, $taxonomy, $post_id);
    }
}
