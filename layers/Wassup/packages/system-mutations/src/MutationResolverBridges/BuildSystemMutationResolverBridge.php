<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\BuildSystemMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class BuildSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    protected BuildSystemMutationResolver $buildSystemMutationResolver;

    #[Required]
    final public function autowireBuildSystemMutationResolverBridge(
        BuildSystemMutationResolver $buildSystemMutationResolver,
    ): void {
        $this->buildSystemMutationResolver = $buildSystemMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->buildSystemMutationResolver;
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "build" executed successfully.', 'pop-system');
        ;
    }
}
