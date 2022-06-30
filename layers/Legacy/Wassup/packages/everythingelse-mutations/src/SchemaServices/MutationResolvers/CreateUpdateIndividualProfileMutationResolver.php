<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateIndividualProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    use CreateUpdateIndividualProfileMutationResolverTrait;

    protected function getUpdateuserData(\PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider)
    {
        $user_data = parent::getUpdateuserData($mutationDataProvider);

        $user_data['lastName'] = $mutationDataProvider->getArgumentValue('last_name');

        return $user_data;
    }
    protected function createupdateuser($user_id, \PoP\ComponentModel\Mutation\MutationDataProviderInterface $mutationDataProvider): void
    {
        parent::createupdateuser($user_id, $mutationDataProvider);

        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $mutationDataProvider->getArgumentValue('individualinterests'));
    }
}
