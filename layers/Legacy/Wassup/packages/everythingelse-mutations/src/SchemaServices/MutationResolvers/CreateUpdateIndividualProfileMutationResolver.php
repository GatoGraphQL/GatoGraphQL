<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateIndividualProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    use CreateUpdateIndividualProfileMutationResolverTrait;

    protected function getUpdateuserData(FieldDataAccessorInterface $fieldDataAccessor)
    {
        $user_data = parent::getUpdateuserData($fieldDataAccessor);

        $user_data['lastName'] = $fieldDataAccessor->getValue('last_name');

        return $user_data;
    }
    protected function createupdateuser($user_id, FieldDataAccessorInterface $fieldDataAccessor): void
    {
        parent::createupdateuser($user_id, $fieldDataAccessor);

        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $fieldDataAccessor->getValue('individualinterests'));
    }
}
