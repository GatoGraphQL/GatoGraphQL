<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoPCMSSchema\UserRoles\FunctionAPIFactory;
class CreateUpdateWithCommunityOrganizationProfileMutationResolver extends CreateUpdateWithCommunityProfileMutationResolver
{
    use CreateUpdateOrganizationProfileMutationResolverTrait;

    protected function createupdateuser($user_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::createupdateuser($user_id, $mutationDataProvider);
        $this->commonuserrolesCreateupdateuser($user_id, $mutationDataProvider);

        // Is community?
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        if ($mutationDataProvider->getValue('is_community')) {
            $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_COMMUNITY);
        } else {
            $cmsuserrolesapi->removeRoleFromUser($user_id, GD_URE_ROLE_COMMUNITY);
        }
    }
}
