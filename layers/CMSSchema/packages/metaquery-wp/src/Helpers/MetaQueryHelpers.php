<?php

declare(strict_types=1);

namespace PoPCMSSchema\MetaQueryWP\Helpers;

class MetaQueryHelpers
{
    /**
     * @return array<string,mixed>
     */
    public static function convertMetaQuery(array $query): array
    {
        if (isset($query['meta-query'])) {
            $query['meta_query'] = $query['meta-query'];
            unset($query['meta-query']);
        }
        return $query;
    }
}
