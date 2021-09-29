<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\CreatePostLinkMutationResolver;

class CreatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    protected CreatePostLinkMutationResolver $createPostLinkMutationResolver;

    #[Required]
    public function autowireCreatePostLinkMutationResolverBridge(
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
