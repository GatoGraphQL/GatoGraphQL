<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostMutations\MutationResolvers\CreatePostMutationResolver;

class CreatePostMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    protected CreatePostMutationResolver $createPostMutationResolver;

    #[Required]
    public function autowireCreatePostMutationResolverBridge(
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
