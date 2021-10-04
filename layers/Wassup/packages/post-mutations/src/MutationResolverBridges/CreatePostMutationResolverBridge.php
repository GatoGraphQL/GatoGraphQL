<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostMutations\MutationResolvers\CreatePostMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class CreatePostMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    protected CreatePostMutationResolver $createPostMutationResolver;

    #[Required]
    final public function autowireCreatePostMutationResolverBridge(
        CreatePostMutationResolver $createPostMutationResolver,
    ): void {
        $this->createPostMutationResolver = $createPostMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->createPostMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
