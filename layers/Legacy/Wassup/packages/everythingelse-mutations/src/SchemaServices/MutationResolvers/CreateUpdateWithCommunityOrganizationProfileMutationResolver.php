<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoPCMSSchema\UserRoles\FunctionAPIFactory;
class CreateUpdateWithCommunityOrganizationProfileMutationResolver extends CreateUpdateWithCommunityProfileMutationResolver
{
    use CreateUpdateOrganizationProfileMutationResolverTrait;

    protected function createupdateuser($user_id, WithArgumentsInterface $withArgumentsAST): void
    {
        parent::createupdateuser($user_id, $withArgumentsAST);
        $this->commonuserrolesCreateupdateuser($user_id, $withArgumentsAST);

        // Is community?
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        if ($withArgumentsAST->getArgumentValue('is_community')) {
            $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_COMMUNITY);
        } else {
            $cmsuserrolesapi->removeRoleFromUser($user_id, GD_URE_ROLE_COMMUNITY);
        }
    }
}
