<?php

declare(strict_types=1);

namespace PoPSchema\Users\ConditionalOnComponent\CustomPosts\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            CustomPostTypeAPI::HOOK_QUERY,
            [$this, 'convertCustomPostsQuery'],
            10,
            2
        );
    }

    public function convertCustomPostsQuery(array $query, array $options): array
    {
        if (isset($query['authors'])) {
            $query['author'] = implode(',', $query['authors']);
            unset($query['authors']);
        }
        return $query;
    }
}
