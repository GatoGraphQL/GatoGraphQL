<?php
namespace PoPSchema\Users\WP;
use PoP\Hooks\Facades\HooksAPIFacade;

class FunctionAPIHooks {

	public function __construct() {

		HooksAPIFacade::getInstance()->addFilter(
		    'CMSAPI:customposts:query',
		    [$this, 'convertCustomPostsQuery'],
		    10,
		    2
		);
	}

	/**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    public function convertCustomPostsQuery(array $query, array $options): array
    {
        if (isset($query['authors'])) {

            $query['author'] = implode(',', $query['authors']);
            unset($query['authors']);
        }

        return $query;
    }
}

/**
 * Initialize
 */
new FunctionAPIHooks();
