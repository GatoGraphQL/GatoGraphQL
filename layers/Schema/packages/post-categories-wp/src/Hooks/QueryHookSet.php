<?php

declare(strict_types=1);

namespace PoPSchema\PostCategoriesWP\Hooks;

use PoP\Hooks\AbstractHookSet;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            'CMSAPI:posts:query',
            [$this, 'convertPostsQuery'],
            10,
            2
        );
    }

	public function convertPostsQuery($query, array $options): array
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
            // Watch out! In WordPress it is a string (either tag ID or comma-separated tag IDs), but in PoP it is an array of IDs!
            $query['category__in'] = $query['category-ids'];
            unset($query['category-ids']);
        }
        if (isset($query['category-id'])) {
            $query['cat'] = $query['category-id'];
            unset($query['category-id']);
        }

        /**
         * @todo Check and adapt this function for categories
         */
        // $this->convertPostQuerySpecialCases($query);

        return $query;
    }
    /**
     * @todo Check and adapt this function for categories
     */
    private function convertPostQuerySpecialCases(&$query)
    {
        // If both "tag" and "tax_query" were set, then the filter will not work for tags
        // Instead, what it requires is to create a nested taxonomy filtering inside the tax_query,
        // including both the tag and the already existing taxonomy filtering (eg: categories)
        // So make that transformation (https://codex.wordpress.org/Class_Reference/WP_Query#Taxonomy_Parameters)
        if ((isset($query['tag_id']) || isset($query['tag'])) && isset($query['tax_query'])) {
            // Create the tag item in the taxonomy
            $tag_slugs = [];
            if (isset($query['tag_id'])) {
                foreach (explode(',', $query['tag_id']) as $tag_id) {
                    $tag = get_tag($tag_id);
                    $tag_slugs[] = $tag->slug;
                }
            }
            if (isset($query['tag'])) {
                $tag_slugs = array_merge(
                    $tag_slugs,
                    explode(',', $query['tag'])
                );
            }
            $tag_item = array(
                'taxonomy' => 'post_tag',
                'terms' => $tag_slugs,
                'field' => 'slug'
            );

            // Will replace the current tax_query with a new one
            $tax_query = $query['tax_query'];
            $new_tax_query = array(
                'relation' => 'AND',//$tax_query['relation']
            );
            unset($tax_query['relation']);
            foreach ($tax_query as $tax_item) {
                $new_tax_query[] = array(
                    // 'relation' => 'AND',
                    $tax_item,
                    $tag_item,
                );
            }
            $query['tax_query'] = $new_tax_query;

            // The tag arg is not needed anymore
            unset($query['tag_id']);
            unset($query['tag']);
        }
    }
}
