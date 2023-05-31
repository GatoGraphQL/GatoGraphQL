<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractWPAdminPageWebserverRequestTestCase;

class WPAdminPageWebserverRequestTestCase extends AbstractWPAdminPageWebserverRequestTestCase
{
    /**
     * Test that all pages added by the plugin produce
     * a 200 status code.
     *
     * @return array<string,string[]>
     */
    protected function providePageEntries(): array
    {
        // @see layers/GatoGraphQLForWP/plugins/gato-graphql/src/Services/Menus/PluginMenu.php function `getName`
        $pluginMenuName = 'gato_graphql';

        $entries = [
            // Default menu page entry
            'default' => [
                sprintf(
                    'wp-admin/admin.php?page=%s',
                    $pluginMenuName
                ),
            ]
        ];
        foreach ($this->getGatoGraphQLPluginMenuPageSlugs() as $pageSlug) {
            $entries[$pageSlug] = [
                sprintf(
                    'wp-admin/admin.php?page=%s_%s',
                    $pluginMenuName,
                    $pageSlug
                ),
            ];
        }
        foreach ($this->getGatoGraphQLPluginCustomPostTypes() as $customPostType) {
            $entries[$customPostType] = [
                sprintf(
                    'wp-admin/edit.php?post_type=%s',
                    $customPostType
                ),
            ];
        }
        return $entries;
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
            // 'graphiql', // This is the default one, which works only without slug!
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
