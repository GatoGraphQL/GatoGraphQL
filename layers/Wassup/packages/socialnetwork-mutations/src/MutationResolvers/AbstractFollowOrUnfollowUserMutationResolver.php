<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;

abstract class AbstractFollowOrUnfollowUserMutationResolver extends AbstractUserUpdateUserMetaValueMutationResolver
{
    protected function additionals($target_id, $form_data)
    {
        parent::additionals($target_id, $form_data);
        HooksAPIFacade::getInstance()->doAction('gd_followunfollow_user', $target_id, $form_data);
    }
}
