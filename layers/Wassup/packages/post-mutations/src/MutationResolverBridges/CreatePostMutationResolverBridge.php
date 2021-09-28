<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostMutations\MutationResolvers\CreatePostMutationResolver;

class CreatePostMutationResolverBridge extends AbstractCreateUpdatePostMutationResolverBridge
{
    protected CreatePostMutationResolver $createPostMutationResolver;

    #[Required]
    public function autowireCreatePostMutationResolverBridge(
        CreatePostMutationResolver $createPostMutationResolver,
    ) {
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
