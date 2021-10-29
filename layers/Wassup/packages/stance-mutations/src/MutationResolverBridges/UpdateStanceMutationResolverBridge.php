<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\StanceMutations\MutationResolvers\UpdateStanceMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UpdateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    private ?UpdateStanceMutationResolver $updateStanceMutationResolver = null;

    public function setUpdateStanceMutationResolver(UpdateStanceMutationResolver $updateStanceMutationResolver): void
    {
        $this->updateStanceMutationResolver = $updateStanceMutationResolver;
    }
    protected function getUpdateStanceMutationResolver(): UpdateStanceMutationResolver
    {
        return $this->updateStanceMutationResolver ??= $this->instanceManager->getInstance(UpdateStanceMutationResolver::class);
    }

    //#[Required]
    final public function autowireUpdateStanceMutationResolverBridge(
        UpdateStanceMutationResolver $updateStanceMutationResolver,
    ): void {
        $this->updateStanceMutationResolver = $updateStanceMutationResolver;
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
