<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

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

    protected function validateContent(array &$errors, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::validateContent($errors, $mutationDataProvider);

        // Allow to validate the extra inputs
        $hooked_errors = App::applyFilters('gd_createupdate_profile:validateContent', array(), $mutationDataProvider);
        foreach ($hooked_errors as $error) {
            $errors[] = $error;
        }
    }

    protected function additionals($user_id, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::additionals($user_id, $mutationDataProvider);
        App::doAction('gd_createupdate_profile:additionals', $user_id, $mutationDataProvider);
    }
    protected function additionalsUpdate($user_id, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::additionalsUpdate($user_id, $mutationDataProvider);
        App::doAction('gd_createupdate_profile:additionalsUpdate', $user_id, $mutationDataProvider);
    }
    protected function additionalsCreate($user_id, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::additionalsCreate($user_id, $mutationDataProvider);

        App::doAction('gd_createupdate_profile:additionalsCreate', $user_id, $mutationDataProvider);
    }
    protected function createupdateuser($user_id, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::createupdateuser($user_id, $mutationDataProvider);

        // Last Edited: needed for the user thumbprint
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LASTEDITED, ComponentModelModuleInfo::get('time'));

        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_DISPLAYEMAIL, $mutationDataProvider->getArgumentValue('display_email'), true, true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, $mutationDataProvider->getArgumentValue('short_description'), true);

        // Comment Leo 05/12/2016: LinkedIn is removed from AgendaUrbana, however we don't check for the condition here, so it will still save null
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_FACEBOOK, $mutationDataProvider->getArgumentValue('facebook'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_TWITTER, $mutationDataProvider->getArgumentValue('twitter'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LINKEDIN, $mutationDataProvider->getArgumentValue('linkedin'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_YOUTUBE, $mutationDataProvider->getArgumentValue('youtube'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_INSTAGRAM, $mutationDataProvider->getArgumentValue('instagram'), true);
    }
}
