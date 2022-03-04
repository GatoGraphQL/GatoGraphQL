<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\Root\Exception\AbstractException;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPCMSSchema\UserMeta\Utils;

class UpdateMyPreferencesMutationResolver extends AbstractMutationResolver
{
    /**
     * @param array<string,mixed> $form_data
     * @throws AbstractException In case of error
     */
    public function executeMutation(array $form_data): mixed
    {
        $user_id = $form_data['user_id'];
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_USERPREFERENCES, $form_data['userPreferences']);
        return $user_id;
    }
}
