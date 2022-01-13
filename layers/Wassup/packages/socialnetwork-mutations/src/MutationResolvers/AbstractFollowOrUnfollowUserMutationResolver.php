<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Root\App;

abstract class AbstractFollowOrUnfollowUserMutationResolver extends AbstractUserUpdateUserMetaValueMutationResolver
{
    protected function additionals($target_id, $form_data): void
    {
        parent::additionals($target_id, $form_data);
        App::getHookManager()->doAction('gd_followunfollow_user', $target_id, $form_data);
    }
}
