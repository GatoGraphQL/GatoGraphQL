<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use PoPSitesWassup\HighlightMutations\MutationResolvers\UpdateHighlightMutationResolver;

class UpdateHighlightMutationResolverBridge extends AbstractCreateUpdateHighlightMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return UpdateHighlightMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
