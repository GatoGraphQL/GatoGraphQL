<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateIndividualProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    use CreateUpdateIndividualProfileMutationResolverTrait;

    protected function getUpdateuserData(MutationDataProviderInterface $mutationDataProvider)
    {
        $user_data = parent::getUpdateuserData($mutationDataProvider);

        $user_data['lastName'] = $mutationDataProvider->getValue('last_name');

        return $user_data;
    }
    protected function createupdateuser($user_id, MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::createupdateuser($user_id, $mutationDataProvider);

        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $mutationDataProvider->getValue('individualinterests'));
    }
}
