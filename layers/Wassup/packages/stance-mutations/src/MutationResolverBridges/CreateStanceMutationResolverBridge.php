<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\StanceMutations\MutationResolvers\CreateStanceMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CreateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    private ?CreateStanceMutationResolver $createStanceMutationResolver = null;

    public function setCreateStanceMutationResolver(CreateStanceMutationResolver $createStanceMutationResolver): void
    {
        $this->createStanceMutationResolver = $createStanceMutationResolver;
    }
    protected function getCreateStanceMutationResolver(): CreateStanceMutationResolver
    {
        return $this->createStanceMutationResolver ??= $this->instanceManager->getInstance(CreateStanceMutationResolver::class);
    }

    //#[Required]
    final public function autowireCreateStanceMutationResolverBridge(
        CreateStanceMutationResolver $createStanceMutationResolver,
    ): void {
        $this->createStanceMutationResolver = $createStanceMutationResolver;
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
