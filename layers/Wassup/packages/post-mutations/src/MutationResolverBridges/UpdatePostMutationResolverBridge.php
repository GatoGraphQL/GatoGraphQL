<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use PoPSitesWassup\PostMutations\MutationResolvers\UpdatePostMutationResolver;

class UpdatePostMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return UpdatePostMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
