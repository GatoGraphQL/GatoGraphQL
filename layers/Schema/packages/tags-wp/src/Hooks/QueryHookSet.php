<?php

declare(strict_types=1);

namespace PoPSchema\TagsWP\Hooks;

use PoP\Hooks\AbstractHookSet;

class QueryHookSet extends AbstractHookSet
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
        if (isset($query['tag-slugs'])) {
            $query['tag'] = implode(',', $query['tag-slugs']);
            unset($query['tag-slugs']);
        }
        if (isset($query['tag-ids'])) {
            $query['tag__in'] = $query['tag-ids'];
            unset($query['tag-ids']);
        }
        return $query;
    }
}
