<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateWithCommunityProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    protected function additionalsCreate($user_id, $form_data): void
    {
        parent::additionalsCreate($user_id, $form_data);
        $this->usercommunitiesAdditionalsCreate($user_id, $form_data);
    }
    protected function usercommunitiesAdditionalsCreate($user_id, $form_data): void
    {
        App::doAction('gd_custom_createupdate_profile:additionalsCreate', $user_id, $form_data);
    }

    protected function createuser($form_data)
    {
        $user_id = parent::createuser($form_data);
        $this->usercommunitiesCreateuser($user_id, $form_data);
        return $user_id;
    }
    protected function usercommunitiesCreateuser($user_id, $form_data): void
    {
        $communities = $form_data['communities'];
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES, $communities);

        // Set the privileges/tags for the new communities
        gdUreUserAddnewcommunities($user_id, $communities);
    }
}
