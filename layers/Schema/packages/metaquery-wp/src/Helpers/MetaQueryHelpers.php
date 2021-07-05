<?php

declare(strict_types=1);

namespace PoPSchema\MetaQueryWP\Helpers;

class MetaQueryHelpers
{
    public static function convertMetaQuery(array $query): array
    {
        if (isset($query['meta-query'])) {
            $query['meta_query'] = $query['meta-query'];
            unset($query['meta-query']);
        }
        return $query;
    }
}
