<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostsWP\TypeAPIs;

use PoP\Root\App;
class CustomPostTypeAPI extends AbstractCustomPostTypeAPI
{
    public const HOOK_QUERY = __CLASS__ . ':query';

    /**
     * @param array<string, mixed> $query
     * @param array<string, mixed> $options
     * @return array<string, mixed>
     */
    protected function convertCustomPostsQuery(array $query, array $options = []): array
    {
        return App::getHookManager()->applyFilters(
            self::HOOK_QUERY,
            parent::convertCustomPostsQuery($query, $options),
            $options
        );
    }
}
