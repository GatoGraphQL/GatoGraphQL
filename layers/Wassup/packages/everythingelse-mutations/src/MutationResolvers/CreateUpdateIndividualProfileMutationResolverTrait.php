<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolvers;

trait CreateUpdateIndividualProfileMutationResolverTrait
{
    protected function createuser($form_data)
    {
        $user_id = parent::createuser($form_data);
        $this->commonuserrolesCreateuser($user_id, $form_data);
        return $user_id;
    }
    protected function commonuserrolesCreateuser($user_id, $form_data)
    {
        // Add the extra User Role
        $cmsuserrolesapi = \PoPSchema\UserRoles\FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_INDIVIDUAL);
    }

    protected function createupdateuser($user_id, $form_data)
    {
        parent::createupdateuser($user_id, $form_data);
        $this->commonuserrolesCreateupdateuser($user_id, $form_data);
    }
    protected function commonuserrolesCreateupdateuser($user_id, $form_data)
    {
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $form_data['individualinterests']);
    }
}
