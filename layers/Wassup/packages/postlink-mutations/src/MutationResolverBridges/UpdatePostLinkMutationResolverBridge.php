<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\UpdatePostLinkMutationResolver;

class UpdatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    protected UpdatePostLinkMutationResolver $updatePostLinkMutationResolver;

    #[Required]
    public function autowireUpdatePostLinkMutationResolverBridge(
        UpdatePostLinkMutationResolver $updatePostLinkMutationResolver,
    ): void {
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
