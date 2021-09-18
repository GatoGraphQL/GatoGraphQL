<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoPSitesWassup\PostLinkMutations\MutationResolvers\UpdatePostLinkMutationResolver;

class UpdatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return UpdatePostLinkMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
