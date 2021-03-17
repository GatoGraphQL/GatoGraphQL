<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

class CreateUpdateIndividualProfileMutationResolver extends CreateUpdateProfileMutationResolver
{
    use CreateUpdateIndividualProfileMutationResolverTrait;

    protected function getUpdateuserData($form_data)
    {
        $user_data = parent::getUpdateuserData($form_data);

        $user_data['lastname'] = $form_data['last_name'];

        return $user_data;
    }
    protected function createupdateuser($user_id, $form_data)
    {
        parent::createupdateuser($user_id, $form_data);

        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_URE_METAKEY_PROFILE_INDIVIDUALINTERESTS, $form_data['individualinterests']);
    }
}
