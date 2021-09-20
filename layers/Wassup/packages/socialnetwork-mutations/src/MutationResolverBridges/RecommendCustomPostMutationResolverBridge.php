<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSchema\CustomPosts\TypeAPIs\CustomPostTypeAPIInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\RecommendCustomPostMutationResolver;

class RecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        MutationResolutionManagerInterface $mutationResolutionManager,
        CustomPostTypeAPIInterface $customPostTypeAPI,
        protected RecommendCustomPostMutationResolver $recommendCustomPostMutationResolver,
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
            $mutationResolutionManager,
            $customPostTypeAPI,
        );
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->recommendCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have recommended <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->customPostTypeAPI->getTitle($result_id)
        );
    }
}
