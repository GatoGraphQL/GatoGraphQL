<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataProviderInterface;
use PoPCMSSchema\UserMeta\Utils;
use PoPCMSSchema\UserRoles\FunctionAPIFactory;
trait CreateUpdateOrganizationProfileMutationResolverTrait
{
    protected function createuser(FieldDataProviderInterface $fieldDataProvider)
    {
        $user_id = parent::createuser($fieldDataProvider);
        $this->commonuserrolesCreateuser($user_id, $fieldDataProvider);
        return $user_id;
    }
    protected function commonuserrolesCreateuser($user_id, FieldDataProviderInterface $fieldDataProvider)
    {
        // Add the extra User Role
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_ORGANIZATION);
    }

    protected function createupdateuser($user_id, FieldDataProviderInterface $fieldDataProvider)
    {
        parent::createupdateuser($user_id, $fieldDataProvider);
        $this->commonuserrolesCreateupdateuser($user_id, $fieldDataProvider);
    }
    protected function commonuserrolesCreateupdateuser($user_id, FieldDataProviderInterface $fieldDataProvider)
    {
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONTYPES, $fieldDataProvider->get('organizationtypes'));
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_ORGANIZATIONCATEGORIES, $fieldDataProvider->get('organizationcategories'));
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTPERSON, $fieldDataProvider->get('contact_person'), true);
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_CONTACTNUMBER, $fieldDataProvider->get('contact_number'), true);
    }
}
