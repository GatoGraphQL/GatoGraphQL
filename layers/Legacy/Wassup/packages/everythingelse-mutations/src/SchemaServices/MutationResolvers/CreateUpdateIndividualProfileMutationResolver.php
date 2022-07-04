<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataAccessorInterface;
use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateIndividualProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    use CreateUpdateIndividualProfileMutationResolverTrait;

    protected function getUpdateuserData(FieldDataAccessorInterface $fieldDataProvider)
    {
        $user_data = parent::getUpdateuserData($fieldDataProvider);

        $user_data['lastName'] = $fieldDataProvider->get('last_name');

        return $user_data;
    }
    protected function createupdateuser($user_id, FieldDataAccessorInterface $fieldDataProvider): void
    {
        parent::createupdateuser($user_id, $fieldDataProvider);

        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $fieldDataProvider->get('individualinterests'));
    }
}
