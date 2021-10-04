<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\FollowUserMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class FollowUserMutationResolverBridge extends AbstractUserUpdateUserMetaValueMutationResolverBridge
{
    protected FollowUserMutationResolver $followUserMutationResolver;

    #[Required]
    final public function autowireFollowUserMutationResolverBridge(
        FollowUserMutationResolver $followUserMutationResolver,
    ): void {
        $this->followUserMutationResolver = $followUserMutationResolver;
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->followUserMutationResolver;
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
