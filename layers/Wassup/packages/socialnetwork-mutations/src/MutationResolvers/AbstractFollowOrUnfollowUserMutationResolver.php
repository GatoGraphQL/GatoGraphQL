<?php

declare(strict_types=1);

namespace PoPSitesWassup\SocialNetworkMutations\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\App;

abstract class AbstractFollowOrUnfollowUserMutationResolver extends AbstractUserUpdateUserMetaValueMutationResolver
{
    protected function additionals($target_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::additionals($target_id, $fieldDataProvider);
        App::doAction('gd_followunfollow_user', $target_id, $fieldDataProvider);
    }
}
