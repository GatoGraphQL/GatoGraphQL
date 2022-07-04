<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataProviderInterface;
use PoPCMSSchema\UserRoles\FunctionAPIFactory;
class CreateUpdateWithCommunityOrganizationProfileMutationResolver extends CreateUpdateWithCommunityProfileMutationResolver
{
    use CreateUpdateOrganizationProfileMutationResolverTrait;

    protected function createupdateuser($user_id, FieldDataProviderInterface $fieldDataProvider): void
    {
        parent::createupdateuser($user_id, $fieldDataProvider);
        $this->commonuserrolesCreateupdateuser($user_id, $fieldDataProvider);

        // Is community?
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        if ($fieldDataProvider->get('is_community')) {
            $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_COMMUNITY);
        } else {
            $cmsuserrolesapi->removeRoleFromUser($user_id, GD_URE_ROLE_COMMUNITY);
        }
    }
}
