<?php

declare(strict_types=1);

namespace PoPCMSSchema\Users\ConditionalOnModule\CustomPosts\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;

class QueryHookSet extends AbstractHookSet
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
        if (isset($query['authors'])) {
            $query['author'] = implode(',', $query['authors']);
            unset($query['authors']);
        }
        return $query;
    }
}
