<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\GenerateThemeMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class GenerateThemeMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    private ?GenerateThemeMutationResolver $generateThemeMutationResolver = null;

    final public function setGenerateThemeMutationResolver(GenerateThemeMutationResolver $generateThemeMutationResolver): void
    {
        $this->generateThemeMutationResolver = $generateThemeMutationResolver;
    }
    final protected function getGenerateThemeMutationResolver(): GenerateThemeMutationResolver
    {
        return $this->generateThemeMutationResolver ??= $this->instanceManager->getInstance(GenerateThemeMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getGenerateThemeMutationResolver();
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->getTranslationAPI()->__('System action "generate theme" executed successfully.', 'pop-system');
    }
}
