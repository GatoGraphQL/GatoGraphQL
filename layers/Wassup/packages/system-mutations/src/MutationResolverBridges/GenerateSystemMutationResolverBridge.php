<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\GenerateSystemMutationResolver;

class GenerateSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    private ?GenerateSystemMutationResolver $generateSystemMutationResolver = null;

    final public function setGenerateSystemMutationResolver(GenerateSystemMutationResolver $generateSystemMutationResolver): void
    {
        $this->generateSystemMutationResolver = $generateSystemMutationResolver;
    }
    final protected function getGenerateSystemMutationResolver(): GenerateSystemMutationResolver
    {
        if ($this->generateSystemMutationResolver === null) {
            /** @var GenerateSystemMutationResolver */
            $generateSystemMutationResolver = $this->instanceManager->getInstance(GenerateSystemMutationResolver::class);
            $this->generateSystemMutationResolver = $generateSystemMutationResolver;
        }
        return $this->generateSystemMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getGenerateSystemMutationResolver();
    }
    public function getSuccessString(string|int $result_id): ?string
    {
        return $this->__('System action "generate" executed successfully.', 'pop-system');
    }
}
