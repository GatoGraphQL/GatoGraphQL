<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\Root\App;
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoPCMSSchema\UserMeta\Utils;

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
        $hooked_errors = App::applyFilters('gd_createupdate_profile:validateContent', array(), $form_data);
        foreach ($hooked_errors as $error) {
            $errors[] = $error;
        }
    }

    protected function additionals($user_id, $form_data): void
    {
        parent::additionals($user_id, $form_data);
        App::doAction('gd_createupdate_profile:additionals', $user_id, $form_data);
    }
    protected function additionalsUpdate($user_id, $form_data): void
    {
        parent::additionalsUpdate($user_id, $form_data);
        App::doAction('gd_createupdate_profile:additionalsUpdate', $user_id, $form_data);
    }
    protected function additionalsCreate($user_id, $form_data): void
    {
        parent::additionalsCreate($user_id, $form_data);

        App::doAction('gd_createupdate_profile:additionalsCreate', $user_id, $form_data);
    }
    protected function createupdateuser($user_id, $form_data): void
    {
        parent::createupdateuser($user_id, $form_data);

        // Last Edited: needed for the user thumbprint
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LASTEDITED, ComponentModelComponentInfo::get('time'));

        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_DISPLAYEMAIL, $form_data['display_email'], true, true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, $form_data['short_description'], true);

        // Comment Leo 05/12/2016: LinkedIn is removed from AgendaUrbana, however we don't check for the condition here, so it will still save null
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_FACEBOOK, $form_data['facebook'], true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_TWITTER, $form_data['twitter'], true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LINKEDIN, $form_data['linkedin'], true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_YOUTUBE, $form_data['youtube'], true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_INSTAGRAM, $form_data['instagram'], true);
    }
}
