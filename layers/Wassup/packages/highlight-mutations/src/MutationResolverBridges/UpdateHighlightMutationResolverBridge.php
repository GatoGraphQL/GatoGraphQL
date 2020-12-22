<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use PoPSitesWassup\HighlightMutations\MutationResolvers\UpdateHighlightMutationResolver;

class UpdateHighlightMutationResolverBridge extends AbstractCreateUpdateHighlightMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return UpdateHighlightMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
