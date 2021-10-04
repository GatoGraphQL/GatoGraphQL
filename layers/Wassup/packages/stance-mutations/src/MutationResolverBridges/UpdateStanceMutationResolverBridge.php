<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\StanceMutations\MutationResolvers\UpdateStanceMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class UpdateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    protected UpdateStanceMutationResolver $updateStanceMutationResolver;

    #[Required]
    final public function autowireUpdateStanceMutationResolverBridge(
        UpdateStanceMutationResolver $updateStanceMutationResolver,
    ): void {
        $this->updateStanceMutationResolver = $updateStanceMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->updateStanceMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
