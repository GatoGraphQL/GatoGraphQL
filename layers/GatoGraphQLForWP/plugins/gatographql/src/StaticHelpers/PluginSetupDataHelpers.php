<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\StaticHelpers;

use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLCustomEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLPersistedQueryEndpointCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\CustomPostTypes\GraphQLSchemaConfigurationCustomPostType;
use GatoGraphQL\GatoGraphQL\Services\Taxonomies\GraphQLEndpointCategoryTaxonomy;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use WP_Term;

class PluginSetupDataHelpers
{
    public static function getEndpointCategoryID(string $slug): ?int
    {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLEndpointCategoryTaxonomy */
        $graphQLEndpointCategoryTaxonomy = $instanceManager->getInstance(GraphQLEndpointCategoryTaxonomy::class);

        /** @var WP_Term|false */
        $endpointCategoryTerm = \get_term_by('slug', $slug, $graphQLEndpointCategoryTaxonomy->getTaxonomy());
        if ($endpointCategoryTerm instanceof WP_Term) {
            return $endpointCategoryTerm->term_id;
        }

        return null;
    }

    /**
     * @param string|string[] $status
     */
    public static function getSchemaConfigurationID(
        string $slug,
        string|array $status = 'publish',
    ): ?int {
        $instanceManager = InstanceManagerFacade::getInstance();
        /** @var GraphQLSchemaConfigurationCustomPostType */
        $graphQLSchemaConfigurationCustomPostType = $instanceManager->getInstance(GraphQLSchemaConfigurationCustomPostType::class);

        /** @var array<string|int> */
        $schemaConfigurations = \get_posts([
            'name' => $slug,
            'post_type' => $graphQLSchemaConfigurationCustomPostType->getCustomPostType(),
            'post_status' => $status,
            'numberposts' => 1,
            'fields' => 'ids',
        ]);
        if (isset($schemaConfigurations[0])) {
            return (int) $schemaConfigurations[0];
        }

        return null;
    }

    /**
     * @param string|string[] $status
     */
    public static function getCustomEndpointID(
        string $slug,
        string|array $status = ['publish', 'private'],
    ): ?int {
        $instanceManager = InstanceManagerFacade::getInstance();

        /** @var GraphQLCustomEndpointCustomPostType */
        $graphQLCustomEndpointCustomPostType = $instanceManager->getInstance(GraphQLCustomEndpointCustomPostType::class);

        /** @var array<string|int> */
        $customEndpoints = \get_posts([
            'name' => $slug,
            'post_type' => $graphQLCustomEndpointCustomPostType->getCustomPostType(),
            'post_status' => $status,
            'numberposts' => 1,
            'fields' => 'ids',
        ]);
        if (isset($customEndpoints[0])) {
            return (int) $customEndpoints[0];
        }

        return null;
    }
}
