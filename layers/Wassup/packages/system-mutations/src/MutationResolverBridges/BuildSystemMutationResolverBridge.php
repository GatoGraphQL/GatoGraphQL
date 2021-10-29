<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\BuildSystemMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class BuildSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    private ?BuildSystemMutationResolver $buildSystemMutationResolver = null;

    public function setBuildSystemMutationResolver(BuildSystemMutationResolver $buildSystemMutationResolver): void
    {
        $this->buildSystemMutationResolver = $buildSystemMutationResolver;
    }
    protected function getBuildSystemMutationResolver(): BuildSystemMutationResolver
    {
        return $this->buildSystemMutationResolver ??= $this->instanceManager->getInstance(BuildSystemMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getBuildSystemMutationResolver();
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "build" executed successfully.', 'pop-system');
        ;
    }
}
