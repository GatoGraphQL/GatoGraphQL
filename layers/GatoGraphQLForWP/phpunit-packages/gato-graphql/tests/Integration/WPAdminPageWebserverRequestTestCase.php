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
        $entries = [];
        foreach ($this->getGatoGraphQLPluginMenuPageSlugs() as $pageSlug) {
            $entries[$pageSlug] = sprintf(
                'wp-admin/edit.php?page=gato_graphql_%s',
                $pageSlug
            );
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
