<?php

declare(strict_types=1);

namespace PoPSchema\Users\Conditional\CustomPosts\Hooks;

use PoP\Hooks\AbstractHookSet;

class QueryHooks extends AbstractHookSet
{
    protected function init(): void
    {
        $this->hooksAPI->addAction(
            'CMSAPI:customposts:query',
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
