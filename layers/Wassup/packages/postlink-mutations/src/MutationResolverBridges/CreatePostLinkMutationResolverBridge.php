<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\CreatePostLinkMutationResolver;

class CreatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        protected CreatePostLinkMutationResolver $CreatePostLinkMutationResolver,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $mutationResolutionManager,
        );
    }
    
    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->CreatePostLinkMutationResolver;
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
