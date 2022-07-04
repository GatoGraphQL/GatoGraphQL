<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateWithCommunityProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    protected function additionalsCreate($user_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::additionalsCreate($user_id, $fieldDataProvider);
        $this->usercommunitiesAdditionalsCreate($user_id, $fieldDataProvider);
    }
    protected function usercommunitiesAdditionalsCreate($user_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        App::doAction('gd_custom_createupdate_profile:additionalsCreate', $user_id, $fieldDataProvider);
    }

    protected function createuser(FieldDataAccessorInterface $fieldDataProvider)
    {
        $user_id = parent::createuser($fieldDataProvider);
        $this->usercommunitiesCreateuser($user_id, $fieldDataProvider);
        return $user_id;
    }
    protected function usercommunitiesCreateuser($user_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        $communities = $fieldDataProvider->get('communities');
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES, $communities);

        // Set the privileges/tags for the new communities
        gdUreUserAddnewcommunities($user_id, $communities);
    }
}
