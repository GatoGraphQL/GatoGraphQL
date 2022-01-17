<?php

declare(strict_types=1);

namespace PoPCMSSchema\UsersWP\ConditionalOnComponent\CustomPosts\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPCMSSchema\CustomPostsWP\TypeAPIs\CustomPostTypeAPI;
use PoPCMSSchema\Users\ConditionalOnComponent\CustomPosts\Constants\CustomPostOrderBy;

class CustomPostQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            AbstractCustomPostTypeAPI::HOOK_QUERY,
            [$this, 'convertCustomPostsQuery'],
            10,
            2
        );

        App::addFilter(
            CustomPostTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            [$this, 'getOrderByQueryArgValue']
        );
    }

    public function convertCustomPostsQuery(array $query, array $options): array
    {
        if (isset($query['author-ids'])) {
            $query['author__in'] = $query['author-ids'];
            unset($query['author-ids']);
        }
        if (isset($query['author-slug'])) {
            $query['author_name'] = $query['author-slug'];
            unset($query['author-slug']);
        }
        if (isset($query['exclude-author-ids'])) {
            $query['author__not_in'] = $query['exclude-author-ids'];
            unset($query['exclude-author-ids']);
        }
        return $query;
    }

    public function getOrderByQueryArgValue(string $orderBy): string
    {
        return match ($orderBy) {
            CustomPostOrderBy::AUTHOR => 'author',
            default => $orderBy,
        };
    }
}
