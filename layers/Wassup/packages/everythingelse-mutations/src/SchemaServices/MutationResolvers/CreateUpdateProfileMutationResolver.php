<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\Hooks\Facades\HooksAPIFacade;

class CreateUpdateProfileMutationResolver extends CreateUpdateUserMutationResolver
{
    protected function getRole()
    {
        return GD_ROLE_PROFILE;
    }

    protected function validateContent(array &$errors, array $form_data): void
    {
        parent::validateContent($errors, $form_data);

        // Allow to validate the extra inputs
        $hooked_errors = HooksAPIFacade::getInstance()->applyFilters('gd_createupdate_profile:validateContent', array(), $form_data);
        foreach ($hooked_errors as $error) {
            $errors[] = $error;
        }
    }

    protected function additionals($user_id, $form_data)
    {
        parent::additionals($user_id, $form_data);
        HooksAPIFacade::getInstance()->doAction('gd_createupdate_profile:additionals', $user_id, $form_data);
    }
    protected function additionalsUpdate($user_id, $form_data)
    {
        parent::additionalsUpdate($user_id, $form_data);
        HooksAPIFacade::getInstance()->doAction('gd_createupdate_profile:additionalsUpdate', $user_id, $form_data);
    }
    protected function additionalsCreate($user_id, $form_data)
    {
        parent::additionalsCreate($user_id, $form_data);

        HooksAPIFacade::getInstance()->doAction('gd_createupdate_profile:additionalsCreate', $user_id, $form_data);
    }
    protected function createupdateuser($user_id, $form_data)
    {
        parent::createupdateuser($user_id, $form_data);

        // Last Edited: needed for the user thumbprint
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LASTEDITED, POP_CONSTANT_TIME);

        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_DISPLAYEMAIL, $form_data['display_email'], true, true);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, $form_data['short_description'], true);

        // Comment Leo 05/12/2016: LinkedIn is removed from AgendaUrbana, however we don't check for the condition here, so it will still save null
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_FACEBOOK, $form_data['facebook'], true);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_TWITTER, $form_data['twitter'], true);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LINKEDIN, $form_data['linkedin'], true);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_YOUTUBE, $form_data['youtube'], true);
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_INSTAGRAM, $form_data['instagram'], true);
    }
}
