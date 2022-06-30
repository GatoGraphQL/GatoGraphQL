<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateWithCommunityProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    protected function additionalsCreate($user_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::additionalsCreate($user_id, $mutationDataProvider);
        $this->usercommunitiesAdditionalsCreate($user_id, $mutationDataProvider);
    }
    protected function usercommunitiesAdditionalsCreate($user_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        App::doAction('gd_custom_createupdate_profile:additionalsCreate', $user_id, $mutationDataProvider);
    }

    protected function createuser(MutationDataProviderInterface $mutationDataProvider)
    {
        $user_id = parent::createuser($mutationDataProvider);
        $this->usercommunitiesCreateuser($user_id, $mutationDataProvider);
        return $user_id;
    }
    protected function usercommunitiesCreateuser($user_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        $communities = $mutationDataProvider->getValue('communities');
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES, $communities);

        // Set the privileges/tags for the new communities
        gdUreUserAddnewcommunities($user_id, $communities);
    }
}
