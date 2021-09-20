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
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UnrecommendCustomPostMutationResolver;

class UnrecommendCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        MutationResolutionManagerInterface $mutationResolutionManager,
        CustomPostTypeAPIInterface $customPostTypeAPI,
        protected UnrecommendCustomPostMutationResolver $unrecommendCustomPostMutationResolver,
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
        return $this->unrecommendCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You have stopped recommending <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->customPostTypeAPI->getTitle($result_id)
        );
    }
}
