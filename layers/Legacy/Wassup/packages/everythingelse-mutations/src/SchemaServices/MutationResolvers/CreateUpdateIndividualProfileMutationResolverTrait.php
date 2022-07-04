<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoPCMSSchema\UserMeta\Utils;
use PoPCMSSchema\UserRoles\FunctionAPIFactory;
trait CreateUpdateIndividualProfileMutationResolverTrait
{
    protected function createuser(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $user_id = parent::createuser($fieldDataAccessor);
        $this->commonuserrolesCreateuser($user_id, $fieldDataAccessor);
        return $user_id;
    }
    protected function commonuserrolesCreateuser($user_id, FieldDataAccessorInterface $fieldDataAccessor)
    {
        // Add the extra User Role
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_INDIVIDUAL);
    }

    protected function createupdateuser($user_id, FieldDataAccessorInterface $fieldDataAccessor)
    {
        parent::createupdateuser($user_id, $fieldDataAccessor);
        $this->commonuserrolesCreateupdateuser($user_id, $fieldDataAccessor);
    }
    protected function commonuserrolesCreateupdateuser($user_id, FieldDataAccessorInterface $fieldDataAccessor)
    {
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $fieldDataAccessor->get('individualinterests'));
    }
}
