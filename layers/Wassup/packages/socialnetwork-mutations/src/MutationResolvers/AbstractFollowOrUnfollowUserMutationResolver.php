<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Root\App;

abstract class AbstractFollowOrUnfollowUserMutationResolver extends AbstractUserUpdateUserMetaValueMutationResolver
{
    protected function additionals($target_id, WithArgumentsInterface $withArgumentsAST): void
    {
        parent::additionals($target_id, $withArgumentsAST);
        App::doAction('gd_followunfollow_user', $target_id, $withArgumentsAST);
    }
}
