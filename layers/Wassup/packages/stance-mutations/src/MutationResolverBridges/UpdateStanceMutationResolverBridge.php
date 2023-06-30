<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\StanceMutations\MutationResolvers\UpdateStanceMutationResolver;

class UpdateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    private ?UpdateStanceMutationResolver $updateStanceMutationResolver = null;

    final public function setUpdateStanceMutationResolver(UpdateStanceMutationResolver $updateStanceMutationResolver): void
    {
        $this->updateStanceMutationResolver = $updateStanceMutationResolver;
    }
    final protected function getUpdateStanceMutationResolver(): UpdateStanceMutationResolver
    {
        if ($this->updateStanceMutationResolver === null) {
            /** @var UpdateStanceMutationResolver */
            $updateStanceMutationResolver = $this->instanceManager->getInstance(UpdateStanceMutationResolver::class);
            $this->updateStanceMutationResolver = $updateStanceMutationResolver;
        }
        return $this->updateStanceMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdateStanceMutationResolver();
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
