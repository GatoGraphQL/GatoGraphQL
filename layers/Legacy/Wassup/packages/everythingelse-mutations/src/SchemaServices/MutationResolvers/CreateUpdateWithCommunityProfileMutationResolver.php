<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\Root\App;
use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateWithCommunityProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    protected function additionalsCreate($user_id, $withArgumentsAST): void
    {
        parent::additionalsCreate($user_id, $withArgumentsAST);
        $this->usercommunitiesAdditionalsCreate($user_id, $withArgumentsAST);
    }
    protected function usercommunitiesAdditionalsCreate($user_id, $withArgumentsAST): void
    {
        App::doAction('gd_custom_createupdate_profile:additionalsCreate', $user_id, $withArgumentsAST);
    }

    protected function createuser(WithArgumentsInterface $withArgumentsAST)
    {
        $user_id = parent::createuser($withArgumentsAST);
        $this->usercommunitiesCreateuser($user_id, $withArgumentsAST);
        return $user_id;
    }
    protected function usercommunitiesCreateuser($user_id, $withArgumentsAST): void
    {
        $communities = $withArgumentsAST->getArgumentValue('communities');
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_COMMUNITIES, $communities);

        // Set the privileges/tags for the new communities
        gdUreUserAddnewcommunities($user_id, $communities);
    }
}
