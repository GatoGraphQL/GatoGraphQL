<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UndoUpvoteCustomPostMutationResolver;

class UndoUpvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    public function __construct(
        \PoP\Hooks\HooksAPIInterface $hooksAPI,
        \PoP\Translation\TranslationAPIInterface $translationAPI,
        \PoP\ComponentModel\Instances\InstanceManagerInterface $instanceManager,
        \PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface $mutationResolutionManager,
        protected UndoUpvoteCustomPostMutationResolver $UndoUpvoteCustomPostMutationResolver,
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
        return $this->UndoUpvoteCustomPostMutationResolver;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return sprintf(
            $this->translationAPI->__('You have stopped up-voting <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $customPostTypeAPI->getTitle($result_id)
        );
    }
}
