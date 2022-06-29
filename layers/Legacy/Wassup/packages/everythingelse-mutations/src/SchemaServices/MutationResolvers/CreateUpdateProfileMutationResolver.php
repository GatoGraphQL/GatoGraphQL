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

    protected function validateContent(array &$errors, WithArgumentsInterface $withArgumentsAST): void
    {
        parent::validateContent($errors, $withArgumentsAST);

        // Allow to validate the extra inputs
        $hooked_errors = App::applyFilters('gd_createupdate_profile:validateContent', array(), $withArgumentsAST);
        foreach ($hooked_errors as $error) {
            $errors[] = $error;
        }
    }

    protected function additionals($user_id, $withArgumentsAST): void
    {
        parent::additionals($user_id, $withArgumentsAST);
        App::doAction('gd_createupdate_profile:additionals', $user_id, $withArgumentsAST);
    }
    protected function additionalsUpdate($user_id, $withArgumentsAST): void
    {
        parent::additionalsUpdate($user_id, $withArgumentsAST);
        App::doAction('gd_createupdate_profile:additionalsUpdate', $user_id, $withArgumentsAST);
    }
    protected function additionalsCreate($user_id, $withArgumentsAST): void
    {
        parent::additionalsCreate($user_id, $withArgumentsAST);

        App::doAction('gd_createupdate_profile:additionalsCreate', $user_id, $withArgumentsAST);
    }
    protected function createupdateuser($user_id, $withArgumentsAST): void
    {
        parent::createupdateuser($user_id, $withArgumentsAST);

        // Last Edited: needed for the user thumbprint
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LASTEDITED, ComponentModelModuleInfo::get('time'));

        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_DISPLAYEMAIL, $withArgumentsAST->getArgumentValue('display_email'), true, true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_SHORTDESCRIPTION, $withArgumentsAST->getArgumentValue('short_description'), true);

        // Comment Leo 05/12/2016: LinkedIn is removed from AgendaUrbana, however we don't check for the condition here, so it will still save null
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_FACEBOOK, $withArgumentsAST->getArgumentValue('facebook'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_TWITTER, $withArgumentsAST->getArgumentValue('twitter'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_LINKEDIN, $withArgumentsAST->getArgumentValue('linkedin'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_YOUTUBE, $withArgumentsAST->getArgumentValue('youtube'), true);
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_INSTAGRAM, $withArgumentsAST->getArgumentValue('instagram'), true);
    }
}
