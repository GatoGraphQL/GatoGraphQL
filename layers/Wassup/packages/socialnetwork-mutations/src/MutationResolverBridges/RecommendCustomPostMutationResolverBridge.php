<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\RecommendCustomPostMutationResolver;

class RecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        protected RecommendCustomPostMutationResolver $RecommendCustomPostMutationResolver,
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
        return $this->RecommendCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return sprintf(
            $this->translationAPI->__('You have recommended <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $customPostTypeAPI->getTitle($result_id)
        );
    }
}
