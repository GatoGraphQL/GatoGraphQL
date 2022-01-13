<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

abstract class AbstractFollowOrUnfollowUserMutationResolver extends AbstractUserUpdateUserMetaValueMutationResolver
{
    protected function additionals($target_id, $form_data): void
    {
        parent::additionals($target_id, $form_data);
        \PoP\Root\App::getHookManager()->doAction('gd_followunfollow_user', $target_id, $form_data);
    }
}
