<?php

declare(strict_types=1);

namespace PoPSitesWassup\EverythingElseMutations\SchemaServices\MutationResolvers;

use PoP\ComponentModel\Mutation\FieldDataProviderInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\Root\Exception\AbstractException;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoPCMSSchema\UserMeta\Utils;

class UpdateMyPreferencesMutationResolver extends AbstractMutationResolver
{
    /**
     * @throws AbstractException In case of error
     */
    public function executeMutation(FieldDataProviderInterface $fieldDataProvider): mixed
    {
        $user_id = $fieldDataProvider->get('user_id');
        Utils::updateUserMeta($user_id, GD_METAKEY_PROFILE_USERPREFERENCES, $fieldDataProvider->get('userPreferences'));
        return $user_id;
    }
}
