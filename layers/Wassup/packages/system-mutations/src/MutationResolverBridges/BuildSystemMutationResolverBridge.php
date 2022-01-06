<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\BuildSystemMutationResolver;

class BuildSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    private ?BuildSystemMutationResolver $buildSystemMutationResolver = null;

    final public function setBuildSystemMutationResolver(BuildSystemMutationResolver $buildSystemMutationResolver): void
    {
        $this->buildSystemMutationResolver = $buildSystemMutationResolver;
    }
    final protected function getBuildSystemMutationResolver(): BuildSystemMutationResolver
    {
        return $this->buildSystemMutationResolver ??= $this->instanceManager->getInstance(BuildSystemMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getBuildSystemMutationResolver();
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->__('System action "build" executed successfully.', 'pop-system');
        ;
    }
}
