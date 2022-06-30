<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoPCMSSchema\UserRoles\FunctionAPIFactory;
class CreateUpdateWithCommunityOrganizationProfileMutationResolver extends CreateUpdateWithCommunityProfileMutationResolver
{
    use CreateUpdateOrganizationProfileMutationResolverTrait;

    protected function createupdateuser($user_id, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::createupdateuser($user_id, $mutationDataProvider);
        $this->commonuserrolesCreateupdateuser($user_id, $mutationDataProvider);

        // Is community?
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        if ($mutationDataProvider->getArgumentValue('is_community')) {
            $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_COMMUNITY);
        } else {
            $cmsuserrolesapi->removeRoleFromUser($user_id, GD_URE_ROLE_COMMUNITY);
        }
    }
}
