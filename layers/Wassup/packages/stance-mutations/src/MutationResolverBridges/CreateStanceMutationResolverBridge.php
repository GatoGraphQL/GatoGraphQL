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
        if ($this->createStanceMutationResolver === null) {
            /** @var CreateStanceMutationResolver */
            $createStanceMutationResolver = $this->instanceManager->getInstance(CreateStanceMutationResolver::class);
            $this->createStanceMutationResolver = $createStanceMutationResolver;
        }
        return $this->createStanceMutationResolver;
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
