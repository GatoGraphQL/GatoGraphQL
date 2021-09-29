<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SystemMutations\MutationResolvers\GenerateSystemMutationResolver;

class GenerateSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    protected GenerateSystemMutationResolver $generateSystemMutationResolver;

    #[Required]
    public function autowireGenerateSystemMutationResolverBridge(
        GenerateSystemMutationResolver $generateSystemMutationResolver,
    ): void {
        $this->generateSystemMutationResolver = $generateSystemMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->generateSystemMutationResolver;
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "generate" executed successfully.', 'pop-system');
    }
}
