<?php

declare(strict_types=1);

namespace PoPSchema\CommentsWP\ConditionalOnComponent\Users\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CommentsWP\TypeAPIs\CommentTypeAPI;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addFilter(
            CommentTypeAPI::HOOK_QUERY,
            [$this, 'convertCommentQuery'],
            10,
            2
        );
    }

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
        return $query;
    }
}
