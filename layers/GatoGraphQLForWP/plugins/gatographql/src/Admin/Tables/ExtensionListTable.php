<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Admin\Tables;

use GatoGraphQL\GatoGraphQL\App;
use GatoGraphQL\GatoGraphQL\Constants\HTMLCodes;
use GatoGraphQL\GatoGraphQL\Facades\Registries\ModuleRegistryFacade;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\BundleExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\Extensions\ExtensionModuleResolverInterface;
use GatoGraphQL\GatoGraphQL\PluginStaticModuleConfiguration;

/**
 * Extension Table implementation, which retrieves the Extensions
 * data pre-defined via ModuleResolvers
 */
class ExtensionListTable extends AbstractExtensionListTable
{
    use WithOpeningModuleDocInModalListTableTrait;

    public function overridePluginsAPIResult(): mixed
    {
        $plugins = $this->getAllItems();
        return (object) [
            'info' => [
                'page' => 1,
                'pages' => 1,
                'results' => count($plugins),
            ],
            'plugins' => $plugins,
        ];
    }

    /**
     * Retrieve all the Extensions from the Registry, and
     * generate an array with the data in the expected format
     * by the upstream WordPress class.
     *
     * @return mixed[]
     */
    protected function getAllItems(): array
    {
        $items = [];
        $moduleRegistry = ModuleRegistryFacade::getInstance();
        $modules = $moduleRegistry->getAllModules(true, false, false);
        $wordPressPluginAPIUnneededRequiredEntries = $this->getWordPressPluginAPIUnneededRequiredEntries();
        foreach ($modules as $module) {
            $moduleResolver = $moduleRegistry->getModuleResolver($module);
            if (!($moduleResolver instanceof ExtensionModuleResolverInterface)) {
                continue;
            }
            $isBundleExtension = $moduleResolver instanceof BundleExtensionModuleResolverInterface;
            $item = [
                'name' => $moduleResolver->getName($module),
                'slug' => $moduleResolver->getGatoGraphQLExtensionSlug($module),
                'short_description' => $moduleResolver->getDescription($module),
                'homepage' => $moduleResolver->getWebsiteURL($module),
                'icons' => [
                    'default' => $moduleResolver->getLogoURL($module),
                ],

                ...$wordPressPluginAPIUnneededRequiredEntries,

                /**
                 * These are custom properties, not required by the upstream class,
                 * but used internally to modify the generated HTML content
                 */
                'gato_extension_module' => $module,
                'gato_extension_is_bundle' => $isBundleExtension,
            ];
            if ($isBundleExtension) {
                /** @var BundleExtensionModuleResolverInterface */
                $bundleExtensionModuleResolver = $moduleResolver;
                $item['gato_extension_bundled_extension_slugs'] = $bundleExtensionModuleResolver->getGatoGraphQLBundledExtensionSlugs($module);
                $item['gato_extension_bundled_bundle_extension_slugs'] = $bundleExtensionModuleResolver->getGatoGraphQLBundledBundleExtensionSlugs($module);
            }
            $items[] = $item;
        }
        return $this->combineExtensionItemsWithCommonPluginData($items);
    }

    /**
     * These entries are not printed in the screen, however they
     * are read by class-wp-plugin-install-list-table.php,
     * hence they must always be added.
     *
     * @see wp-admin/includes/class-wp-plugin-install-list-table.php
     * @see http://api.wordpress.org/plugins/info/1.2/?action=query_plugins&per_page=1
     *
     * @return array<string,mixed>
     */
    protected function getWordPressPluginAPIUnneededRequiredEntries(): array
    {
        return [
            'rating' => 100,
            'ratings' => [
                '5' => 10000,
                '4' => 0,
                '3' => 0,
                '2' => 0,
                '1' => 0
            ],
            'num_ratings' => 10000,
            'support_threads' => 0,
            'support_threads_resolved' => 0,
            'active_installs' => 1000000,
            'downloaded' => 1000000,
            'last_updated' => '2023-08-06 8:25am GMT',
            'added' => '2023-08-06',
        ];
    }

    /**
     * Allow to change the title for extensions active via a bundle
     *
     * @param array<string,mixed> $plugin
     */
    public function getPluginInstallActionLabel(array $plugin): string
    {
        $offerGatoGraphQLPROBundle = PluginStaticModuleConfiguration::offerGatoGraphQLPROBundle();
        $offerGatoGraphQLPROFeatureBundles = PluginStaticModuleConfiguration::offerGatoGraphQLPROFeatureBundles();
        $offerGatoGraphQLPROExtensions = PluginStaticModuleConfiguration::offerGatoGraphQLPROExtensions();

        $isBundle = $plugin['gato_extension_is_bundle'];

        // If it's a Bundle => "Get Bundle", otherwise "Get Extension"
        if ($isBundle && $offerGatoGraphQLPROExtensions) {
            $extensionActionLabel = sprintf(
                '%s%s',
                $offerGatoGraphQLPROBundle && !$offerGatoGraphQLPROFeatureBundles ? sprintf('<strong>%s</strong>', \__('Go PRO', 'gatographql')) : \__('Get Bundle', 'gatographql'),
                HTMLCodes::OPEN_IN_NEW_WINDOW
            );
        }
        elseif ($isBundle && !$offerGatoGraphQLPROExtensions) {
            $extensionActionLabel = parent::getPluginInstallActionLabel($plugin);
        } elseif ($offerGatoGraphQLPROBundle || $offerGatoGraphQLPROFeatureBundles) {
            $extensionActionLabel = sprintf(
                '%s%s',
                \__('Visit on website', 'gatographql'),
                HTMLCodes::OPEN_IN_NEW_WINDOW
            );
        } else {
            $extensionActionLabel = parent::getPluginInstallActionLabel($plugin);
        }
        return sprintf(
            '
                <span class="gatographql-extension-action-label">%s</span>
                <span class="gatographql-extension-bundle-action-label" style="display: none;">%s</span>
            ',
            $extensionActionLabel,
            $offerGatoGraphQLPROBundle && !$offerGatoGraphQLPROFeatureBundles ? \__('Active (via PRO)', 'gatographql') : \__('Active (via Bundle)', 'gatographql')
        );
    }

    /**
     * @param array<string,mixed> $plugin
     */
    protected function getAdditionalPluginCardClassnames(array $plugin): ?string
    {
        if ($plugin['gato_extension_is_bundle']) {
            $additionalPluginCardClassnames = 'plugin-card-extension-bundle';
            if (
                in_array($plugin['gato_extension_module'], [
                BundleExtensionModuleResolver::PRO,
                ])
            ) {
                $additionalPluginCardClassnames .= ' plugin-card-highlight';
            } else {
                $additionalPluginCardClassnames .= ' plugin-card-not-highlight';
            }
            return $additionalPluginCardClassnames;
        }
        return parent::getAdditionalPluginCardClassnames($plugin);
    }

    /**
     * @param array<string,mixed> $plugin
     */
    protected function getAdaptedDetailsLink(array $plugin): string
    {
        /**
         * This is a custom property, not required by the upstream class,
         * but used internally to modify the generated HTML content
         *
         * @var string
         */
        $extensionModule = $plugin['gato_extension_module'];
        return $this->getOpeningModuleDocInModalLinkURL(
            App::request('page') ?? App::query('page', ''),
            $extensionModule,
        );
    }

    /**
     * Gets a list of CSS classes for the WP_List_Table table tag.
     *
     * @since 3.1.0
     *
     * @return string[] Array of CSS classes for the table tag.
     * phpcs:disable PSR1.Methods.CamelCapsMethodName.NotCamelCaps
     */
    protected function get_table_classes()
    {
        return array_merge(
            parent::get_table_classes(),
            [
                'gatographql-list-table',
            ]
        );
    }

    /**
     * Add a class to the bundled extensions
     */
    protected function adaptDisplayRowsHTML(string $html): string
    {
        $html = parent::adaptDisplayRowsHTML($html);

        /**
         * @see wp-admin/includes/class-wp-plugin-install-list-table.php
         */
        $activeButtonHTML = sprintf(
            '<button type="button" class="button button-disabled" disabled="disabled">%s</button>',
            _x('Active', 'plugin')
        );

        foreach ((array) $this->items as $plugin) {
            // Check it is a Bundle Extension
            if (!$plugin['gato_extension_is_bundle']) {
                continue;
            }

            // Check it is active
            $pluginName = $plugin['name'];
            $actionLinks = $this->pluginActionLinks[$pluginName] ?? [];
            if (($actionLinks[0] ?? '') !== $activeButtonHTML) {
                continue;
            }

            /**
             * Replace classname "plugin-card-non-installed" with
             * "plugin-card-bundler-active" in the bundled extensions,
             * and in the bundled bundle extensions.
             *
             * This will change the style of the already-active items,
             * and disable the "Get Extension" and "Get Bundle" buttons.
             *
             * @var string[]
             */
            $bundledExtensionSlugs = array_merge(
                $plugin['gato_extension_bundled_extension_slugs'],
                $plugin['gato_extension_bundled_bundle_extension_slugs']
            );
            foreach ($bundledExtensionSlugs as $bundledExtensionSlug) {
                $pluginCardClassname = 'plugin-card-' . sanitize_html_class($bundledExtensionSlug);
                $pos = strpos($html, $pluginCardClassname . ' plugin-card-non-installed');
                if ($pos !== false) {
                    $html = substr_replace($html, $pluginCardClassname . ' plugin-card-bundler-active', $pos, strlen($pluginCardClassname . ' plugin-card-non-installed'));
                }
            }
        }

        return $html;
    }
}
