<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateWithCommunityProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    protected function additionalsCreate($user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::additionalsCreate($user_id, $fieldDataAccessor);
        $this->usercommunitiesAdditionalsCreate($user_id, $fieldDataAccessor);
    }
    protected function usercommunitiesAdditionalsCreate($user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        App::doAction('gd_custom_createupdate_profile:additionalsCreate', $user_id, $fieldDataAccessor);
    }

    protected function createuser(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $user_id = parent::createuser($fieldDataAccessor);
        $this->usercommunitiesCreateuser($user_id, $fieldDataAccessor);
        return $user_id;
    }
    protected function usercommunitiesCreateuser($user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        $communities = $fieldDataAccessor->get('communities');
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES, $communities);

        // Set the privileges/tags for the new communities
        gdUreUserAddnewcommunities($user_id, $communities);
    }
}
