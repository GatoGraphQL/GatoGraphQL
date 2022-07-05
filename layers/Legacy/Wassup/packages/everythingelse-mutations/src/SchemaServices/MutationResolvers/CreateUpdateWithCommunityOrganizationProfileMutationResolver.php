<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoPCMSSchema\UserRoles\FunctionAPIFactory;
class CreateUpdateWithCommunityOrganizationProfileMutationResolver extends CreateUpdateWithCommunityProfileMutationResolver
{
    use CreateUpdateOrganizationProfileMutationResolverTrait;

    protected function createupdateuser($user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::createupdateuser($user_id, $fieldDataAccessor);
        $this->commonuserrolesCreateupdateuser($user_id, $fieldDataAccessor);

        // Is community?
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        if ($fieldDataAccessor->getValue('is_community')) {
            $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_COMMUNITY);
        } else {
            $cmsuserrolesapi->removeRoleFromUser($user_id, GD_URE_ROLE_COMMUNITY);
        }
    }
}
