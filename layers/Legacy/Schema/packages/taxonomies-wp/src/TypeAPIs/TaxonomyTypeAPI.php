<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\TaxonomiesWP\TypeAPIs;

use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPCMSSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPCMSSchema\Taxonomies\TypeAPIs\TaxonomyTypeAPIInterface;
use WP_Error;
use WP_Taxonomy;

use function wp_set_post_terms;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class TaxonomyTypeAPI implements TaxonomyTypeAPIInterface
{
    /**
     * @return string[]|WP_Taxonomy[]
     */
    public function getCustomPostTypeTaxonomies(string $post_type): array
    {
        return get_object_taxonomies($post_type);
    }
    public function isTaxonomyHierarchical(string $taxonomy): bool
    {
        $taxonomy_object = get_taxonomy($taxonomy);
        return $taxonomy_object->hierarchical;
    }
    
    /**
     * @return array<string,int>|object[]
     * @param array<string,mixed> $options
     */
    public function getTaxonomyTerms(string $taxonomy, array $options = []): array
    {
        $query = [
            'taxonomy' => $taxonomy,
        ];
        $return_type = $options[QueryOptions::RETURN_TYPE] ?? null;
        if ($return_type == ReturnTypes::IDS) {
            $query['fields'] = 'ids';
        }
        return get_terms($query);
    }
    public function getCustomPostTaxonomyTerms($post_id, $taxonomy, $options = [])
    {
        if ($terms = get_the_terms($post_id, $taxonomy)) {
            if ($return_type = $options[QueryOptions::RETURN_TYPE] ?? null) {
                if ($return_type == ReturnTypes::IDS) {
                    return array_map(
                        function (WP_Taxonomy $term_object): int {
                            return $term_object->term_id;
                        },
                        $terms
                    );
                } elseif ($return_type == ReturnTypes::SLUGS) {
                    return array_map(
                        function (WP_Taxonomy $term_object): string {
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

    public function setPostTerms($post_id, $tags, $taxonomy, $append = false): void
    {
        wp_set_post_terms($post_id, $tags, $taxonomy, $append);
    }

    public function getTermLink($term_id): string|WP_Error
    {
        return get_term_link($term_id);
    }

    public function getTermName($term_id, $taxonomy): string
    {
        $term = get_term($term_id, $taxonomy);
        return $term->name;
    }

    public function hasTerm($term_id, $taxonomy, $post_id): bool
    {
        return has_term($term_id, $taxonomy, $post_id);
    }
}
