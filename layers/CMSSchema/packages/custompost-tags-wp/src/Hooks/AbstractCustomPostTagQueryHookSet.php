<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagsWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;

use function get_tags;

abstract class AbstractCustomPostTagQueryHookSet extends AbstractHookSet
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
        if (
            isset($query['tag-ids'])
            || isset($query['exclude-tag-ids'])
            || isset($query['tag-slugs'])
            || isset($query['exclude-tag-slugs'])
        ) {
            $query = $this->initializeTaxQuery($query);
            $taxonomy = $query['tag-taxonomy'] ?? 'post_tag';
            unset($query['tag-taxonomy']);

            if (!empty($query['tag-ids'])) {
                $query['tax_query'][] = [
                    'taxonomy' => $taxonomy,
                    'terms' => $query['tag-ids']
                ];
                unset($query['tag-ids']);
            }

            if (!empty($query['exclude-tag-ids'])) {
                $query['tax_query'][] = [
                    'taxonomy' => $taxonomy,
                    'terms' => $query['exclude-tag-ids'],
                    'operator' => 'NOT IN',
                ];
                unset($query['exclude-tag-ids']);
            }

            if (!empty($query['tag-slugs'])) {
                $query['tax_query'][] = [
                    'taxonomy' => $taxonomy,
                    'terms' => $query['tag-slugs'],
                    'field' => 'slug',
                ];
                unset($query['tag-slugs']);
            }

            if (!empty($query['exclude-tag-slugs'])) {
                $query['tax_query'][] = [
                    'taxonomy' => $taxonomy,
                    'terms' => $query['exclude-tag-slugs'],
                    'field' => 'slug',
                    'operator' => 'NOT IN',
                ];
                unset($query['exclude-tag-slugs']);
            }
        }

        $query = $this->convertCustomPostTagQuerySpecialCases($query);

        return $query;
    }

    /**
     * Use the AND relation for all provided query filters
     *
     * @param array<string,mixed> $query
     * @return array<string,mixed>
     */
    protected function initializeTaxQuery(array $query): array
    {
        if (!isset($query['tax_query'])) {
            $query['tax_query'] = [
                [
                    'relation' => 'AND',
                ],
            ];
        } else {
            $query['tax_query'][0]['relation'] = 'AND';
        }
        return $query;
    }

    /**
     * If both "tag" and "tax_query" were set, then the filter will not work for tags.
     * Instead, what it requires is to create a nested taxonomy filtering inside the tax_query,
     * including both the tag and the already existing taxonomy filtering (eg: categories).
     * So make that transformation.
     *
     * @see https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters)
     *
     * @param array<string,mixed> $query
     * @return array<string,mixed>
     */
    private function convertCustomPostTagQuerySpecialCases(array $query): array
    {
        if (!(isset($query['tax_query']) && (isset($query['tag_id']) || isset($query['tag'])))) {
            return $query;
        }

        // Create the tag item in the taxonomy
        $tagIDs = [];
        if (isset($query['tag_id'])) {
            $tagIDs = explode(',', $query['tag_id']);
        }
        if (isset($query['tag'])) {
            /** @var int[] */
            $slugTagIDs = get_tags([
                'taxonomy' => $this->getTagTaxonomy(),
                'fields' => 'ids',
                'slug' => $query['tag']
            ]);
            $tagIDs = [
                ...$tagIDs,
                ...$slugTagIDs
            ];
        }
        if ($tagIDs === []) {
            return $query;
        }

        $tagItem = array(
            'taxonomy' => $this->getTagTaxonomy(),
            'terms' => $tagIDs,
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
                $tagItem,
            );
        }
        $query['tax_query'] = $combinedTaxQuery;

        // The tag arg is not needed anymore
        unset($query['tag_id']);
        unset($query['tag']);

        return $query;
    }

    abstract protected function getTagTaxonomy(): string;
}
