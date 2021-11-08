<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\StanceMutations\MutationResolvers\CreateStanceMutationResolver;

class CreateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    private ?CreateStanceMutationResolver $createStanceMutationResolver = null;

    final public function setCreateStanceMutationResolver(CreateStanceMutationResolver $createStanceMutationResolver): void
    {
        $this->createStanceMutationResolver = $createStanceMutationResolver;
    }
    final protected function getCreateStanceMutationResolver(): CreateStanceMutationResolver
    {
        return $this->createStanceMutationResolver ??= $this->instanceManager->getInstance(CreateStanceMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getCreateStanceMutationResolver();
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
