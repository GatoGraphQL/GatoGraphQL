<?php

namespace PoPSchema\Categories\WP;

use PoP\Hooks\Facades\HooksAPIFacade;

class FunctionAPIHooks {

	public function __construct() {

		HooksAPIFacade::getInstance()->addFilter(
		    'CMSAPI:customposts:query',
		    [$this, 'convertPostsQuery'],
		    10,
		    2
		);
	}

	/**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function convertPostsQuery(array $query, array $options): array
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

        return $query;
    }
}

/**
 * Initialize
 */
new FunctionAPIHooks();
