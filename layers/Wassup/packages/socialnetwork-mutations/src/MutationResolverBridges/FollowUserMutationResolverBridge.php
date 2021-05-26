<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\FollowUserMutationResolver;

class FollowUserMutationResolverBridge extends AbstractUserUpdateUserMetaValueMutationResolverBridge
{
    public function getMutationResolverClass(): string
    {
        return FollowUserMutationResolver::class;
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You are now following <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->userTypeAPI->getUserDisplayName($result_id)
        );
    }
}
