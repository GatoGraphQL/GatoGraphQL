<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoPCMSSchema\UserMeta\Utils;
use PoPCMSSchema\UserRoles\FunctionAPIFactory;
trait CreateUpdateOrganizationProfileMutationResolverTrait
{
    protected function createuser(FieldDataAccessorInterface $fieldDataProvider)
    {
        $user_id = parent::createuser($fieldDataProvider);
        $this->commonuserrolesCreateuser($user_id, $fieldDataProvider);
        return $user_id;
    }
    protected function commonuserrolesCreateuser($user_id, FieldDataAccessorInterface $fieldDataProvider)
    {
        // Add the extra User Role
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_ORGANIZATION);
    }

    protected function createupdateuser($user_id, FieldDataAccessorInterface $fieldDataProvider)
    {
        parent::createupdateuser($user_id, $fieldDataProvider);
        $this->commonuserrolesCreateupdateuser($user_id, $fieldDataProvider);
    }
    protected function commonuserrolesCreateupdateuser($user_id, FieldDataAccessorInterface $fieldDataProvider)
    {
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES, $fieldDataProvider->get('organizationtypes'));
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES, $fieldDataProvider->get('organizationcategories'));
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTPERSON, $fieldDataProvider->get('contact_person'), true);
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTNUMBER, $fieldDataProvider->get('contact_number'), true);
    }
}
