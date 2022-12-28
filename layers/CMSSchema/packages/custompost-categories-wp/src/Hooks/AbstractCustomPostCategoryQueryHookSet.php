<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostCategoriesWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use WP_Term;

abstract class AbstractCustomPostCategoryQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractCustomPostTypeAPI::HOOK_QUERY,
            $this->convertCustomPostsQuery(...),
            10,
            2
        );
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function convertCustomPostsQuery(array $query, array $options): array
    {
        if (isset($query['categories'])) {
            // Watch out! In WordPress it is a string (either category id or comma-separated category ids), but in PoP it is an array of category ids!
            $query['cat'] = implode(',', $query['categories']);
            unset($query['categories']);
        }
        if (isset($query['category-in'])) {
            $query['category__in'] = $query['category-in'];
            unset($query['category-in']);
        }
        if (isset($query['category-not-in'])) {
            $query['category__not_in'] = $query['category-not-in'];
            unset($query['category-not-in']);
        }
        if (isset($query['category-ids'])) {
            if (isset($query['category-taxonomy'])) {
                if (!isset($query['tax_query'])) {
                    $query['tax_query'] = [
                        [
                            'relation' => 'AND',
                        ],
                    ];
                } else {
                    $query['tax_query'][0]['relation'] = 'AND';
                }
                $query['tax_query'][] = [
                    'taxonomy' => $query['category-taxonomy'],
                    'terms' => $query['category-ids']
                ];
                unset($query['category-taxonomy']);
            } else {
                $query['category__in'] = $query['category-ids'];
            }
            unset($query['category-ids']);
        }
        if (isset($query['category-id'])) {
            $query['cat'] = $query['category-id'];
            unset($query['category-id']);
        }

        $query = $this->convertCustomPostCategoryQuerySpecialCases($query);

        return $query;
    }
    /**
     * If both "cat" and "tax_query" were set, then the filter will not work for categories.
     * Instead, what it requires is to create a nested taxonomy filtering inside the tax_query,
     * including both the cat and the already existing taxonomy filtering (eg: tags).
     * So make that transformation.
     *
     * @see https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters)
     *
     * @param array<string,mixed> $query
     * @return array<string,mixed>
     */
    private function convertCustomPostCategoryQuerySpecialCases(array $query): array
    {
        if (!(isset($query['tax_query']) && isset($query['cat']))) {
            return $query;
        }

        $catItem = array(
            'taxonomy' => $this->getCategoryTaxonomy(),
            'terms' => $query['cat'],
            'field' => 'term_id'
        );

        // Replace the current tax_query with a new one
        $taxQuery = $query['tax_query'];
        $combinedTaxQuery = [
            'relation' => 'AND',
        ];
        foreach ($taxQuery as $taxQueryItem) {
            $combinedTaxQuery[] = array(
                $taxQueryItem,
                $catItem,
            );
        }
        $query['tax_query'] = $combinedTaxQuery;

        // The cat arg is not needed anymore
        unset($query['cat']);
        
        return $query;
    }

    abstract protected function getCategoryTaxonomy(): string;
}
