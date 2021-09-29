<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\GenerateThemeMutationResolver;

class GenerateThemeMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    protected GenerateThemeMutationResolver $generateThemeMutationResolver;

    #[Required]
    public function autowireGenerateThemeMutationResolverBridge(
        GenerateThemeMutationResolver $generateThemeMutationResolver,
    ): void {
        $this->generateThemeMutationResolver = $generateThemeMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->generateThemeMutationResolver;
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "generate theme" executed successfully.', 'pop-system');
    }
}
