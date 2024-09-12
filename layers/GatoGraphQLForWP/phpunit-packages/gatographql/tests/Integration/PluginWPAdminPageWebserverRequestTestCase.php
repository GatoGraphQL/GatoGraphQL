<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\GatoGraphQL\Integration\AbstractPluginWPAdminPageWebserverRequestTestCase;

class PluginWPAdminPageWebserverRequestTestCase extends AbstractPluginWPAdminPageWebserverRequestTestCase
{
    protected static function getGatoGraphQLPluginDefaultMenuPageSlug(): ?string
    {
        return 'graphiql';
    }

    /**
     * Provide all the MenuPageSlug registered by Gato GraphQL.
     * These are under function `getMenuPageSlug` from `MenuPageInterface`,
     * such as `AboutMenuPage`.
     *
     * @see layers/GatoGraphQLForWP/plugins/gatographql/src/Services/MenuPages/AboutMenuPage.php
     *
     * @return string[]
     */
    protected static function getGatoGraphQLPluginMenuPageSlugs(): array
    {
        return [
            'about',
            'extensiondocs',
            'extensions',
            'graphiql',
            'modules',
            'tutorial',
            'settings',
            'voyager',
        ];
    }

    /**
     * Provide all the Custom Post Types registered by Gato GraphQL.
     * These are under function `getCustomPostType` from `CustomPostTypeInterface`,
     * such as `GraphQLPersistedQueryEndpointCustomPostType`.
     *
     * @see layers/GatoGraphQLForWP/plugins/gatographql/src/Services/CustomPostTypes/GraphQLPersistedQueryEndpointCustomPostType.php
     *
     * @return string[]
     */
    protected static function getGatoGraphQLPluginCustomPostTypes(): array
    {
        return [
            'graphql-schemaconfig',
        ];
    }
}
