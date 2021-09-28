<?php

declare(strict_types=1);

namespace PoPSitesWassup\StanceMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\StanceMutations\MutationResolvers\CreateStanceMutationResolver;

class CreateStanceMutationResolverBridge extends AbstractCreateUpdateStanceMutationResolverBridge
{
    protected CreateStanceMutationResolver $createStanceMutationResolver;

    #[Required]
    public function autowireCreateStanceMutationResolverBridge(
        CreateStanceMutationResolver $createStanceMutationResolver,
    ) {
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
