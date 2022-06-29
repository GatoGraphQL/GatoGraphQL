<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoPCMSSchema\UserMeta\Utils;
use PoPCMSSchema\UserRoles\FunctionAPIFactory;
trait CreateUpdateOrganizationProfileMutationResolverTrait
{
    protected function createuser(WithArgumentsInterface $withArgumentsAST)
    {
        $user_id = parent::createuser($withArgumentsAST);
        $this->commonuserrolesCreateuser($user_id, $withArgumentsAST);
        return $user_id;
    }
    protected function commonuserrolesCreateuser($user_id, WithArgumentsInterface $withArgumentsAST)
    {
        // Add the extra User Role
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_ORGANIZATION);
    }

    protected function createupdateuser($user_id, WithArgumentsInterface $withArgumentsAST)
    {
        parent::createupdateuser($user_id, $withArgumentsAST);
        $this->commonuserrolesCreateupdateuser($user_id, $withArgumentsAST);
    }
    protected function commonuserrolesCreateupdateuser($user_id, WithArgumentsInterface $withArgumentsAST)
    {
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES, $withArgumentsAST->getArgumentValue('organizationtypes'));
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES, $withArgumentsAST->getArgumentValue('organizationcategories'));
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTPERSON, $withArgumentsAST->getArgumentValue('contact_person'), true);
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTNUMBER, $withArgumentsAST->getArgumentValue('contact_number'), true);
    }
}
