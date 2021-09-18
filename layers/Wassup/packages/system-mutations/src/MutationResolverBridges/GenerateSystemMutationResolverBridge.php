<?php

declare(strict_types=1);

namespace PoPSitesWassup\SystemMutations\MutationResolverBridges;

use PoPSitesWassup\SystemMutations\MutationResolvers\GenerateSystemMutationResolver;
use PoPSitesWassup\SystemMutations\MutationResolverBridges\AbstractSystemComponentMutationResolverBridge;

class GenerateSystemMutationResolverBridge extends AbstractSystemComponentMutationResolverBridge
{
    public function getMutationResolver(): \PoP\ComponentModel\MutationResolvers\MutationResolverInterface
    {
        return GenerateSystemMutationResolver::class;
    }
    public function getSuccessString(string | int $result_id): ?string
    {
        return $this->translationAPI->__('System action "generate" executed successfully.', 'pop-system');
    }
}
