<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoPCMSSchema\UserMeta\Utils;
class CreateUpdateIndividualProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    use CreateUpdateIndividualProfileMutationResolverTrait;

    protected function getUpdateuserData(WithArgumentsInterface $withArgumentsAST)
    {
        $user_data = parent::getUpdateuserData($withArgumentsAST);

        $user_data['lastName'] = $withArgumentsAST->getArgumentValue('last_name');

        return $user_data;
    }
    protected function createupdateuser($user_id, WithArgumentsInterface $withArgumentsAST): void
    {
        parent::createupdateuser($user_id, $withArgumentsAST);

        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $withArgumentsAST->getArgumentValue('individualinterests'));
    }
}
