<?php

declare(strict_types=1);

namespace PHPUnitForGatoGraphQL\GatoGraphQL\Integration;

use PHPUnitForGatoGraphQL\WebserverRequests\AbstractWPAdminPageWebserverRequestTestCase;

abstract class AbstractPluginWPAdminPageWebserverRequestTestCase extends AbstractWPAdminPageWebserverRequestTestCase
{
    /**
     * Test that all pages added by the plugin produce
     * a 200 status code.
     *
     * @return array<string,string[]>
     */
    public static function providePageEntries(): array
    {
        $pluginMenuName = static::getPluginMenuName();

        $entries = [];

        $defaultMenuPageSlug = static::getGatoGraphQLPluginDefaultMenuPageSlug();
        if ($defaultMenuPageSlug !== null) {
            // Default menu page entry
            $entries['default'] = [
                sprintf(
                    'wp-admin/admin.php?page=%s',
                    $pluginMenuName
                ),
            ];
        }

        foreach (static::getGatoGraphQLPluginMenuPageSlugs() as $pageSlug) {
            if ($pageSlug === $defaultMenuPageSlug) {
                continue;
            }
            $entries[$pageSlug] = [
                sprintf(
                    'wp-admin/admin.php?page=%s_%s',
                    $pluginMenuName,
                    $pageSlug
                ),
            ];
        }

        foreach (static::getGatoGraphQLPluginCustomPostTypes() as $customPostType) {
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
     * @see layers/GatoGraphQLForWP/plugins/gato-graphql/src/Services/Menus/PluginMenu.php function `getName`
     */
    protected static function getPluginMenuName(): string
    {
        return 'gato_graphql';
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
    abstract protected static function getGatoGraphQLPluginMenuPageSlugs(): array;

    protected static function getGatoGraphQLPluginDefaultMenuPageSlug(): ?string
    {
        return null;
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
    abstract protected static function getGatoGraphQLPluginCustomPostTypes(): array;
}
