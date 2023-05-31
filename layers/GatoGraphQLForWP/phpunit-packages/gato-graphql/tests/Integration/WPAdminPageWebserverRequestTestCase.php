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
                    'wp-admin/edit.php?page=%s',
                    $pluginMenuName
                ),
            ]
        ];
        foreach ($this->getGatoGraphQLPluginMenuPageSlugs() as $pageSlug) {
            $entries[$pageSlug] = [
                sprintf(
                    'wp-admin/edit.php?page=%s_%s',
                    $pluginMenuName,
                    $pageSlug
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
            'graphiql',
            'modules',
            'recipes',
            'settings',
            'voyager',
        ];
    }
}
