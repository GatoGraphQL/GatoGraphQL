<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolverBridges;

use PoP\Translation\Facades\TranslationAPIFacade;
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

    /**
     * @param mixed $result_id Maybe an int, maybe a string
     */
    public function getSuccessString($result_id): ?string
    {
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        return sprintf(
            TranslationAPIFacade::getInstance()->__('You are now following <em><strong>%s</strong></em>.', 'pop-coreprocessors'),
            $cmsusersapi->getUserDisplayName($result_id)
        );
    }
}

