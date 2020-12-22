<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolvers;

class CreateUpdateWithCommunityOrganizationProfileMutationResolver extends CreateUpdateWithCommunityProfileMutationResolver
{
    use CreateUpdateOrganizationProfileMutationResolverTrait;

    protected function createupdateuser($user_id, $form_data)
    {
        parent::createupdateuser($user_id, $form_data);
        $this->commonuserrolesCreateupdateuser($user_id, $form_data);

        // Is community?
        $cmsuserrolesapi = \PoPSchema\UserRoles\FunctionAPIFactory::getInstance();
        if ($form_data['is_community'] ?? null) {
            $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_COMMUNITY);
        } else {
            $cmsuserrolesapi->removeRoleFromUser($user_id, GD_URE_ROLE_COMMUNITY);
        }
    }
}
