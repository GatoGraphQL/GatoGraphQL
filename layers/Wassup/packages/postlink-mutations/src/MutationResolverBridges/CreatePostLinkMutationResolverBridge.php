<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\CreatePostLinkMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CreatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    protected CreatePostLinkMutationResolver $createPostLinkMutationResolver;

    #[Required]
    final public function autowireCreatePostLinkMutationResolverBridge(
        CreatePostLinkMutationResolver $createPostLinkMutationResolver,
    ): void {
        $this->createPostLinkMutationResolver = $createPostLinkMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->createPostLinkMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
