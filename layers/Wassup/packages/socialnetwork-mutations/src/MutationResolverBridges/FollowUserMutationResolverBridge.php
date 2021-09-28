<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use Symfony\Contracts\Service\Attribute\Required;
use PoP\Hooks\HooksAPIInterface;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoPSchema\Users\TypeAPIs\UserTypeAPIInterface;
use PoPSitesWassup\SocialNetworkMutations\MutationResolvers\FollowUserMutationResolver;

class FollowUserMutationResolverBridge extends AbstractUserUpdateUserMetaValueMutationResolverBridge
{
    protected FollowUserMutationResolver $followUserMutationResolver;

    #[Required]
    public function autowireFollowUserMutationResolverBridge(
        FollowUserMutationResolver $followUserMutationResolver,
    ) {
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
