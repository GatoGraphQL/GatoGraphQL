<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\StanceMutations\MutationResolvers\CreateStanceMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CreateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    protected CreateStanceMutationResolver $createStanceMutationResolver;

    #[Required]
    final public function autowireCreateStanceMutationResolverBridge(
        CreateStanceMutationResolver $createStanceMutationResolver,
    ): void {
        $this->createStanceMutationResolver = $createStanceMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->createStanceMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
