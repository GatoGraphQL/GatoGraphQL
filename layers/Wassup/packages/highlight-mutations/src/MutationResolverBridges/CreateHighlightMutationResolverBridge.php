<?php

declare(strict_types=1);

namespace PoPSitesWassup\HighlightMutations\MutationResolverBridges;

use PoPSitesWassup\HighlightMutations\MutationResolvers\CreateHighlightMutationResolver;

class CreateHighlightMutationResolverBridge extends AbstractCreateUpdateHighlightMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return CreateHighlightMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
