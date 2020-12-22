<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\MutationResolvers;

use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;

class UpdateMyPreferencesMutationResolver extends AbstractMutationResolver
{
    /**
     * @return mixed
     */
    public function execute(array $form_data)
    {
        $user_id = $form_data['user_id'];
        \PoPSchema\UserMeta\Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_USERPREFERENCES, $form_data['userPreferences']);
        return $user_id;
    }
}
