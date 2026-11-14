<?php

declare(strict_types=1);

namespace PoPWPSchema\Site\Services;

interface URLToEntityResolverInterface
{
    /**
     * The entity a URL of the site points to, as `{"type": ..., "id": ...}`
     * plus `customPostType` for a custom post or a post type archive and
     * `taxonomy` for a taxonomy term, or `null` when the URL is not internal
     * or points to no entity.
     *
     * @return array<string,string|int>|null
     */
    public function resolveURLToEntity(string $url): ?array;
}
