<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractPluginWPAdminPageWebserverRequestTestCase;

class PluginWPAdminPageWebserverRequestTestCase extends AbstractPluginWPAdminPageWebserverRequestTestCase
{
    protected function getGatoGraphQLPluginDefaultMenuPageSlug(): ?string
    {
        return 'graphiql';
    }

    /**
     * Provide all the MenuPageSlug registered by Gato GraphQL.
     * These are under function `getMenuPageSlug` from `MenuPageInterface`,
     * such as `AboutMenuPage`.
     *
     * @see layers/GatoGraphQLForWP/plugins/gato-graphql/src/Services/MenuPages/AboutMenuPage.php
     *
     * @return string[]
     */
    protected function getGatoGraphQLPluginMenuPageSlugs(): array
    {
        return [
            'about',
            'extensiondocs',
            'extensions',
            'graphiql',
            'modules',
            'recipes',
            'settings',
            'voyager',
        ];
    }

    /**
     * Provide all the Custom Post Types registered by Gato GraphQL.
     * These are under function `getCustomPostType` from `CustomPostTypeInterface`,
     * such as `GraphQLPersistedQueryEndpointCustomPostType`.
     *
     * @see layers/GatoGraphQLForWP/plugins/gato-graphql/src/Services/CustomPostTypes/GraphQLPersistedQueryEndpointCustomPostType.php
     *
     * @return string[]
     */
    protected function getGatoGraphQLPluginCustomPostTypes(): array
    {
        return [
            'graphql-endpoint',
            'graphql-query',
            'graphql-schemaconfig',
        ];
    }
}
