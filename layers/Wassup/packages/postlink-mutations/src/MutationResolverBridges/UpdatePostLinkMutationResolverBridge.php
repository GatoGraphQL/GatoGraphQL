<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\UpdatePostLinkMutationResolver;

class UpdatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    private ?UpdatePostLinkMutationResolver $updatePostLinkMutationResolver = null;

    final public function setUpdatePostLinkMutationResolver(UpdatePostLinkMutationResolver $updatePostLinkMutationResolver): void
    {
        $this->updatePostLinkMutationResolver = $updatePostLinkMutationResolver;
    }
    final protected function getUpdatePostLinkMutationResolver(): UpdatePostLinkMutationResolver
    {
        /** @var UpdatePostLinkMutationResolver */
        return $this->updatePostLinkMutationResolver ??= $this->instanceManager->getInstance(UpdatePostLinkMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getUpdatePostLinkMutationResolver();
    }

    protected function isUpdate(): bool
    {
        return true;
    }
}
