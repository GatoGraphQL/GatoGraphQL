<?php

declare(strict_types=1);

namespace PoPSitesWassup\PostLinkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\PostLinkMutations\MutationResolvers\CreatePostLinkMutationResolver;

class CreatePostLinkMutationResolverBridge extends AbstractCreateUpdatePostLinkMutationResolverBridge
{
    public function __construct(
        protected \PoP\Hooks\HooksAPIInterface $hooksAPI,
        protected \PoP\Translation\TranslationAPIInterface $translationAPI,
        protected \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        protected \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
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
        protected CreatePostLinkMutationResolver $CreatePostLinkMutationResolver,
    }

    protected function isUpdate(): bool
    {
        return false;
    }
}
