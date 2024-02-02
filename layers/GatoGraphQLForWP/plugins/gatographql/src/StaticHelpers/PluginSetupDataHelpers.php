<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class PluginSetupDataHelpers
{
    public static function getPersistedQueryEndpointID(string $slug): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var GraphQLPersistedQueryEndpointCustomPostType */
        $graphQLPersistedQueryEndpointCustomPostType = $instanceManager->getInstance(GraphQLPersistedQueryEndpointCustomPostType::class);

        /** @var array<string|int> */
        $persistedQueryEndpoints = \get_posts([
            'name' => $slug,
            'post_type' => $graphQLPersistedQueryEndpointCustomPostType->getCustomPostType(),
            'post_status' => ['publish', 'private'],
            'numberposts' => 1,
            'fields' => 'ids',
        ]);
        if (isset($persistedQueryEndpoints[0])) {
            return (int) $persistedQueryEndpoints[0];
        }

        return null;
    }
}
