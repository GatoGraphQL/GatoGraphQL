<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\App;
use PoP\ComponentModel\ModuleInfo as ComponentModelModuleInfo;
use PoPCMSSchema\UserMeta\Utils;

class CreateUpdateProfileMutationResolver extends CreateUpdateUserMutationResolver
{
    protected function getRole()
    {
        return GD_ROLE_PROFILE;
    }

    protected function validateContent(array &$errors, FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::validateContent($errors, $fieldDataProvider);

        // Allow to validate the extra inputs
        $hooked_errors = App::applyFilters('gd_createupdate_profile:validateContent', array(), $fieldDataProvider);
        foreach ($hooked_errors as $error) {
            $errors[] = $error;
        }
    }

    protected function additionals($user_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::additionals($user_id, $fieldDataProvider);
        App::doAction('gd_createupdate_profile:additionals', $user_id, $fieldDataProvider);
    }
    protected function additionalsUpdate($user_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::additionalsUpdate($user_id, $fieldDataProvider);
        App::doAction('gd_createupdate_profile:additionalsUpdate', $user_id, $fieldDataProvider);
    }
    protected function additionalsCreate($user_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::additionalsCreate($user_id, $fieldDataProvider);

        App::doAction('gd_createupdate_profile:additionalsCreate', $user_id, $fieldDataProvider);
    }
    protected function createupdateuser($user_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::createupdateuser($user_id, $fieldDataProvider);

        // Last Edited: needed for the user thumbprint
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LASTEDITED, ComponentModelModuleInfo::get('time'));

        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_DISPLAYEMAIL, $fieldDataProvider->get('display_email'), true, true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, $fieldDataProvider->get('short_description'), true);

        // Comment Leo 05/12/2016: LinkedIn is removed from AgendaUrbana, however we don't check for the condition here, so it will still save null
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_FACEBOOK, $fieldDataProvider->get('facebook'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_TWITTER, $fieldDataProvider->get('twitter'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LINKEDIN, $fieldDataProvider->get('linkedin'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_YOUTUBE, $fieldDataProvider->get('youtube'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_INSTAGRAM, $fieldDataProvider->get('instagram'), true);
    }
}
