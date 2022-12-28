<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagsWP\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use WP_Term;

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
        if (isset($query['tag-ids'])) {
            if (isset($query['tag-taxonomy'])) {
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
                    'taxonomy' => $query['tag-taxonomy'],
                    'terms' => $query['tag-ids']
                ];
                unset($query['tag-taxonomy']);
            } else {
                $query['tag__in'] = $query['tag-ids'];
            }
            unset($query['tag-ids']);
        }
        if (isset($query['tag-slugs'])) {
            $query['tag'] = implode(',', $query['tag-slugs']);
            unset($query['tag-slugs']);
        }
        if (isset($query['tags'])) {
            // Watch out! In WordPress it is a string (either tag slug or comma-separated tag slugs), but in PoP it is an array of slugs!
            $query['tag'] = implode(',', $query['tags']);
            unset($query['tags']);
        }

        $query = $this->convertCustomPostTagQuerySpecialCases($query);

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
            $tagIDs = [
                ...$tagIDs,
                ...get_tags([
                    'taxonomy' => $this->getTagTaxonomy(),
                    'fields' => 'ids',
                    'slug' => $query['tag']
                ])
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
