<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\UpdatePostLinkMutationResolver;

class UpdatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    protected UpdatePostLinkMutationResolver $updatePostLinkMutationResolver;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireUpdatePostLinkMutationResolverBridge(
        UpdatePostLinkMutationResolver $updatePostLinkMutationResolver,
    ) {
        $this->updatePostLinkMutationResolver = $updatePostLinkMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->updatePostLinkMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
