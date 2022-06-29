<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoPCMSSchema\UserMeta\Utils;
use PoPCMSSchema\UserRoles\FunctionAPIFactory;
trait CreateUpdateIndividualProfileMutationResolverTrait
{
    protected function createuser($withArgumentsAST)
    {
        $user_id = parent::createuser($withArgumentsAST);
        $this->commonuserrolesCreateuser($user_id, $withArgumentsAST);
        return $user_id;
    }
    protected function commonuserrolesCreateuser($user_id, $withArgumentsAST)
    {
        // Add the extra User Role
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_INDIVIDUAL);
    }

    protected function createupdateuser($user_id, $withArgumentsAST)
    {
        parent::createupdateuser($user_id, $withArgumentsAST);
        $this->commonuserrolesCreateupdateuser($user_id, $withArgumentsAST);
    }
    protected function commonuserrolesCreateupdateuser($user_id, $withArgumentsAST)
    {
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $withArgumentsAST->getArgumentValue('individualinterests'));
    }
}
