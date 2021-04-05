<?php

namespace PoPSchema\PostTags\WP;

use PoP\Hooks\Facades\HooksAPIFacade;

class FunctionAPIHooks {

	public function __construct() {

		HooksAPIFacade::getInstance()->addFilter(
		    'CMSAPI:posts:query',
		    [$this, 'convertPostsQuery'],
		    10,
		    2
		);
	}

	public function convertPostsQuery($query, array $options): array
    {
        if (isset($query['tag-ids'])) {

            // Watch out! In WordPress it is a string (either tag ID or comma-separated tag IDs), but in PoP it is an array of IDs!
            $query['tag_id'] = implode(',', $query['tag-ids']);
            unset($query['tag-ids']);
        }
        if (isset($query['tags']) ){

            // Watch out! In WordPress it is a string (either tag slug or comma-separated tag slugs), but in PoP it is an array of slugs!
            $query['tag'] = implode(',', $query['tags']);
            unset($query['tags']);
        }

        $this->convertPostQuerySpecialCases($query);

        return $query;
    }
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

/**
 * Initialize
 */
new FunctionAPIHooks();
