<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\FollowUserMutationResolver;
use Symfony\Contracts\Service\Attribute\Required;

class FollowUserMutationResolverBridge extends AbstractUserUpdateUserMetaValueMutationResolverBridge
{
    private ?FollowUserMutationResolver $followUserMutationResolver = null;

    public function setFollowUserMutationResolver(FollowUserMutationResolver $followUserMutationResolver): void
    {
        $this->followUserMutationResolver = $followUserMutationResolver;
    }
    protected function getFollowUserMutationResolver(): FollowUserMutationResolver
    {
        return $this->followUserMutationResolver ??= $this->instanceManager->getInstance(FollowUserMutationResolver::class);
    }

    public function getMutationResolver(): MutationResolverInterface
    {
        return $this->getFollowUserMutationResolver();
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return false;
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return sprintf(
            $this->translationAPI->__('You are now following <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $this->getUserTypeAPI()->getUserDisplayName($result_id)
        );
    }
}
