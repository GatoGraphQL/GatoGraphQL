<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;


class CreateUpdateWithCommunityProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    protected function additionalsCreate($user_id, $form_data)
    {
        parent::additionalsCreate($user_id, $form_data);
        $this->usercommunitiesAdditionalsCreate($user_id, $form_data);
    }
    protected function usercommunitiesAdditionalsCreate($user_id, $form_data)
    {
        $this->hooksAPI->doAction('gd_custom_createupdate_profile:additionalsCreate', $user_id, $form_data);
    }

    protected function createuser($form_data)
    {
        $user_id = parent::createuser($form_data);
        $this->usercommunitiesCreateuser($user_id, $form_data);
        return $user_id;
    }
    protected function usercommunitiesCreateuser($user_id, $form_data)
    {
        $communities = $form_data['communities'];
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES, $communities);

        // Set the privileges/tags for the new communities
        gdUreUserAddnewcommunities($user_id, $communities);
    }
}
