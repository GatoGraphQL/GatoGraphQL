<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoPSchema\CustomPosts\Facades\CustomPostTypeAPIFacade;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\UpvoteCustomPostMutationResolver;

class UpvoteCustomPostMutationResolverBridge extends AbstractCustomPostUpdateUserMetaValueMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return UpvoteCustomPostMutationResolver::class;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        $customPostTypeAPI = CustomPostTypeAPIFacade::getInstance();
        return sprintf(
            $this->translationAPI->__('You have up-voted <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $customPostTypeAPI->getTitle($result_id)
        );
    }
}
