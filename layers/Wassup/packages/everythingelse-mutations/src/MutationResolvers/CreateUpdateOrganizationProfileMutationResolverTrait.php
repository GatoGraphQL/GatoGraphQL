<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolvers;

trait CreateUpdateOrganizationProfileMutationResolverTrait
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
        $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_ORGANIZATION);
    }

    protected function createupdateuser($user_id, $form_data)
    {
        parent::createupdateuser($user_id, $form_data);
        $this->commonuserrolesCreateupdateuser($user_id, $form_data);
    }
    protected function commonuserrolesCreateupdateuser($user_id, $form_data)
    {
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES, $form_data['organizationtypes']);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES, $form_data['organizationcategories']);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTPERSON, $form_data['contact_person'], true);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTNUMBER, $form_data['contact_number'], true);
    }
}
