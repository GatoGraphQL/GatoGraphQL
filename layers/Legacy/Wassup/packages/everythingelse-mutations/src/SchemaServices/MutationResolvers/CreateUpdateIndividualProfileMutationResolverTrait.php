<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoPCMSSchema\UserMeta\Utils;
use PoPCMSSchema\UserRoles\FunctionAPIFactory;
trait CreateUpdateIndividualProfileMutationResolverTrait
{
    protected function createuser(MutationDataProviderInterface $mutationDataProvider)
    {
        $user_id = parent::createuser($mutationDataProvider);
        $this->commonuserrolesCreateuser($user_id, $mutationDataProvider);
        return $user_id;
    }
    protected function commonuserrolesCreateuser($user_id, MutationDataProviderInterface $mutationDataProvider)
    {
        // Add the extra User Role
        $cmsuserrolesapi = FunctionAPIFactory::getInstance();
        $cmsuserrolesapi->addRoleToUser($user_id, GD_URE_ROLE_INDIVIDUAL);
    }

    protected function createupdateuser($user_id, MutationDataProviderInterface $mutationDataProvider)
    {
        parent::createupdateuser($user_id, $mutationDataProvider);
        $this->commonuserrolesCreateupdateuser($user_id, $mutationDataProvider);
    }
    protected function commonuserrolesCreateupdateuser($user_id, MutationDataProviderInterface $mutationDataProvider)
    {
        Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $mutationDataProvider->getValue('individualinterests'));
    }
}
