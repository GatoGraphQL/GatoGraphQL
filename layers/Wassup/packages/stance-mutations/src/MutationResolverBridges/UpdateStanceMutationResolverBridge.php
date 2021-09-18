<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoPSitesWassup\StanceMutations\MutationResolvers\UpdateStanceMutationResolver;

class UpdateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return UpdateStanceMutationResolver::class;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
