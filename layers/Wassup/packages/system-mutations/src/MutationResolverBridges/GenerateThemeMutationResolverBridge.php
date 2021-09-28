<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\GenerateThemeMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class GenerateThemeMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    protected GenerateThemeMutationResolver $generateThemeMutationResolver;

    #[Required]
    public function autowireGenerateThemeMutationResolverBridge(
        GenerateThemeMutationResolver $generateThemeMutationResolver,
    ) {
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
