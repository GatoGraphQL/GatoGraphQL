<?php

declare(strict_types=1);

namespace PoPCMSSchema\CommentsWP\ConditionalOnModule\Users\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;
use PoPCMSSchema\Comments\ConditionalOnModule\Users\Constants\CommentOrderBy;
use PoPCMSSchema\CommentsWP\TypeAPIs\CommentTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::addFilter(
            CommentTypeAPI::HOOK_QUERY,
            $this->convertCommentQuery(...),
            10,
            2
        );

        App::addFilter(
            CommentTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            $this->getOrderByQueryArgValue(...)
        );
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $query
     * @param array<string,mixed> $options
     */
    public function convertCommentQuery(array $query, array $options): array
    {
        if (isset($query['userID'])) {
            $query['user_id'] = $query['userID'];
            unset($query['userID']);
        }
        if (isset($query['authors'])) {
            $query['author__in'] = $query['authors'];
            unset($query['authors']);
        }
        if (isset($query['author-ids'])) {
            $query['author__in'] = $query['author-ids'];
            unset($query['author-ids']);
        }
        if (isset($query['exclude-author-ids'])) {
            $query['author__not_in'] = $query['exclude-author-ids'];
            unset($query['exclude-author-ids']);
        }
        if (isset($query['custompost-author-ids'])) {
            $query['post_author__in'] = $query['custompost-author-ids'];
            unset($query['custompost-author-ids']);
        }
        if (isset($query['exclude-custompost-author-ids'])) {
            $query['post_author__not_in'] = $query['exclude-custompost-author-ids'];
            unset($query['exclude-custompost-author-ids']);
        }
        return $query;
    }

    public function getOrderByQueryArgValue(string $orderBy): string
    {
        return match ($orderBy) {
            CommentOrderBy::AUTHOR => 'comment_author',
            default => $orderBy,
        };
    }
}
